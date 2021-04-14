<?php
function chaski_regular_shipping_method() {
        if ( ! class_exists( 'WC_Shipping_Chazki_Regular_Method' ) ) {
            class WC_Shipping_Chazki_Regular_Method extends WC_Shipping_Method {
              /**
               * Constructor. The instance ID is passed to this.
               */
              public function __construct( $instance_id = 0 ) {
                $this->id                    = 'chaskiRegular';
                $this->instance_id           = absint( $instance_id );
                $this->method_title          = __( 'Chaski Regular' );
                $this->method_description    = __( 'Envíe con Chaski.' );
                $this->supports              = array(
                  'shipping-zones',
                  'instance-settings',
                  'instance-settings-modal'
                );
                
                $this->instance_form_fields = array(
                        'title' => array(
                          'title'     => __( 'Titulo del Metodo' ),
                          'type'      => 'text',
                          'description'   => __( 'Nombre del metodo que se mostrara.' ),
                          'default'   => __( 'Chaski Regular' ),
                          'desc_tip'    => true
                        )
                );
                 $this->init();
                $this->enabled        = 'yes';//$this->get_option( 'enabled' );
                $this->title          = __( 'Chaski Regular' );
              }

               function init() {
                    // Load the settings API                 
                    $this->init_form_fields(); 
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }

              /**
               * calculate_shipping function.
               * @param array $package (default: array())
               */
              public function calculate_shipping( $package = array() ) {
                $this->add_rate( array(
                  'id'    => $this->id . $this->instance_id,
                  'label' => $this->title,
                  'cost'  => 100,
                ) );
              }
            }
        }
    }
//=UAK!a-LdwBh#69$Av-HwQ--qM^hZyNF    

add_action( 'woocommerce_shipping_init', 'chaski_regular_shipping_method' );
function add_chaski_regular_shipping_method( $methods ) {
        $methods['chaskiRegular'] = 'WC_Shipping_Chazki_Regular_Method';
        return $methods;
} 
add_filter( 'woocommerce_shipping_methods', 'add_chaski_regular_shipping_method' );



add_filter('woocommerce_package_rates', 'update_shipping_costs_based_on_cart_session_custom_data_for_chaski_regular', 10, 2);
function update_shipping_costs_based_on_cart_session_custom_data_for_chaski_regular( $rates, $package ){
    
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return $rates;

    // SET HERE the default cost (when "calculated cost" is not yet defined)
    $cost = '0';

    // Get Shipping rate calculated cost (if it exists)
    $calculated_cost = WC()->session->get( 'shipping_calculated_cost_chazki_regular');

    // Iterating though Shipping Methods
    foreach ( $rates as $rate_key => $rate_values ) {

        $method_id = $rate_values->method_id;
        $rate_id = $rate_values->id;
        if ( 'chaskiRegular' === $method_id ) {
            if( ! empty( $calculated_cost ) ) {
                $cost = $calculated_cost;
            }
            
            $rates[$rate_id]->cost = $cost;
        }
    }
    return $rates;
}

?>