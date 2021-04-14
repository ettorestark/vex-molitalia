<?php
/**
 * Visanet Peru - WooCommerce Gateway Session.
 *
 * @since   2.0.0
 * @package Visanet_Peru_WooCommerce_Gateway
 */

/**
 * Visanet Peru - WooCommerce Gateway Session.
 *
 * @since 2.0.0
 */
class WGVP_Session {

	protected $_session = null;

	/**
	 * Singleton instance of session.
	 *
	 * @var    Woocommerce_Gateway_VisanetPeru
	 * @since  2.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Constructor.
	 *
	 * @since  2.0.0
	 *
	 */
	protected function __construct() {
		$this->_session = WC()->session->get( 'order_visanet_session' );
	}

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   2.0.0
	 * @return  Woocommerce_Gateway_VisanetPeru A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	public function get( $key = null ){
		if( $key !== null ){
			if( ! array_key_exists( $key, $this->_session ) ){
				return false;
			} else {
				return $this->_session[$key];
			}
		} else {
			return $this->_session;
		}
	}

	public function valid_session(){
// 		return false;
		if( is_array( $this->_session ) && ! empty( $this->_session ) ){
			// check expiration
			$time = current_time( 'timestamp' );
			if( $this->_session['expirationTime'] > $time ){
				return true;
			}
		}

		return false;
	}

	protected function getGUID(){
	    if (function_exists('com_create_guid')){
	        return com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	        .substr($charid, 0, 8).$hyphen
	        .substr($charid, 8, 4).$hyphen
	        .substr($charid,12, 4).$hyphen
	        .substr($charid,16, 4).$hyphen
	        .substr($charid,20,12).$hyphen
	        .chr(125);// "}"
	        $uuid = substr($uuid, 1, 36);
	        return $uuid;
	    }
	}

	public function make_session( WGVP_Gateway_Visanet_Peru $gateway, WC_Order $order ){
		//$uuid = uniqid();
	    $uuid = $this->getGUID();
		$time = current_time( 'timestamp' );

		$accessKey   = $gateway->get_option( 'merchant_access_key' );
		$secretKey   = $gateway->get_option( 'merchant_secret_access_key' );

		//$gateway->log( 'make_session_data: ' . array( $accessKey, $secretKey ) );

		$request_url = $this->_get_request_url( $gateway );

		$data = array(
			'amount' => $order->get_total()
		);

		$args = array(
			'headers' => array(
				'Authorization'       => 'Basic ' . base64_encode( $accessKey . ':' . $secretKey ),
				'Content-Type'        => 'application/json',
				'VisaNet-Session-Key' => $uuid
			),
			'body' => json_encode( $data )
		);

		$response = wp_remote_post( $request_url, $args );
		$gateway->log( 'sessionHttpCode: ' . wp_remote_retrieve_response_code( $response ) );

		if ( is_wp_error( $response ) ) {
			wc_add_notice( $response->get_error_message(), 'error' );
			return;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( is_wp_error( $body ) ) {
			wc_add_notice( $body->get_error_message(), 'error' );
			return;
		}

		//$gateway->log( 'sessionKeyBody: ' . $body );

		$data = json_decode( $body );
		// $time = substr( intval( $data->expirationTime ), 0, 10 );
		// $time = intval( $data->expirationTime );

		$gateway->log( 'sessionKey: ' . json_encode( array( 'sessionKey' => $data->sessionKey, 'sessionToken' => $uuid ) ) );

		$this->_session = array(
			'sessionToken'   => $uuid,
			'sessionKey'     => $data->sessionKey,
			// 'expirationTime' => $data->expirationTime,
			'expirationTime' => strtotime( '+30 minutes', $time ) // 30min
		);

		WC()->session->set( 'order_visanet_session', $this->_session );
	}

	private function _get_request_url( WGVP_Gateway_Visanet_Peru $gateway ) {
	    if ( $gateway->testmode ) {
			$request_url = 'https://devapice.vnforapps.com/api.ecommerce/api/v1/ecommerce/token/';
		} else {
			$request_url = 'https://apice.vnforapps.com/api.ecommerce/api/v1/ecommerce/token/';
		}

		return $request_url . $gateway->get_option( 'merchant_code' );
	}
}
