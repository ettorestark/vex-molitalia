<?php
/**
 * PagoEfectivo Gateway Pagoefectivo Bancaporinternet.
 *
 * @since   1.0.0
 * @package PagoEfectivo_Gateway
 */

/**
 * PagoEfectivo Gateway Pagoefectivo Bancaporinternet.
 *
 * @since 1.0.0
 */
class VGP_Gateway_Pagoefectivo_Transferencia extends WC_Payment_Gateway {

    const PE_SERVER = 'https://pre1a.pagoefectivo.pe/';

    const NTF_STATUS_EXTORNADO = 592;
    const NTF_STATUS_PAGADO    = 593;
    const NTF_STATUS_EXPIRADO  = 595;

    function __construct() {
        $this->id                 = "pagoefectivo";
        $this->has_fields         = false;
        //$this->order_button_text  = __( "Order with VISA", 'woocommerce-gateway-visanetperu' );
        $this->method_title       = __( "PagoEfectvo", 'woocommerce-gateway-visanetperu' );
        $this->method_description = __( "PagoEfectvo Payment Gateway Plug-in for WooCommerce",  'woocommerce-gateway-visanetperu' );
        $this->supports           = array( 'products' );

        $this->icon = vexsoluciones_gateway_pagoefectivo()->url( 'assets/images/rsz_uno.png' );

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables.
        $this->enabled     = $this->get_option( 'enabled' );
        $this->title       = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->merchant_ID = $this->get_option( 'merchant_ID' );

        // Actions
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

        if( 'yes' == $this->enabled ){
            add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
            add_action( 'woocommerce_api_' . $this->id, array( $this, '_check_response' ) );
            add_action( 'woocommerce_api_' . $this->id . '-check', array( $this, '_check_response_check' ) );

            add_action( 'init', array( $this, 'register_my_new_order_statuses' ) );
            add_filter( 'wc_order_statuses', array( $this, 'my_new_wc_order_statuses' ) );
            add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ) );
        }
    }

    // New order status AFTER woo 2.2
    public function register_my_new_order_statuses() {
        register_post_status( 'wc-expired', array(
            'label'                     => _x( 'Expirado', 'Order status', 'woocommerce' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Expirado <span class="count">(%s)</span>', 'Expirado<span class="count">(%s)</span>', 'woocommerce' )
        ) );
    }

    // Register in wc_order_statuses.
    public function my_new_wc_order_statuses( $order_statuses ) {
        $order_statuses['wc-expired'] = _x( 'Expirado', 'Order status', 'woocommerce' );

        return $order_statuses;
    }

    public function available_payment_gateways( $available_gateways ){
        $index = array_search( 'pagoefectivo', array_keys( $available_gateways ) );

        $start = array_slice( $available_gateways, 0, $index+1 );
        $end = array_slice( $available_gateways, $index+1 );

        $newVal = array(
            'pagoefectivo-deposito' => new VGP_Gateway_Pagoefectivo_Deposito()
        );

        $available_gateways = array_merge( $start, $newVal, $end );

        return $available_gateways;
    }

    private function _payment_complete( $order_id ){
        $data = get_post_meta( $order_id, '_PE_data', true );
        $order = wc_get_order( $order_id );

        $order->payment_complete( $data->CodTrans );
        $order->update_status( 'completed' );
    }

    public function _check_response(){
        $data = isset( $_POST['data'] ) ? $_POST['data'] : '';
        $version = isset( $_POST['version'] ) ? $_POST['version'] : '';

        if( $data && $version ){
            $pagoEfectivo = $this->_getService();

            $paymentResponse = simplexml_load_string( $pagoEfectivo->desencriptarData( $data ) );

            // dc_logger( $paymentResponse );

            if( $paymentResponse != null ){
                $CIPResult = $paymentResponse->CIP;
                $order_id = ( string ) $CIPResult->OrderIdComercio;

                switch ( $paymentResponse->Estado ) {
                    case self::NTF_STATUS_EXTORNADO:
                        // pendiente de pago
                        $order = wc_get_order( $order_id );
                        $order->update_status( 'pending' );
//                      echo 'Estado: PENDIENTE DE PAGO';
                        break;
                    case self::NTF_STATUS_PAGADO:
                        // pagado
                        $this->_payment_complete( $order_id );
//                         echo 'Estado: PAGADO';

                        //$CIPResult->UsuarioEmail;
                        break;
                    case self::NTF_STATUS_EXPIRADO:
                        // vencido
                        $order = wc_get_order( $order_id );
                        $order->update_status( 'expired' );
                        // echo 'Estado: EXPIRADO';
                        break;
                    default:
//                         echo 'Estado: ERROR';
                        break;
                }

                // 	            dc_logger( $paymentResponse, $data, $version );

            }

            die();

        }
    }

    public function _check_response_check(){
        $order_id = isset( $_POST['pe-order_id'] ) ? $_POST['pe-order_id'] : '';
        $cip = isset( $_POST['pe-cip_code'] ) ? trim( $_POST['pe-cip_code'] ) : '';

        if( $order_id && $cip ){
            $pagoEfectivo = $this->_getService();
            if( $paymentResponse = $pagoEfectivo->consultarCIP( $cip ) ) {
                if( $paymentResponse->Estado ){
                    $CIPResult = $paymentResponse->CIPs->ConfirSolPago;
                    $CIPResult = $CIPResult->CIP;

                    // si a sido pagado
                    if( $CIPResult->IdEstado == 23 ){
                        $this->_payment_complete( $order_id );
                    } else {
                        $arr_estado = array(
                            '22'=> 'Pendiente de pago',
                            '23'=>'Pagado',
                            '21'=>'Expirado',
                            '25'=>'Eliminado'
                        );

                        wc_add_notice( 'CIP se encuentra: ' . $arr_estado[ ( string ) $CIPResult->IdEstado ], 'error' );
                    }
                    // 	                dc_logger( array( $order_id, $cip, $paymentResponse ) );
                } else {
                    wc_add_notice( $paymentResponse->Mensaje, 'error' );
                }
            } else {
                wc_add_notice( 'Error inesperado.', 'error' );
            }

            if ( wp_get_referer() ){
                wp_safe_redirect( wp_get_referer() );
            } else {
                wp_safe_redirect( get_home_url() );
            }

            die();
        }
    }

    /**
     * Output for the order received page.
     *
     * @param int $order_id
     */
    public function receipt_page( $order_id ) {
        $order = wc_get_order( $order_id );

        if( $order->has_status( 'completed' ) ) return;

        $data = get_post_meta( $order_id, '_PE_data', true );

        $site_name = get_bloginfo( 'name' );
        $action = WC()->api_request_url( $this->id . '-check' );
        $cip = $data->IdOrdenPago;
        $url = self::PE_SERVER . 'GenPagoIF.aspx?Token=' . $data->Token;

        // echo $url;

        echo <<<EOF
		<h2>¡Gracias por elegir {$site_name}!</h2>
		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-pending">Ahora por favor ingrese su código de pago efectivo para continuar el proceso.</p>

        <form method="post" action="{$action}">
            <div class="wc-gateway-pagoefectivo-input">
                <input type="hidden" name="pe-order_id" value="{$order_id}">
            	<input type="text" name="pe-cip_code" class="input-text" placeholder="Código" id="pe-cip_code" autofocus>
            	<button type="submit" class="button">Verificar</button>
            </div>
        </form>

	    <iframe src="{$url}" width="1020"  height="1209" frameborder="no"  style="border:1px solid #000000;"></iframe>
        <script type="text/javascript">
        document.getElementById( 'pe-cip_code').value = '{$cip}';
        </script>
EOF;
    }

    private function array_to_xml( array $arr, SimpleXMLElement $xml ){
        foreach( $arr as $k => $v ){
            is_array( $v ) ? $this->array_to_xml( $v, $xml->addChild( $k ) ) : $xml->addChild( $k, $v );
        }

        return $xml;
    }

    private function _getService(){
        $upload_dir = wp_upload_dir();

        $pagoEfectivo = new VGP_Pagoefectivo_Service( array(
            'apiKey' => $this->merchant_ID,
            'url2'   => self::PE_SERVER . 'PagoEfectivoWSGeneralv2/service.asmx?wsdl',
            'url3'   => self::PE_SERVER . 'pasarela/pasarela/crypta.asmx?wsdl',
            'crypto' => array(
                'securityPath' => $upload_dir['basedir'] . '/pago-efectivo',
                'publicKey'    => 'SPE_PublicKey.1pz',
                'privateKey'   => 'DKC_PrivateKey.1pz',
                'url'          => self::PE_SERVER . 'PagoEfectivoWSCrypto/WSCrypto.asmx?wsdl'
            ),

            'gen'    => array(
                'url' => self::PE_SERVER . 'GenPago.aspx'
            ),

            'mailAdmin' => get_option( 'admin_email' ),
            'medioPago' => '1'
        ) );

        return $pagoEfectivo;
    }

    public function process_payment( $order_id ) {
        $order = wc_get_order( $order_id );

        // 	    return array(
        // 	        'result'   => 'success',
        // 	        //'redirect' => $order->get_checkout_payment_url(),
        // 	        'redirect' => $this->get_return_url( $order )
        // 	    );

        $held_duration = get_option( 'woocommerce_hold_stock_minutes' );
        if ( $held_duration < 1 || 'yes' !== get_option( 'woocommerce_manage_stock' ) ) {
            $held_duration = 5 * 60; // 5h apartir de ahora
        }

        $concepToPago = 'Paycash: Orden #' . $order->get_id();

        $payLoad = array(
            'IdMoneda'         => false ? 1 : 2,
            'Total'            => $order->get_total(),
            'MetodosPago'      => 1,
            'CodServicio'      => $this->merchant_ID,
            'Codtransaccion'   => $order->get_id(),
            'EmailComercio'    => get_option( 'admin_email' ),

            // strtotime( '+' . absint( $held_duration ) . ' MINUTES', current_time( 'timestamp' ) )
            'FechaAExpirar'    => date( 'd/m/Y H:i:s', current_time( 'timestamp' ) + ((int)$held_duration * 60) ),
            'UsuarioId'        => $order->get_user_id(),
            'DataAdicional'    => "Via Web",
            'UsuarioNombre'    => $order->get_billing_first_name(),
            'UsuarioApellidos' => $order->get_billing_last_name(),
            'UsuarioLocalidad' => $order->get_billing_address_1(),
            'UsuarioProvincia' => $order->get_billing_state(),
            'UsuarioPais'      => $order->get_billing_country(),
            'UsuarioEmail'     => $order->get_billing_email(),
            //'UsuarioEmail'     => 'ruzzll7@gmail.com',
            'ConceptoPago'     => $concepToPago,
            'Telefono'         => $order->get_billing_phone(),
            'Detalles'         => array(
                'Detalle' => array(
                    'ConceptoPago' => $concepToPago,
                    'Importe'      => $order->get_total()
                )
            )
        );

        // 	    var_export( $payLoad );
        // 	    echo "\n";

        // 	    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?<SolPago/>');
        // 	    $paymentRequest = $this->array_to_xml( $payLoad, $xml )->asXML();

        $pagoEfectivo = $this->_getService();

        // 	    $paymentResponse = null;
        $paymentResponse = $pagoEfectivo->GenerarCip( $payLoad );

        // 	    dc_logger( $paymentResponse );
        // 	    return;

        if( $paymentResponse->Estado > 0 ){
            $data = ( object ) array(
                'IdOrdenPago' => ( string ) $paymentResponse->CIP->IdOrdenPago,
                'Token'        => ( string ) $paymentResponse->Token,
                'CodTrans'     => ( string ) $paymentResponse->CodTrans
            );

            update_post_meta( $order_id, '_PE_data', $data );

            //$order->payment_complete( ( string ) $paymentResponse->CodTrans );

            return array(
                'result'   => 'success',
                'redirect' => $order->get_checkout_payment_url( true )
            );
        } elseif( $paymentResponse->Estado == 0 ) {
            throw new Exception( 'Pago Efectivo : ' . $paymentResponse->Mensaje );
        } elseif( $paymentResponse->Estado == -1 ) {
            throw new Exception( 'Pago Efectivo : Hubo problemas al realizar la transacción' );
        }

        //$payLoad = array_flip( $payLoad );

        // 	    array_walk_recursive( $payLoad, array( $xml, 'addChild' ) );
        // 	    print $xml->asXML();

        // 	    print $your_xml;

        // 	    var_export( array( $payLoad, $paymentResponse ) );
        // 	    exit();
    }

    public function generate_media_html( $key, $data ) {
        wp_enqueue_media();
        $field_key = $this->get_field_key( $key );

        $defaults  = array(
            'title'             => '',
            'disabled'          => false,
            'class'             => '',
            'css'               => '',
            'placeholder'       => '',
            'type'              => 'media',
            'desc_tip'          => false,
            'description'       => '',
            'custom_attributes' => array(),
        );

        $data = wp_parse_args( $data, $defaults );
        ob_start();

        $attachment_post_id = $this->get_option( $key );

        // 		add_image_size( 'visa_store_header_logo', 240, 100, true );
        // 		$upload_link = esc_url( get_upload_iframe_src( 'image', $attachment_post_id ) );
        // 		$your_img_src = wp_get_attachment_image_src( $attachment_post_id, 'visa_store_header_logo' );


        // 		$attr = array(
        // 			'id' => 'image-preview'
        // 		);
        // 		echo wp_get_attachment_image( $attachment_post_id, 'visa_store_header_logo', false, $attr );

        // 	    		echo '<pre>';
        // 	    		var_export( array( $this->get_option( $key ), wp_get_attachment_url( $this->get_option( $key ) ) ) );
        // 	    		echo '</pre>';
        ?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php echo $this->get_tooltip_html( $data ); ?>
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<?php if( ! empty( $this->get_option( $key ) ) ){ ?>
					<div class="image-preview-wrapper">
						<img id="image-preview" src="<?php echo wp_get_attachment_url( $this->get_option( $key ) ) ?>" style="max-width: 240px; max-height: 100px;">
					</div>
					<?php } ?>
					<input id="<?php echo esc_attr( $field_key ); ?>" type="button" class="button <?php echo esc_attr( $data['class'] ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php _e( 'Upload File' ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>  />
					<input type="hidden" name="<?php echo esc_attr( $field_key ); ?>" id="image_attachment_id" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>">
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
			<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				// Uploading files
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
				var set_to_post_id = '<?php echo $attachment_post_id; ?>'; // Set this

				jQuery('#<?php echo esc_attr( $field_key ); ?>').on('click', function( event ){
					event.preventDefault();
					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						// Set the post ID to what we want
						file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
						// Open frame
						file_frame.open();
						return;
					} else {
						// Set the wp.media post id so the uploader grabs the ID we want when initialised
						wp.media.model.settings.post.id = set_to_post_id;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Select a File to upload',
						button: {
							text: 'Use this File',
						},
						multiple: false	// Set to true to allow multiple files to be selected
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();
						// Do something with attachment.id and/or attachment.url here
						$( '#image-preview' ).attr( 'src', attachment.url );
						$( '#image_attachment_id' ).val( attachment.id );
						// Restore the main post ID
						wp.media.model.settings.post.id = wp_media_post_id;
					});
						// Finally, open the modal
						file_frame.open();
				});

				// Restore the main ID when the add media button is pressed
				jQuery( 'a.add_media' ).on( 'click', function() {
					wp.media.model.settings.post.id = wp_media_post_id;
				});
			});
			</script>
		</tr>
		<?php
		return ob_get_clean();
    }

    public function init_form_fields() {
        $license = get_option( 'woocommerce_pagoefectivo_license', '' );
        $activated = get_option( 'woocommerce_pagoefectivo_activated', '0' );

        if( !empty( $license ) && $activated ){
            $this->form_fields = $this->_settings_fields();
            return;
        }

        $this->form_fields = $this->_license_fields();
    }

    private function _license_fields(){
        return array(
            'code'    => array(
                'title'   => __( 'License key', 'woocommerce-gateway-visanetperu' ),
                'label'   => __( 'Enter the license key', 'woocommerce-gateway-visanetperu' ),
                'type'    => 'text',
                'default' => get_option( 'woocommerce_pagoefectivo_license', '' )
            )
        );
    }

    private function _settings_fields() {
		return array(
			'enabled' => array(
				'title'   => __( 'Enable / Disable', 'woocommerce-gateway-visanetperu'),
				'label'   => __( 'Enable this payment gateway', 'woocommerce-gateway-visanetperu'),
				'type'    => 'checkbox',
				'default' => 'no',
			),
			'title' => array(
				'title'    => __( 'Title', 'woocommerce-gateway-visanetperu'),
				'type'     => 'text',
				'desc_tip' => __( 'Payment title the customer will see during the checkout process.', 'woocommerce-gateway-visanetperu'),
				'default'  => __( 'Tranferencia bancaria (PagoEfectivo)', 'woocommerce-gateway-visanetperu'),
				//'custom_attributes' => array( 'readonly' => true )
			),
			'description' => array(
				'title'    => __( 'Description', 'woocommerce-gateway-visanetperu'),
				'type'     => 'textarea',
				'desc_tip' => __( 'Payment description the customer will see during the checkout process.', 'woocommerce-gateway-visanetperu'),
				'default'  => __( 'Paga a través de tu banca por internet en BBVA, BCP, INTERBANK, SCOTIABANK y BANBIF. Debítalo de tu cuenta o cárgalo a tu tarjeta de crédito asociada. ', 'woocommerce-gateway-visanetperu'),
				//'css'      => 'max-width:350px;',
				//'custom_attributes' => array( 'readonly' => true )
			),

		    // pagoEfectivo credentials
		    'merchant_ID' => array(
		        'title'     => __( 'Merchand ID', 'woocommerce-gateway-visanetperu'),
		        'type'      => 'text',
		        'desc_tip'  => __( 'Código de 3 caracteres que identifica al comercio.', 'woocommerce-gateway-visanetperu'),
		    ),

		    'public_key' => array(
		        'title'    => __( 'Llave Pública', 'woocommerce-gateway-visanetperu'),
		        'desc_tip' => __( 'Carga la llave pública provista por Pago Efectivo.', 'woocommerce-gateway-visanetperu'),
		        'type'     => 'media'
		    ),

		    'private_key' => array(
		        'title'    => __( 'Llave Privada', 'woocommerce-gateway-visanetperu'),
		        'desc_tip' => __( 'Carga la llave privada provista por Pago Efectivo.', 'woocommerce-gateway-visanetperu'),
		        'type'     => 'media'
		    )
		);
	}
}
