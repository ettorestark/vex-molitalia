<?php

class WCST_Trigger_Savings extends WCST_Base_Trigger {

	public $slug = 'savings';
	public $parent_slug = 'wcst_savings_settings';
	public $title = '';
	public $is_override_meta = true;
	public $default_priority = 3;

	public function get_defaults() {
		return array(
			'label'                             => __( 'You Save: {{savings_value_percentage}}', WCST_SLUG ),
			'text_color'                        => '#dd3333',
			'hide_decimal_in_saving_percentage' => 'no',
			'show_below_variation_price'        => 'yes',
			'hide_top_variation_price'          => 'yes',
			'font_size'                         => 16,
			'position'                          => '4',
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_savings_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can display savings in three different formats using these merge tags <i>{{savings_value_percentage}}</i>, <i>{{savings_value}}</i> or <i>{{savings_percentage}}</i> for the products on sale.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'savings',
				),
			),
			array(
				'name'       => __( 'Text', WCST_SLUG ),
				'desc'       => __( '<i>{{savings_value_percentage}}</i> display savings in both price and percentage. Example: $10 (33%).
     <br/><i>{{savings_value}}</i> display savings in price. Example: $10.
     <br/><i>{{savings_percentage}}</i> display savings in percentage. Example: 33%.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'savings',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'savings',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_savings_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'savings',
				),
			),
			array(
				'name'       => __( 'Hide Decimals in Saving Percentage', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_hide_decimal_in_saving_percentage',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'savings',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Show below Variation\'s Price', WCST_SLUG ),
				'desc'       => __( 'Applicable only for variable products', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_show_below_variation_price',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'savings',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Hide Top Savings (range) Price', WCST_SLUG ),
				'desc'       => __( 'Applicable only for variable products', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_hide_top_variation_price',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'savings',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_savings_show_below_variation_price',
					'data-wcst-conditional-value' => "yes",
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can display savings in three different formats using these merge tags <i>{{savings_value_percentage}}</i>, <i>{{savings_value}}</i> or <i>{{savings_percentage}}</i> for the products on sale.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_savings_mode',
				'type'                     => 'wcst_switch',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Savings', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			array(
				'name'       => __( 'Text', WCST_SLUG ),
				'desc'       => __( '<i>{{savings_value_percentage}}</i> display savings in both price and percentage. Example: $10 (33%).
     <br/><i>{{savings_value}}</i> display savings in price. Example: $10.
     <br/><i>{{savings_percentage}}</i> display savings in percentage. Example: 33%.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_savings_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_savings_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_savings_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_savings_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_savings_position',
				'type'             => 'select',
				'show_option_none' => false,
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_savings_mode',
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
			array(
				'name'       => __( 'Show below Variation\'s Price', WCST_SLUG ),
				'desc'       => __( 'Applicable only for variable products', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_show_below_variation_price',
				'type'       => 'radio_inline',
				'after_row'  => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_savings_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Hide Top Savings (range) Price', WCST_SLUG ),
				'desc'       => __( 'Applicable only for variable products', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_savings_hide_top_variation_price',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'         => '_wcst_data_wcst_savings_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_savings_show_below_variation_price',
					'data-wcst-conditional-value' => "yes",
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
		);
	}

	public function handle_single_product_request( $data, $productInfo, $position ) {
		$wcst_savings_arr = $data;
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		foreach ( $wcst_savings_arr as $trigger_key => $savings_single ) {
			$badge_position = $savings_single['position'];
			if ( $badge_position == $position ) {
				WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );
				$this->output_html( $trigger_key, $savings_single, $productInfo, 'product' );
			}
		}
	}

	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();
		array_push( $parent, 'external' );

		return $parent;
	}

	public function get_title() {
		return __( 'Savings', WCST_SLUG );
	}

	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {
		$classes            = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes            .= " wcst_on_" . $page . " ";
		$decimal_points     = apply_filters( 'wcst_trigger_savings_decimal_point', 1 );
		$decimal_separator  = wc_get_price_decimal_separator();
		$thousand_separator = wc_get_price_thousand_separator();

		$product = $productInfo->product;
		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) ) {
			if ( ! $product->is_on_sale() ) {
				return false;
			}
			if ( isset( $single['hide_top_variation_price'] ) && $single['hide_top_variation_price'] == "yes" ) {
				return false;
			}
			$min_regular_price = $product->get_variation_regular_price( 'min', true );
			$min_sale_price    = $product->get_variation_sale_price( 'min', true );
			$max_regular_price = $product->get_variation_regular_price( 'max', true );
			$max_sale_price    = $product->get_variation_sale_price( 'max', true );

			if ( empty( $min_regular_price ) || empty( $min_sale_price ) ) {
				return false;
			}

			$max_diff = ( ( $max_regular_price - $max_sale_price ) / $max_regular_price ) * 100;
			$max_diff = number_format( $max_diff, $decimal_points, $decimal_separator, $thousand_separator );
			$min_diff = ( ( $min_regular_price - $min_sale_price ) / $min_regular_price ) * 100;
			$min_diff = number_format( $min_diff, $decimal_points, $decimal_separator, $thousand_separator );

			$final_val     = array( ( $min_regular_price - $min_sale_price ), ( $max_regular_price - $max_sale_price ) );
			$final_percent = array( $min_diff, $max_diff );

			$you_save_html = $single['label'];
			if ( min( $final_val ) == max( $final_val ) ) {
				$final_percent = min( $final_percent );
				$final_percent = ( ( (int) $final_percent == $final_percent ) || $single['hide_decimal_in_saving_percentage'] == 'yes' ) ? (int) $final_percent : $final_percent;

				$you_save_html = str_replace( '{{savings_value}}', '<span class="you_save_value">' . wc_price( min( $final_val ) ) . '</span>', $you_save_html );
				$you_save_html = str_replace( '{{savings_percentage}}', '<span class="you_save_percentage">' . $final_percent . '%</span>', $you_save_html );
				$you_save_html = str_replace( '{{savings_value_percentage}}', '<span class="you_save_value_percentage">' . wc_price( min( $final_val ) ) . ' (' . $final_percent . '%)</span>', $you_save_html );
			} else {
				$final_percent = ( min( $final_percent ) . '%-' . max( $final_percent ) );
				$final_percent = ( ( (int) $final_percent == $final_percent ) || $single['hide_decimal_in_saving_percentage'] == 'yes' ) ? (int) $final_percent : $final_percent;

				$you_save_html = str_replace( '{{savings_value}}', '<span class="you_save_value">' . ( wc_price( min( $final_val ) ) . '–' . wc_price( max( $final_val ) ) ) . '</span>', $you_save_html );
				$you_save_html = str_replace( '{{savings_percentage}}', '<span class="you_save_percentage">' . $final_percent . '%</span>', $you_save_html );
				$you_save_html = str_replace( '{{savings_value_percentage}}', '<span class="you_save_value_percentage">' . ( wc_price( min( $final_val ) ) . '–' . wc_price( max( $final_val ) ) ) . ' (' . $final_percent . '%)</span>', $you_save_html );
			}
			$you_save_html = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $you_save_html, $this->slug ) );

