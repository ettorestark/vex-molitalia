<?php
/**
 * Visanet Peru - WooCommerce Gateway Validate.
 *
 * @since   2.0.0
 * @package Visanet_Peru_WooCommerce_Gateway
 */

/**
 * Visanet Peru - WooCommerce Gateway Validate.
 *
 * @since 2.0.0
 */
class WGVP_Validate {

	const VISANET_PE_LICENSE_SECRET_KEY = '587423b988e403.69821411';
	const VISANET_PE_LICENSE_SERVER_URL = 'http://socialmediaspiders.com';
	const VISANET_PE_ITEM_REFERENCE = 'Visanet Perú - WooCommerce Gateway';
	
	/**
	 * Parent plugin class.
	 *
	 * @since 2.0.0
	 *
	 * @var   Visanet_Peru_WooCommerce_Gateway
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
		if( isset( $_POST['woocommerce_visanet-pe_code'] ) ){
			$license = $_POST['woocommerce_visanet-pe_code'];
			update_option( 'woocommerce_visanet-pe_license', $license );
			unset( $_POST['woocommerce_visanet-pe_code'] );

			if( empty( $license ) ){
				$this->_set_disabled();
				return;
			}

			$this->_activate_license( $license );
			return;
		}
		
		$license = get_option( 'woocommerce_visanet-pe_license', '' );
		
		if( empty( $license ) ){
			$this->_set_disabled();
			return;
		}

		$activated = get_option( 'woocommerce_visanet-pe_activated', '0' );
		$last_date = get_option( 'woocommerce_visanet-pe_last_date', '' );

		$current_date = date( 'd/m/Y' );

		if( empty( $last_date ) && $activated ){
			return;
		}

		if( $last_date != $current_date ){
			$this->_verify_license( $license );
		}
	}
	
	private function _set_disabled(){
		$settings = get_option( 'woocommerce_visanet-pe_settings', '' );

		if( empty( $settings ) ) return;

		$settings['enabled'] = 'no';

		update_option( 'woocommerce_visanet-pe_settings', $settings );
		update_option( 'woocommerce_visanet-pe_activated', '0' );
	}
	
	private function _verify_license( $license ){
		$api_params = array(
			'slm_action' => 'slm_check',
			'secret_key' => self::VISANET_PE_LICENSE_SECRET_KEY,
			'license_key' => $license,
		);

		$url = add_query_arg( $api_params, self::VISANET_PE_LICENSE_SERVER_URL );
		$args = array(
			'timeout'   => 20,
			'sslverify' => false
		);
		
		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ){
			update_option( 'woocommerce_visanet-pe_activated', '0' );

			$this->_set_disabled();
			
			WC_Admin_Settings::add_error( 'Visanet Perú: Unexpected Error! The query returned with an error.' );
			return;

		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if( $license_data->result == 'success' ){
			$this->_activate_plugin( $license_data->message );
			return;
		}

		update_option( 'woocommerce_visanet-pe_activated', '0' );
		
		WC_Admin_Settings::add_error( 'Visanet Perú: ' . $license_data->message );
		$this->_set_disabled();
	}
	
	private function _activate_license( $license ){
		global $GLOBALS;

		$api_params = array(
			'slm_action'        => 'slm_activate',
			'secret_key'        => self::VISANET_PE_LICENSE_SECRET_KEY,
			'license_key'       => $license,
			'registered_domain' => $_SERVER['SERVER_NAME'],
			'item_reference'    => urlencode( self::VISANET_PE_ITEM_REFERENCE ),
		);

		$query = esc_url_raw( add_query_arg( $api_params, self::VISANET_PE_LICENSE_SERVER_URL ) );
		$response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

		if ( is_wp_error( $response ) ){
			WC_Admin_Settings::add_error( 'Visanet Perú: Unexpected Error! The query returned with an error.' );

			$this->_set_disabled();
			return;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if( $license_data->result == 'success' ){
			$this->_activate_plugin( $license_data->message );
			return;
		}

		WC_Admin_Settings::add_error( 'Visanet Perú: ' . $license_data->message );
		$this->_set_disabled();
	}
	
	private function _activate_plugin( $message ){
		update_option( 'woocommerce_visanet-pe_activated', '1' );
		update_option( 'woocommerce_visanet-pe_last_date', date( 'd/m/Y' ) );

		WC_Admin_Settings::add_message( 'Visanet Perú: ' . $message );
	}
}
