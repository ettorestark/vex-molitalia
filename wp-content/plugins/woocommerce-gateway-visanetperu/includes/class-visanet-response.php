<?php
/**
 * Visanet Peru - WooCommerce Gateway Visanet Response.
 *
 * @since   2.0.0
 * @package Visanet_Peru_WooCommerce_Gateway
 */

/**
 * Visanet Peru - WooCommerce Gateway Visanet Response.
 *
 * @since 2.0.0
 */
class WGVP_Visanet_Response {
	const RESPUESTA_AUTORIZADA = "1";

	/**
	 * Parent gateway class.
	 *
	 * @since 2.0.0
	 *
	 * @var   WGVP_Gateway_Visanet_Peru
	 */
	protected $_gateway = null;

	/**
	 * data array.
	 *
	 * @since 2.0.0
	 *
	 * @var   Array
	 */
	protected $_data = null;

	/**
	 * Constructor.
	 *
	 * @since  2.0.0
	 *
	 * @param  WGVP_Gateway_Visanet_Peru $gateway object.
	 */
	public function __construct( $gateway, $data ) {
		$this->_gateway = $gateway;
		$this->_data = ( object ) $data;

		//$session = WGVP_Session::get_instance();

		//echo 'xd';

		//echo '<pre>';
		//var_export( array( $data ) );
		//echo '</pre>';
		$this->_transaction = $this->_request_transaction();

		if( $this->_transaction && ( $order = wc_get_order( $this->_transaction->data->numorden ) ) ){
				//echo '<pre>';
				//var_export( array( $order, $this->_transaction ) );
				//var_export( array( $this->_transaction ) );
				//echo '</pre>';
				//exit();

			update_post_meta( $order->id, '_operation_visanet', $this->_transaction );
			$this->payment_on_hold( $order, 'Processing payment' );

			if( $this->_valid_transaction( $order ) ){
				// save userToken
				if( is_user_logged_in() ){
					$userTokenId = '';
					$current_user = wp_get_current_user();

					if( ! empty( $this->_transaction->usertokenid ) ){
						$userTokenId = $this->_transaction->usertokenid;
					} elseif( ! empty( $this->_transaction->data->usertokenuuid )  ){
						$userTokenId = $this->_transaction->data->usertokenuuid;
					}

					if( ! empty( $userTokenId ) ){
						update_user_meta( $current_user->ID, '_visaperu_userTokenId', $userTokenId );
					}
				}

				$this->payment_status_completed( $order, $this->_transaction->data );
				$order->update_status( 'completed', $order->id );
			} else {
				$message = array_shift( $this->_transaction->errormessage );

				$order->add_order_note( $message );
				$order->update_status( 'failed', $order->id );

				wc_add_notice( $message, 'error' );
			}

			WC()->session->set( 'order_visanet_session', null );
			wp_redirect( $order->get_checkout_order_received_url() );
		} else {
		    $this->_cancel_order();
		    WC()->session->set( 'order_visanet_session', null );
			wc_add_notice( 'Unexpected error, please try again', 'error' );
			wp_redirect( wc_get_page_permalink( 'checkout' ) );
		}

		exit();
	}

	private function _cancel_order(){
		$order_id = WC()->session->get( 'order_awaiting_payment' );

		if( $order_id && ( $order = wc_get_order( $order_id ) ) ){
			WC()->session->set( 'order_awaiting_payment', false );
			$order->update_status( 'cancelled', __( 'Order cancelled by system.', 'woocommerce-gateway-visanetperu' ) );
			do_action( 'woocommerce_cancelled_order', $order->get_id() );
		}
	}

	/**
	 * @param WC_Order $order
	 * @param array $operation
	 */
	private function payment_status_completed( $order, $operation ) {
		if ( $order->has_status( 'completed' ) ) {
			WC_Gateway_Paypal::log( 'Aborting, Order #' . $order->id . ' is already complete.' );
			return;
		}

		WC()->session->set( 'order_visanet_session', null );
		$this->payment_complete( $order, $operation->id_unico, 'Visanet payment completed' );
	}

