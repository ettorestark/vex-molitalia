<?php

class WCST_Trigger_Sales_Count extends WCST_Base_Trigger {
	public $slug = 'sales_count';
	public $parent_slug = 'wcst_sales_activities_settings';
	public $title = '';
	public $default_priority = 7;

	public function get_defaults() {
		return array(
			'label'        => __( '{{order_count}} orders in last 30 days.', WCST_SLUG ),
			'date_limit'   => '1',
			'from_date'    => WCST_Common::get_date_modified( "-30 days", "Y-m-d" ),
			'to_date'      => WCST_Common::get_date_modified( false, "Y-m-d" ),
			'box_bg_color' => '#ffffff',
			'border_color' => '#ececec',
			'text_color'   => '#252525',
			'font_size'    => 16,
			'restrict'     => 0,
			'position'     => '5',
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_sales_count_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can select <i>Order Start and End date</i> to display sales count like "25 orders in last 30 days". for selected dates.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_count',
				),
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_sales_count_date_limit',
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
					'data-conditional-value' => 'sales_count',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_count_from_date',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'sales_count',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_count_date_limit',
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
				'id'          => '_wcst_data_wcst_sales_count_to_date',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'sales_count',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_count_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'       => __( 'Sales Count Text', WCST_SLUG ),
				'desc'       => __( '<i>{{order_count}}</i> outputs the number of sales for given period.<br/><i>{{sold_item_count}}</i> outputs the number of item sales for given period.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_count',
				),
			),
			array(
				'name'        => __( 'Hide if Number of Orders is Less than', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_count_restrict',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_count',
				),
			),
			array(
				'name'       => __( 'Background Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_box_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_count',
				),
			),
			array(
				'name'       => __( 'Border Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_border_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_count',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_count',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_count_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'sales_count',
				),
			),
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can select <i>Order Start and End date</i> to display sales count like "25 orders in last 30 days". for selected dates.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_sales_count_mode',
				'type'                     => 'wcst_switch',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Sales Count', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_sales_count_date_limit',
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
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_count_from_date',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_count_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'options'     => array(
					'is_date_start' => 'yes',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'        => __( 'To Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_count_to_date',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_sales_count_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'       => __( 'Sales Count Text', WCST_SLUG ),
				'desc'       => __( '<i>{{order_count}}</i> outputs the number of sales for given period.<br/><i>{{sold_item_count}}</i> outputs the number of item sales for given period.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Hide if Number of Orders is Less than', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_count_restrict',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Background Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_box_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Border Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_border_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_sales_count_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_sales_count_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_sales_count_position',
				'show_option_none' => false,
				'after_row'        => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
				'type'             => 'select',
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_sales_count_mode',
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

		foreach ( $wcst_sales_count_arr as $trigger_key => $sales_count_single ) {
			$badge_position = $sales_count_single['position'];
			if ( $badge_position == $position ) {
				WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );

				$this->output_html( $trigger_key, $sales_count_single, $productInfo, 'product' );
			}
		}
	}

	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();

		array_push( $parent, 'booking' );

		return $parent;
	}

	public function get_title() {
		return __( 'Sales Count', WCST_SLUG );
	}

	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {
		global $wpdb, $wcst_pro, $set_transient_arr;
		$xl_transient_obj                 = XL_Transient::get_instance();
		$xl_cache_obj                     = XL_Cache::get_instance();
		$classes                          = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes                          .= " wcst_on_" . $page . " ";
		$wpdb->woocommerce_order_items    = $wpdb->prefix . 'woocommerce_order_items';
		$wpdb->woocommerce_order_itemmeta = $wpdb->prefix . 'woocommerce_order_itemmeta';

		$date_to   = WCST_Common::get_current_date( "Y-m-d" );
		$date_from = WCST_Common::get_date_modified( "-30 days", "Y-m-d" );
		WCST_Common::insert_log( print_r( $single, true ), $this->slug );

		$out_put_content = apply_filters( 'wcst_sales_count_display_content_before_data', '', $single, $productInfo, $trigger_key, $this );

		if ( $out_put_content !== "" ) {
			echo $out_put_content;

			return;
		}

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
		$cache_key     = $cache_prod_id . '_' . md5( 'wcst_sales_count_orders_' . $cache_prod_id . '_' . $date_from . '_' . $date_to );

		/**
		 * Setting xl cache and transient for category best seller data
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
				$wc_states      = WCST_Common::wcst_get_wc_states();
				$prod_ids_arr   = array();
				$prod_ids_arr[] = $productInfo->product->get_id();
				$prod_ids_arr   = apply_filters( 'wcst_check_wpml_sibling_prod_ids', $prod_ids_arr );

				$query1 = $wpdb->prepare( "SELECT `" . $wpdb->posts . "`.`ID`, `" . $wpdb->woocommerce_order_items . "`.`order_item_id` FROM `" . $wpdb->woocommerce_order_items . "`, `" . $wpdb->posts . "`, `" . $wpdb->woocommerce_order_itemmeta . "` WHERE `" . $wpdb->woocommerce_order_items . "`.`order_id` = `" . $wpdb->posts . "`.`ID` AND `" . $wpdb->woocommerce_order_items . "`.`order_item_id` = `" . $wpdb->woocommerce_order_itemmeta . "`.`order_item_id` AND `" . $wpdb->woocommerce_order_itemmeta . "`.`meta_key` = '_product_id' AND `" . $wpdb->woocommerce_order_itemmeta . "`.`meta_value` IN ('" . implode( '\',\'', $prod_ids_arr ) . "') AND `" . $wpdb->posts . "`.`post_status` IN ('" . implode( '\',\'', $wc_states ) . "') AND `" . $wpdb->posts . "`.`post_date` BETWEEN %s AND %s ORDER BY `" . $wpdb->woocommerce_order_items . "`.`order_id` DESC", $date_from . " 00:00:01", $date_to . " 23:59:59" );
				WCST_Common::insert_log( "Query 1: " . $query1, $this->slug );
				$query1_result = $wpdb->get_results( $query1, ARRAY_A );
				$xl_transient_obj->set_transient( $cache_key, $query1_result, apply_filters( 'wcst_transients_ttl', 86400, 'wcst_sales_count_orders_' ), 'sales-trigger' );
				$set_transient_arr[ $productInfo->product->get_id() ][ $cache_key ] = $query1;
			}
			$xl_cache_obj->set_cache( $cache_key, $query1_result, 'sales-trigger' );
		}

		WCST_Common::insert_log( "Query 1 Result: " . print_r( $query1_result, true ), $this->slug );

		if ( is_array( $query1_result ) && count( $query1_result ) > 0 ) {
			$query1_result_id      = array();
			$query1_result_item_id = array();
			foreach ( $query1_result as $result_single ) {
				$query1_result_id[]      = $result_single['ID'];
				$query1_result_item_id[] = $result_single['order_item_id'];
			}
			$count = count( $query1_result_id );

			WCST_Common::insert_log( "Count: " . $count, $this->slug );
			$sold_item_count = 0;

			if ( $count > 0 ) {
				if ( ( $single['restrict'] - 1 ) < $count ) {
					$template_output = $single['label'];
					$template_output = str_replace( '{{order_count}}', '<span>' . $count . '</span>', $template_output );

					if ( strpos( $template_output, '{{sold_item_count}}' ) !== false ) {

//                        $cache_key = md5('wcst_sales_count_items_' . $productInfo->product->get_id() . '_' . $date_from . '_' . $date_to);
//
//                        $cache_key_transient = false;
//                        if(WCST_Common::$is_force_transient === FALSE){
//                            $cache_key_transient = get_transient($cache_key);
//                        }
//
//                        if ($cache_key_transient) {
//                            $query2_result = $cache_key_transient;
//                        } else {
						$query2        = "SELECT Sum(`meta_value`) AS 'qty' FROM `" . $wpdb->woocommerce_order_itemmeta . "` WHERE `meta_key` = '_qty' AND `order_item_id` IN (" . implode( ',', $query1_result_item_id ) . ")";
						$query2_result = $wpdb->get_results( $query2, ARRAY_A );
//                            set_transient($cache_key, $query2_result, 3600);
//                            $set_transient_arr[$productInfo->product->get_id()][$cache_key] = $query2;
//                        }


						$sold_item_count = $query2_result[0]['qty'];
						$template_output = str_replace( '{{sold_item_count}}', '<span>' . $sold_item_count . '</span>', $template_output );
						$template_output = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $template_output, $this->slug ) );
					}

					WCST_Common::insert_log( "template output: " . $template_output . "\r\n\r\n", $this->slug );

					$template_output = '<div class="' . trim( $classes ) . ' wcst_sales_count wcst_sales_count_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="' . $trigger_key . '">' . $template_output . '</div>';

					echo apply_filters( 'wcst_sales_count_display_content', $template_output, $count, $sold_item_count, $single, $trigger_key, $productInfo );
				}
			}
		}
	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_wp_head_css     = "";
		$wcst_sales_count_arr = $data;
		if ( ! $productInfo->product ) {
			return "";
		}
		foreach ( $wcst_sales_count_arr as $trigger_key => $sales_count_single ) {
			$sales_insight_css = '';
			ob_start();
			?>
            body .wcst_sales_count.wcst_sales_count_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> { <?php
			echo isset( $sales_count_single['box_bg_color'] ) ? ' background: ' . $sales_count_single['box_bg_color'] . ';' : '';
			echo isset( $sales_count_single['border_color'] ) ? ' border: 1px solid ' . $sales_count_single['border_color'] . ';' : '';
			?>}
            body .wcst_sales_count.wcst_sales_count_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> { <?php
			echo isset( $sales_count_single['text_color'] ) ? ' color: ' . $sales_count_single['text_color'] . ';' : '';
			echo isset( $sales_count_single['font_size'] ) ? ' font-size: ' . $sales_count_single['font_size'] . ';' : '';
			?>}
			<?php
			$sales_insight_css = ob_get_clean();
			$wcst_wp_head_css  .= $sales_insight_css;
		}

		return $wcst_wp_head_css;
	}

}

WCST_Triggers::register( new WCST_Trigger_Sales_Count() );
