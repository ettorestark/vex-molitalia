<?php
/**
 * Visanet Peru - WooCommerce Gateway Wc Gateway Visanetperu.
 *
 * @since   2.0.0
 * @package Visanet_Peru_WooCommerce_Gateway
 */

/**
 * Visanet Peru - WooCommerce Gateway Wc Gateway Visanetperu.
 *
 * @since 2.0.0
 */
class WGVP_Gateway_Visanet_Peru extends WC_Payment_Gateway {
	public $settings;

	/** @var bool */
	public static $log_enabled = false;

	/** @var WC_Logger */
	public static $log = false;

	private $_denied_messages = array(
	    101 => 'Operación Denegada. Tarjeta Vencida. Verifique los datos en su tarjeta e ingréselos correctamente.',
	    102 => 'Operación Denegada. Contactar con entidad emisora de su tarjeta.',
	    104 => 'Operación Denegada. Operación no permitida para esta tarjeta. Contactar con la entidad emisora de su tarjeta.',
	    106 => 'Operación Denegada. Intentos de clave secreta excedidos. Contactar con la entidad emisora de su tarjeta.',
	    107 => 'Operación Denegada. Contactar con la entidad emisora de su tarjeta.',
	    108 => 'Operación Denegada. Contactar con la entidad emisora de su tarjeta.',
	    109 => 'Operación Denegada. Contactar con el comercio.',
	    110 => 'Operación Denegada. Operación no permitida para esta tarjeta. Contactar con la entidad emisora de su tarjeta.',
	    111 => 'Operación Denegada. Contactar con el comercio.',
	    112 => 'Operación Denegada. Se requiere clave secreta.',
	    116 => 'Operación Denegada. Fondos insuficientes. Contactar con entidad emisora de su tarjeta',
	    117 => 'Operación Denegada. Clave secreta incorrecta.',
	    118 => 'Operación Denegada. Tarjeta Inválida. Contactar con entidad emisora de su tarjeta.',
	    119 => 'Operación Denegada. Intentos de clave secreta excedidos. Contactar con entidad emisora de su tarjeta.',
	    121 => 'Operación Denegada.',
	    126 => 'Operación Denegada. Clave secreta inválida.',
	    129 => 'Operación Denegada. Código de seguridad invalido. Contactar con entidad emisora de su tarjeta',
	    180 => 'Operación Denegada. Tarjeta Inválida. Contactar con entidad emisora de su tarjeta.',
	    181 => 'Operación Denegada. Tarjeta con restricciones de débito. Contactar con entidad emisora de su tarjeta.',
	    182 => 'Operación Denegada. Tarjeta con restricciones de crédito. Contactar con entidad emisora de su tarjeta.',
	    183 => 'Operación Denegada. Problemas de comunicación. Intente más tarde.',
	    190 => 'Operación Denegada. Contactar con entidad emisora de su tarjeta.',
	    191 => 'Operación Denegada. Contactar con entidad emisora de su tarjeta.',
	    192 => 'Operación Denegada. Contactar con entidad emisora de su tarjeta.',
	    199 => 'Operación Denegada.',
	    201 => 'Operación Denegada. Tarjeta vencida. Contactar con entidad emisora de su tarjeta.'
	);

