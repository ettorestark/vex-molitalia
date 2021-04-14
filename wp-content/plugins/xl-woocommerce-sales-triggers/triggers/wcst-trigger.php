<?php

abstract class WCST_Base_Trigger {


	public $slug = '';
	public $title = '';
	public $settings = array();
	public $is_override_meta = false;
	public $supported_product_type = array( 'simple', 'variable', 'subscription', 'variable-subscription', 'bundle', 'yith_bundle' );
	public $default_priority = 1;

	public function __construct() {
		$this->register_settings();
	}

	public function register_settings() {
		$this->settings = array();
	}

	public function get_defaults() {
		return array();
	}

	public function get_settings() {
		return $this->settings;
	}

	public function get_instance_settings() {
		return $this->settings;
	}

	public function get_post_settings() {
		return $this->settings;
	}

	public function register_script_data( $data, $productInfo ) {

		return $data;
	}

	public function get_meta_prefix() {
		return sprintf( '_wcst_data_wcst_%s_', $this->slug );
	}

	/**
	 * handles request for single product page
	 * handles modal logic for that triggers, prepares and generate HTML
	 *
	 * @param $data Trigger Data
	 * @param $productInfo WCST_Product Product instance
	 * @param $position position to place into on single page
	 */
	public function handle_single_product_request( $data, $productInfo, $position ) {

	}

	/**
	 * handles request for product grid
	 * handles modal logic for grid and prepares HTML
	 *
	 * @param $data  Trigger Data
	 * @param $productInfo WCST_Product Product instance
	 */
	public function handle_grid_request( $data, $productInfo ) {


		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			WCST_Common::insert_log( "*******Terminating******* || Reason: Product Support mismatch ||  product: " . $productInfo->product->get_title() . ":" . $productInfo->product->get_id(), $this->slug );

			return;
		}
		if ( $data && is_array( $data ) && count( $data ) > 0 ) {
			foreach ( $data as $trigger_key => $single ) {

				$this->output_html( $trigger_key, $single, $productInfo, "grid" );

			}
		}
	}

	public function get_supported_product_type() {

		return $this->supported_product_type;
	}

	/**
	 * Outputs HTML for the trigger
	 *
	 * @param $trigger_key Trigger/InstanceID
	 * @param $single Single Instance Data
	 * @param $productInfo XL_WCST_Product Product instance
	 * @param string $template
	 */
	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {

	}

	/**
	 * handles request for shortcode/API
	 * handles modal logic for shortcode and prepares HTML
	 *
	 * @param $data Trigger Data
	 * @param $productInfo WCST_Product Product instance
	 * @param $page page it is requested for
	 * @param int $variationID if a variation given
	 */
	public function handle_custom_request( $data, $productInfo, $page, $variationID = 0, $cart_item = null ) {


		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		WCST_Common::insert_log( "Custom Request For " . $productInfo->product->get_id() . "-- " . $this->get_title() );

		if ( $page == "cart" ) {

			$this->handle_cart_request( $data, $productInfo, $variationID, $cart_item );
		} else {


			if ( $page == "grid" && in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) ) {
				$variation_ID = $productInfo->get_variation_for_grid();
			}

			if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) && $variationID ) {

				$this->output_for_variation( $data, $productInfo, $variationID, "custom" );
			} else {

				if ( $data && is_array( $data ) && count( $data ) > 0 ) {
					foreach ( $data as $trigger_key => $single ) {

						$this->output_html( $trigger_key, $single, $productInfo, "custom" );
					}
				}
			}
		}

	}

	public function get_title() {
		return $this->slug;
	}

	/**
	 * Handles Request for Cart Page
	 * Handles modal logic and prepares HTML
	 *
	 * @param $data Trigger Data
	 * @param $productInfo
	 * @param $cart_item WCST_Product Product instance
	 */
	public function handle_cart_request( $data, $productInfo, $variationID = 0, $cart_item ) {

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		if ( $data && is_array( $data ) && count( $data ) > 0 ) {
			foreach ( $data as $trigger_key => $single ) {

				$this->output_html( $trigger_key, $single, $productInfo, "cart" );

			}
		}

	}

	/**
	 * Outputs HTML for chosen variation
	 *
	 * @param $data Instances Data
	 * @param $productInfo WCST_Product Product instance
	 * @param int $variationID if a variation given
	 */
	public function output_for_variation( $data, $productInfo, $variationID = 0, $page ) {
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}

		$wcst_best_seller_badge_arr = $data;
		foreach ( $wcst_best_seller_badge_arr as $trigger_key => $best_seller_badge_single ) {
			$badge_position = $best_seller_badge_single['position'];

			$this->output_html( $trigger_key, $best_seller_badge_single, $productInfo, $page );
		}


	}

	/**
	 * Generates & Outputs Dynamic CSS for the respective trigger
	 *
	 * @param $data Instance Data
	 * @param $productInfo WCST_Product Product instance
	 */
	public function output_dynamic_css( $data, $productInfo ) {

	}


}
