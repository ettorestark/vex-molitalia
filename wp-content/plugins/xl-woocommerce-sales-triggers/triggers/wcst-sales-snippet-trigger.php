<?php

class WCST_Trigger_Sales_Snippet extends WCST_Base_Trigger {

	public $slug = 'sales_snippet';
	public $parent_slug = 'wcst_sales_activities_settings';
	public $default_priority = 6;
	public $title = '';

	public function get_defaults() {
		return array(
			'label'        => __( '{{sales_snippet}} bought this item recently.', WCST_SLUG ),
			'date_limit'   => '1',
			'output'       => 'default',
			'from_date'    => WCST_Common::get_date_modified( "-30 days", "Y-m-d" ),
			'to_date'      => WCST_Common::get_date_modified( false, "Y-m-d" ),
			'box_bg_color' => '#efeddc',
			'border_color' => '#efeace',
			'text_color'   => '#252525',
			'font_size'    => 16,
			'restrict'     => 0,
			'position'     => '6',
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_savings_head',
				'type'       => 'wcst_html_content_field',
				'content'    => __( '<div class="wcst_desc_before_row">You can select <i>Order Start and End date</i> to display sales snippet like "John from Chicago, Taylor from Miami and 7 others bought this item recently" for selected dates.</div>', WCST_SLUG ),
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_sales_snippet_date_limit',
				'show_option_none' => false,
				'type'             => 'select',
				'options'          => array(
					'-4' => __( '1 Day', WCST_SLUG ),
					'-3' => __( '3 Days', WCST_SLUG ),
					'-2' => __( '7 Days', WCST_SLUG ),
					'-1' => __( '15 Days', WCST_SLUG ),
					'1'  => __( '30 Days', WCST_SLUG ),
					'2'  => __( '3 Months', WCST_SLUG ),
					'3'  => __( '6 Months', WCST_SLUG ),
					'4'  => __( '1 Year', WCST_SLUG ),
					'5'  => __( 'Custom', WCST_SLUG ),
				),
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_from_date',
				'type'        => 'text_date',
				'row_classes' => array( 'WCST_Admin_CMB2_Support', 'row_date_classes' ),
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
//				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'sales_snippet',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_snippet_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'options'     => array(
					'is_date_start' => 'yes',
				),
				// 'timezone_meta_key' => 'wiki_test_timezone',
				'date_format' => 'Y-m-d',
			),
			array(
				'name'        => __( 'To Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_to_date',
				'type'        => 'text_date',
				'row_classes' => array( 'WCST_Admin_CMB2_Support', 'row_date_classes' ),
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'sales_snippet',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_snippet_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'       => __( 'Sales Snippet Text', WCST_SLUG ),
				'desc'       => __( '<i>{{sales_snippet}}</i> outputs the text "Buyer A from Location A , Buyer B from Location B and X others"', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
			array(
				'name'       => __( 'Snippet Output', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_output',
				'type'       => 'radio',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
				'options'    => array(
					'default'   => __( 'Buyer A from Location A, Buyer B from Location B and X others', WCST_SLUG ),
					'buyer'     => __( 'Buyer A, Buyer B and X others', WCST_SLUG ),
					'anonymous' => __( 'Someone from Location A and X others', WCST_SLUG ),
				),
			),
			array(
				'name'        => __( 'Hide if Number of Orders is Less than', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_restrict',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
			array(
				'name'       => __( 'Background Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_box_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
			array(
				'name'       => __( 'Border Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_border_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_snippet',
				),
			),
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can select <i>Order Start and End date</i> to display sales snippet like "John from Chicago, Taylor from Miami and 7 others bought this item recently" for selected dates.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_sales_snippet_mode',
				'type'                     => 'wcst_switch',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Sales Snippet', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_sales_snippet_date_limit',
				'show_option_none' => false,
				'type'             => 'select',
				'options'          => array(
					'-4' => __( '1 Day', WCST_SLUG ),
					'-3' => __( '3 Days', WCST_SLUG ),
					'-2' => __( '7 Days', WCST_SLUG ),
					'-1' => __( '15 Days', WCST_SLUG ),
					'1'  => __( '30 Days', WCST_SLUG ),
					'2'  => __( '3 Months', WCST_SLUG ),
					'3'  => __( '6 Months', WCST_SLUG ),
					'4'  => __( '1 Year', WCST_SLUG ),
					'5'  => __( 'Custom', WCST_SLUG ),
				),
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_from_date',
				'type'        => 'text_date',
				'row_classes' => array( 'WCST_Admin_CMB2_Support', 'row_date_classes' ),
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
//				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_snippet_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'options'     => array(
					'is_date_start' => 'yes',
				),
				// 'timezone_meta_key' => 'wiki_test_timezone',
				'date_format' => 'Y-m-d',
			),
			array(
				'name'        => __( 'To Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_to_date',
				'type'        => 'text_date',
				'row_classes' => array( 'WCST_Admin_CMB2_Support', 'row_date_classes' ),
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_snippet_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'       => __( 'Sales Snippet Text', WCST_SLUG ),
				'desc'       => __( '<i>{{sales_snippet}}</i> outputs the text "Buyer A from City A , Buyer B from City B and X others"', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Snippet Output', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_output',
				'type'       => 'radio',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'default'   => __( 'Buyer A from Location A, Buyer B from Location B and X others', WCST_SLUG ),
					'buyer'     => __( 'Buyer A, Buyer B and X others', WCST_SLUG ),
					'anonymous' => __( 'Someone from Location A and X others', WCST_SLUG ),
				),
			),
			array(
				'name'        => __( 'Hide if Number of Orders is Less than', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_restrict',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Background Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_box_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Border Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_border_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_snippet_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_snippet_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_sales_snippet_position',
				'show_option_none' => false,
				'type'             => 'select',
				'after_row'        => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_sales_snippet_mode',
					'data-conditional-value' => '1',
				),
				'options'          => array(
					'1'  => __( 'Above the Title', WCST_SLUG ),
					'2'  => __( 'Below the Title', WCST_SLUG ),
					'3'  => __( 'Below the Review Rating', WCST_SLUG ),
					'4'  => __( 'Below the Price', WCST_SLUG ),
					'5'  => __( 'Below Short Description', WCST_SLUG ),
					'6'  => __( 'Below Add to Cart Button', WCST_SLUG ),
					'7'  => __( 'Below SKU & Categories', WCST_SLUG ),
					'8'  => __( 'Above the Tabs', WCST_SLUG ),
					'11' => __( 'Below Related Products', WCST_SLUG ),
				),
			),
		);
	}

	public function handle_single_product_request( $data, $productInfo, $position ) {
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_sales_count_arr = $data;

		foreach ( $wcst_sales_count_arr as $trigger_key => $single ) {
			$badge_position = $single['position'];
			if ( $badge_position == $position ) {

				WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title() );

				$this->output_html( $trigger_key, $single, $productInfo, 'product' );
			}
		}
	}

	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();
		array_push( $parent, 'booking' );

		return $parent;
	}

	public function get_title() {
		return __( 'Sales Snippet', WCST_SLUG );
	}

	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {

		global $wpdb, $set_transient_arr;
		$xl_transient_obj                 = XL_Transient::get_instance();
		$xl_cache_obj                     = XL_Cache::get_instance();
		$wpdb->woocommerce_order_items    = $wpdb->prefix . 'woocommerce_order_items';
		$wpdb->woocommerce_order_itemmeta = $wpdb->prefix . 'woocommerce_order_itemmeta';

		$classes    = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes    .= " wcst_on_" . $page . " ";
		$date_to    = WCST_Common::get_current_date( "Y-m-d" );
		$text_array = WCST_Common::get_hard_text();

		$out_put_content = apply_filters( 'wcst_sales_snippet_display_content_before_data', '', $single, $productInfo, $trigger_key, $this );

		if ( $out_put_content !== "" ) {
			echo $out_put_content;

			return;
		}

		$date_from = WCST_Common::get_date_modified( "-30 days", "Y-m-d" );
		WCST_Common::insert_log( print_r( $single, true ), $this->slug );
		switch ( $single['date_limit'] ) {
			case "-4":
				$date_from = WCST_Common::get_date_modified( "-1 day", "Y-m-d" );
				break;
			case "-3":
				$date_from = WCST_Common::get_date_modified( "-3 days", "Y-m-d" );
				break;
			case "-2":
				$date_from = WCST_Common::get_date_modified( "-7 days", "Y-m-d" );
				break;
			case "-1":
				$date_from = WCST_Common::get_date_modified( "-15 days", "Y-m-d" );
				break;
			case "1":
				$date_from = WCST_Common::get_date_modified( "-30 days", "Y-m-d" );
				break;
			case "2":
				$date_from = WCST_Common::get_date_modified( "-3 months", "Y-m-d" );
				break;
			case "3":
				$date_from = WCST_Common::get_date_modified( "-6 months", "Y-m-d" );
				break;
			case "4":
				$date_from = WCST_Common::get_date_modified( "-12 months", "Y-m-d" );
				break;
			case "5":
				$date_from = $single['from_date'];
				$date_to   = $single['to_date'];
				break;
			default:
		}

		$cache_prod_id = apply_filters( 'wcst_get_wpml_parent_prod_id', $productInfo->product->get_id() );
		$cache_key     = $cache_prod_id . '_' . md5( 'wcst_sales_snippet_count_' . $cache_prod_id . '_' . $date_from . '_' . $date_to );

		/**
		 * Setting xl cache and transient for category query1 result data
		 */
		$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );
		if ( false !== $cache_data ) {
			$query1_result = $cache_data;
		} else {
			$cache_key_transient = false;
			if ( WCST_Common::$is_force_transient === false ) {
				$cache_key_transient = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );
			}

			if ( false !== $cache_key_transient ) {
				$query1_result = $cache_key_transient;
			} else {
				$wc_states = WCST_Common::wcst_get_wc_states();

				$prod_ids_arr   = array();
				$prod_ids_arr[] = $productInfo->product->get_id();
				$prod_ids_arr   = apply_filters( 'wcst_check_wpml_sibling_prod_ids', $prod_ids_arr );

				$query1 = $wpdb->prepare( "SELECT COUNT(*) as `count` FROM `" . $wpdb->woocommerce_order_items . "`, `" . $wpdb->posts . "`, `" . $wpdb->woocommerce_order_itemmeta . "` WHERE `" . $wpdb->woocommerce_order_items . "`.`order_id` = `" . $wpdb->posts . "`.`ID` AND `" . $wpdb->woocommerce_order_items . "`.`order_item_id` = `" . $wpdb->woocommerce_order_itemmeta . "`.`order_item_id` AND `" . $wpdb->woocommerce_order_itemmeta . "`.`meta_key` = '_product_id' AND `" . $wpdb->woocommerce_order_itemmeta . "`.`meta_value` IN ('" . implode( '\',\'', $prod_ids_arr ) . "') AND `" . $wpdb->posts . "`.`post_status` IN ('" . implode( '\',\'', $wc_states ) . "') AND `" . $wpdb->posts . "`.`post_date` BETWEEN %s AND %s ORDER BY `" . $wpdb->woocommerce_order_items . "`.`order_id` DESC", $date_from . " 00:00:01", $date_to . " 23:59:59" );

				WCST_Common::insert_log( "Query 1: " . $query1, $this->slug );
				$query1_result = $wpdb->get_results( $query1, ARRAY_A );
				add_filter( 'wcst_transients_ttl', function ( $ttl ) {
					return 3600;
				}, 10 );

				$xl_transient_obj->set_transient( $cache_key, $query1_result, apply_filters( 'wcst_transients_ttl', 86400, 'wcst_sales_snippet_count_' ), 'sales-trigger' );
				$set_transient_arr[ $productInfo->product->get_id() ][ $cache_key ] = $query1;
			}
			$xl_cache_obj->set_cache( $cache_key, $query1_result, 'sales-trigger' );
		}

		WCST_Common::insert_log( "Query 1 Result: " . print_r( $query1_result, true ), $this->slug );

		$count = $query1_result[0]['count'];

		WCST_Common::insert_log( "Count: " . $count, $this->slug );
		$dynamic_html = "";
		if ( $count > 0 && ( $single['restrict'] - 1 ) < $count ) {

			$cache_key = $cache_prod_id . '_' . md5( 'wcst_sales_snippet_orders_' . $cache_prod_id . '_' . $date_from . '_' . $date_to );

			/**
			 * Setting xl cache and transient for query 2 result data
			 */
			$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );

			if ( false !== $cache_data ) {
				$query2_result = $cache_data;
			} else {
				if ( WCST_Common::$is_force_transient === false ) {
					$cache_key_transient = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );
				}
				$wc_states = WCST_Common::wcst_get_wc_states();
				if ( false !== $cache_key_transient ) {
					$query2_result = $cache_key_transient;
				} else {
					$query2 = $wpdb->prepare( "SELECT `" . $wpdb->woocommerce_order_items . "`.`order_id` FROM `" . $wpdb->woocommerce_order_items . "`, `" . $wpdb->posts . "`, `" . $wpdb->woocommerce_order_itemmeta . "` WHERE `" . $wpdb->woocommerce_order_items . "`.`order_id` = `" . $wpdb->posts . "`.`ID` AND `" . $wpdb->woocommerce_order_items . "`.`order_item_id` = `" . $wpdb->woocommerce_order_itemmeta . "`.`order_item_id` AND `" . $wpdb->woocommerce_order_itemmeta . "`.`meta_key` = '_product_id' AND `" . $wpdb->woocommerce_order_itemmeta . "`.`meta_value` IN ('" . implode( '\',\'', $prod_ids_arr ) . "') AND `" . $wpdb->posts . "`.`post_status` IN ('" . implode( '\',\'', $wc_states ) . "') AND `" . $wpdb->posts . "`.`post_date` BETWEEN %s AND %s ORDER BY `" . $wpdb->woocommerce_order_items . "`.`order_id` DESC LIMIT 0,4", $date_from . " 00:00:01", $date_to . " 23:59:59" );

					$query2_result = $wpdb->get_results( $query2, ARRAY_A );
					$xl_transient_obj->set_transient( $cache_key, $query2_result, apply_filters( 'wcst_transients_ttl', 86400, 'wcst_sales_snippet_orders_' ), 'sales-trigger' );
					$set_transient_arr[ $productInfo->product->get_id() ][ $cache_key ] = $query2;
				}
				$xl_cache_obj->set_cache( $cache_key, $query2_result, 'sales-trigger' );
			}


			WCST_Common::insert_log( "Query 2 Result: " . print_r( $query2_result, true ), $this->slug );
			$data_Array = array();

			if ( is_array( $query2_result ) && count( $query2_result ) > 0 ) {
				$dynamic_text = array();
				foreach ( $query2_result as $key => $val ) {
					if ( ( count( $dynamic_text ) > 1 ) || ( $single['output'] == 'anonymous' && ( 1 == count( $dynamic_text ) ) ) ) {
						break;
					}
					$order_id    = $val['order_id'];
					$user_f_name = get_post_meta( $order_id, '_billing_first_name', true );
					if ( empty( $user_f_name ) ) {
						continue;
					}

					if ( $single['output'] != 'buyer' ) {
						$user_city = get_post_meta( $order_id, '_billing_city', true );
						if ( empty( $user_city ) ) {
							$user_state = get_post_meta( $order_id, '_billing_state', true );
							if ( empty( $user_state ) ) {
								$user_country = get_post_meta( $order_id, '_billing_country', true );
								if ( empty( $user_country ) ) {
									$user_city = '';
								} else {
									$user_city = $user_country;
								}
							} else {
								$user_city = $user_state;
							}
						}
					}
					$user_string = $user_f_name;

					if ( $single['output'] == 'anonymous' ) {
						$user_string = $text_array['someone'];
					}

					if ( $single['output'] != 'buyer' ) {
						$user_string .= $user_city ? " " . $text_array['from'] . " " . $user_city : '';
					}

					if ( ! in_array( $user_string, $dynamic_text ) ) {
						$dynamic_text[] = $user_string;
					}
				}
			}

			if ( $count > 2 || ( $single['output'] == 'anonymous' && $count > 1 ) ) {
				$dynamic_html = implode( ', ', $dynamic_text );
				$def_display  = 2;
				if ( is_array( $dynamic_text ) && count( $dynamic_text ) > 0 ) {
					$def_display = count( $dynamic_text );
				}
				$diff = $count - $def_display;

				if ( $diff > 1 ) {
					$dynamic_html .= " " . $text_array['&'] . " " . $diff . " " . $text_array['others'];
				} else {
					$dynamic_html .= " " . $text_array['&'] . " " . $diff . " " . $text_array['other'];
				}
			} else {
				$dynamic_html = implode( ' ' . $text_array['&'] . ' ', $dynamic_text );
			}

			if ( is_array( $dynamic_text ) && count( $dynamic_text ) ) {
				$template_output = $single['label'];
				$template_output = str_replace( '{{sales_snippet}}', '<span>' . $dynamic_html . '</span>', $template_output );

				// $template_output = $this->parse_user_history_merge_tags($template_output, $data_Array);
				$template_output = str_replace( '{{product_name}}', $productInfo->product->get_title(), $template_output );
				$template_output = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $template_output, $this->slug ) );

