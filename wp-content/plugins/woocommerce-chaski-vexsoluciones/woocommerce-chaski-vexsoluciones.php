<?php
/*
  Plugin Name: Woocommerce Chaski Plugin by VexSoluciones
  Plugin URI:  https://www.pasarelasdepagos.com/
  Description: Woocommerce Chaski Plugin by VexSoluciones.
  Version: 1.0
  Author: Vex Soluciones
  Text Domain: woocommerce-chaski-vexsoluciones
  Copyright 2018 Vex Soluciones
 */

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}
define('WUP_PATH', dirname(__FILE__));

include_once(WUP_PATH .'/class-chaski-express.php');
include_once(WUP_PATH .'/class-chaski-regular.php');

add_action('admin_notices', 'wp_chaski_admin_notice');
function wp_chaski_admin_notice() {
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

        echo '<div class="error"><p>' . __('WooCommerce Chaski requires woocoomerce plugin to be installed before..', 'woocommerce-pickup-location') . '</p></div>';

        deactivate_plugins('woocommerce-chaski-vexsoluciones/woocommerce-chaski-vexsoluciones.php');
    }
}


add_action( 'admin_menu', 'chaski_add_admin_menu' );
add_action( 'admin_init', 'chaski_settings_init' );

function chaski_add_admin_menu(  ) { 
  add_menu_page( 'Chaski', 'Chaski', 'manage_options', 'chaski', 'chaski_options_page' );
}


function chaski_settings_init(  ) { 

  register_setting( 'pluginPage', 'chaski_settings' );

  add_settings_section(
    'chaski_pluginPage_section', 
    __( 'Configuracion Chaski', 'chaski' ), 
    'chaski_settings_section_callback', 
    'pluginPage'
  );


  add_settings_field( 
    'titulo', 
    __( 'Titulo', 'chaski' ), 
    'titulo_render', 
    'pluginPage', 
    'chaski_pluginPage_section' 
  );

  add_settings_field( 
    'urlChaski', 
    __( 'URL Chaski', 'chaski' ), 
    'urlChaski_render', 
    'pluginPage', 
    'chaski_pluginPage_section' 
  );

  add_settings_field( 
    'chaskiKey', 
    __( 'Chaski Key', 'chaski' ), 
    'chaskiKey_render', 
    'pluginPage', 
    'chaski_pluginPage_section' 
  );

  add_settings_field( 
    'storeID', 
    __( 'Store ID', 'chaski' ), 
    'storeID_render', 
    'pluginPage', 
    'chaski_pluginPage_section' 
  );

  add_settings_field( 
    'branchId', 
    __( 'Branch ID', 'chaski' ), 
    'branchId_render', 
    'pluginPage', 
    'chaski_pluginPage_section' 
  );

  add_settings_field( 
    'latInit', 
    __( 'Latitud Inicial', 'chaski' ), 
    'latInit_render', 
    'pluginPage', 
    'chaski_pluginPage_section' 
  );

  add_settings_field( 
    'lonInit', 
    __( 'Longitud Inicial', 'chaski' ), 
    'lonInit_render', 
    'pluginPage', 
    'chaski_pluginPage_section' 
  );


}





function titulo_render(  ) { 

  $options = get_option( 'chaski_settings' );
  ?>
  <input type='text' name='chaski_settings[titulo]' value='<?php echo $options['titulo']; ?>'>
  <?php

}


function urlChaski_render(  ) { 

  $options = get_option( 'chaski_settings' );
  ?>
  <input type='text' name='chaski_settings[urlChaski]' value='<?php echo $options['urlChaski']; ?>'>
  <?php

}


function chaskiKey_render(  ) { 

  $options = get_option( 'chaski_settings' );
  ?>
  <input type='text' name='chaski_settings[chaskiKey]' value='<?php echo $options['chaskiKey']; ?>'>
  <?php

}


function storeID_render(  ) { 

  $options = get_option( 'chaski_settings' );
  ?>
  <input type='text' name='chaski_settings[storeID]' value='<?php echo $options['storeID']; ?>'>
  <?php

}


function branchId_render(  ) { 

  $options = get_option( 'chaski_settings' );
  ?>
  <input type='text' name='chaski_settings[branchId]' value='<?php echo $options['branchId']; ?>'>
  <?php

}


