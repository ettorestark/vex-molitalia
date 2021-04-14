<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WCST_Core {

	/**
	 * @var null Instance Self
	 */
	public static $instance = null;

	/**
	 * @var string Url for the root of the plugin
	 */
	public $wcst_url;

	/**
	 * @var array single product data for cart and grid pages
	 */
	public $single_product_data = array();

	/**
	 * @var array Single product request data
	 */
	public $single_wcst_product = array();

	/**
	 * @var null Trigger instances to show on loop
	 */
	public $show_in_loop_instances = null;

	/**
	 * @var null Trigger instances to show on loop
	 */
	public $products_data_inloop = array();

	/**
	 * @var null Trigger instances to show on cart
	 */
	public $show_in_cart_instances = null;

	/**
	 * @var null Trigger instances to show on cart
	 */
	public $products_data_incart = array();

	/**
	 * @var null Request Type
	 */
	public $request_type;

	/**
	 * @var Bool If is mini cart
	 */
	public $is_mini_cart;

	/**
	 * @var Bool If is checkout page
	 */
	public $is_checkout;

	/**
	 * @var array Scripts to be collected in whole execution cycle
	 */
	public $script_data = array();

	/**
	 * @var array Styles to be collected in whole execution cycle
	 */
	public $style_data = '';

	public function __construct() {
		$this->wcst_url = plugin_dir_url( WCST_PLUGIN_FILE );


		// wp hook
		add_action( 'wp', array( $this, "wcst_setup_request" ), 800 );

		add_action( 'wp', array( $this, 'wcst_setup_data' ), 801 );

		add_action( 'wp_head', array( $this, 'wcst_single_product_css' ), 802 );
		add_action( 'wp', array( $this, "wcst_wc_price_hook_checking" ), 998 );


		add_action( 'wp', array( $this, 'wcst_maybe_init_logging' ), 1 );

		// wc thank you
		add_action( 'woocommerce_thankyou', array( $this, 'wcst_woocommerce_thankyou' ) );
		add_action( 'wcst_new_order_schedule_event', array( $this, 'wcst_new_order_schedule_single_event' ), 10, 1 );


		add_action( 'wp_footer', array( $this, 'wcst_try_localize_script' ) );
		add_action( 'wp_footer', array( $this, 'wcst_try_output_css' ) );
		add_action( 'wp_enqueue_scripts', array( $this, "wcst_wp_enqueue_scripts" ) );

		/**
		 * Hooks to process triggers for single product page
		 */
		add_action( 'woocommerce_single_product_summary', array( $this, 'wcst_position_above_title' ), 2.2 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wcst_position_below_title' ), 9.2 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wcst_position_below_review' ), 11 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wcst_position_below_price' ), 17.2 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wcst_position_below_short_desc' ), 21.2 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wcst_position_below_add_cart' ), 39.2 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'wcst_position_below_meta' ), 41.2 );

		// woocommerce_after_single_product_summary
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'wcst_position_above_tab_area' ), 9.8 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'wcst_position_below_related_products' ), 21.2 );

		/**
		 * Product Grid support Hook
		 */
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wcst_show_in_loop' ), 9 );


		/**
		 * Cart Support Hook
		 */
		add_action( 'woocommerce_cart_item_name', array( $this, 'wcst_show_in_cart' ), 999, 3 );

		/**
		 * Filter Values before passing it to modal functions for triggers
		 */
		add_filter( 'wcst_filter_values', array( $this, 'filter_to_integer_values' ), 10, 3 );
		add_filter( 'wcst_filter_values', array( $this, 'filter_font_size' ), 10, 3 );
		add_filter( 'wcst_filter_values', array( $this, 'filter_guarantee_modes' ), 10, 3 );

		/**
		 * Handling mini cart / to not show our triggers to the mini cart
		 */
		add_action( 'woocommerce_before_mini_cart', array( $this, 'detect_mini_cart_start' ) );
		add_action( 'woocommerce_after_mini_cart', array( $this, 'detect_mini_cart_ends' ) );

		/**
		 * Handling checkout to not show our triggers to the mini cart
		 */
		add_action( 'woocommerce_before_checkout_form', array( $this, 'detect_checkout_start' ) );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'detect_checkout_ends' ) );

		/**
		 * Handling checkout to not show our triggers to the mini cart
		 */
		add_action( 'woocommerce_checkout_update_order_review', array( $this, 'detect_checkout_start' ) );

		/**
		 * to handle qty for the savings trigger on the cart page
		 */
		add_filter( 'wcst_modify_savings_price', array( $this, 'multiply_qty_with_savings_price' ), 10, 5 );

		add_filter( 'wcst_exclude_cats_from_best_seller_list', array( $this, 'wcst_handle_cats_for_best_sellers' ), 999, 2 );

		/**
		 * Adding meta tag
		 * @see wcst_generator_tag()
		 */
		add_action( 'get_the_generator_html', array( $this, 'wcst_generator_tag' ), 10, 2 );
		add_action( 'get_the_generator_xhtml', array( $this, 'wcst_generator_tag' ), 10, 2 );


		add_filter( 'wcst_sales_count_display_content_before_data', array( $this, 'wcst_modify_sales_count_display_if_no_merge_tag' ), 10, 5 );
		add_filter( 'wcst_sales_snippet_display_content_before_data', array( $this, 'wcst_modify_sales_snippet_display_if_no_merge_tag' ), 10, 5 );

		add_filter( 'wcst_trigger_savings_decimal_point', array( $this, 'wcst_get_native_decimal_point' ), 1 );
		/**
		 * SHORT-CODES
		 */
		add_shortcode( 'wcst_deal_expiry', array( $this, 'shortcode_deal_expiry' ) );
		add_shortcode( 'wcst_savings', array( $this, 'shortcode_savings' ) );
		add_shortcode( 'wcst_low_stock', array( $this, 'shortcode_low_stock' ) );
		add_shortcode( 'wcst_smarter_reviews', array( $this, 'shortcode_smarter_reviews' ) );
		add_shortcode( 'wcst_guarantee', array( $this, 'shortcode_guarantee' ) );
		add_shortcode( 'wcst_sales_count', array( $this, 'shortcode_sales_count' ) );
		add_shortcode( 'wcst_sales_snippet', array( $this, 'shortcode_sales_snippet' ) );
		add_shortcode( 'wcst_best_seller_badge', array( $this, 'shortcode_best_seller_badge' ) );
		add_shortcode( 'wcst_best_seller_list', array( $this, 'shortcode_best_seller_list' ) );

		add_filter( 'woocommerce_available_variation', array( $this, 'add_our_own_stock_param_to_read_in_js' ), 10, 3 );
		add_filter( 'wcst_check_wpml_sibling_prod_ids', array( $this, 'add_sibling_post_ids_for_wpml' ), 10, 1 );
		add_filter( 'wcst_get_wpml_parent_prod_id', array( $this, 'get_parent_post_id_for_wpml' ), 10, 1 );

		add_action( 'wp_head', array( $this, 'wcst_page_noindex' ) );
	}

	/**
	 * Return an instance of this class.
	 * @since 1.0.0
	 * @return WCST_Core
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Try and initialize logging
	 * Checking if DEBUG ON
	 */
	public function wcst_maybe_init_logging() {

		if ( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && is_singular( "product" ) ) {
			if ( ( WCST_Common::$is_force_debug === true ) || ( WP_DEBUG === true && ! is_admin() ) ) {
				wcst_logging( 'Logging Started', "debug_trigger.txt", "w" );
				$getall = WCST_Triggers::get_all();
				if ( $getall && count( $getall ) > 0 ) {
					foreach ( $getall as $key => $trigger ) {
						wcst_logging( 'Logging Started', $key . "_trigger.txt", "w" );
					}
				}
			}
		}

	}

	/**
	 * Initiate Fron End and set up request type
	 * Hooked on `wp`
	 */
	public function wcst_setup_request() {
		if ( ! is_admin() ) {
			$this->request_type = false;
		}
		if ( is_singular( 'product' ) ) {
			$this->request_type = 'product';
		} elseif ( is_cart() ) {
			$this->request_type = 'cart';
		} elseif ( is_product_category() || is_shop() ) {
			$this->request_type = 'grid';
		}
	}

	/**
	 * Setting up data for the single product page visit
	 */
	public function wcst_setup_data() {
		global $post;
		if ( $this->request_type == 'product' ) {
			WCST_Common::insert_log( "Single Product Request Initiates Now For" . $post->ID );
			$this->wcst_process_single_product_request( $post->ID );
		}
	}

	/**
	 * Process Single product request and gather appropriate data
	 *
	 * @param $postID Product ID
	 */
	public function wcst_process_single_product_request( $postID ) {
		$triggerData               = new WCST_Triggers_Data( $postID );
		$this->single_product_data = $triggerData->wcst_maybe_process_data();

		WCST_Common::insert_log( "Trigger data about to process--" );
		WCST_Common::insert_log( print_r( $this->single_product_data, true ) );

		$this->single_wcst_product = XL_WCST_Product::get_instance( $postID );

		WCST_Common::insert_log( "Product Instance--" );
		WCST_Common::insert_log( print_r( $this->single_wcst_product, true ) );
	}


	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 2.2
	 */
	public function wcst_position_above_title() {
		$this->wcst_triggers( 1 );
	}

	/**
	 * Function that process all triggers for the single product page
	 * Iterate over the data gathered for the single product request and fires single product request to each trigger found
	 *
	 * @param $position Integer to detect the position on single product page
	 */
	public function wcst_triggers( $position ) {
		if ( is_user_logged_in() && current_user_can( 'administrator' ) && isset( $_GET['wcst_positions'] ) && $_GET['wcst_positions'] == 'yes' ) {
			WCST_Common::pr( "Position: " . $this->get_position_for_index( $position ) );
		}

		foreach ( $this->single_product_data as $display_order => $trigger ) {
			foreach ( $trigger as $trigger_key => $trigger_data ) {
				$trigger_object = WCST_Triggers::get( $trigger_key );
				$trigger_object->handle_single_product_request( $trigger_data, $this->single_wcst_product, $position );
				$this->script_data['compatibility'][ $trigger_object->slug ] = $trigger_object->get_supported_product_type();
				if ( isset( $this->script_data['settings'][ $trigger_key ] ) ) {
					$this->script_data['settings'][ $trigger_key ] = array_replace( $this->script_data['settings'][ $trigger_key ], $trigger_object->register_script_data( $trigger_data, $this->single_wcst_product ) );
				} else {
					$this->script_data['settings'][ $trigger_key ] = $trigger_object->register_script_data( $trigger_data, $this->single_wcst_product );
				}
			}
		}
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 9.2
	 */
	public function wcst_position_below_title() {
		$this->wcst_triggers( 2 );
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 11
	 */
	public function wcst_position_below_review() {
		$this->wcst_triggers( 3 );
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 17.2
	 */
	public function wcst_position_below_price() {
		$this->wcst_triggers( 4 );
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 21.2
	 */
	public function wcst_position_below_short_desc() {
		$this->wcst_triggers( 5 );
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 39.2
	 */
	public function wcst_position_below_add_cart() {
		$this->wcst_triggers( 6 );
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 41.2
	 */
	public function wcst_position_below_meta() {
		$this->wcst_triggers( 7 );
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 9.8
	 */
	public function wcst_position_above_tab_area() {
		echo '<div class="wcst_clear"></div>';
		$this->wcst_triggers( 8 );
	}

	/**
	 * Hooked on `woocommerce_single_product_summary`
	 * Position 21.2
	 */
	public function wcst_position_below_related_products() {
		$this->wcst_triggers( 11 );
	}

	/**
	 * Enqueues Scripts and Styles for Front End
	 * Also localizes necessary attributes
	 */
	public function wcst_wp_enqueue_scripts() {
		global $woocommerce;
		if ( true === SCRIPT_DEBUG ) {
			wp_enqueue_style( 'wcst_public_css', $this->wcst_url . 'assets/css/wcst_style.css', array(), WCST_VERSION );
			wp_enqueue_style( 'wcst_ecom_fonts', $this->wcst_url . 'assets/css/woothemes_ecommerce.min.css', array(), WCST_VERSION );
			wp_enqueue_script( 'wcst_countdown', $this->wcst_url . 'assets/js/jquery.countdown.min.js', array( 'jquery' ), '2.2.0', true );
			wp_enqueue_script( 'wcst_humanized_time', $this->wcst_url . 'assets/js/humanized_time_span.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'wcst_public_js', $this->wcst_url . 'assets/js/wcst_custom.js', array( 'jquery' ), WCST_VERSION, true );
		} else {
			wp_enqueue_style( 'wcst_public_css', $this->wcst_url . 'assets/css/wcst_combined.css', array(), WCST_VERSION );
			wp_enqueue_script( 'wcst_public_js', $this->wcst_url . 'assets/js/wcst_combined.min.js', array( 'jquery' ), WCST_VERSION, true );
		}

		// store currency
		$wcst_currency                    = get_woocommerce_currency_symbol();
		$localize_arr['version']          = WCST_VERSION;
		$localize_arr['ajax_url']         = admin_url( 'admin-ajax.php' );
		$localize_arr['wc_version']       = $woocommerce->version;
		$localize_arr['currency']         = $wcst_currency;
		$localize_arr['timezone_diff']    = WCST_Common::get_timezone_difference();
		$localize_arr['date_format']      = WCST_Common::wcst_get_date_format();
		$localize_arr['wc_decimal_count'] = apply_filters( 'wcst_trigger_savings_decimal_point', 1 );
		$localize_arr['wc_decimal_sep']   = wc_get_price_decimal_separator();
		$localize_arr['wc_thousand_sep']  = wc_get_price_thousand_separator();

		$localize_arr['hard_texts']     = WCST_Common::get_hard_text();
		$localize_arr['current_postid'] = 0;

		$localize_arr['wcstajax_url']        = add_query_arg( array( 'wcstajax' => 'yes' ), site_url() . "/" );
		$this->script_data['current_postid'] = 0;

		if ( $this->request_type == "product" ) {
			global $post;

			$productObj                     = XL_WCST_Product::get_instance( $post->ID );
			$localize_arr['product_type']   = $productObj->product->get_type();
			$localize_arr['current_postid'] = $post->ID;

			$localize_arr['utc0_time'] = WCST_Common::get_site_time();

			// product stock status
			$localize_arr['product_status'] = $productObj->get_stock_status();

			// product stock status
			$localize_arr['product_backorder_status'] = $productObj->get_backorder_status();
			$this->script_data['settings']            = array();
			$this->script_data['compatibility']       = array();
			$localize_arr['currency_pos']             = get_option( 'woocommerce_currency_pos' );


		}
		$localize_arr = WCST_Common::add_license_info( $localize_arr );

		$this->script_data = $localize_arr;
	}

	/**
	 * Collect and outputs generated css from each trigger to the page.
	 * Hooked into `wp_head`
	 */
	public function wcst_single_product_css() {

		$product_css = '';
		if ( $this->request_type == 'product' ) {

			foreach ( $this->single_product_data as $display_order => $trigger ) {
				foreach ( $trigger as $trigger_key => $trigger_data ) {
					$trigger_object = WCST_Triggers::get( $trigger_key );
					$product_css    .= $trigger_object->output_dynamic_css( $trigger_data, $this->single_wcst_product );

				}
			}
		}

		$is_internal_css = apply_filters( 'wcst_is_internal_css', 1 );
		if ( $is_internal_css === 1 ) {
			echo $product_css ? '<style>' . $product_css . '</style>' : '';
		}
	}

	/**
	 * Hooked over `woocommerce_after_shop_loop_item`
	 * process Product grid request
	 * Gather & prepares data for the loop triggers
	 * Render HTML and Css accordingly
	 * global $product Product is been iterating
	 */
	public function wcst_show_in_loop() {
		global $product;


		if ( ! $product ) {
			return;
		}

		if ( $this->show_in_loop_instances === null ) {

			$this->wcst_setup_data_abstract( "loop" );
		}
		$product_css = "";


		$data = $this->wcst_setup_data_for_product( $product->get_id(), "loop" );


		$current_product = XL_WCST_Product::get_instance( $product->get_id() );

		$triggers = WCST_Triggers::get_all();


		foreach ( $data as $trigger => $trigger_data ) {


			$get_trigger = WCST_Triggers::get( $trigger );

			WCST_Common::insert_log( "Grid request started for: " . $product->get_id(), $get_trigger->slug );


			$get_trigger->handle_grid_request( $trigger_data, $current_product );

			$this->style_data .= $get_trigger->output_dynamic_css( $trigger_data, $current_product, 'grid' );
		}
	}

	/**
	 * Process & prepares data for cart and product grids
	 * Executes query and get correct instances
	 *
	 * @param $page_now cart | loop
	 */
	public function wcst_setup_data_abstract( $page_now ) {

		$xl_cache_obj = XL_Cache::get_instance();

		if ( $page_now == "loop" ) {


			$args = array(
				'post_type'        => WCST_Common::get_trigger_post_type_slug(),
				'post_status'      => 'publish',
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'nopaging'         => true,
				'meta_key'         => '_wcst_data_showon_grid',
				'meta_value'       => 'yes',
				'fields'           => 'ids',
				'suppress_filters' => false
			);


			$cache_key = "wcst_wp_query_";

			// handling for WPML
			if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE !== "" ) {
				$cache_key .= ICL_LANGUAGE_CODE . "_";
			}

			$cache_key .= md5( json_encode( $args ) );

			$results = $xl_cache_obj->get_cache( $cache_key, 'wcst_trigger_data' );

			if ( ! $results ) {
				$this->show_in_loop_instances = get_posts( $args );
				$xl_cache_obj->set_cache( $cache_key, $this->show_in_loop_instances, 'wcst_trigger_data' );
			} else {
				$this->show_in_loop_instances = $results;
			}

			$this->show_in_loop_instances = apply_filters( 'wcst_grid_trigger_ids', $this->show_in_loop_instances );
		}
		if ( $page_now == "cart" ) {
			$args = array(
				'post_type'        => WCST_Common::get_trigger_post_type_slug(),
				'post_status'      => 'publish',
				'nopaging'         => true,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'meta_key'         => '_wcst_data_showon_cart',
				'meta_value'       => 'yes',
				'fields'           => 'ids',
				'suppress_filters' => false
			);

			$cache_key = "wcst_wp_query_";

			// handling for WPML
			if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE !== "" ) {
				$cache_key .= ICL_LANGUAGE_CODE . "_";
			}

			$cache_key .= md5( json_encode( $args ) );

			$results = $xl_cache_obj->get_cache( $cache_key, 'wcst_trigger_data' );


			if ( ! $results ) {

				$this->show_in_cart_instances = get_posts( $args );
				$xl_cache_obj->set_cache( $cache_key, $this->show_in_loop_instances, 'wcst_trigger_data' );
			} else {
				$this->show_in_cart_instances = $results;
			}

			$this->show_in_cart_instances = apply_filters( 'wcst_cart_trigger_ids', $this->show_in_cart_instances );

		}
	}

	/**
	 * Sets Up data for the given product based on the type of request
	 *
	 * @param $postID Product ID to et up data for
	 * @param $page_now Cart | Loop
	 * @param $wpdb Global $wpdb object
	 *
	 * @return array Array of triggers and their data
	 */
	public function wcst_setup_data_for_product( $postID, $page_now ) {
		global $wpdb;

		$data = array();
		if ( $page_now == "loop" ) {

			$contents = $this->show_in_loop_instances;

			if ( $contents && count( $contents ) ) {

				foreach ( $contents as $content ) {


					if ( WCST_Common::match_groups( $content, $postID ) ) {

						$slug = get_post_meta( $content, "_wcst_data_choose_trigger", true );

						if ( $slug === "" ) {
							continue;
						}

						$new_trigger_data                      = new WCST_Triggers_Data( $postID );
						$this->products_data_inloop[ $slug ][] = $data[ $slug ][ $content ] = $new_trigger_data->get_single_instance_data( $content, $slug );
					}
				}
			}
		}

		if ( $page_now == "cart" ) {

			$contents = $this->show_in_cart_instances;


			if ( $contents && count( $contents ) ) {

				foreach ( $contents as $content ) {


					if ( WCST_Common::match_groups( $content, $postID ) ) {

						$slug = get_post_meta( $content, "_wcst_data_choose_trigger", true );

						if ( $slug === "" ) {
							continue;
						}
						$new_trigger_data = new WCST_Triggers_Data( $postID );


						$this->products_data_inloop[ $slug ][] = $data[ $slug ][ $content ] = $new_trigger_data->get_single_instance_data( $content, $slug );
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Hooked over `woocommerce_cart_item_name`
	 * Gather and prepares data, iterate over found data and outputs html for each trigger
	 *
	 * @param $title Title of the Product
	 * @param $cart_item Cart item object/array that contains info about the product
	 * @param $cart_item_key Unique Item key provided by WC
	 *
	 * @return string $title
	 */
	public function wcst_show_in_cart( $title, $cart_item, $cart_item_key ) {


		if ( $this->is_mini_cart || $this->is_checkout ) {
			return $title;
		}
		if ( $this->show_in_cart_instances === null ) {
			$this->wcst_setup_data_abstract( "cart" );
		}
		$product_css     = "";
		$data            = $this->wcst_setup_data_for_product( $cart_item['product_id'], "cart" );
		$current_product = XL_WCST_Product::get_instance( ( $cart_item['product_id'] ) );

		ob_start();
		foreach ( $data as $trigger => $trigger_data ) {

			$get_trigger = WCST_Triggers::get( $trigger );


			$get_trigger->handle_cart_request( $trigger_data, $current_product, $cart_item['variation_id'], $cart_item );
			$this->style_data .= $get_trigger->output_dynamic_css( $trigger_data, $current_product, 'cart' );
		}


		$data_product_cart = ob_get_clean();

		return $title . $data_product_cart . "";
	}

	/**
	 * Hooked over `wcst_filter_values`
	 * Covert non integer values to integer ones
	 *
	 * @param $value Value to be sanitized
	 * @param $key key_slug
	 * @param $trigger trigger_slug
	 *
	 * @return int
	 */
	public function filter_to_integer_values( $value, $key, $trigger ) {
		$boolean_val_array = array(
			"mode",
		);
		if ( in_array( $key, $boolean_val_array ) ) {
			return (int) $value;
		}

		return $value;
	}

	/**
	 * Hooked over `wcst_filter_values`
	 * Adds px after font sizes.
	 *
	 * @param $value Value to be sanitized
	 * @param $key key_slug
	 * @param $trigger trigger_slug
	 *
	 * @return int
	 */
	public function filter_font_size( $value, $key, $trigger ) {
		if ( $key == "font_size" ) {
			return $value . "px";
		}

		return $value;
	}

	/**
	 * Hooked over `wcst_filter_values`
	 * Filtering Guarantee to process the correct data
	 *
	 * @param $value Value to be sanitized
	 * @param $key key_slug
	 * @param $trigger trigger_slug
	 *
	 * @return $array
	 */
	public function filter_guarantee_modes( $value, $key, $trigger ) {


		$cloned_array = array();

		if ( 'guarantee' == $key && isset( $value ) && is_array( $value ) && count( $value ) > 0 ) {

			foreach ( $value as $key => $val ) {


				if ( ! isset( $val['style_mode'] ) ) {
					$cloned_array[ $key ] = array(
						'heading' => ( isset( $val['heading'] ) ) ? $val['heading'] : "",
						'text'    => ( isset( $val['text'] ) ) ? $val['text'] : "",
					);
					continue;
				}


				if ( $val["style_mode"] == "icon" ) {
					$cloned_array[ $key ] = array(
						'heading' => ( isset( $val['heading'] ) ) ? $val['heading'] : "",
						'text'    => ( isset( $val['text'] ) ) ? $val['text'] : "",
					);

					if ( $val['icon'] != '0' ) {
						$cloned_array[ $key ]['icon'] = $val['icon'];
					}
				}

				if ( $val["style_mode"] == "image" ) {
					$cloned_array[ $key ] = array(
						'heading' => ( isset( $val['heading'] ) ) ? $val['heading'] : "",
						'text'    => ( isset( $val['text'] ) ) ? $val['text'] : "",
					);
					$getUrl               = wp_get_attachment_image_url( $val['image_id'], 'full' );

					if ( $getUrl ) {
						$cloned_array[ $key ]['image'] = $getUrl;
					}
				}
				if ( $val["style_mode"] == "none" || $val["style_mode"] == "" ) {
					$cloned_array[ $key ] = array(
						'heading' => ( isset( $val['heading'] ) ) ? $val['heading'] : "",
						'text'    => ( isset( $val['text'] ) ) ? $val['text'] : "",
					);
				}
			}

			return $cloned_array;
		}


		return $value;
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_deal_expiry( $attrs ) {

		return $this->handle_shortcode_request( $attrs, 'deal_expiry' );
	}

	/**
	 * Process Shortcode request
	 * Get Product and the triggers using attributes given in shortcode
	 * Process data and generated HTML
	 *
	 * @param $attrs shortcode attributes
	 * @param $trigger trigger_slug
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function handle_shortcode_request( $attrs, $trigger ) {
		global $product;
		$cart_item = array();

		$attrs = shortcode_atts( array(
			'trigger_ids'   => '',
			'product_id'    => 0,
			'variation_id'  => 0,
			'location'      => 'product',
			'cart_item_key' => 0,
			'skip_rules'    => 'yes',
			'debug'         => 'no'
		), $attrs );


		$product_shortcode = null;


		//checking if user provided the ID of the product
		if ( $attrs['product_id'] == 0 || $attrs['product_id'] == "" ) {
			$product_shortcode = $product;

		} else {

			//getting product from user given ID
			$product_main      = XL_WCST_Product::get_instance( $attrs['product_id'] );
			$product_shortcode = $product_main->product;

		}

		//checking if product not found
		if ( is_null( $product_shortcode ) || ! is_object( $product_shortcode ) ) {

			if ( $attrs['cart_item_key'] !== 0 ) {

				$cart_item           = WC()->cart->get_cart_item( $attrs['cart_item_key'] );
				$attrs['product_id'] = $cart_item['product_id'];
				$product_main        = XL_WCST_Product::get_instance( $attrs['product_id'] );

				if ( $cart_item && $cart_item['variation_id'] ) {
					$attrs['variation_id'] = $cart_item['variation_id'];
				}
				$attrs['location'] = "cart";
			} else {

				if ( $attrs['debug'] == "yes" ) {
					return __( "Unable to show shortcode, Product ID is missing/invalid" );
				} else {
					return;
				}
				// no product found from any checks
				// terminating shortcode process
			}
		} else {

			//get object from the found ID
			$product_main = XL_WCST_Product::get_instance( $product_shortcode->get_id() );
		}


		$get_data = new WCST_Triggers_Data( $product_main->product->get_id() );

		$data = $get_data->get_trigger_data( $trigger, $product_main->product->get_id(), $attrs['location'], $attrs['trigger_ids'], false, $attrs['skip_rules'] );


		if ( empty( $data ) ) {

			if ( $attrs['debug'] == "yes" ) {
				return __( "Unable to show shortcode, No Triggers Found." );
			} else {
				return;
			}


		}

		ob_start();

		$trigger = WCST_Triggers::get( $trigger );


		$trigger->handle_custom_request( isset( $data[ $trigger->slug ] ) ? $data[ $trigger->slug ] : array(), $product_main, $attrs['location'], $attrs['variation_id'], $cart_item );

		$this->script_data['compatibility'][ $trigger->slug ] = $trigger->get_supported_product_type();
		if ( isset( $this->script_data['settings'][ $trigger->slug ] ) ) {


			$this->script_data['settings'][ $trigger->slug ] = array_replace( $this->script_data['settings'][ $trigger->slug ], $trigger->register_script_data( $data[ $trigger->slug ], $product_main ) );
		} else {
			$this->script_data['settings'][ $trigger->slug ] = $trigger->register_script_data( $data[ $trigger->slug ], $product_main );
		}


		$this->style_data .= $trigger->output_dynamic_css( $data[ $trigger->slug ], $product_main, $attrs['location'] );

		return ob_get_clean();
	}

	/**
	 * Shortcode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_savings( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'savings' );
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_low_stock( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'low_stock' );
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_smarter_reviews( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'smarter_reviews' );
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_guarantee( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'guarantee' );
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_sales_count( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'sales_count' );
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_sales_snippet( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'sales_snippet' );
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_best_seller_badge( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'best_seller_badge' );
	}

	/**
	 * Shortocode cb
	 *
	 * @param $attrs attributes for shortcodes
	 *
	 * @return string|void html when we have something to render, void otherwise
	 */
	public function shortcode_best_seller_list( $attrs ) {
		return $this->handle_shortcode_request( $attrs, 'best_seller_list' );
	}

	/**
	 * Hooked onto `wp_footer`
	 * try and localize script data collected by the class property
	 */
	public function wcst_try_localize_script() {


		wp_localize_script( 'wcst_public_js', 'wcst_data', $this->script_data );
	}

	/**
	 * Hooked onto `wp_footer`
	 * try and outputs CSS collected by the class property
	 */
	public function wcst_try_output_css() {

		$is_internal_css = apply_filters( 'wcst_is_internal_css', 1 );
		if ( $is_internal_css === 1 ) {
			echo ( ! empty( $this->style_data ) ) ? '<style>' . $this->style_data . '</style>' : '';
		}
	}

	/**
	 * Hooked over `woocommerce_after_mini_cart`
	 * Checking into mini cart ends
	 */
	public function detect_mini_cart_ends() {
		$this->is_mini_cart = false;
	}

	/**
	 * Hooked over `woocommerce_before_mini_cart`
	 * Checking into mini cart starts
	 */
	public function detect_mini_cart_start() {
		$this->is_mini_cart = true;
	}

	/**
	 * Hooked over `woocommerce_after_checkout_form`
	 * Checking into checkout ends
	 */
	public function detect_checkout_ends() {

		$this->is_checkout = false;
	}

	/**
	 * Hooked over `woocommerce_before_checkout_form`
	 * Checking into checkout starts
	 */
	public function detect_checkout_start() {

		$this->is_checkout = true;
	}

	/**
	 * Hooked over `wcst_modify_savings_price`
	 * Checking if we can process cart item and calculate savings based on the quantity chosen by the user on cart
	 *
	 * @param $price_difference Price to show and filter
	 * @param $deal_expiry_single Single array/data from deal expiry
	 * @param $productInfo WCST_Product Product object
	 * @param $showon Current page
	 * @param $args Array array of arguments
	 *
	 * @return float modified price difference
	 */
	public function multiply_qty_with_savings_price( $price_difference, $deal_expiry_single, $productInfo, $showon, $args ) {


		if ( $showon == "cart" ) {


			if ( isset( $args['cart_item'] ) && count( $args['cart_item'] ) ) {

				return (float) ( $price_difference * $args['cart_item']['quantity'] );
			}
		}

		return $price_difference;
	}

	/**
	 * Additionally checking if price hook for single page gets modified by the other themes/plugins
	 * Only then modifying the priority to get fit between review and price
	 */
	public function wcst_wc_price_hook_checking() {
		global $wp_filter;
		if ( isset( $wp_filter['woocommerce_single_product_summary'] ) && isset( $wp_filter['woocommerce_single_product_summary']->callbacks[10] ) && is_array( $wp_filter['woocommerce_single_product_summary']->callbacks[10] ) && array_key_exists( 'woocommerce_template_single_price', $wp_filter['woocommerce_single_product_summary']->callbacks[10] ) ) {
			// change woocommerce_template_single_price priority from 10 to 15
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
		}
	}

	/**
	 * Hooked onto `wcst_exclude_cats_from_best_seller_list`
	 * Removing Categories from the list if they already get covered by the best seller badge
	 *
	 * @param $exclude_cats array Excluded categories
	 * @param $product_ID Int Product ID
	 *
	 * @return mixed Array excluded categories if any
	 */
	public function wcst_handle_cats_for_best_sellers( $exclude_cats, $product_ID ) {

		global $wcst_top_cat_id;

		if ( ! $wcst_top_cat_id ) {
			return $exclude_cats;
		}
		$clone = $wcst_top_cat_id;

		if ( ! isset( $clone[ $product_ID ] ) ) {
			return $exclude_cats;
		}

		if ( $clone[ $product_ID ] === null ) {
			return $exclude_cats;
		}

		reset( $clone[ $product_ID ] );
		$getcatID = key( $clone[ $product_ID ] );


		if ( ! array_key_exists( $getcatID, $exclude_cats ) ) {
			$exclude_cats[ $getcatID ] = '';
		}

		return $exclude_cats;
	}


	public function wcst_woocommerce_thankyou( $order_id ) {
		$order       = wc_get_order( $order_id );
		$order_items = $order->get_items();
		if ( is_array( $order_items ) && count( $order_items ) > 0 ) {
			foreach ( $order_items as $item ) {
				$pid = $item['product_id'];

				wp_schedule_single_event( time() + 1, 'wcst_new_order_schedule_event', array( $pid ) );
			}
		}
	}

	/**
	 * handling over thank you hook to delete all the transients from db for the product
	 *
	 * @param $product_id
	 */
	public function wcst_new_order_schedule_single_event( $product_id ) {
		global $wpdb;
		if ( class_exists( 'XL_Transient' ) ) {
			$xl_transient_obj = XL_Transient::get_instance();
			$xl_transient_obj->delete_all_transients( 'sales-trigger' );
		}
	}

	/**
	 * Output generator tag to aid debugging.
	 *
	 * @access public
	 */
	public function wcst_generator_tag( $gen, $type ) {

		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="XL-Sales-Trigger  ' . esc_attr( WCST_VERSION ) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="XL-Sales-Trigger ' . esc_attr( WCST_VERSION ) . '" />';
				break;
		}

		return $gen;
	}


	public function wcst_modify_sales_count_display_if_no_merge_tag( $content, $settings, $productInfo, $triggerKey, $trigger ) {
		if ( ( false === strpos( $settings["label"], "{{order_count}}" ) ) && ( false === strpos( $settings["label"], "{{sold_item_count}}" ) ) ) {
			$template_output = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $settings["label"], $trigger->slug ) );

			return '<div class="wcst_sales_count wcst_sales_count_key_' . $productInfo->product->get_id() . '_' . $triggerKey . '">' . $template_output . '</div>';
		}

		return $content;
	}

	public function wcst_modify_sales_snippet_display_if_no_merge_tag( $content, $settings, $productInfo, $triggerKey, $trigger ) {
		if ( ( false === strpos( $settings["label"], "{{sales_snippet}}" ) ) ) {
			$template_output = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $settings["label"], $trigger->slug ) );

			return '<div class="wcst_sales_snippet wcst_sales_snippet_key_' . $productInfo->product->get_id() . '_' . $triggerKey . '">' . $template_output . '</div>';

		}

		return $content;
	}

	public function get_position_for_index( $index ) {

		$locations = array(
			'0'  => __( 'Grid', WCST_SLUG ),
			'1'  => __( 'Above the Title', WCST_SLUG ),
			'2'  => __( 'Below the Title', WCST_SLUG ),
			'3'  => __( 'Below the Review Rating', WCST_SLUG ),
			'4'  => __( 'Below the Price', WCST_SLUG ),
			'5'  => __( 'Below Short Description', WCST_SLUG ),
			'6'  => __( 'Below Add to Cart Button', WCST_SLUG ),
			'7'  => __( 'Below Category and SKU', WCST_SLUG ),
			'8'  => __( 'Above the Tabs', WCST_SLUG ),
			'11' => __( 'Below Related Products', WCST_SLUG ),
		);

		return $locations[ $index ];
	}

	public function wcst_get_native_decimal_point( $decimal ) {

		return wc_get_price_decimals();
	}

	/**
	 * @param $variations
	 * @param $variable_object
	 * @param $variation WC_Product
	 *
	 * @return mixed
	 */
	public function add_our_own_stock_param_to_read_in_js( $variations, $variable_object, $variation ) {
		global $woocommerce;
		$variations['wcst_stock_qty']    = $variation->get_stock_quantity();
		$variations['wcst_manage_stock'] = $variation->get_manage_stock();

		$sale_price_from = 0;
		$sale_price_to   = 0;
		if ( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
			// if version greater or equal to 3.0.0
			$sale_price_from_obj = $variation->get_date_on_sale_from();
			if ( $sale_price_from_obj != null ) {
				$sale_price_from = $sale_price_from_obj->getTimestamp();
			}
			$sale_price_to_obj = $variation->get_date_on_sale_to();
			if ( $sale_price_to_obj != null ) {
				$sale_price_to = $sale_price_to_obj->getTimestamp();
			}
		} else {
			// for older version
			$sale_price_from = get_post_meta( $variation->get_id(), '_sale_price_dates_from', true );
			if ( $sale_price_from && $sale_price_from != '' ) {
				$sale_price_from = $sale_price_from - ( WCST_Common::get_timezone_difference() );
			}
			$sale_price_to = get_post_meta( $variation->get_id(), '_sale_price_dates_to', true );
			if ( $sale_price_to && $sale_price_to != '' ) {
				$sale_price_to = $sale_price_to - ( WCST_Common::get_timezone_difference() );
			}
		}

		$variations['wcst_sale_from']   = $sale_price_from;
		$variations['wcst_sale_to']     = $sale_price_to;
		$variations['wcst_sale_to_val'] = ( $sale_price_to > 0 ) ? date( WCST_Common::wcst_get_date_format(), $sale_price_to ) : '';

		return $variations;
	}

	public function add_sibling_post_ids_for_wpml( $prod_ids ) {
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$post_id             = $prod_ids[0];
			$all_ative_languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );
			if ( is_array( $all_ative_languages ) && count( $all_ative_languages ) > 0 ) {
				$all_ative_languages_temp = array();
				foreach ( $all_ative_languages as $key1 => $value1 ) {
					$all_ative_languages_temp[] = $key1;
				}
				$all_ative_languages = $all_ative_languages_temp;
				$get_wcml_obj        = woocommerce_wpml::instance();
				$get_translate_id    = get_post( $get_wcml_obj->products->get_original_product_id( $post_id ) );
				if ( isset( $get_translate_id->ID ) ) {
					$parent_post_id = $get_translate_id->ID;
					$all_post_ids   = array();
					foreach ( $all_ative_languages as $key1 => $lang_code ) {
						$translation_post_id = icl_object_id( $parent_post_id, 'product', false, $lang_code );
						if ( '' != $translation_post_id ) {
							$all_post_ids[] = $translation_post_id;
						}
					}
				}
			}

			if ( is_array( $all_post_ids ) && count( $all_post_ids ) > 0 ) {
				$prod_ids = $all_post_ids;
			}
		}

		return $prod_ids;
	}

	public function get_parent_post_id_for_wpml( $prod_id ) {
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$get_wcml_obj     = woocommerce_wpml::instance();
			$get_translate_id = get_post( $get_wcml_obj->products->get_original_product_id( $prod_id ) );
			if ( isset( $get_translate_id->ID ) ) {
				$prod_id = $get_translate_id->ID;
			}
		}

		return $prod_id;
	}

	public function wcst_page_noindex() {
		$post_type = WCST_Common::get_trigger_post_type_slug();
		if ( is_singular( $post_type ) ) {
			echo "<meta name='robots' content='noindex,follow' />\n";
		}
	}

}

WCST_Core::get_instance();
