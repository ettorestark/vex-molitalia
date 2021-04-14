<?php
/**
 * Visanet Peru - WooCommerce Gateway Request Session Visanet.
 *
 * @since   2.0.0
 * @package Visanet_Peru_WooCommerce_Gateway
 */

/**
 * Visanet Peru - WooCommerce Gateway Request Session Visanet.
 *
 * @since 2.0.0
 */
class WGVP_Visanet_Request {
	/**
	 * Parent gateway class.
	 *
	 * @since 2.0.0
	 *
	 * @var   WGVP_Gateway_Visanet_Peru
	 */
	protected $_gateway = null;

	/**
	 * order class.
	 *
	 * @since 2.0.0
	 *
	 * @var   WC_Order
	 */
	protected $_order = null;

	/**
	 * Constructor.
	 *
	 * @since  2.0.0
	 *
	 * @param  WGVP_Gateway_Visanet_Peru $gateway object.
	 */
	public function __construct( $gateway, $order ) {
		$this->_gateway = $gateway;
		$this->_order = $order;

		$session = WGVP_Session::get_instance();
		//$this->_session = WC()->session->get( 'order_visanet_session' );

		if( ! $session->valid_session() ){
			$session->make_session( $gateway, $order );
		}
	}

	private function _get_script_url(){
	    if ( $this->_gateway->testmode ) {
			$script_url = 'https://static-content.vnforapps.com/v1/js/checkout.js?qa=true';
		} else {
			$script_url = 'https://static-content.vnforapps.com/v1/js/checkout.js';
		}

		return $script_url;
	}

	public function form(){
		$session = WGVP_Session::get_instance();
		$current_user = wp_get_current_user();
		$return_url = WC()->api_request_url( $this->_gateway->id );

		$uuid = $session->get( 'sessionToken' );
		$order_id = $this->_order->get_id();
		$amount = $this->_order->get_total();

		$merchant_id = $this->_gateway->get_option( 'merchant_code' );
		$accessKey = $this->_gateway->get_option( 'merchant_access_key' );
		$secretKey = $this->_gateway->get_option( 'merchant_secret_access_key' );

		//echo '<pre>';
		//var_export( array( $accessKey, $secretKey ) );
		//var_export( array( $this->_session ) );
		//echo '</pre>';
		///return;

		?>
		<p>Haga click en el botón para realizar su pago mediante Visanet Perú.</p>
		<form id="visanet-form" action="<?php echo $return_url ?>" method="post" onsubmit="return false">
		<script src="<?php echo $this->_get_script_url() ?>"
		 data-sessiontoken="<?php echo $uuid ?>"
		 data-merchantid="<?php echo $merchant_id ?>"
		<?php
// 		 data-recurrence="true"
// 		 data-configrecurrence="true"
// 		 data-frequency="Quarterly"
// 		 data-recurrencetype="fixed"
// 		 data-recurrenceamount="200"

// 		data-documenttype="0"
// 		data-documentid=""
// 		data-beneficiaryid=""
// 		data-productid=""
// 		data-phone=""
		 ?>
		 data-showamount="<?php echo $this->_gateway->get_option( 'showamount' ) ?>"
		 data-buttonsize="<?php echo $this->_gateway->get_option( 'button_size' ) ?>"
		 data-buttoncolor="<?php echo $this->_gateway->get_option( 'button_color' ) ?>"
		 data-merchantlogo="<?php echo wp_get_attachment_url( $this->_gateway->get_option( 'merchant_logo' ) ) ?>"
		 data-formbuttoncolor="<?php echo $this->_gateway->get_option( 'button_modal_color' ) ?>"
		 data-purchasenumber="<?php echo $order_id ?>"
		 <?php if( is_user_logged_in() && ( $userTokenId = get_user_meta( $current_user->ID, '_visaperu_userTokenId', true ) ) ){ ?>
		 data-usertoken="<?php echo $userTokenId ?>"
		 <?php } ?>
		 data-amount="<?php echo number_format( $amount, 2, '.', '' ) ?>"></script>
		 </form>
		 <script type="text/javascript">jQuery('.start-js-btn').trigger('click');</script>
		<?php
	}
}