				WCST_Common::insert_log( "template output: " . $template_output . "\r\n\r\n", $this->slug );
				$template_output = '<div class="' . trim( $classes ) . ' wcst_sales_snippet wcst_sales_snippet_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="' . $trigger_key . '">' . $template_output . '</div>';

				echo apply_filters( 'wcst_sales_snippet_display_content', $template_output, $count, $dynamic_html, $single, $trigger_key, $productInfo );
			}
		}
	}

	public function parse_user_history_merge_tags( $template_output, $data_Array ) {
		if ( $data_Array ) {
			foreach ( $data_Array as $key => $val ) {
				foreach ( $val as $slug => $value ) {
					$template_output = str_replace( "{{" . $slug . ":" . ( $key + 1 ) . "}}", $value, $template_output );
				}
			}
		}

		return $template_output;
	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_wp_head_css       = "";
		$wcst_sales_snippet_arr = $data;
		if ( ! $productInfo->product ) {
			return "";
		}
		foreach ( $wcst_sales_snippet_arr as $trigger_key => $sales_snippet_single ) {
			$sales_insight_css = '';
			ob_start();
			?>
            body .wcst_sales_snippet.wcst_sales_snippet_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> { <?php
			echo isset( $sales_snippet_single['box_bg_color'] ) ? ' background: ' . $sales_snippet_single['box_bg_color'] . ';' : '';
			echo isset( $sales_snippet_single['border_color'] ) ? ' border: 1px solid ' . $sales_snippet_single['border_color'] . ';' : '';
			?>}
            body .wcst_sales_snippet.wcst_sales_snippet_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> { <?php
			echo isset( $sales_snippet_single['text_color'] ) ? ' color: ' . $sales_snippet_single['text_color'] . ';' : '';
			echo isset( $sales_snippet_single['font_size'] ) ? ' font-size: ' . $sales_snippet_single['font_size'] . ';' : '';
			?>}
			<?php
			$sales_insight_css = ob_get_clean();

			$wcst_wp_head_css .= $sales_insight_css;
		}

		return $wcst_wp_head_css;
	}

}

WCST_Triggers::register( new WCST_Trigger_Sales_Snippet() );
