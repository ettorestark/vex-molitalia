<?php

class WCST_Trigger_Low_Stock extends WCST_Base_Trigger {
	public $slug = 'low_stock';
	public $parent_slug = 'wcst_low_stock_settings';
	public $title = '';
	public $default_priority = 5;

	public function get_defaults() {
		return array(
			'assurance_label'           => __( 'In Stock', WCST_SLUG ),
			'scarcity_label'            => __( 'Only {{stock_quantity_left}} left in stock. Almost Gone!', WCST_SLUG ),
			'default_mode'              => 'assurance',
			'out_of_stock_label'        => __( 'Just Sold Out. Expect to come in 4-6 days.', WCST_SLUG ),
			'switch_scarcity_min_stock' => 5,
			'out_of_stock_text_color'   => '#dd3333',
			'assurance_text_color'      => '#77a464',
			'scarcity_text_color'       => '#dd3333',
			'font_size'                 => 16,
			'position'                  => '5',
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_low_stock_head',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can set it up in one of the two modes: <i>Commitment</i> or <i>Scarcity</i>. This trigger is applicable for the products on sale.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'       => __( 'Commitment Text', WCST_SLUG ),
				'desc'       => 'Show above message when you have excessive items in stock.<br><i>{{stock_quantity_left}}</i> outputs the number of items left in stock.',
				'id'         => '_wcst_data_wcst_low_stock_assurance_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'       => __( 'Scarcity Text', WCST_SLUG ),
				'desc'       => 'Show above message when you have few items in stock.<br><i>{{stock_quantity_left}}</i> outputs the number of items left in stock.',
				'id'         => '_wcst_data_wcst_low_stock_scarcity_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'       => __( 'Out of Stock Text', WCST_SLUG ),
				'desc'       => 'Message when item Out of Stock.', // <br><i>{{out_of_stock_date inc="2"}}</i> is today is 16 Feb, then this will display 18 Feb.
				'id'         => '_wcst_data_wcst_low_stock_out_of_stock_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '2',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'       => __( 'Choose Mode', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_default_mode',
				'type'       => 'radio_inline',
				'options'    => array(
					'assurance' => __( 'Commitment', WCST_SLUG ),
					'scarcity'  => __( 'Scarcity', WCST_SLUG ),
				),
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'        => __( 'Auto Switch to Scarcity Mode', WCST_SLUG ),
				'desc'        => __( 'items left in stock', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_low_stock_switch_scarcity_min_stock',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                        => 'number',
					'min'                         => '0',
					'pattern'                     => '\d*',
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'low_stock',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_low_stock_default_mode',
					'data-wcst-conditional-value' => 'assurance',
				),
			),
			array(
				'name'       => __( 'Commitment Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_assurance_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'       => __( 'Scarcity Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_scarcity_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'       => __( 'Out of Stock Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_out_of_stock_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_low_stock_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'low_stock',
				),
			),
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can set it up in one of the two modes: <i>Commitment</i> or <i>Scarcity</i>. This trigger is applicable for the products on sale.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_low_stock_mode',
				'type'                     => 'wcst_switch',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Stock Status', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			array(
				'name'       => __( 'Commitment Text', WCST_SLUG ),
				'desc'       => 'Show above message when you have excessive items in stock.<br><i>{{stock_quantity_left}}</i> outputs the number of items left in stock.',
				'id'         => '_wcst_data_wcst_low_stock_assurance_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Scarcity Text', WCST_SLUG ),
				'desc'       => 'Show above message when you have few items in stock.<br><i>{{stock_quantity_left}}</i> outputs the number of items left in stock.',
				'id'         => '_wcst_data_wcst_low_stock_scarcity_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Out of Stock Text', WCST_SLUG ),
				'desc'       => 'Message when item Out of Stock.', //<br><i>{{out_of_stock_date inc="2"}}</i> is today is 16 Feb, then this will display 18 Feb.
				'id'         => '_wcst_data_wcst_low_stock_out_of_stock_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '2',
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Choose Mode', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_default_mode',
				'type'       => 'radio_inline',
				'options'    => array(
					'assurance' => __( 'Commitment', WCST_SLUG ),
					'scarcity'  => __( 'Scarcity', WCST_SLUG ),
				),
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Auto Switch to Scarcity Mode', WCST_SLUG ),
				'desc'        => __( 'items left in stock', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_low_stock_switch_scarcity_min_stock',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                        => 'number',
					'min'                         => '0',
					'pattern'                     => '\d*',
					'data-conditional-id'         => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_low_stock_default_mode',
					'data-wcst-conditional-value' => 'assurance',
				),
			),
			array(
				'name'       => __( 'Commitment Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_assurance_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Scarcity Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_scarcity_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Out of Stock Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_low_stock_out_of_stock_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_low_stock_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_low_stock_position',
				'show_option_none' => false,
				'type'             => 'select',
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_low_stock_mode',
					'data-conditional-value' => '1',
				),
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
			),
		);
	}

	public function handle_grid_request( $data, $productInfo ) {

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$low_stock_data = $data;
		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) ) {
			$variation_ID = $productInfo->get_variation_for_grid();
			$this->output_for_variation( $data, $productInfo, $variation_ID, 'grid' );
		} else {
			foreach ( $low_stock_data as $trigger_key => $low_stock_data ) {
				$this->output_html( $trigger_key, $low_stock_data, $productInfo, 'grid' );
			}
		}
	}

	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();
		array_push( $parent, 'grouped' );
		array_push( $parent, 'booking' );

		return $parent;
	}

	public function output_for_variation( $data, $productInfo, $variationID = 0, $page = '' ) {

		global $woocommerce;
		$wcst_low_stock_arr = $data;

		$classes = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes .= " wcst_on_" . $page . " ";

		if ( ! $variationID ) {
			return;
		}
		if ( ! is_object( $productInfo->product ) ) {
			return;
		}
		foreach ( $wcst_low_stock_arr as $trigger_key => $low_stock_single ) {
			if ( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
				$variation = XL_WCST_Product::get_instance( $variationID );
				if ( ! $variation->product ) {
					return;
				}
				$scarcity_text  = $low_stock_single['scarcity_label'];
				$assurance_text = $low_stock_single['assurance_label'];
				$stock_qty      = $variation->product->get_stock_quantity();

//        if ($stock_qty >= 0) {
				$scarcity_text  = str_replace( "{{stock_quantity_left}}", $stock_qty, $scarcity_text );
				$assurance_text = str_replace( "{{stock_quantity_left}}", $stock_qty, $assurance_text );

				if ( $variation->product->is_in_stock() ) {
					if ( $variation->product->get_stock_quantity() === null ) {
						$wcst_low_stock_html = '<div class="wcst_low_stock_assurance"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $assurance_text, $this->slug ) ) . '</span></div>';
					} else {
						if ( $low_stock_single['default_mode'] == "scarcity" ) {
							$wcst_low_stock_html = '<div class="wcst_low_stock_scarcity"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $scarcity_text, $this->slug ) ) . '</span></div>';
						} else {
							if ( $low_stock_single['switch_scarcity_min_stock'] > 0 && $low_stock_single['switch_scarcity_min_stock'] >= $stock_qty && $stock_qty != '' ) {
								$wcst_low_stock_html = '<div class="wcst_low_stock_scarcity"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $scarcity_text, $this->slug ) ) . '</span></div>';
							} else {
								$wcst_low_stock_html = '<div class="wcst_low_stock_assurance"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $assurance_text, $this->slug ) ) . '</span></div>';
							}
						}
					}
					echo '<div class="' . trim( $classes ) . ' wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '">' . $wcst_low_stock_html . '</div>';
				} else {
					echo '<div class="' . trim( $classes ) . ' wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . ' wcst_out_of_stock">' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $low_stock_single['out_of_stock_label'], $this->slug ) ) . '</div>';
				}
			} else {
				$variation = $productInfo->product->get_available_variation( $productInfo->product->get_child( $variationID ) );
				if ( $variation['is_in_stock'] ) {
					$scarcity_text  = $low_stock_single['scarcity_label'];
					$assurance_text = $low_stock_single['assurance_label'];
					$stock_qty      = $variation['max_qty'];

					$scarcity_text  = str_replace( "{{stock_quantity_left}}", $stock_qty, $scarcity_text );
					$assurance_text = str_replace( "{{stock_quantity_left}}", $stock_qty, $assurance_text );

					if ( $low_stock_single['default_mode'] == "scarcity" ) {
						$wcst_low_stock_html = '<div class="wcst_low_stock_scarcity"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $scarcity_text, $this->slug ) ) . '</span></div>';
					} else {
						if ( $low_stock_single['switch_scarcity_min_stock'] > 0 && $low_stock_single['switch_scarcity_min_stock'] >= $stock_qty && $stock_qty != '' ) {
							$wcst_low_stock_html = '<div class="wcst_low_stock_scarcity"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $scarcity_text, $this->slug ) ) . '</span></div>';
						} else {
							$wcst_low_stock_html = '<div class="wcst_low_stock_assurance"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $assurance_text, $this->slug ) ) . '</span></div>';
						}
					}
					echo '<div class="wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '">' . $wcst_low_stock_html . '</div>';
				} else {
					echo '<div class="wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . ' wcst_out_of_stock">' . $low_stock_single['out_of_stock_label'] . '</div>';
				}
			}
		}
	}

	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {
		$classes = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes .= " wcst_on_" . $page . " ";
		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) ) {
			echo '<div class=" ' . trim( $classes ) . ' wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="<?php echo $trigger_key ?>"></div>';
		} else {
			if ( $productInfo->get_in_stock() ) {
				echo '<div class="' . trim( $classes ) . ' wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="<?php echo $trigger_key ?>">' . $this->wcst_low_stock_display( $single, $productInfo, $trigger_key, $page ) . '</div>';
			} else {
				if ( $productInfo->product->is_in_stock() && $productInfo->product->backorders_allowed() && $productInfo->product->get_stock_quantity() < 1 ) {
					echo '<div class="' . trim( $classes ) . ' wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="<?php echo $trigger_key ?>"><div class="wcst_low_stock_native"><span>' . __( 'In stock', 'woocommerce' ) . '</span></div></div>';
				} else {
					echo '<div class="' . trim( $classes ) . ' wcst_low_stock wcst_low_stock_key_' . $productInfo->product->get_id() . '_' . $trigger_key . ' wcst_out_of_stock" data-trigger-id="<?php echo $trigger_key ?>">' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $single['out_of_stock_label'], $this->slug ) ) . '</div>';
				}
			}
		}
	}

	/**
	 * @param $single single data array
	 * @param WCST_Product $productInfo
	 * @param integer $trigger_key
	 * @param string $page
	 *
	 * @return string
	 */
	public function wcst_low_stock_display( $single, $productInfo, $trigger_key, $page = '' ) {

		$scarcity_text  = $single['scarcity_label'];
		$assurance_text = $single['assurance_label'];
		$stock_qty      = (int) get_post_meta( $productInfo->product->get_id(), '_stock', true );
		$stock_qty      = $productInfo->product->get_stock_quantity();

		$stock_qty = $stock_qty ? $stock_qty : '';

		$scarcity_text  = str_replace( "{{stock_quantity_left}}", $stock_qty, $scarcity_text );
		$assurance_text = str_replace( "{{stock_quantity_left}}", $stock_qty, $assurance_text );

		if ( ! $productInfo->is_manage_stock() ) {
			$wcst_low_stock_html = '<div class="wcst_low_stock_assurance"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $assurance_text, $this->slug ) ) . '</span></div>';
		} else {
			if ( $single['default_mode'] == "scarcity" ) {
				$wcst_low_stock_html = '<div class="wcst_low_stock_scarcity"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $scarcity_text, $this->slug ) ) . '</span></div>';
			} else {
				if ( $single['switch_scarcity_min_stock'] > 0 && $single['switch_scarcity_min_stock'] >= $stock_qty && $stock_qty != '' ) {
					$wcst_low_stock_html = '<div class="wcst_low_stock_scarcity"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $scarcity_text, $this->slug ) ) . '</span></div>';
				} else {
					$wcst_low_stock_html = '<div class="wcst_low_stock_assurance"><span>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $assurance_text, $this->slug ) ) . '</span></div>';
				}
			}
		}

		return $wcst_low_stock_html;

	}

	public function handle_single_product_request( $data, $productInfo, $position ) {
		global $woocommerce;
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_low_stock_arr = $data;
		foreach ( $wcst_low_stock_arr as $trigger_key => $low_stock_single ) {
			$badge_position = $low_stock_single['position'];
			if ( $badge_position == $position ) {
				WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );

				$this->output_html( $trigger_key, $low_stock_single, $productInfo, 'product' );
				if ( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
					add_filter( 'woocommerce_get_stock_html', array( $this, 'wcst_woocommerce_get_stock_html' ), 10, 2 );

				} else {
					add_filter( 'woocommerce_stock_html', array( $this, 'wcst_woocommerce_stock_html' ), 10, 3 );
				}
			}
		}
	}

	public function get_title() {
		return __( 'Low Stock', WCST_SLUG );
	}

	public function handle_cart_request( $data, $productInfo, $variationID = 0, $cart_item = array() ) {

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_low_stock_arr = $data;

		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) && $variationID !== 0 ) {
			$this->output_for_variation( $data, $productInfo, $variationID, 'cart' );
		} else {
			foreach ( $wcst_low_stock_arr as $trigger_key => $single ) {
				$this->output_html( $trigger_key, $single, $productInfo, 'cart' );
			}
		}
	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_wp_head_css = "";
		if ( ! $productInfo->product ) {
			return "";
		}
		$wcst_low_stock_arr = $data;
		foreach ( $wcst_low_stock_arr as $trigger_key => $low_stock_single ) {
			$low_stock_css = '';
			ob_start();
			?>
            body .wcst_low_stock_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_low_stock .wcst_low_stock_scarcity span { <?php
			echo isset( $low_stock_single['scarcity_text_color'] ) ? 'color:' . $low_stock_single['scarcity_text_color'] . ';' : '';
			echo isset( $low_stock_single['font_size'] ) ? 'font-size:' . $low_stock_single['font_size'] . '; line-height: 1.4;' : '';
			?>}
            body .wcst_low_stock_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_low_stock .wcst_low_stock_assurance span { <?php
			echo isset( $low_stock_single['assurance_text_color'] ) ? 'color:' . $low_stock_single['assurance_text_color'] . ';' : '';
			echo isset( $low_stock_single['font_size'] ) ? 'font-size:' . $low_stock_single['font_size'] . '; line-height: 1.4;' : '';
			?>}
            body .wcst_low_stock_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_low_stock .wcst_low_stock_native span { <?php
			echo isset( $low_stock_single['assurance_text_color'] ) ? 'color:' . $low_stock_single['assurance_text_color'] . ';' : '';
			echo isset( $low_stock_single['font_size'] ) ? 'font-size:' . $low_stock_single['font_size'] . '; line-height: 1.4;' : '';
			?>}
            body .wcst_low_stock_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>.wcst_low_stock.wcst_out_of_stock { <?php
			echo isset( $low_stock_single['out_of_stock_text_color'] ) ? 'color:' . $low_stock_single['out_of_stock_text_color'] . ';' : '';
			echo isset( $low_stock_single['font_size'] ) ? 'font-size:' . $low_stock_single['font_size'] . '; line-height: 1.4;' : '';
			?>}
			<?php
			$low_stock_css    = ob_get_clean();
			$wcst_wp_head_css .= $low_stock_css;
		}

		return $wcst_wp_head_css;
	}

	/**
	 * return blank html on 'woocommerce_stock_html' filter
	 *
	 * @param type $html * @param type $availability
	 * @param type $product
	 *
	 * @return type
	 */
	public function wcst_woocommerce_stock_html( $html, $availability, $product ) {
		return;
	}

	/**
	 * return blank html on 'woocommerce_get_stock_html' filter
	 *
	 * @param type $html
	 * @param type $product
	 *
	 * @return type
	 * @since 1.3
	 */
	public function wcst_woocommerce_get_stock_html( $html, $product ) {
		return;
	}
}

WCST_Triggers::register( new WCST_Trigger_Low_Stock() );
