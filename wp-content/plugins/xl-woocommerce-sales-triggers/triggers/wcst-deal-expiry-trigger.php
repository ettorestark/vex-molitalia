<?php

class WCST_Trigger_Deal_Expiry extends WCST_Base_Trigger {
	public $slug = 'deal_expiry';
	public $parent_slug = 'wcst_deal_expiry_settings';
	public $title = '';
	public $default_priority = 4;

	public function get_defaults() {
		return array(
			'reverse_date_label'  => __( 'Sale ends in {{time_left}}', WCST_SLUG ),
			'reverse_timer_label' => __( 'Hurry up! Sale ends in {{countdown_timer}}', WCST_SLUG ),
			'expiry_date_label'   => __( 'Prices go up after {{end_date}}', WCST_SLUG ),
			'display_mode'        => 'reverse_date',
			'switch_period'       => 24,
			'text_color'          => '#ec1f1f',
			'font_size'           => 16,
			'position'            => '4',
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_deal_expiry_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can set it up in one of the three modes: <i>Time Left</i>, <i>End Date</i> or <i>Countdown Timer</i>. This trigger is applicable for the products on scheduled sale.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'deal_expiry',
				),
			),
			array(
				'name'       => __( 'Time Left Text', WCST_SLUG ),
				'desc'       => __( 'Left over time for sale to end.<br><i>{{time_left}}</i> outputs as 1 week | 5 days | 21 hours.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_reverse_date_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'deal_expiry',
				),
			),
			array(
				'name'       => __( 'End Date Text', WCST_SLUG ),
				'desc'       => __( 'Date when the sale ends.<br><i>{{end_date}}</i> outputs as ' . date_i18n( WCST_Common::wcst_get_date_format() ), WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_expiry_date_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'deal_expiry',
				),
			),
			array(
				'name'       => __( 'Countdown Timer Text', WCST_SLUG ),
				'desc'       => __( 'Left over time for sale to end in reverse countdown mode.<br><i>{{countdown_timer}}</i> outputs as 1 day 16h 25m 03s with reverse countdown', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_reverse_timer_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'deal_expiry',
				),
			),
			array(
				'name'       => __( 'Choose Mode', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_display_mode',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'deal_expiry',
				),
				'options'    => array(
					'reverse_date'  => __( 'Time Left', WCST_SLUG ),
					'expiry_date'   => __( 'End Date', WCST_SLUG ),
					'reverse_timer' => __( 'Countdown Timer', WCST_SLUG ),
				),
			),
			array(
				'name'        => __( 'Auto Switch to Countdown Timer Mode', WCST_SLUG ),
				'desc'        => __( 'hrs left before sale ends', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_deal_expiry_switch_period',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                        => 'number',
					'min'                         => '1',
					'pattern'                     => '\d*',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_deal_expiry_display_mode',
					'data-wcst-conditional-value' => json_encode( array( 'reverse_date', 'expiry_date' ) ),
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'deal_expiry',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'deal_expiry',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_deal_expiry_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'deal_expiry',
				),
			),
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can set it up in one of the three modes: <i>Time Left</i>, <i>End Date</i> or <i>Countdown Timer</i>. This trigger is applicable for the products on scheduled sale.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_deal_expiry_mode',
				'type'                     => 'wcst_switch',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Deal Expiry', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			array(
				'name'       => __( 'Time Left Text', WCST_SLUG ),
				'desc'       => __( 'Left over time for sale to end.<br><i>{{time_left}}</i> outputs as 1 week | 5 days | 21 hours.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_reverse_date_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_deal_expiry_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'End Date Text', WCST_SLUG ),
				'desc'       => __( 'Date when the sale ends.<br><i>{{end_date}}</i> outputs as ' . date_i18n( WCST_Common::wcst_get_date_format() ), WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_expiry_date_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_deal_expiry_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Countdown Timer Text', WCST_SLUG ),
				'desc'       => __( 'Left over time for sale to end in reverse countdown mode.<br><i>{{countdown_timer}}</i> outputs as 1 day 16h 25m 03s with reverse countdown', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_reverse_timer_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_deal_expiry_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Choose Mode', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_display_mode',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_deal_expiry_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'reverse_date'  => __( 'Time Left', WCST_SLUG ),
					'expiry_date'   => __( 'End Date', WCST_SLUG ),
					'reverse_timer' => __( 'Countdown Timer', WCST_SLUG ),
				),
			),
			array(
				'name'        => __( 'Auto Switch to Countdown Timer Mode', WCST_SLUG ),
				'desc'        => __( 'hrs left before sale ends', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_deal_expiry_switch_period',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                        => 'number',
					'min'                         => '1',
					'pattern'                     => '\d*',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_deal_expiry_display_mode',
					'data-wcst-conditional-value' => json_encode( array( 'reverse_date', 'expiry_date' ) ),
					'data-conditional-id'         => '_wcst_data_wcst_deal_expiry_mode',
					'data-conditional-value'      => '1',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_deal_expiry_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_deal_expiry_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_deal_expiry_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_deal_expiry_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_deal_expiry_position',
				'show_option_none' => false,
				'after_row'        => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
				'type'             => 'select',
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_deal_expiry_mode',
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
		$wcst_deal_expiry_arr = $data;
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
			$badge_position = $deal_expiry_single['position'];
			if ( $badge_position == $position ) {
				WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );
				$this->output_html( $trigger_key, $deal_expiry_single, $productInfo, "product" );
			}
		}
	}

	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();
		array_push( $parent, 'external' );