	function __construct() {
		$this->id                 = "visanet-pe";
		$this->has_fields         = false;
		$this->order_button_text  = __( "Order with VISA", 'woocommerce-gateway-visanetperu' );
		$this->method_title       = __( "Visanet", 'woocommerce-gateway-visanetperu' );
		$this->method_description = __( "Visanet Payment Gateway Plug-in for WooCommerce",  'woocommerce-gateway-visanetperu' );
		$this->supports           = array( 'products' );
		$this->icon = wc_gateway_visanetperu()->url( 'assets/images/visa.jpg' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );
		$this->testmode       = 'yes' === $this->get_option( 'testmode', 'no' );
		$this->debug          = 'yes' === $this->get_option( 'debug', 'no' );

		self::$log_enabled    = $this->debug;

		// Actions
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'capture_payment' ) );
		add_action( 'woocommerce_order_status_on-hold_to_completed', array( $this, 'capture_payment' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = 'no';
		} else {
			add_action( 'woocommerce_api_' . $this->id, array( $this, '_check_response' ) );
			add_action( 'woocommerce_receipt_' . $this->id, array( $this, '_order_pay_tpl' ) );
		}

		//echo '<pre>';
		//var_export( array( $this->get_option( 'code' ), $this->settings ) );
		//echo '</pre>';
	}

	/**
	 * Capture payment when the order is changed from on-hold to complete or processing
	 *
	 * @param  int $order_id
	 */
	public function capture_payment( $order_id ) {

	}

	/**
	 * Output for the order received page.
	 *
	 * @param int $order_id
	 */
	public function thankyou_page( $order_id ) {
	    $order = wc_get_order( $order_id );
	    $transaction = get_post_meta( $order->get_id(), '_operation_visanet', true );
	    //var_export( $order_id );
	    $account_html = '';
	    $instructions = '';
	    //$account_html .= '<h3 class="wc-bacs-bank-details-account-name">' . wp_kses_post( wp_unslash( 'Xdddasdasdasdasdsad' ) ) . ':</h3>' . PHP_EOL;

	    $account_html .= '<ul class="wc-visanet-pe-details order_details visanet-pe_details">' . PHP_EOL;


	    // Visanet fields shown on the thanks page and in emails
	    if( $order->has_status( 'completed' ) ){
    	    $account_fields = array(
    	        'cardholder_name' => array(
    	            'label' => __( 'Nombre del tarjetahabiente', 'woocommerce' ),
    	            'value' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
    	        ),
    	        'card_number' => array(
    	            'label' => __( 'Número de Tarjeta', 'woocommerce' ),
    	            'value' => $transaction->data->pan,
    	        ),
    	        'datetime'          => array(
    	            'label' => __( 'Fecha y hora del pedido', 'woocommerce' ),
    	            'value' => $transaction->data->fechayhora_tx,
    	        ),
    	        'total'           => array(
    	            'label' => __( 'Importe de la transacción', 'woocommerce' ),
    	            'value' => $order->get_formatted_order_total(),
    	        ),
    	        'currency'           => array(
    	            'label' => __( 'Moneda', 'woocommerce' ),
    	            'value' => $order->get_currency(),
    	        )
    	    );

    	    $instructions = '<p><a href="' . get_permalink( wc_get_page_id( 'terms' ) ) . '" target="_blank">Términos y Condiciones</a><br>* Imprime esta pagina y guardalo como comprobante de pago.</p>' . PHP_EOL;

    	    $instructions .= '<p><a href="javascript:window.print(); void 0;" class="no-print" style="background-color: #000; color: #fff; padding: 5px 10px;">Imprimir</a></p>' . PHP_EOL;
	    } else {
    	    $account_fields = array(
    	        'order_number' => array(
    	            'label' => __( 'Número de pedido', 'woocommerce' ),
    	            'value' => $order->get_order_number(),
    	        ),
    	        'datetime'          => array(
    	            'label' => __( 'Fecha y hora del pedido', 'woocommerce' ),
    	            'value' => $transaction->data->fechayhora_tx,
    	        ),
    	        'reason_deny' => array(
    	            'label' => __( 'Motivo de denegación', 'woocommerce' ),
    	            'value' => $this->_denied_messages[ intval( $transaction->data->codaccion ) ],
    	            //'value' => $transaction->data->dsc_cod_accion,
    	        )
    	    );
	    }

	    foreach ( $account_fields as $field_key => $field ) {
	        if ( ! empty( $field['value'] ) ) {
	            $account_html .= '<li class="' . esc_attr( $field_key ) . '">' . wp_kses_post( $field['label'] ) . ': <strong>' . wp_kses_post( wptexturize( $field['value'] ) ) . '</strong></li>' . PHP_EOL;
	        }
	    }

	    $account_html .= '</ul>';

	    echo '<section class="woocommerce-visanet-pe-details"><h2 class="wc-visanet-pe-details-heading">Detalles Visanet</h2>' . PHP_EOL . $account_html . PHP_EOL . $instructions . '</section>';
	    echo '<style type="text/css">@media print{.no-print, .no-print *{display: none !important;}}</style>';
	}

	/**
	 * Logging method.
	 *
	 * @param string $message Log message.
	 * @param string $level   Optional. Default 'info'.
	 *     emergency|alert|critical|error|warning|notice|info|debug
	 */
	public static function log( $message, $level = 'info' ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}

			if ( ! is_string( $message ) ) {
			    $message = json_encode( $message );
			}

			self::$log->log( $level, $message, array( 'source' => "visanet-pe" ) );
		}
	}

	public function admin_options() {
		if ( $this->is_valid_for_use() ) {
			parent::admin_options();
		} else {
			?>
			<div class="inline error"><p><strong><?php _e( 'Gateway disabled', 'woocommerce' ); ?></strong>: <?php _e( 'Visa does not support your store currency.', 'woocommerce' ); ?></p></div>
			<?php
		}
	}

	public function init_settings(){
		parent::init_settings();

		if( $this->is_valid_for_use() ){
			$currency = get_woocommerce_currency();

			$this->settings['merchant_code']              = $this->get_option( 'visa_code_' . $currency );
			$this->settings['merchant_access_key']        = $this->get_option( 'visa_accesskey_' . $currency );
			$this->settings['merchant_secret_access_key'] = $this->get_option( 'visa_secretaccesskey_' . $currency );
		}
	}

	public function _check_response(){
		if( $posted = wp_unslash( $_POST ) ){
			new WGVP_Visanet_Response( $this, $posted );
		}

		exit();
	}

	public function _order_pay_tpl( $order_id ){
		if( $order = wc_get_order( $order_id ) ){
			$request = new WGVP_Visanet_Request( $this, $order );
			$request->form();
		}
	}

	function process_payment( $order_id ) {
		if( $order = wc_get_order( $order_id ) ){
			return array(
				'result'   => 'success',
				'redirect' => $order->get_checkout_payment_url( true )
			);
		}

		return false;
	}

	public function is_valid_for_use() {
		return in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_paypal_supported_currencies', array(
			'PEN',
			'USD'
		) ) );
	}

	function init_form_fields() {
		$license = get_option( 'woocommerce_visanet-pe_license', '' );
		$activated = get_option( 'woocommerce_visanet-pe_activated', '0' );

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
				'default' => get_option( 'woocommerce_visanet-pe_license', '' )
			)
		);
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