function latInit_render(  ) { 

  $options = get_option( 'chaski_settings' );
  ?>
  <input type='text' name='chaski_settings[latInit]' value='<?php echo $options['latInit']; ?>'>
  <?php

}


function lonInit_render(  ) { 

  $options = get_option( 'chaski_settings' );
  ?>
  <input type='text' name='chaski_settings[lonInit]' value='<?php echo $options['lonInit']; ?>'>
  <?php

}


function chaski_settings_section_callback(  ) { 

  echo __( 'Datos necesarios para el plugin', 'chaski' );

}


function chaski_options_page(  ) { 

  ?>
  <form action='options.php' method='post'>

    <h2>Chaski</h2>

    <?php
    settings_fields( 'pluginPage' );
    do_settings_sections( 'pluginPage' );
    submit_button();
    ?>

  </form>
  <?php

}

/*

add_filter('woocommerce_package_rates', 'update_shipping_costs_based_on_cart_session_custom_data_for_chaski', 10, 2);
function update_shipping_costs_based_on_cart_session_custom_data_for_chaski( $rates, $package ){
    
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return $rates;

    // SET HERE the default cost (when "calculated cost" is not yet defined)
    $cost = '0';

    // Get Shipping rate calculated cost (if it exists)
    $calculated_cost = WC()->session->get( 'shipping_calculated_cost_chazki');

    // Iterating though Shipping Methods
    foreach ( $rates as $rate_key => $rate_values ) {

        $method_id = $rate_values->method_id;
        $rate_id = $rate_values->id;
        if ( 'chaski' === $method_id ) {
            if( ! empty( $calculated_cost ) ) {
                $cost = $calculated_cost;
            }
            
            $rates[$rate_id]->cost = $cost;
        }
    }
    return $rates;
}



function chaski_shipping_method() {
        if ( ! class_exists( 'Chaski_Shipping_Method' ) ) {
            class Chaski_Shipping_Method extends WC_Shipping_Method {
                public function __construct() {
                    $this->id                 = 'chaski'; 
                    $this->method_title       = __( 'Chazki', 'tutsplus' );  
                    $this->method_description = __( 'Metodo de envio Chaski', 'tutsplus' ); 
 
                    $this->init();
 
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Chazki', 'tutsplus' );
                    $this->storeidconfig = $this->settings['storeidconfig'];
                    $this->branchidconfig = $this->settings['branchidconfig'];
                    $this->url_chazki = $this->settings['url_chazki'];
                    $this->chazki_key_config = $this->settings['chazki_key_config'];
                }
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                    
                }
                function init_form_fields() { 
 
                    $this->form_fields = array( 
                     'enabled' => array(
                          'title' => __( 'Activar', 'tutsplus' ),
                          'type' => 'checkbox',
                          'description' => __( 'Activar este tipo de envio.', 'tutsplus' ),
                          'default' => 'yes'
                          ),
             
                     'title' => array(
                        'title' => __( 'Titulo', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Titulo para mostrar', 'tutsplus' ),
                          'default' => __( 'Chaski', 'tutsplus' )
                          ),

                     'url_chazki' => array(
                        'title' => __( 'Chazki WS URL', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Chazki web service url', 'tutsplus' ),
                          'default' => __( 'https://sandboxintegracion.chazki.com:8443/', 'tutsplus' )
                          ),

                     'chazki_key_config'=> array(
                        'title' => __( 'Chazki API key', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Chazki API Key', 'tutsplus' ),
                          'default' => __( '6UdfUPiIPR5Zez1chaZkihPh5eMUtr0QOjzxl0', 'tutsplus' )
                          ),

                     'storeidconfig' => array(
                        'title' => __( 'Store ID', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Store ID', 'tutsplus' ),
                          'default' => __( '10294', 'tutsplus' )
                          ),

                     'branchidconfig' => array(
                        'title' => __( 'Branch ID', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Branch ID', 'tutsplus' ),
                          'default' => __( 'AZASUC172', 'tutsplus' )
                          ),

                     'latInit' => array(
                        'title' => __( 'Latitud Inicial', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Latitud de la tienda', 'tutsplus' ),
                          'default' => __( '-77.0282400', 'tutsplus' )
                          ),

                     'longInit' => array(
                        'title' => __( 'Longitud Inicial', 'tutsplus' ),
                          'type' => 'text',
                          'description' => __( 'Longitud de la tienda', 'tutsplus' ),
                          'default' => __( '-12.0431800', 'tutsplus' )
                          ),
             
                     );
 
                }
                public function calculate_shipping( $package ) {
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        //'cost' => 20
                    );
 
                    $this->add_rate( $rate );
                    
                }
            }
        }
    }


    add_action( 'woocommerce_shipping_init', 'chaski_shipping_method' );

    function add_chaski_shipping_method( $methods ) {
        $methods[] = 'Chaski_Shipping_Method';
        return $methods;
    }
 
    add_filter( 'woocommerce_shipping_methods', 'add_chaski_shipping_method' );
*/