		return $parent;
	}

	public function get_title() {
		return __( 'Deal Expiry', WCST_SLUG );
	}

	public function output_html( $trigger_key, $deal_expiry_single, $productInfo, $page = '', $helper_args = array() ) {
		global $woocommerce;

		$sale_end_diff   = 0;
		$classes         = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes         .= " wcst_on_" . $page . " ";
		$hard_text_array = WCST_Common::get_hard_text();
		$wc_product_type = $productInfo->product_type;

		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) ) {
			?>
            <div class="<?php echo trim( $classes ) ?>
                 wcst_deal_expiry wcst_deal_expiry_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?>
                 wcst_deal_expiry_<?php echo $productInfo->product_type; ?>" data-trigger-id="<?php echo $trigger_key ?>">
            </div>
			<?php
		} else {
			$sale_price_from = 0;
			$sale_price_to   = 0;
			if ( ! $productInfo->product->is_in_stock() || ! $productInfo->product->is_on_sale() ) {
				WCST_Common::insert_log( "*******Terminating******* || Reason: Not in stock or no sale set ||  product: " . $productInfo->product->get_title() . ":" . $productInfo->product->get_id(), $this->slug );

				return false;
			}
			if ( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
				// if version greater or equal to 3.0.0
				$sale_price_from_obj = $productInfo->product->get_date_on_sale_from();
				if ( $sale_price_from_obj != null ) {
					$sale_price_from = $sale_price_from_obj->getTimestamp();
				}
				$sale_price_to_obj = $productInfo->product->get_date_on_sale_to();
				if ( $sale_price_to_obj != null ) {
					$sale_price_to = $sale_price_to_obj->getTimestamp();
				}
			} else {
				// for older version
				$sale_price_from = get_post_meta( $productInfo->product->get_id(), '_sale_price_dates_from', true );
				$sale_price_to   = get_post_meta( $productInfo->product->get_id(), '_sale_price_dates_to', true );
				$sale_price_from = $sale_price_from - ( WCST_Common::get_timezone_difference() );
				$sale_price_to   = $sale_price_to - ( WCST_Common::get_timezone_difference() );
			}

			$wc_10_sale      = 0;
			$sale_price_from = apply_filters( 'wcst_deal_expiry_product_sale_date_from', $sale_price_from, $productInfo, $deal_expiry_single );
			$sale_price_to   = apply_filters( 'wcst_deal_expiry_product_sale_date_to', $sale_price_to, $productInfo, $deal_expiry_single );

			if ( ! empty( $sale_price_from ) && ! empty( $sale_price_to ) ) {
				if ( WCST_Common::get_site_time() > $sale_price_from && WCST_Common::get_site_time() < $sale_price_to ) {
					$wc_10_sale    = 1;
					$sale_end_diff = $sale_price_to - WCST_Common::get_site_time();
				}
			} else {
				if ( ! empty( $sale_price_from ) && WCST_Common::get_site_time() > $sale_price_from ) {
					$wc_10_sale = 1;
				}
				if ( ! empty( $sale_price_to ) && WCST_Common::get_site_time() < $sale_price_to ) {
					$wc_10_sale    = 1;
					$sale_end_diff = $sale_price_to - WCST_Common::get_site_time();
				}
			}

			// display
			if ( $wc_10_sale === 1 && $sale_end_diff > 0 ) {
				$timer_class  = '';
				$data_display = '';
				if ( $deal_expiry_single['display_mode'] == 'reverse_date' || $deal_expiry_single['display_mode'] == 'expiry_date' ) {
					$data_display = 'days';
					if ( $deal_expiry_single['display_mode'] == 'reverse_date' ) {
						$display_html  = human_time_diff( $sale_price_to, WCST_Common::get_site_time() );
						$display_label = $deal_expiry_single['reverse_date_label'];
						$merge_tag     = '{{time_left}}';
					}
					if ( $deal_expiry_single['display_mode'] == 'expiry_date' ) {
						$display_html  = date_i18n( WCST_Common::wcst_get_date_format(), $sale_price_to );
						$display_label = $deal_expiry_single['expiry_date_label'];
						$merge_tag     = '{{end_date}}';
					}

					// checking switch mode
					if ( $deal_expiry_single['switch_period'] > 0 ) {
						if ( $sale_end_diff < ( $deal_expiry_single['switch_period'] * 3600 ) ) {
							$display_label = $deal_expiry_single['reverse_timer_label'];
							$display_html  = "";
							$merge_tag     = '{{countdown_timer}}';
							$timer_class   = ' wcst_timer';
							if ( $sale_end_diff < 86400 ) {
								$data_display = 'hrs';
							}
						}
					}
					$display_html  = '<span data-diff="' . $sale_end_diff . '" data-date = "' . $sale_price_to . '" data-display="' . $data_display . '" data-actual="' . $display_html . '">' . $display_html . '</span>';
					$display_label = str_replace( $merge_tag, $display_html, $display_label );
				} elseif ( $deal_expiry_single['display_mode'] == 'reverse_timer' ) {
					$display_label = $deal_expiry_single['reverse_timer_label'];
					$merge_tag     = '{{countdown_timer}}';
					$timer_class   = ' wcst_timer';
					if ( $sale_end_diff < 86400 ) {
						$display_html = gmdate( "H:i:s", $sale_end_diff );
						$data_display = 'hrs';
					} else {
						// has days
						$display_html = gmdate( "d", $sale_end_diff ) . " " . $hard_text_array['days'] . " " . gmdate( "H:i:s", $sale_end_diff );
						$data_display = 'days';
					}
					$display_html  = '<span data-diff="' . $sale_end_diff . '" data-date = "' . $sale_price_to . '" data-display="' . $data_display . '" data-actual="' . $display_html . '"></span>';
					$display_label = str_replace( $merge_tag, $display_html, $display_label );
				}
				$display_label = str_replace( "{{regular_price}}", wc_price( $productInfo->product->get_regular_price() ), $display_label );
				$display_label = str_replace( "{{sale_price}}", wc_price( $productInfo->product->get_sale_price() ), $display_label );
				$display_label = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $display_label, $this->slug ) );
				WCST_Common::insert_log( "Generated Display Is: " . $display_label, $this->slug );
				?>
                <div
                        class="<?php echo trim( $classes ); ?> wcst_deal_expiry wcst_static wcst_deal_expiry_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> wcst_deal_expiry_<?php echo $wc_product_type; ?> <?php echo $timer_class ?>" data-trigger-id="<?php echo $trigger_key ?>">
					<?php echo $display_label; ?>
                </div>
				<?php
			} else {
				WCST_Common::insert_log( "*******Terminating******* || Reason: Dates are not set correctly ||  product: " . $productInfo->product->get_title() . ":" . $productInfo->product->get_id(), $this->slug );
			}
		}
	}

	public function handle_grid_request( $data, $productInfo ) {
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			WCST_Common::insert_log( "*******Terminating******* || Reason: Product Support mismatch ||  product: " . $productInfo->product->get_title() . ":" . $productInfo->product->get_id(), $this->slug );

			return;
		}
		$wcst_deal_expiry_arr = $data;

		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) ) {
			$variation_ID = $productInfo->get_variation_for_grid();
			$this->output_for_variation( $data, $productInfo, $variation_ID, "grid" );
		} else {
			if ( $data && is_array( $data ) && count( $data ) > 0 ) {
				foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
					$this->output_html( $trigger_key, $deal_expiry_single, $productInfo );
				}
			}
		}
	}

	public function output_for_variation( $data, $productInfo, $variationID = 0, $page = '' ) {

		global $woocommerce;
		$wcst_low_stock_arr = $data;
		$classes            = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes            .= " wcst_on_" . $page . " ";

		WCST_Common::insert_log( "initiating for variation ID:" . $variationID, $this->slug );
		if ( ! $variationID ) {
			return;
		}

		foreach ( $wcst_low_stock_arr as $trigger_key => $deal_expiry_single ) {
			if ( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
				$variation = XL_WCST_Product::get_instance( $variationID );
				if ( ! $variation->product ) {
					return;
				}
				if ( $variation->product->is_in_stock() === false ) {
					return;
				}
				if ( $variation->product->get_regular_price() == $variation->product->get_sale_price() ) {
					return;
				}
				$display_regular_price = $variation->product->get_regular_price();
				$display_price         = $variation->product->get_sale_price();
			} else {
				$variation = $productInfo->product->get_available_variation( $productInfo->product->get_child( $variationID ) );
				if ( ! is_array( $variation ) ) {
					return;
				}
				if ( $variation['is_in_stock'] === false ) {
					return;
				}
				if ( $variation['display_price'] == $variation['display_regular_price'] ) {
					return;
				}
				$display_price         = $variation['display_price'];
				$display_regular_price = $variation['display_regular_price'];
			}

			$variation_dates = $productInfo->sales_date_for_product_variations();
			$sale_price_from = $variation_dates['variations'][ $variationID ]['from'];
			$sale_price_to   = $variation_dates['variations'][ $variationID ]['to'];

			$wc_10_sale = 0;

			if ( ! empty( $sale_price_from ) && ! empty( $sale_price_to ) ) {
				if ( WCST_Common::get_site_time() > $sale_price_from && WCST_Common::get_site_time() < $sale_price_to ) {
					$wc_10_sale    = 1;
					$sale_end_diff = $sale_price_to - WCST_Common::get_site_time();
				}
			} else {
				if ( ! empty( $sale_price_from ) && WCST_Common::get_site_time() > $sale_price_from ) {
					$wc_10_sale = 1;
				}
				if ( ! empty( $sale_price_to ) && WCST_Common::get_site_time() < $sale_price_to ) {
					$wc_10_sale    = 1;
					$sale_end_diff = $sale_price_to - WCST_Common::get_site_time();
				}
			}

			// display
			if ( $wc_10_sale === 1 && $sale_end_diff > 0 ) {
				$timer_class  = '';
				$data_display = '';
				if ( $deal_expiry_single['display_mode'] == 'reverse_date' || $deal_expiry_single['display_mode'] == 'expiry_date' ) {
					if ( $deal_expiry_single['display_mode'] == 'reverse_date' ) {
						$display_html = human_time_diff( $sale_price_to, WCST_Common::get_site_time() );
						$display_html = $this->replace_hard_text( $display_html );

						$display_label = $deal_expiry_single['reverse_date_label'];
						$merge_tag     = '{{time_left}}';
					}
					if ( $deal_expiry_single['display_mode'] == 'expiry_date' ) {
						$display_html  = date_i18n( WCST_Common::wcst_get_date_format(), $sale_price_to );
						$display_label = $deal_expiry_single['expiry_date_label'];
						$merge_tag     = '{{end_date}}';
					}

					// checking switch mode
					if ( $deal_expiry_single['switch_period'] > 0 ) {
						if ( $sale_end_diff < ( $deal_expiry_single['switch_period'] * 3600 ) ) {
							$display_label = $deal_expiry_single['reverse_timer_label'];
							$merge_tag     = '{{countdown_timer}}';
							$timer_class   = ' wcst_timer';
							$display_html  = "";
							if ( $sale_end_diff < 86400 ) {
								// $display_html = gmdate("H:i:s", $sale_end_diff);
								$data_display = 'hrs';
							} else {
								// has days
								// $display_html = gmdate("d", $sale_end_diff) . ' Days ' . gmdate("H:i:s", $sale_end_diff);
								$data_display = 'days';
							}
						}
					}
					$display_html = '<span data-diff="' . $sale_end_diff . '" data-date="" data-display="' . $data_display . '" data-actual="' . $display_html . '">' . $display_html . '</span>';
					if ( $timer_class == ' wcst_timer' ) {
//                        $display_html .= ' hours';
					}
					$display_label = str_replace( $merge_tag, $display_html, $display_label );
				} elseif ( $deal_expiry_single['display_mode'] == 'reverse_timer' ) {
					$display_label = $deal_expiry_single['reverse_timer_label'];
					$merge_tag     = '{{countdown_timer}}';
					$timer_class   = ' wcst_timer';
					$display_html  = "";
					if ( $sale_end_diff < 86400 ) {
						// $display_html = gmdate("H:i:s", $sale_end_diff);
						$data_display = 'hrs';
					} else {
						// has days
						// $display_html = gmdate("d", $sale_end_diff) . __(' Days ', WCST_SLUG) . gmdate("H:i:s", $sale_end_diff);
						$data_display = 'days';
					}
					$display_html = '<span data-diff="' . $sale_end_diff . '" data-display="' . $data_display . '" data-actual="' . $display_html . '"></span>';
					if ( $timer_class == 'wcst_timer' ) {
//                        $display_html .= ' hours';
					}
					$display_label = str_replace( $merge_tag, $display_html, $display_label );
				}

				$display_label = str_replace( "{{regular_price}}", wc_price( $display_regular_price ), $display_label );
				$display_label = str_replace( "{{sale_price}}", wc_price( $display_price ), $display_label );
				WCST_Common::insert_log( "Generated Display Is: " . $display_label, $this->slug );


				$display_label = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $display_label, $this->slug ) );
				?>
                <div
                        class="<?php echo trim( $classes ); ?> wcst_deal_expiry wcst_static wcst_deal_expiry_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> wcst_deal_expiry_simple <?php echo $timer_class ?>">
					<?php echo $display_label; ?>
                </div>
				<?php
			} else {

			}
		}
	}

	public function replace_hard_text( $original_text ) {
		$get_all_hard = WCST_Common::get_hard_text();
		foreach ( $get_all_hard as $key => $text ) {
			$original_text = str_replace( $key, $text, $original_text );
		}

		return $original_text;
	}

	public function handle_custom_request( $data, $productInfo, $page, $variationID = 0, $cart_item = null ) {
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_deal_expiry_arr = $data;

		if ( $page == "cart" ) {
			$this->handle_cart_request( $data, $productInfo, $variationID, $cart_item );
		} else {
			if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) && $variationID !== 0 ) {
				$this->output_for_variation( $data, $productInfo, $variationID, "product" );
			} else {
				if ( $data && is_array( $data ) && count( $data ) > 0 ) {
					foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
						$this->output_html( $trigger_key, $deal_expiry_single, $productInfo, "custom" );
					}
				}
			}
		}
	}

	public function handle_cart_request( $data, $productInfo, $variationID = 0, $cart_item = array() ) {

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_deal_expiry_arr = $data;

		if ( in_array( $productInfo->product_type, WCST_Common::wcst_woocommerce_product_type_variations() ) && $variationID !== 0 ) {
			$this->output_for_variation( $data, $productInfo, $variationID, "cart" );
		} else {
			foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
				$this->output_html( $trigger_key, $deal_expiry_single, $productInfo, "cart" );
			}
		}
	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_wp_head_css = '';
		if ( ! $productInfo->product ) {
			return "";
		}
		foreach ( $data as $trigger_key => $deal_expiry_single ) {
			$deal_expiry_css = '';
			ob_start();
			?>
            body .wcst_deal_expiry.wcst_deal_expiry_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> { <?php
			echo isset( $deal_expiry_single['text_color'] ) ? 'color:' . $deal_expiry_single['text_color'] . ';' : '';
			echo isset( $deal_expiry_single['font_size'] ) ? 'font-size:' . $deal_expiry_single['font_size'] . '; line-height: 1.4;' : '';
			?>}
			<?php
			$deal_expiry_css  = ob_get_clean();
			$wcst_wp_head_css .= $deal_expiry_css;
		}

		return $wcst_wp_head_css;
	}

}

WCST_Triggers::register( new WCST_Trigger_Deal_Expiry() );
