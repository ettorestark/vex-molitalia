<?php

namespace vexfacturacionelectronica;
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*

include_once plugin_dir_path(__FILE__) .  '/admin.php';
include_once plugin_dir_path(__FILE__) .  '/validate.php';

*/


class wpFacturacionWoocommerce{

    public function __construct(){

    }

    public function ready(){
 
        add_action( 'wp_enqueue_scripts',  array(&$this, 'checkout_script') );

        add_action( 'woocommerce_order_details_after_order_table', array( &$this, 'descargar_factura') );

        add_action( 'woocommerce_after_checkout_billing_form' , array(&$this, 'show_factura_checkout_field') );

        add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'factura_update_order_meta') );
 
        add_filter( 'woocommerce_default_address_fields' , array(  &$this, 'custom_override_default_address_fields') );

        add_filter( 'woocommerce_checkout_fields', array( &$this, 'factura_checkout_field') );

        add_filter( 'woocommerce_checkout_fields', array( &$this, 'custom_override_billing_checkout_fields') );
 
        add_filter( 'woocommerce_payment_complete_order_status', array( &$this, 'mysite_woocommerce_order_status_completed'), 10, 2 );
 
    }

     // Our hooked in function - $fields is passed via the filter!
     function custom_override_billing_checkout_fields($fields) {

            unset($fields['billing']['billing_address_2']);
 
            $fields['billing']['tipo_de_documento'] = array(
                'type' => 'select',
                'label' => __('Tipo de documento', 'woocommerce'),
                'clear' => false,
                'options' => array(
                    '1' => __('DNI', 'woocommerce'),
                    '7' => __('Pasaporte', 'woocommerce'),
                    '4' => __('Carnet de extranjeria', 'woocommerce')
                ),
                'placeholder' => _x('Seleccione un tipo de documento', 'placeholder', 'woocommerce'),
                'required' => true
            ); 

            $fields['billing']['numero_documento'] = array(
                'type' => 'text',
                'required' => true,
                'label' => 'N° de documento',
                'maxlength' => '20'
            );


            return $fields;

    }


    public function descargar_factura( $order ) {
 
        $objComporbante = new \vexfacturacionelectronica\comprobanteDAO();
  
        list($comprobanteInfo) = $objComporbante->get(['ref_id' => $order->id]);

        $dataComprobante = json_decode($comprobanteInfo['data_respuesta'], true);
       

    ?>
 
        <h2> Factura electrónica </h2>

        <a href="<?PHP echo $dataComprobante['enlace_del_pdf'] ?>" target="_blank">
            <button type="submit"> PDF </button>
        </a>
   
        <a href="<?PHP echo $dataComprobante['enlace_del_xml'] ?>" target="_blank">
            <button type="submit"> XML </button>
        </a>
   
    <?php


    }
 
    public function custom_override_default_address_fields( $address_fields ) {
        unset( $address_fields['company'] );

        return $address_fields;
    }


    public function checkout_script(){
 
        // Localize the script with new data
        $translation_array = array(
            'some_string' => __( 'Some string to translate', 'plugin-domain' ),
            'a_value' => '10'
        );
        wp_enqueue_script( 'checkout_script',  plugins_url().'/facturacion-sunat/js/checkout.js', array('jquery'), '1.00', true );

        wp_localize_script('checkout_script', 'bullet_cliente', $translation_array );


        if ( is_checkout() )
        {


        }
    }


    public function factura_checkout_field($fields){

        $fields['solicitar_factura'] = array(
            'type' => 'checkbox',
            'required' => false,
            'label' => 'Factura'
        );

        $fields['nombre_fiscal'] = array(
            'type' => 'text',
            'required' => false,
            'label' => 'Razón social'
        );

        $fields['ruc'] = array(
            'type' => 'text',
            'required' => false,
            'label' => 'RUC',
            'maxlength' => '14'
        );

        $fields['direccion_fiscal'] = array(
            'type' => 'text',
            'required' => false,
            'label' => 'Dirección fiscal'
        );

        return $fields;
    }

 
    public function show_factura_checkout_field(){

        $checkout = WC()->checkout();

        $fields = $checkout->checkout_fields;

        $key = 'solicitar_factura';

        $field = $fields[$key];
 
    ?>
        <hr/> 
        <div style="margin:10px 0;">

            <div class="solicitar-factura">

                <p> Recibirás una boleta de venta. Si necesitas una factura por favor marque esta opción.</p>

                <div>
                    <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                </div>

            </div>

            <div class="datos-facturacion">
                <?php woocommerce_form_field( 'nombre_fiscal', $fields['nombre_fiscal'], get_user_meta( get_current_user_id(), 'nombre_fiscal', true ) ) ?>
                <?php woocommerce_form_field( 'ruc', $fields['ruc'], get_user_meta( get_current_user_id(), 'ruc', true ) ) ?>
                <?php woocommerce_form_field( 'direccion_fiscal', $fields['direccion_fiscal'], get_user_meta( get_current_user_id(), 'direccion_fiscal', true ) ) ?>
            </div>
            
        </div>
 
    <?php 

    }

 
    public function factura_update_order_meta( $order_id ) {

       // if( !isset( $_POST['solicitar_factura'] ) ) return;

        $this->save_order_meta( $order_id, 'solicitar_factura' );
 
        $this->save_order_meta( $order_id, 'tipo_de_documento' );
        $this->save_order_meta( $order_id, 'numero_documento' );


        if( trim($_POST['solicitar_factura']) == '1' ) {

            $this->save_order_meta( $order_id, 'nombre_fiscal' );
            $this->save_order_meta( $order_id, 'ruc' );
            $this->save_order_meta( $order_id, 'direccion_fiscal' );

            update_user_meta( get_current_user_id(), 'nombre_fiscal', $_POST['nombre_fiscal'] );
            update_user_meta( get_current_user_id(), 'ruc', $_POST['ruc'] );
            update_user_meta( get_current_user_id(), 'direccion_fiscal', $_POST['direccion_fiscal'] );

        }


    }


    private function save_order_meta( $order_id, $field ) {

        if ( empty( $_POST[$field] ) ) return;

        update_post_meta( $order_id, $field, sanitize_text_field( $_POST[$field] ) );

    }


   public function mysite_woocommerce_order_status_completed( $order_status, $order_id ) {


        /*
            $order_id

        */  
        $order = wc_get_order( $order_id );
        $order_data = $order->get_data(); // The Order data


        $factura = get_post_meta($order_id, 'solicitar_factura', true);

        if($factura == '1'){

            $tipo_de_comprobante = '1';
            $tipo_documento_cliente = '6';
            $cliente_numero = get_post_meta($order_id, 'ruc', true);
            $cliente_nombre = get_post_meta($order_id, 'nombre_fiscal', true);
            $cliente_direccion = get_post_meta($order_id, 'direccion_fiscal', true);

        }
        else
        {
            $tipo_de_comprobante = '2';
            $tipo_documento_cliente = get_post_meta($order_id, 'tipo_de_documento', true);
            $cliente_numero = get_post_meta($order_id, 'numero_documento', true);

            $cliente_nombre = $order_data['billing']['first_name'].' '.$order_data['billing']['last_name'];
            $cliente_direccion = $order_data['billing']['address_1'];
        }

        $fecha_de_emision = $order_data['date_created']->date('Y-m-d');
     
        $total_gravada = round( $order_data['total'] * 100 / 118, 2);
        $total_igv = round( ( $order_data['total'] - ($order_data['total'] * 100 / 118)), 2);
        $total = round( $order_data['total'], 2);

 
        $parametrosCliente = array(
           'cliente_email' =>  '',
           'cliente_tipo_de_documento' =>  $tipo_documento_cliente,
           'cliente_numero_de_documento' => $cliente_numero,
           'cliente_denominacion' =>  $cliente_nombre,
           'cliente_direccion' => $cliente_direccion
        );


        $parametrosPedido = array(
            'ref_tipo' => 'PEDIDO',
            'ref_id' => $order_id,
            'tipo_de_comprobante' => $tipo_de_comprobante,
            'fecha_de_emision' => $fecha_de_emision,
            'total_gravada' => $total_gravada,
            'total_igv' => $total_igv,
            'total' => $total
        );

        $detallePedido = array();


        foreach ($order->get_items() as $item_key => $productDetail){

           $item_data = $productDetail->get_data();
           $productInfo = $productDetail->get_product();
 
           $precioUnitarioSinImpuesto = round(( $productInfo->price *1) / 1.18, 2);

           $impuestoUnitario = round( $precioUnitarioSinImpuesto * 0.18, 2);
           $cantidad = $item_data['quantity'];

           $TotalSinImpuesto = round($precioUnitarioSinImpuesto * $cantidad, 2);
           $impuesto = round($TotalSinImpuesto * 0.18,2);

           $reg = array(   'detalle' => $item_data['name'],
                           'cantidad' => $cantidad,
                           'precio_unitario' => $precioUnitarioSinImpuesto, // unitario sin impuesto
                           'precio_referencial' => ($precioUnitarioSinImpuesto + $impuestoUnitario), // unitario con impuesto
                           'unidad_medida' => $item_data['unity'],
                           'subtotal' => $TotalSinImpuesto,
                           'impuesto' => $impuesto,
                           'product_id' => $item_data['product_id']
                       );


           array_push($detallePedido, $reg);
        } 

        vexfeCore::generarComprobante( $parametrosCliente, $parametrosPedido, $detallePedido );

        return true;


    } 

}

/*

add_action( 'woocommerce_view_order' ,'woocommerce_view_order' );

function woocommerce_view_order($order_id){

    $order = wc_get_order( $order_id );

    //var_dump($order);
    echo '<h1> Aquí </h1>';

}*/
