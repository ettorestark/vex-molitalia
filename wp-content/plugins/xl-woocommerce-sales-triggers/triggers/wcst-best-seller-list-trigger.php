<?php

class WCST_Trigger_Best_Seller_List extends WCST_Base_Trigger {


	public $slug = 'best_seller_list';
	public $parent_slug = 'wcst_best_sellers_settings';
	public $title = '';
	public $default_priority = 8;


	public function get_title() {
		return __( 'Best Seller List', WCST_SLUG );
	}

	public function get_defaults() {
		return array(
			'heading'                    => __( 'This item is Best Seller in following categories:', WCST_SLUG ),
			'label'                      => __( '#{{category_rank}} Best Seller in {{category_name}} ', WCST_SLUG ),
			'date_limit'                 => '1',
			'from_date'                  => WCST_Common::get_date_modified( "-30 days", "Y-m-d" ),
			'to_date'                    => WCST_Common::get_date_modified( false, "Y-m-d" ),
			'hyperlink_category'         => 'yes',
			'number_of_list_items'       => 4,
			'show_list_item_if_position' => '100',
			'position'                   => '6',
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_best_seller_list_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can set it up to display best seller category list that shows product\'s rank, by comparing sales of other products from all its categories.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_list',
				),
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_best_seller_list_date_limit',
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
					'data-conditional-value' => 'best_seller_list',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_list_date_from',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'best_seller_list',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_list_date_limit',
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
				'id'          => '_wcst_data_wcst_best_seller_list_date_to',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'best_seller_list',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_list_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'       => __( 'List Heading', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_list_heading',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_list',
				),
			),
			array(
				'name'       => __( 'List Text', WCST_SLUG ),
				'desc'       => '{{category_rank}} displays rank of the product in the particular category {{category_name}}',
				'id'         => '_wcst_data_wcst_best_seller_list_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_list',
				),
			),
			array(
				'name'        => __( 'Number Of Categories In List', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_list_number_of_list_items',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_list',
				),
			),
			array(
				'name'        => __( 'Hide Category if Rank Above', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_list_show_list_item_if_position',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_list',
				),
			),
			array(
				'name'       => __( 'Hyperlink Category', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_list_hyperlink_category',
				'type'       => 'radio_inline',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_list',
				),
				'options'    => array(
					'yes' => __( 'Yes ', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
		);
	}

	public function get_post_settings() {
		return array(

			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can set it up to display best seller category list that shows product\'s rank, by comparing sales of other products from all its categories.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_best_seller_list_mode',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Best Seller List', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'type'                     => 'wcst_switch',
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_best_seller_list_date_limit',
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
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_list_date_from',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_list_date_limit',
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
				'id'          => '_wcst_data_wcst_best_seller_list_date_to',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_list_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'       => __( 'List Heading', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_list_heading',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'List Text', WCST_SLUG ),
				'desc'       => '<i>{{category_rank}}</i> displays rank of the product in the particular category <i>{{category_name}}</i>',
				'id'         => '_wcst_data_wcst_best_seller_list_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Number Of Categories In List', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_list_number_of_list_items',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Hide Category if Rank Above', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_list_show_list_item_if_position',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Hyperlink Category', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_list_hyperlink_category',
				'type'       => 'radio_inline',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_list_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'yes' => __( 'Yes ', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_best_seller_list_position',
				'after_row'        => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
				'show_option_none' => false,
				'type'             => 'select',
				'attributes'       => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_list_mode',
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
			)
		);
	}

	public function handle_single_product_request( $data, $productInfo, $position ) {
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		if ( $productInfo->product_type != 'external' ) {
			$wcst_best_seller_list_arr = $data;
			foreach ( $wcst_best_seller_list_arr as $trigger_key => $best_seller_list_single ) {
				$badge_position = $best_seller_list_single['position'];
				if ( $badge_position == $position ) {
					WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );

					$this->output_html( $trigger_key, $best_seller_list_single, $productInfo, "product" );
				}
			}

		}
	}

	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {
		global $wpdb;
		$xl_cache_obj     = XL_Cache::get_instance();
		$xl_transient_obj = XL_Transient::get_instance();
		$classes          = apply_filters( 'wcst_html_classes', '', $this->slug );

		$is_show_div               = apply_filters( 'wcst_is_best_seller_list_on_div', 0, $single );
		$classes                   .= " wcst_on_" . $page . " ";
		$best_seller_list_settings = $single;
		WCST_Common::insert_log( print_r( $best_seller_list_settings, true ), $this->slug );

		$show_list_content = apply_filters( 'wcst_best_seller_list_display_content_before_data', '', $best_seller_list_settings, $productInfo, $is_show_div );
		if ( $show_list_content !== '' ) {
			echo $show_list_content;

			return;
		}

		$wcst_best_seller  = array();
		$wcst_pro_cats     = array();
		$wcst_pro_cats_raw = array();
		// product cats
		$product_categories = get_the_terms( $productInfo->product->get_id(), 'product_cat' );
		if ( is_array( $product_categories ) && count( $product_categories ) > 0 ) {
			foreach ( $product_categories as $val ) {
				$term_id                       = $val->term_id;
				$wcst_pro_cats_raw[ $term_id ] = $val;
			}
		}

		// product has categories
		if ( is_array( $product_categories ) && count( $product_categories ) > 0 ) {
			$wpdb->woocommerce_order_items    = $wpdb->prefix . 'woocommerce_order_items';
			$wpdb->woocommerce_order_itemmeta = $wpdb->prefix . 'woocommerce_order_itemmeta';

			$results = WCST_Common::get_products_sales( $single['date_limit'], $single );

			$dates = WCST_Common::get_date_from_to_for_query( $single['date_limit'], $single );

			$date_from = $dates['from'];
			$date_to   = $dates['to'];

			$cache_key = $productInfo->product->get_id() . '_' . md5( 'wcst_best_seller_list_' . $productInfo->product->get_id() . '_' . $date_from . '_' . $date_to );

			/**
			 * Setting xl cache and transient for category best seller data
			 */
			$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );

			if ( false !== $cache_data ) {
				$wcst_pro_cats = $cache_data;
			} else {
				$best_seller_list_data_transient = false;
				if ( WCST_Common::$is_force_transient === false ) {
					$best_seller_list_data_transient = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );
				}
				if ( false !== $best_seller_list_data_transient ) {
					$wcst_pro_cats = $best_seller_list_data_transient;
				} elseif ( $results ) {
					$filtered = WCST_Common::array_flat_mysql_results( $results, 'product_id', 'order_item_qty' );
					WCST_Common::insert_log( print_r( $results, true ), $this->slug );

					$product_id_arr = array();
					$product_id_arr = array_keys( $filtered );
					if ( is_array( $filtered ) && count( $filtered ) > 0 && in_array( $productInfo->product->get_id(), $product_id_arr ) ) {
						foreach ( $product_categories as $val ) {
							$term_id   = $val->term_id;
							$term_slug = $val->slug;

							$cat_wp_query = null;
							$args         = array(
								'post_type'   => 'product',
								'post_status' => 'publish',
								'showposts'   => - 1,
								'fields'      => 'ids',
								'tax_query'   => array(
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'slug',
										'terms'    => $term_slug,
//                                    'include_children' => false
									),
								),
								'post__in'    => $product_id_arr,
							);
							$cat_wp_query = new WP_Query( $args );
							WCST_Common::insert_log( $cat_wp_query->request, $this->slug );

							if ( $cat_wp_query->found_posts > 0 ) {

								foreach ( $cat_wp_query->posts as $postID ) {
									$pro_id                               = $postID;
									$wcst_pro_cats[ $term_id ][ $pro_id ] = isset( $filtered[ $pro_id ] ) ? $filtered[ $pro_id ] : '0';
								}
								arsort( $wcst_pro_cats[ $term_id ] );
							}
						}
					}
					$xl_transient_obj->set_transient( $cache_key, $wcst_pro_cats, 3600, 'sales-trigger' );
				}
				$xl_cache_obj->set_cache( $cache_key, $wcst_pro_cats, 'sales-trigger' );
			}

			if ( count( $wcst_pro_cats ) > 0 ) {
				WCST_Common::insert_log( print_r( $wcst_pro_cats, true ), $this->slug );
				foreach ( $wcst_pro_cats as $term_id => $val ) {
					$position = 0;
					if ( ! array_key_exists( $productInfo->product->get_id(), $val ) ) {
						break;
					}
					foreach ( $val as $post_ids => $post_orders ) {
						$position ++;
						if ( $post_ids == $productInfo->product->get_id() ) {
							break;
						}
					}
					if ( $position !== 0 ) {
						$wcst_best_seller[ $term_id ] = $position;
					}
				}
			}
		}

		if ( count( $wcst_best_seller ) > 0 && $best_seller_list_settings['number_of_list_items'] > 0 ) {
			asort( $wcst_best_seller );

			WCST_Common::insert_log( "category has products with positions.", $this->slug );
			WCST_Common::insert_log( print_r( $wcst_best_seller, true ), $this->slug );
			$removed_cats = apply_filters( 'wcst_exclude_cats_from_best_seller_list', array(), $productInfo->product->get_id() );

			if ( is_array( $removed_cats ) && count( $removed_cats ) > 0 ) {
				$wcst_best_seller = array_diff_key( $wcst_best_seller, $removed_cats );
			}

//            if (is_array($wcst_top_cat_id) && count($wcst_top_cat_id) > 0) {
//                $wcst_best_seller = array_diff_key($wcst_best_seller, $wcst_top_cat_id);
//            }
			$best_seller_cat_prefix = '';
			$best_seller_cat_suffix = '';
			ob_start();
			echo '<div class="wcst_best_seller_wrap ' . trim( $classes ) . ' wcst_best_sellers_list wcst_best_sellers_list_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="' . $trigger_key . '">';
			echo isset( $best_seller_list_settings['heading'] ) ? '<div class="wcst_best_seller_list_heading">' . $best_seller_list_settings['heading'] . '</div>' : '';

			if ( $is_show_div ) {
				echo '<div class="wcst_best_seller_list_div">';
			} else {
				echo '<ul class="wcst_best_seller_list">';
			}

			$y = 1;

			$list_display = array();
			foreach ( $wcst_best_seller as $key => $val ) {
				if ( ( $best_seller_list_settings['show_list_item_if_position'] ) >= $val ) {
					$list_display[] = $key;
					if ( $best_seller_list_settings['hyperlink_category'] == 'yes' ) {
						$best_seller_cat_prefix = '<a href="' . get_term_link( $key, 'product_cat' ) . '">';
						$best_seller_cat_suffix = '</a>';
					}
					$list_item_label = $best_seller_list_settings['label'];
					$list_item_label = str_replace( '{{category_rank}}', $val, $list_item_label );
					$list_item_label = str_replace( '{{category_name}}', $best_seller_cat_prefix . $wcst_pro_cats_raw[ $key ]->name . $best_seller_cat_suffix, $list_item_label );

					$list_item_label = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $list_item_label, $this->slug ) );

					if ( $is_show_div ) {
						echo '<div data-id="' . $key . '">' . $list_item_label . '</div>';
					} else {
						echo '<li data-id="' . $key . '">' . $list_item_label . '</li>';
					}
					if ( $best_seller_list_settings['number_of_list_items'] > 0 && $y >= $best_seller_list_settings['number_of_list_items'] ) {
						break;
					}
					$y ++;
				}
			}
			if ( $is_show_div ) {
				echo '</div>';
			} else {
				echo '</ul>';
			}
			echo '</div>';
			$wcst_best_sellers_list = ob_get_clean();
			if ( is_array( $list_display ) && count( $list_display ) > 0 ) {

				$show_list_content = apply_filters( 'wcst_best_seller_list_display_content', $wcst_best_sellers_list, $wcst_best_seller, $productInfo, $trigger_key, $best_seller_list_settings );

				echo $show_list_content;
			}
		}

	}


	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();
		array_push( $parent, 'booking' );

		return $parent;
	}
}

WCST_Triggers::register( new WCST_Trigger_Best_Seller_List() );