// 		echo '<pre>';
// 		var_export( array( $field_key, $your_img_src, $key, $this->get_option( $key ), $data ) );
// 		echo '</pre>';
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php echo $this->get_tooltip_html( $data ); ?>
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<div class="image-preview-wrapper">
						<img id="image-preview" src="<?php echo wp_get_attachment_url( $this->get_option( $key ) ) ?>" style="max-width: 240px; max-height: 100px;">
					</div>
					<input id="<?php echo esc_attr( $field_key ); ?>" type="button" class="button <?php echo esc_attr( $data['class'] ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php _e( 'Upload image' ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>  />
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
						title: 'Select a image to upload',
						button: {
							text: 'Use this image',
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

	private function _settings_fields(){
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
				'default'  => __( 'Visanet', 'woocommerce-gateway-visanetperu'),
				//'custom_attributes' => array( 'readonly' => true )
			),
			'description' => array(
				'title'    => __( 'Description', 'woocommerce-gateway-visanetperu'),
				'type'     => 'textarea',
				'desc_tip' => __( 'Payment description the customer will see during the checkout process.', 'woocommerce-gateway-visanetperu'),
				'default'  => __( 'Visa', 'woocommerce-gateway-visanetperu'),
				//'css'      => 'max-width:350px;',
				//'custom_attributes' => array( 'readonly' => true )
			),

			// Visa credentials
			'api_details' => array(
				'title'       => __( 'API credentials', 'woocommerce' ),
				'type'        => 'title',
				'description' => sprintf( __( 'Enter your Visa API credentials to process refunds via Visa. Learn how to access your <a href="%s">Visa API Credentials</a>.', 'woocommerce' ), '#' ),
			),
			'visa_code_PEN' => array(
				'title'     => __( 'Visanet Perú Client Code (PEN: Peruvian Nuevo Sol)', 'woocommerce-gateway-visanetperu'),
				'type'      => 'text',
				'desc_tip'  => __( 'This is the code provided by Visanet Perú.', 'woocommerce-gateway-visanetperu'),
			),
			'visa_accesskey_PEN' => array(
				'title'          => __( 'Visanet Perú Access Key (PEN: Peruvian Nuevo Sol)', 'woocommerce-gateway-visanetperu'),
				'type'           => 'text',
				'desc_tip'       => __( 'This is the access key provided by Visanet Perú.', 'woocommerce-gateway-visanetperu'),
			),
			'visa_secretaccesskey_PEN' => array(
				'title'                => __( 'Visanet Perú Secret Key (PEN: Peruvian Nuevo Sol)', 'woocommerce-gateway-visanetperu'),
				'type'                 => 'text',
				'desc_tip'             => __( 'This is the secret key provided by Visanet Perú.', 'woocommerce-gateway-visanetperu'),
			),
			'visa_code_USD' => array(
				'title'     => __( 'Visanet Perú Client Code (USD: USA Dollar)', 'woocommerce-gateway-visanetperu'),
				'type'      => 'text',
				'desc_tip'  => __( 'This is the code provided by Visanet Perú.', 'woocommerce-gateway-visanetperu'),
			),
			'visa_accesskey_USD' => array(
				'title'          => __( 'Visanet Perú Access Key (USD: USA Dollar)', 'woocommerce-gateway-visanetperu'),
				'type'           => 'text',
				'desc_tip'       => __( 'This is the access key provided by Visanet Perú.', 'woocommerce-gateway-visanetperu'),
			),
			'visa_secretaccesskey_USD' => array(
				'title'                => __( 'Visanet Perú Secret Key (USD: USA Dollar)', 'woocommerce-gateway-visanetperu'),
				'type'                 => 'text',
				'desc_tip'             => __( 'This is the secret key provided by Visanet Perú.', 'woocommerce-gateway-visanetperu'),
			),

			// merchant
			'merchant_title' => array(
				'title'    => __( 'Formulario de pago', 'woocommerce-gateway-visanetperu'),
				'type'     => 'title',
				'description' => __( 'Datos a mostrar en el modal', 'woocommerce-gateway-visanetperu'),
			),
			'merchant_name' => array(
				'title'    => __( 'Nombre', 'woocommerce-gateway-visanetperu'),
				'type'     => 'text',
				'description' => __( 'Nombre del comercio (se mostrará en caso se omita el logo). Se sugiere una longitud máxima de 25 caracteres.', 'woocommerce-gateway-visanetperu'),
			),
			'merchant_logo' => array(
				'title'       => __( 'Logo del comercio', 'woocommerce-gateway-visanetperu'),
				'description' => __( 'Altamente recomendable incluir un logo, caso contrario se mostrará el nombre del comercio', 'woocommerce-gateway-visanetperu'),
				'type'       => 'media',
				'default'    => ''
			),
			'button_modal_color' => array(
				'title'    => __( 'Color del boton', 'woocommerce-gateway-visanetperu'),
				'type'     => 'color',
				'description' => __( 'Define el color del botón “Pagar” en el formulario.', 'woocommerce-gateway-visanetperu'),
				'default'  => '#D80000'
			),
			'showamount' => array(
				'title'    => __( 'Mostrar cantidad', 'woocommerce-gateway-visanetperu'),
				'type'     => 'select',
				'description' => __( 'Indica si se muestra el monto a pagar en el botón Pagar del formulario. ', 'woocommerce-gateway-visanetperu'),
				'default'  => 'true',
				'options'  => array(
						'true' => __( 'Si', 'woocommerce-gateway-visanetperu' ),
						'false' => __( 'No', 'woocommerce-gateway-visanetperu' )
				)
			),

			// modal visa
			'modal_visa' => array(
				'title'    => __( 'Boton Visa', 'woocommerce-gateway-visanetperu'),
				'type'     => 'title',
				'description' => __( 'Personaliza el boton que levanta el formulario de pago.', 'woocommerce-gateway-visanetperu'),
			),
			'button_size'  => array(
				'title'    => __( 'Tamaño', 'woocommerce-gateway-visanetperu' ),
				'type'     => 'select',
				'description' => __( 'Tamaño del Botón de Pago', 'woocommerce-gateway-visanetperu'),
				'default'  => 'default',
				'options'  => array(
					'default' => __( 'Default', 'woocommerce-gateway-visanetperu' ),
					'small'   => __( 'Small', 'woocommerce-gateway-visanetperu' ),
					'medium'  => __( 'Medium', 'woocommerce-gateway-visanetperu' ),
					'large'   => __( 'Large', 'woocommerce-gateway-visanetperu' )
				)
			),
			'button_color' => array(
				'title'    => __( 'Color', 'woocommerce-gateway-visanetperu'),
				'type'     => 'select',
				'description' => __( 'Color del Botón de Pago.', 'woocommerce-gateway-visanetperu'),
				'default'  => 'navy',
				'options'  => array(
					'navy' => __( 'Navy', 'woocommerce-gateway-visanetperu' ),
					'gray' => __( 'Gray', 'woocommerce-gateway-visanetperu' )
				)
			),

			'testing_title' => array(
				'title'    => __( 'Modo de pruebas', 'woocommerce-gateway-visanetperu'),
				'type'     => 'title',
				'description' => __( 'Compruebe que funciona correctamente.', 'woocommerce-gateway-visanetperu'),
			),

		    // testing
		    'testmode'        => array(
		        'title'       => __( 'Visanet Perú Test Mode', 'woocommerce-gateway-visanetperu'),
		        'label'       => __( 'Enable Test Mode', 'woocommerce-gateway-visanetperu'),
		        'type'        => 'checkbox',
		        'description' => __( 'Place the payment gateway in test mode.', 'woocommerce-gateway-visanetperu'),
		        'default'     => 'yes',
		    ),

		    'debug' => array(
		        'title'       => __( 'Debug log', 'woocommerce-gateway-visanetperu' ),
		        'type'        => 'checkbox',
		        'label'       => __( 'Enable logging', 'woocommerce-gateway-visanetperu' ),
		        'default'     => 'no',
		        'description' => sprintf( __( 'Log Visanet events, such as HTTP requests, inside %s', 'woocommerce-gateway-visanetperu' ), '<code>' . WC_Log_Handler_File::get_log_file_path( $this->id ) . '</code>' ),
		    )
		);
	}
}