			$get_min_regular = $product->get_variation_regular_price( 'min', true );
			$get_max_regular = $product->get_variation_regular_price( 'max', true );
			$get_min_sale    = $product->get_variation_sale_price( 'min', true );
			$get_max_sale    = $product->get_variation_sale_price( 'max', true );

			$you_save_html = str_replace( "{{regular_price}}", wc_price( $get_min_regular ) . " - " . wc_price( $get_max_regular ), $you_save_html );
			$you_save_html = str_replace( "{{sale_price}}", wc_price( $get_min_sale ) . " - " . wc_price( $get_max_sale ), $you_save_html );

			if ( min( $final_val ) > 0 ) {
				$wcst_you_save_html = '<div class="' . trim( $classes ) . ' wcst_savings_top wcst_savings_top_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="' . $trigger_key . '">' . $you_save_html . '</div>';
				echo $wcst_you_save_html;
			}
		} else {
			if ( ! $product->is_on_sale() || ! $product->is_in_stock() ) {
				return false;
			}
			$regular_price = $product->get_regular_price();
			$sale_price    = $product->get_sale_price();
			if ( $sale_price !== $regular_price && $sale_price >= 0 ) {
				// sale price must have a value for price difference
				$diff = ( ( $regular_price - $sale_price ) / $regular_price ) * 100;
				$diff = number_format( $diff, $decimal_points, $decimal_separator, $thousand_separator );

				$diff = ( ( (int) $diff == $diff ) || $single['hide_decimal_in_saving_percentage'] == 'yes' ) ? (int) $diff : $diff;

				$you_save_html = $single['label'];

				$price_difference = ( $regular_price - $sale_price );
				$price_difference = apply_filters( 'wcst_modify_savings_price', $price_difference, $single, $productInfo, $page, $helper_args );

				$you_save_html      = str_replace( '{{savings_value}}', '<span class="you_save_value">' . ( wc_price( $price_difference ) ) . '</span>', $you_save_html );
				$you_save_html      = str_replace( '{{savings_percentage}}', '<span class="you_save_percentage">' . $diff . '%</span>', $you_save_html );
				$you_save_html      = str_replace( '{{savings_value_percentage}}', '<span class="you_save_value_percentage">' . ( wc_price( $price_difference ) ) . ' (' . $diff . '%)</span>', $you_save_html );
				$you_save_html      = str_replace( "{{regular_price}}", wc_price( $regular_price ), $you_save_html );
				$you_save_html      = str_replace( "{{sale_price}}", wc_price( $sale_price ), $you_save_html );
				$you_save_html      = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $you_save_html, $this->slug ) );
				$wcst_you_save_html = '<div class=" ' . trim( $classes ) . ' wcst_savings_top wcst_savings_top_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="' . $trigger_key . '">' . $you_save_html . '</div>';
				echo $wcst_you_save_html;
			}
		}
	}

	public function handle_grid_request( $data, $productInfo ) {
		$wcst_deal_expiry_arr = $data;

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}

		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) ) {
			$variation_ID = $productInfo->get_variation_for_grid();
			$this->output_for_variation( $data, $productInfo, $variation_ID, "grid", array() );
		} else {
			foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
				$this->output_html( $trigger_key, $deal_expiry_single, $productInfo, "grid", array() );
			}
		}
	}

	public function output_for_variation( $data, $productInfo, $variationID = 0, $showon = "cart", $helper_args = array() ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
			$variation_info = XL_WCST_Product::get_instance( $variationID );
			if ( ! $variation_info->product ) {
				return;
			}
			if ( $variation_info->product->is_in_stock() === false ) {
				return;
			}
		} else {
			$variation_info = $productInfo->product->get_available_variation( $productInfo->product->get_child( $variationID ) );
			if ( $variation_info['is_in_stock'] === false ) {
				return;
			}
		}

		$all_variation_prices = $productInfo->product->get_variation_prices( false );
		$classes              = apply_filters( 'wcst_html_classes', '', $this->slug );

		$wcst_deal_expiry_arr        = $data;
		$get_variation_sale_price    = $all_variation_prices['sale_price'][ $variationID ];
		$get_variation_regular_price = $all_variation_prices['regular_price'][ $variationID ];

		$decimal_points     = apply_filters( 'wcst_trigger_savings_decimal_point', 1 );
		$decimal_separator  = wc_get_price_decimal_separator();
		$thousand_separator = wc_get_price_thousand_separator();

		foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
			if ( $get_variation_sale_price !== $get_variation_regular_price && $get_variation_sale_price >= 0 ) {
				// sale price must have a value for price difference
				$diff = ( ( $get_variation_regular_price - $get_variation_sale_price ) / $get_variation_regular_price ) * 100;
				$diff = number_format( $diff, $decimal_points, $decimal_separator, $thousand_separator );

				$you_save_html    = $deal_expiry_single['label'];
				$price_difference = ( $get_variation_regular_price - $get_variation_sale_price );
				$price_difference = apply_filters( 'wcst_modify_savings_price', $price_difference, $deal_expiry_single, $productInfo, $showon, $helper_args );

				$you_save_html = str_replace( '{{savings_value}}', '<span class="you_save_value">' . ( wc_price( $price_difference ) ) . '</span>', $you_save_html );
				$you_save_html = str_replace( '{{savings_percentage}}', '<span class="you_save_percentage">' . $diff . '%</span>', $you_save_html );
				$you_save_html = str_replace( '{{savings_value_percentage}}', '<span class="you_save_value_percentage">' . ( wc_price( $price_difference ) ) . ' (' . $diff . '%)</span>', $you_save_html );
				$you_save_html = str_replace( "{{regular_price}}", wc_price( $get_variation_regular_price ), $you_save_html );
				$you_save_html = str_replace( "{{sale_price}}", wc_price( $get_variation_sale_price ), $you_save_html );

				$wcst_you_save_html = '<div class="' . trim( $classes ) . ' wcst_savings_top wcst_savings_top_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '">' . $you_save_html . '</div>';
				echo $wcst_you_save_html;
			}
		}
	}

	public function handle_cart_request( $data, $productInfo, $variationID = 0, $cart_item = array() ) {

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_deal_expiry_arr = $data;
		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) && $variationID !== 0 ) {
			$this->output_for_variation( $data, $productInfo, $variationID, "cart", array( 'cart_item' => $cart_item ) );
		} else {
			foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
				$this->output_html( $trigger_key, $deal_expiry_single, $productInfo, "cart", array( 'cart_item' => $cart_item ) );
			}
		}
	}

	public function output_dynamic_css( $data, $productInfo ) {
		if ( ! $productInfo->product ) {
			return "";
		}
		$wcst_wp_head_css = "";
		$wcst_savings_arr = $data;
		foreach ( $wcst_savings_arr as $trigger_key => $savings_single ) {
			$savings_css = '';
			ob_start();
			?>
            body .wcst_savings_top.wcst_savings_top_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> span, body .wcst_savings_variation.wcst_savings_variation_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> span { <?php
			echo isset( $savings_single['text_color'] ) ? 'color:' . $savings_single['text_color'] . ';' : '';
			echo isset( $savings_single['font_size'] ) ? 'font-size:' . $savings_single['font_size'] . '; line-height: 1.4;' : '';
			?>}
			<?php
			$savings_css      = ob_get_clean();
			$wcst_wp_head_css .= $savings_css;
		}

		return $wcst_wp_head_css;
	}

}

WCST_Triggers::register( new WCST_Trigger_Savings() );