add_action('wp_enqueue_scripts','gogolemaps_init_1');

function gogolemaps_init_1() {
  
    wp_enqueue_script( 'initMapChazki', plugin_dir_url( __FILE__ ) . 'js/chaski.js' , '', '', true);
    wp_localize_script('initMapChazki', 'myScript', array(
        'chazkiPlugin' => plugin_dir_url( __FILE__ ),
        'latInit' => get_option('chaski_settings')['latInit'],
        'longInit' => get_option('chaski_settings')['lonInit']
    ));
    wp_enqueue_style( 'styleChazki', plugin_dir_url( __FILE__ ) . '/css/chazki.css',false,'','all');
    wp_enqueue_script( 'googlemaps-async-defer', '//maps.googleapis.com/maps/api/js?key=AIzaSyDycZSwKf1axWn936tPCMf36HqSwGMp-pg&libraries=places,geometry&sensor=false&callback=initMap', '', '', true );
}

$DELIVERY_TRACK_CHAZKI = "";

add_action( 'woocommerce_order_status_completed', 'wc_send_order_to_mypage_chaski' );
function wc_send_order_to_mypage_chaski( $order_id ) {
/*$shipping_add = [
            "firstname" => $order->shipping_first_name,
            "lastname" => $order->shipping_last_name,
            "address1" => $order->shipping_address_1,
            "address2" => $order->shipping_address_2,
            "city" => $order->shipping_city,
            "zipcode" => $order->shipping_postcode,
            "phone" => $order->shipping_phone,
            "state_name" => $order->shipping_state,
            "country" => $order->shipping_country
        ];*/
        

        $order = wc_get_order($order_id);

        $order_data = $order->get_data(); 

        
        $storeidconfig = get_option('chaski_settings')['storeID'];
        $branchidconfig = get_option('chaski_settings')['branchId'];   
        $orderWoo_id = $branchidconfig.$order_id;
        $orderShipping = $order->get_total_shipping();

        $customer_firstname = ($order->get_shipping_first_name() == "") ? $order->get_billing_first_name() : $order->get_shipping_first_name();
        $customer_lastname = ($order->get_shipping_last_name() == "") ? $order->get_billing_last_name() : $order->get_shipping_last_name();
        $company_name = "Vex Soluciones";
        $correo = $order->get_billing_email();
        $customer_phone = $order->get_billing_phone();
        $street1 = ($order->get_shipping_address_1() == "") ? $order->get_billing_address_1() : $order->get_shipping_address_1();
        $nota = $order->get_customer_note();

        $customerCountry = ($order->get_shipping_country() == "") ? $order->get_billing_country() : $order->get_shipping_country();
        $productoslistado = '';

        // Return an array of shipping costs within this order.
        $order->get_shipping_methods(); 

        // Conditional function based on the Order shipping method 
        if( $order->has_shipping_method('chaskiExpress') || $order->has_shipping_method('chaskiRegular')) { 
            $order = wc_get_order(  $order_id );
            $items;
            $modeC = '';
            ($order->has_shipping_method('chaskiExpress')) ? $modeC = 'Express' : 'Regular';
            // The text for the note
            foreach( $order->get_items() as $item  ){
                $items .= $item;
                $product = wc_get_product( $item['product_id'] );
                $weight = $product->weight;
                $product_name = $item['name'];
                $precio =  $item['total'];                
                $num_quantity =  $item['quantity'];
                $currency =  $order_data['currency'];

                 $productoslistado .= "{
                    \"name\": \"".$product_name."\",
                    \"currency\": \"".$currency."\",
                    \"price\": ".$precio.",
                    \"weight\": ".$weight.",
                    \"volum_weight\": ".$weight.",
                    \"num_quantity\": ".$num_quantity.",
                    \"quantity\": \"Paquete\",
                    \"size\":\"S\"
                },";
            }
            $productoslistado = ($productoslistado=="")?"":substr($productoslistado, 0, -1);

            $CURL_REQUEST = "[
                                        {
                                            \"storeId\": \"".$storeidconfig."\",
                                            \"branchId\": \"".$branchidconfig."\",
                                            \"deliveryTrackCode\": \"".$orderWoo_id.'3'."\",
                                            \"proofPayment\": \"Boleta\",
                                            \"deliveryCost\": ".$orderShipping.",
                                            \"mode\": \"".$modeC."\",
                                            \"time\" : \"\",
                                            \"paymentMethod\": \"Pagado\",
                                            \"country\": \"".$customerCountry."\",
                                            \"listItemSold\": [
                                                ".$productoslistado."
                                            ],
                                            \"notes\": \"".$nota."\",
                                            \"documentNumber\": \"12345678987\",
                                            \"name_tmp\": \"".$customer_firstname."\",
                                            \"last_tmp\": \"".$customer_lastname."\",
                                            \"company_name\": \"".$company_name."\",
                                            \"email\": \"".$correo."\",
                                            \"phone\": \"".$customer_phone."\",
                                            \"documentType\": \"RUC\",
                                            \"addressClient\": [
                                                {
                                                    \"department\": \"LIMA\",
                                                    \"province\": \"LIMA\",
                                                    \"district\": \"SAN BORJA\",
                                                    \"name\": \"".$street1."\",
                                                    \"phone\":\"".$customer_phone."\",
                                                    \"reference\": \"".$nota."\",
                                                    \"alias\":\"".""."\",
                                                    \"position\": {
                                                        \"latitude\": 0,
                                                        \"longitude\": 0
                                                    }
                                                }
                                            ]
                                        }
                                    ]";
            // Add the note
            $urlconfig = get_option('chaski_settings')['urlChaski'];
            $keyconfig = get_option('chaski_settings')['chaskiKey'];   

                    $curl = curl_init($urlconfig."chazkiServices/delivery/create/deliveryService");
                    /*curl_setopt($curl, CURLOPT_VERBOSE, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 0);*/ 
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
                    curl_setopt($curl, CURLOPT_POST, 1);

                    curl_setopt($curl, CURLOPT_POSTFIELDS, $CURL_REQUEST);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                                       
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
                            "Cache-Control: no-cache",
                            "Content-Type: application/json",
                            "chazki-api-key: ".$keyconfig
                            )                                                                       
                        );     

                    $response = curl_exec($curl);
                    
                    $response = json_decode($response,true);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        //$order->add_order_note($err);
                         $order->update_meta_data( 'chazki_delivery_track', $err );
                         //$order->update_meta_data( 'latlngchazki', WC()->session->get( 'latlngchazki'));
                    } else {
                        $order->update_meta_data( 'chazki_delivery_track', $response['codeDelivery'] );
                         //$order->update_meta_data( 'latlngchazki', WC()->session->get( 'latlngchazki'));
                        //$order->add_order_note($response['codeDelivery']);
                    }

            /*$order->add_order_note( $storeidconfig.'+'.$branchidconfig.'+'.$orderWoo_id.'+'.$orderShipping.'+'.$customer.'+'.$customerCountry+' '+$productoslistadoprint_r($response, true));*/

            // Save the data
            $order->save();
        }
        
}