	/**
	 * Complete order, add transaction ID and note.
	 *
	 * @param  WC_Order $order
	 * @param  string $txn_id
	 * @param  string $note
	 */
	private function payment_complete( $order, $txn_id = '', $note = '' ) {
		$order->add_order_note( $txn_id . ' - ' . $note );
		$order->payment_complete( $txn_id );
	}

	/**
	 * Hold order and add note.
	 *
	 * @param  WC_Order $order
	 * @param  string $reason
	 */
	private function payment_on_hold( WC_Order $order, $reason = '' ) {
		$order->update_status( 'on-hold', $reason );

		// reduce stock
		wc_reduce_stock_levels( $order->get_id() );
		wc_empty_cart();
	}

	private function _valid_transaction( WC_Order $order ){
		$valid = (
			$this->_transaction->data->respuesta == self::RESPUESTA_AUTORIZADA &&
			$this->_transaction->data->imp_autorizado == $order->get_total() &&
			true
		);

		return $valid;
	}

	private function _get_request_url() {
	    if ( $this->_gateway->testmode ) {
			$request_url = 'https://devapice.vnforapps.com/api.authorization/api/v1/authorization/web/';
		} else {
			$request_url = 'https://apice.vnforapps.com/api.authorization/api/v1/authorization/web/';
		}

		//$request_url = 'https://apice.vnforapps.com/api.authorizatio/api/v1/authorization/web/';

		return $request_url . $this->_gateway->get_option( 'merchant_code' );
	}

	private function _request_transaction(){
		$accessKey   = $this->_gateway->get_option( 'merchant_access_key' );
		$secretKey   = $this->_gateway->get_option( 'merchant_secret_access_key' );

		$session = WGVP_Session::get_instance();
		$request_url = $this->_get_request_url();

		$data = array(
			'transactionToken' => $this->_data->transactionToken,
			'sessionToken'     => $session->get( 'sessionToken' )
		);

		$args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $accessKey . ':' . $secretKey ),
			    'Content-Type'  => 'application/json',
			    'VisaNet-Session-Key' => $session->get( 'sessionToken' )
			),
			'body' => json_encode( $data )
		);

		$response = wp_remote_post( $request_url, $args );
		$this->_gateway->log( 'authorizationHttpCode: ' . wp_remote_retrieve_response_code( $response ) );
		$this->_gateway->log( 'authorizationHttpCode:request: ' . json_encode( $args ) );
		$this->_gateway->log( 'authorizationHttpCode:response: ' . json_encode( $response ) );

		//echo '<pre>';
		//print_r( array( $response ) );
		//echo "\n";
		//print_r( array( $response, $data, $args, $request_url, is_wp_error( $response ), wp_remote_retrieve_response_code( $response ) ) );
		//echo '</pre>';

		if ( is_wp_error( $response ) ) {
			echo $response->get_error_message();
			wc_add_notice( $response->get_error_message(), 'error' );
			return false;
		} else {
			$body = wp_remote_retrieve_body( $response );
			//$body = new WP_Error( 'vn-error-body', 'que hijo de perra' );
			if ( is_wp_error( $body ) ) {
				echo $body->get_error_message();
				wc_add_notice( $body->get_error_message(), 'error' );
				return false;
			} else {
				$data = @json_decode( $body );

				//echo '<pre>';
				//print_r( array( $response, $data ) );
				//echo '</pre>';
				//exit();

				if( $data !== null && json_last_error() == JSON_ERROR_NONE && isset( $data->data ) ){
					$data = array_change_key_case( ( array ) $data );
					$data['data'] = ( object ) array_change_key_case( ( array ) $data['data'] );


					if( preg_match( '/^\[.*\]$/', $data['errormessage'] ) ){
						$messages = @json_decode( $data['errormessage'] );
						if( $messages !== null && json_last_error() == JSON_ERROR_NONE ){
							if( ! is_array( $messages ) ){
								$messages = array( $messages );
							}

							$data['errormessage'] = array_map( 'utf8_decode', $messages );
						}
					}

					return ( object ) $data;
				} else {
					wc_add_notice( 'Error parsing transaction', 'error' );
					return false;
				}
			}
		}

		return false;
	}
}
