<?php

class WCST_Trigger_Best_Seller_Badge extends WCST_Base_Trigger {
	public $slug = 'best_seller_badge';
	public $parent_slug = 'wcst_best_sellers_settings';
	public $title = '';
	public $default_priority = 1;

	public function get_defaults() {
		return array(
			'label'                  => __( '#{{rank}} Best Seller', WCST_SLUG ),
			'badge_style'            => 1,
			'date_limit'             => '1',
			'from_date'              => WCST_Common::get_date_modified( "-30 days", "Y-m-d" ),
			'to_date'                => WCST_Common::get_date_modified( false, "Y-m-d" ),
			'badge_bg_color'         => '#dd3333',
			'badge_text_color'       => '#fff',
			'show_badge_if_position' => 5,
			'hyperlink_category'     => 'yes',
			'position'               => 2,
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_best_seller_badge_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can set it up to display best seller badge that shows product\'s rank, by comparing sales of other products from all its categories.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_badge',
				),
			),
			array(
				'name'                     => __( 'Badge Text', WCST_SLUG ),
				'desc'                     => '<i>{{rank}}</i> displays highest rank of the product in any category',
				'id'                       => '_wcst_data_wcst_best_seller_badge_label',
				'wcst_is_accordion_opened' => true,
				'wcst_accordion_title'     => __( 'Best Sellers Badge', WCST_SLUG ),
				'type'                     => 'textarea',
				'attributes'               => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_badge',
				),
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_best_seller_badge_date_limit',
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
					'data-conditional-value' => 'best_seller_badge',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_badge_date_from',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'best_seller_badge',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_badge_date_limit',
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
				'id'          => '_wcst_data_wcst_best_seller_badge_date_to',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'best_seller_badge',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_badge_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'        => __( 'Hide Badge If Highest Rank Above', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_badge_show_badge_if_position',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                        => 'number',
					'min'                         => '0',
					'pattern'                     => '\d*',
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'best_seller_badge',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_type',
					'data-wcst-conditional-value' => 'best_seller_badge',
				),
			),
			array(
				'name'       => __( 'Badge Style', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_badge_style',
				'type'       => 'radio',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_badge',
				),
				'options'    => array(
					'1' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_1.jpg" />',
					'2' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_2.jpg" />',
					'3' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_3.jpg" />',
					'4' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_4.jpg" />',
				),
			),
			array(
				'name'       => __( 'Hyperlink Category', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_hyperlink_category',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_badge',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Badge Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_badge_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_badge',
				),
			),
			array(
				'name'       => __( 'Badge Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_badge_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'best_seller_badge',
				),
			)
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can set it up to display best seller badge that shows product\'s rank, by comparing sales of other products from all its categories.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_best_seller_badge_mode',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Best Sellers Badge', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'type'                     => 'wcst_switch',
				'default'                  => 0,
				'label'                    => array(
					'on'  => __( 'Activate', WCST_SLUG ),
					'off' => __( 'Deactivate', WCST_SLUG )
				)
			),
			array(
				'name'       => __( 'Badge Text', WCST_SLUG ),
				'desc'       => '<i>{{rank}}</i> displays highest rank of the product in any category',
				'id'         => '_wcst_data_wcst_best_seller_badge_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Date Limit', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_best_seller_badge_date_limit',
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
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'From Date', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_badge_date_from',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'before_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb_for_order_date' ),
				'label_cb'    => array( 'WCST_Admin_CMB2_Support', 'cmb2_label_callback_for_date_fields' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_badge_date_limit',
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
				'id'          => '_wcst_data_wcst_best_seller_badge_date_to',
				'type'        => 'text_date',
				'row_classes' => 'wcst_field_date_range',
				'after_row'   => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb_for_order_date' ),
				'attributes'  => array(
					'data-conditional-id'         => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_best_seller_badge_date_limit',
					'data-wcst-conditional-value' => '5',
				),
				'date_format' => 'Y-m-d',
			),
			array(
				'name'        => __( 'Hide Badge If Highest Rank Above', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_best_seller_badge_show_badge_if_position',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Badge Style', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_badge_style',
				'type'       => 'radio',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'1' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_1.jpg" />',
					'2' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_2.jpg" />',
					'3' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_3.jpg" />',
					'4' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_4.jpg" />',
				),
			),
			array(
				'name'       => __( 'Hyperlink Category', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_hyperlink_category',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Badge Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_badge_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Badge Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_best_seller_badge_badge_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_best_seller_badge_position',
				'show_option_none' => false,
				'type'             => 'select',
				'after_row'        => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
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
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_best_seller_badge_mode',
					'data-conditional-value' => '1',
				),
			),
		);
	}

	public function handle_single_product_request( $data, $productInfo, $position ) {
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		if ( $productInfo->product_type != 'external' ) {
			$wcst_best_seller_badge_arr = $data;
			foreach ( $wcst_best_seller_badge_arr as $trigger_key => $best_seller_badge_single ) {
				$badge_position = $best_seller_badge_single['position'];
				if ( $badge_position == $position ) {
					WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );
					$this->output_html( $trigger_key, $best_seller_badge_single, $productInfo, "product" );
				}
			}
		}
	}

	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();
		array_push( $parent, 'booking' );

		return $parent;
	}

	public function get_title() {
		return __( 'Best Seller Badge', WCST_SLUG );
	}

	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {
		global $wpdb, $wcst_top_cat_id;
		$xl_transient_obj = XL_Transient::get_instance();
		$xl_cache_obj     = XL_Cache::get_instance();

		$classes                    = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes                    .= " wcst_on_" . $page . " ";
		$hard_text_array            = WCST_Common::get_hard_text();
		$best_seller_badge_settings = $single;

		$wcst_top_cat_id[ $productInfo->product->get_id() ] = array();

		$wcst_best_seller = array();
		$wcst_pro_cats    = array();

		$show_static_badge = apply_filters( 'wcst_show_static_best_seller_badge', false, $single, $productInfo );
		if ( $show_static_badge === true ) {
			$template_output = $single["label"];
			$template_output = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $template_output, $this->slug ) );
			?>
            <div class="<?php echo trim( $classes ); ?> wcst_best_sellers_badge wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> wcst_best_sellers_badge_<?php echo $best_seller_badge_settings['badge_style'] ?>" data-trigger-id="<?php echo $trigger_key ?>">
                <span class="wcst_best_sellers_badge_span_one"> <span><?php echo $template_output; ?></span></span>
            </div>
			<?php
			return;
		}

		$out_put_content = apply_filters( 'wcst_best_seller_badge_display_content_before_data', '', $single, $productInfo, $trigger_key, $this );
		if ( $out_put_content !== "" ) {
			echo $out_put_content;

			return;
		}

		// product cats
		$product_categories = get_the_terms( $productInfo->product->get_id(), 'product_cat' );

		WCST_Common::insert_log( print_r( $single, true ), $this->slug );

		$removed_cats = apply_filters( 'wcst_exclude_cats_from_best_seller_badge', array(), $productInfo->product->get_id() );

		// product has categories
		if ( is_array( $product_categories ) && count( $product_categories ) > 0 ) {
			foreach ( $product_categories as $val ) {
				$term_id = $val->term_id;

				if ( in_array( $term_id, $removed_cats ) ) {
					continue;
				}
				$wcst_pro_cats_raw[ $term_id ] = $val;
			}
		}

		if ( is_array( $product_categories ) && count( $product_categories ) > 0 ) {
			$results = WCST_Common::get_products_sales( $single['date_limit'], $single );

			$filtered  = array();
			$dates     = WCST_Common::get_date_from_to_for_query( $single['date_limit'], $single );
			$date_from = $dates['from'];
			$date_to   = $dates['to'];
			$cache_key = $productInfo->product->get_id() . '_' . md5( 'wcst_best_seller_badge_' . $productInfo->product->get_id() . '_' . $date_from . '_' . $date_to );

			/**
			 * Setting xl cache and transient for category best seller data
			 */
			$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );
			if ( false !== $cache_data ) {
				$wcst_pro_cats = $cache_data;
			} else {
				$best_seller_badge_data_transient = false;
				if ( WCST_Common::$is_force_transient === false ) {
					$best_seller_badge_data_transient = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );
				}

				if ( false !== $best_seller_badge_data_transient ) {
					$wcst_pro_cats = $best_seller_badge_data_transient;
				} elseif ( $results ) {
					WCST_Common::insert_log( print_r( $results, true ), $this->slug );
					$filtered       = WCST_Common::array_flat_mysql_results( $results, 'product_id', 'order_item_qty' );
					$product_id_arr = array();
					$product_id_arr = array_keys( $filtered );
					if ( is_array( $filtered ) && count( $filtered ) > 0 && in_array( $productInfo->product->get_id(), $product_id_arr ) ) {
						foreach ( $product_categories as $val ) {
							$term_id = $val->term_id;
							if ( in_array( $term_id, $removed_cats ) ) {
								continue;
							}
							$term_slug    = $val->slug;
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

			if ( is_array( $wcst_pro_cats ) && count( $wcst_pro_cats ) > 0 ) {
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

		if ( count( $wcst_best_seller ) > 0 ) {
			asort( $wcst_best_seller );
			WCST_Common::insert_log( "category has products with positions.", $this->slug );
			WCST_Common::insert_log( print_r( $wcst_best_seller, true ), $this->slug );

			$term_id = apply_filters( 'wcst_best_seller_badge_cat_id', key( $wcst_best_seller ), $productInfo );
			foreach ( $wcst_best_seller as $key1 => $value1 ) {
				if ( $term_id == $key1 ) {
					$top_category['key']   = $term_id;
					$top_category['value'] = $wcst_best_seller[ $term_id ];
					break;
				}
			}

			$wcst_top_cat_id[ $productInfo->product->get_id() ][ $term_id ] = '';

			$position = $top_category['value'];

			WCST_Common::insert_log( "term: " . $wcst_pro_cats_raw[ $term_id ]->name . " with position " . $position . "\r\n\r\n", $this->slug );

			$best_seller_cat_prefix = '';
			$best_seller_cat_suffix = '';
			if ( $best_seller_badge_settings['hyperlink_category'] == 'yes' ) {
				$best_seller_cat_prefix = '<a href="' . get_term_link( $term_id, 'product_cat' ) . '">';
				$best_seller_cat_suffix = '</a>';
			}

			if ( ( $best_seller_badge_settings['show_badge_if_position'] ) < $position ) {
				return false;
			}
			$display_cat_name = apply_filters( 'wcst_best_seller_badge_display_cat_name', 1 );

			$template_output = $best_seller_badge_settings['label'];
			$template_output = str_replace( '{{rank}}', $position, $template_output );

			$template_output = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $template_output, $this->slug ) );
			ob_start();
			?>
            <div class="<?php echo trim( $classes ); ?> wcst_best_sellers_badge wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> wcst_best_sellers_badge_<?php echo $best_seller_badge_settings['badge_style'] ?>" data-trigger-id="<?php echo $trigger_key ?>">
                <span class="wcst_best_sellers_badge_span_one"> <span><?php echo $template_output; ?></span></span>

				<?php
				if ( $display_cat_name ) {
					echo ' ' . $hard_text_array['in'];
					echo ' ' . $best_seller_cat_prefix . $wcst_pro_cats_raw[ $term_id ]->name . $best_seller_cat_suffix;
				}
				?>
            </div>
			<?php
			$wcst_best_sellers_badge = ob_get_clean();
			echo apply_filters( 'wcst_best_seller_badge_display_content', $wcst_best_sellers_badge, $wcst_pro_cats_raw[ $term_id ], $template_output, $productInfo, $trigger_key, $best_seller_badge_settings );
		}
	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_wp_head_css = "";
		if ( ! $productInfo->product ) {
			return "";
		}
		if ( $productInfo->product_type != 'external' ) {
			$wcst_best_seller_badge_arr = $data;

			foreach ( $wcst_best_seller_badge_arr as $trigger_key => $best_seller_badge_single ) {
				$badge_style      = $best_seller_badge_single['badge_style'];
				$badge_bg_color   = $best_seller_badge_single['badge_bg_color'];
				$badge_text_color = $best_seller_badge_single['badge_text_color'];
				$best_seller_css  = '';
				ob_start();
				if ( $badge_style == '1' ) {
					?>
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_1 span.wcst_best_sellers_badge_span_one { <?php
					echo $badge_bg_color ? ' background: ' . $badge_bg_color . ';' : '';
					echo $badge_text_color ? ' color: ' . $badge_text_color . ';' : '';
					?>}
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_1 span.wcst_best_sellers_badge_span_one:after { <?php echo $badge_bg_color ? ' border-color: ' . $badge_bg_color . ';' : ''; ?> border-right-color: transparent; }
					<?php
				} elseif ( $badge_style == '2' ) {
					?>
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_2 span.wcst_best_sellers_badge_span_one { <?php
					echo $badge_bg_color ? ' background: ' . $badge_bg_color . ';' : '';
					echo $badge_text_color ? ' color: ' . $badge_text_color . ';' : '';
					?>}
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_2 span.wcst_best_sellers_badge_span_one:before { <?php echo $badge_bg_color ? ' border-color: ' . $badge_bg_color . ';' : ''; ?> border-left-color: transparent; }
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_2 span.wcst_best_sellers_badge_span_one:after { <?php echo $badge_bg_color ? ' border-color: ' . $badge_bg_color . ';' : ''; ?> border-right-color: transparent; }
					<?php
				} elseif ( $badge_style == '3' ) {
					?>
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_3 span.wcst_best_sellers_badge_span_one { <?php
					echo $badge_bg_color ? ' background: ' . $badge_bg_color . ';' : '';
					echo $badge_text_color ? ' color: ' . $badge_text_color . ';' : '';
					?>}
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_3 span.wcst_best_sellers_badge_span_one:before { <?php echo $badge_bg_color ? ' border-color: ' . $badge_bg_color . ';' : ''; ?> border-left-color: transparent; }
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_3 span.wcst_best_sellers_badge_span_one:after { <?php echo $badge_bg_color ? ' border-color: ' . $badge_bg_color . ';' : ''; ?> border-right-color: transparent; }
					<?php
				} elseif ( $badge_style == '4' ) {
					?>
                    body .wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_best_sellers_badge_4 span.wcst_best_sellers_badge_span_one { <?php
					echo $badge_bg_color ? ' background: ' . $badge_bg_color . ';' : '';
					echo $badge_text_color ? ' color: ' . $badge_text_color . ';' : '';
					?>}
					<?php
				}
				$best_seller_css  = ob_get_clean();
				$wcst_wp_head_css .= $best_seller_css;
			}
		}

		return $wcst_wp_head_css;
	}

}

WCST_Triggers::register( new WCST_Trigger_Best_Seller_Badge() );