add_action('woocommerce_checkout_update_order_meta', 'customise_checkout_field_update_order_meta_chazki');
 
function customise_checkout_field_update_order_meta_chazki($order_id)
{		
	if (!empty($_POST['latlngchazki'])) {
		update_post_meta($order_id, 'latlngchazki', sanitize_text_field($_POST['latlngchazki']));
	}

  //update_post_meta($order_id, 'latlngchazki', sanitize_text_field($_POST['latlngchazki']));
}

/**
 * Add the field to the checkout page
 */
add_action('woocommerce_after_order_notes', 'customise_checkout_field_chazki');
 
function customise_checkout_field_chazki($checkout)
{
  echo '<div id="customise_checkout_field">';
  woocommerce_form_field('latlngchazki', array(
    'type' => 'text',
    'class' => array(
      'latlngchazki-form form-row-wide'
    ) ,
    'label' => __('Customise Additional Field') ,
    'placeholder' => __('Guidence') ,
    'required' => false,
  ) , $checkout->get_value('latlngchazki'));
  echo '</div>';
}

function sv_wc_add_my_account_orders_column_chazki( $columns ) {
  $new_columns = array();
  foreach ( $columns as $key => $name ) {
    $new_columns[ $key ] = $name;
    // add ship-to after order status column
    if ( 'order-status' === $key ) {
      $new_columns['chazki-track'] = __( 'UBICACION DE PEDIDO', 'chazki-woocommerce' );
    }
  }
  return $new_columns;
}
add_filter( 'woocommerce_my_account_my_orders_columns', 'sv_wc_add_my_account_orders_column_chazki' );
$orderNum=0;
function sv_wc_my_orders_ship_to_column_chazki( $order ) {
  $chazki_track = $order->get_meta('chazki_delivery_track');
  $storeidconfig = get_option('chaski_settings')['storeID'];

  $url_chazki = get_option('chaski_settings')['urlChaski'];
  $keyconfig = get_option('chaski_settings')['chaskiKey']; 
          $curl = curl_init($url_chazki.'chazkiServices/track/select/deliveryCode?code='.$chazki_track.'&store='.$storeidconfig);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);                                                              
          curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
              "Cache-Control: no-cache",
              "Content-Type: application/json",
              "chazki-api-key: ".$keyconfig
              )                                                                       
          );
          $response = curl_exec($curl);
          $response = json_decode($response,true);
          $response['icono'] = "https://api.migeodelivery.com/apidoc/img/icons/scooter.png";

          $err = curl_error($curl);

          curl_close($curl);
          if ($err) {
              $response = $err;
          }

        //  return $response;
        //}
        
        /*$response['destino']['latitude'] = $latlngarray[0];
        $response['destino']['longitude'] = $latlngarray[1];*/

  if(!empty($chazki_track)) {
    echo '
    <a class="btn btn-primary btn-lg" href="#myModal-'.$order->get_order_number().'" data-toggle="modal">Ver Ubicacion</a>

<!-- Modal -->
<div id="myModal-'.$order->get_order_number().'" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="text-align: center;">
            <input type="hidden" id="latlangChazki-'.$order->get_order_number().'" value="'.$response['position']['latitude'].','.$response['position']['longitude'].'">
            <input type="hidden" id="latlngDest-'.$order->get_order_number().'" value="'.get_post_meta($order->get_order_number(), 'latlngchazki', true).'">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Seguimiento Chazki</h4>
            </div>
            <div id="map-'.$order->get_order_number().'" style="width: 100%;height: 400px;margin-bottom: 20px"></div>
            <b>Estado: </b>'.$response['status'].'<br>
            <b>Fecha: </b>'.$response['timestamp'].'<br>
            <b>Motorizado: </b>'.$response['rd'].'
            <div class="modal-footer"></div>
            </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';

  };
  
}
add_action( 'woocommerce_my_account_my_orders_column_chazki-track', 'sv_wc_my_orders_ship_to_column_chazki' );


add_action('woocommerce_checkout_before_order_review', function(){
        
        ?>
        <div class="urbaner-map-template" style="margin-bottom: 20px">      
            <h2>Seleccione punto de envío</h2>
            <div id="map" style="width: auto;height: 400px;margin-bottom: 20px"></div>
            <div id="chaskiKm"></div>
            <input type="hidden" id="priceReload">

            <!--div style="width: 100%;display: inline-flex;">
              <input name="chaskiType" type="radio" value="3"> 
              <span style="font-weight: bold; display: flex;">Programado</span>
            </div-->

        </div>
        <?php
  });

?>