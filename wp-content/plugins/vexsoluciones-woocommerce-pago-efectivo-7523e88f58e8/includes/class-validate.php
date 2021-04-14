<?php
/**
 * Vex Soluciones - WooCommerce Gateway Validate.
 *
 * @since   2.0.0
 * @package Visanet_Peru_WooCommerce_Gateway
 */

/**
 * Vex Soluciones - WooCommerce Gateway Validate.
 *
 * @since 2.0.0
 */
class VGP_Validate {

    const LICENSE_SECRET_KEY = '587423b988e403.69821411';
    const LICENSE_SERVER_URL = 'http://socialmediaspiders.com';
    const ITEM_REFERENCE = 'Vex Soluciones - WooCommerce Gateway';

    /**
     * Parent plugin class.
     *
     * @since 2.0.0
     *
     * @var   Visanet_Peru_WooCommerce_Gateway
     * https://www.dekoclick.com/get-started/payment/order-pay/44452?pay_for_order=true&key=wc_order_5a04c2de6c219
     */
    protected $plugin = null;

    /**
     * Constructor.
     *
     * @since  2.0.0
     *
     * @param  Visanet_Peru_WooCommerce_Gateway $plugin Main plugin object.
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        $this->hooks();
    }

    /**
     * Initiate our hooks.
     *
     * @since  2.0.0
     */
    public function hooks() {
        if( isset( $_POST['woocommerce_pagoefectivo_code'] ) ){
            $license = $_POST['woocommerce_pagoefectivo_code'];
            update_option( 'woocommerce_pagoefectivo_license', $license, 'no' );
            unset( $_POST['woocommerce_pagoefectivo_code'] );

            if( empty( $license ) ){
                $this->_set_disabled();
                return;
            }

            $this->_activate_license( $license );
            return;
        }

        $license = get_option( 'woocommerce_pagoefectivo_license', '' );

        if( empty( $license ) ){
            $this->_set_disabled();
            return;
        }

        $activated = get_option( 'woocommerce_pagoefectivo_activated', '0' );
        $last_date = get_option( 'woocommerce_pagoefectivo_last_date', '' );

        $current_date = date( 'd/m/Y' );

        if( empty( $last_date ) && $activated ){
            return;
        }

        if( $last_date != $current_date ){
            $this->_verify_license( $license );
        }
    }

    private function _set_disabled(){
        $settings = get_option( 'woocommerce_pagoefectivo_settings', '' );

        if( empty( $settings ) ) return;

        $settings['enabled'] = 'no';

        update_option( 'woocommerce_pagoefectivo_settings', $settings, 'no' );
        update_option( 'woocommerce_pagoefectivo_activated', '0', 'no' );
    }

    private function _verify_license( $license ){
        $api_params = array(
            'slm_action' => 'slm_check',
            'secret_key' => self::LICENSE_SECRET_KEY,
            'license_key' => $license,
        );

        $url = add_query_arg( $api_params, self::LICENSE_SERVER_URL );
        $args = array(
            'timeout'   => 20,
            'sslverify' => false
        );

        $response = wp_remote_get( $url, $args );

        if ( is_wp_error( $response ) ){
            update_option( 'woocommerce_pagoefectivo_activated', '0', 'no' );

            $this->_set_disabled();

            WC_Admin_Settings::add_error( 'Vex Soluciones: Unexpected Error! The query returned with an error.' );
            return;

        }

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if( $license_data->result == 'success' ){
            $this->_activate_plugin( $license_data->message );
            return;
        }

        update_option( 'woocommerce_pagoefectivo_activated', '0', 'no' );

        WC_Admin_Settings::add_error( 'Vex Soluciones: ' . $license_data->message );
        $this->_set_disabled();
    }

    private function _activate_license( $license ){
        global $GLOBALS;

        $api_params = array(
            'slm_action'        => 'slm_activate',
            'secret_key'        => self::LICENSE_SECRET_KEY,
            'license_key'       => $license,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference'    => urlencode( self::ITEM_REFERENCE ),
        );

        $query = esc_url_raw( add_query_arg( $api_params, self::LICENSE_SERVER_URL ) );
        $response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

        if ( is_wp_error( $response ) ){
            WC_Admin_Settings::add_error( 'Vex Soluciones: Unexpected Error! The query returned with an error.' );

            $this->_set_disabled();
            return;
        }

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if( $license_data->result == 'success' ){
            $this->_activate_plugin( $license_data->message );
            return;
        }

        WC_Admin_Settings::add_error( 'Vex Soluciones: ' . $license_data->message );
        $this->_set_disabled();
    }

    private function _activate_plugin( $message ){
        update_option( 'woocommerce_pagoefectivo_activated', '1', 'no' );
        update_option( 'woocommerce_pagoefectivo_last_date', date( 'd/m/Y' ), 'no' );

        WC_Admin_Settings::add_message( 'Vex Soluciones: ' . $message );
    }
}