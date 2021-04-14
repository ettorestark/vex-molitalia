<?php

class WCST_Trigger_Smarter_Reviews extends WCST_Base_Trigger {
	public $slug = 'smarter_reviews';
	public $parent_slug = 'wcst_smarter_reviews_settings';
	public $title = '';
	public $default_priority = 2;

	public function get_defaults() {
		return array(

			'template'                    => 'satisfaction_rate',
			'satisfaction_rate_label'     => '{{rating_percentage}} of buyers said they were satisfied.',
			'rating_greater_than_4_label' => '{{positive_feedback_percentage}} of buyers gave more than 4 star rating.',
			'switch_to_max'               => 'yes',
			'dont_show_until'             => '40',
			'hide_if_disable_comments'    => 'no',
			'hyperlink_text_review'       => 'no',
			'text_color'                  => '#242424',
			'font_size'                   => 15,
			'position'                    => '3',
		);
	}

	public function register_settings() {
		$this->settings = array(

			array(
				'id'         => '_wcst_data_wcst_customer_reviews_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can set it up in one of the two templates: <i>Satisfaction Rate Template</i> and <i>Positive Feedback Rate Template</i>.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
			),
			array(
				'name'       => __( 'Satisfaction Rate Text', WCST_SLUG ),
				'desc'       => __( 'Calculates percentage of rating. For example, if product has average rating of 4.3, it will show satisfaction rate as 86%.<br/><i>{{rating_percentage}}</i> displays percentage of rating.<br/><i>{{number_of_review}}</i> displays number of reviews.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_satisfaction_rate_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
			),
			array(
				'name'       => __( 'Positive Feedback Rate Text', WCST_SLUG ),
				'desc'       => __( 'Calculates percentage of people who gave 4 or 5 stars. For example, if 8 out 10 buyers gave rating greater than or equal to 4, it will show positive feedback as 80%.<br/><i>{{positive_feedback_percentage}}</i> displays percentage of people who gave four or greater than four stars.<br/><i>{{number_of_review}}</i> displays number of reviews.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_rating_greater_than_4_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
			),
			array(
				'name'       => __( 'Show Template with higher percentage', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_switch_to_max',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Choose Template', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_template',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'         => '_wcst_data_choose_trigger',
					'data-conditional-value'      => 'smarter_reviews',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_smarter_reviews_switch_to_max',
					'data-wcst-conditional-value' => 'no',
				),
				'options'    => array(
					'satisfaction_rate'     => __( 'Satisfaction Rate', WCST_SLUG ),
					'rating_greater_than_4' => __( 'Positive Feedback Rate', WCST_SLUG ),
				),
			),
			array(
				'name'        => __( 'Hide Template if rating percentage less than', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_smarter_reviews_dont_show_until',
				'desc'        => __( '%', WCST_SLUG ),
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
			),
			array(
				'name'       => __( 'Hide Template if Reviews are Disabled', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_hide_if_disable_comments',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Hyperlink Text with Reviews Area', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_hyperlink_text_review',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
			),
			array(
				'name'        => __( 'Font size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_smarter_reviews_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'smarter_reviews',
				),
			)
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can set it up in one of the two templates: <i>Satisfaction Rate Template</i> and <i>Positive Feedback Rate Template</i>.', WCST_SLUG ),
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Smarter Reviews', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'id'                       => '_wcst_data_wcst_smarter_reviews_mode',
				'type'                     => 'wcst_switch',
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			array(
				'name'       => __( 'Satisfaction Rate Text', WCST_SLUG ),
				'desc'       => __( 'Calculates percentage of rating. For example, if product has average rating of 4.3, it will show satisfaction rate as 86%.<br/><i>{{rating_percentage}}</i> displays percentage of rating.<br/><i>{{number_of_review}}</i> displays number of reviews.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_satisfaction_rate_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Positive Feedback Rate Text', WCST_SLUG ),
				'desc'       => __( 'Calculates percentage of people who gave 4 or 5 stars. For example, if 8 out 10 buyers gave rating greater than or equal to 4, it will show positive feedback as 80%.<br/><i>{{positive_feedback_percentage}}</i> displays percentage of people who gave four or greater than four stars.<br/><i>{{number_of_review}}</i> displays number of reviews.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_rating_greater_than_4_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Show Template with higher percentage', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_switch_to_max',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Choose Template', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_template',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'         => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value'      => '1',
					'data-wcst-conditional-id'    => '_wcst_data_wcst_smarter_reviews_switch_to_max',
					'data-wcst-conditional-value' => 'no',
				),
				'options'    => array(
					'satisfaction_rate'     => __( 'Satisfaction Rate', WCST_SLUG ),
					'rating_greater_than_4' => __( 'Positive Feedback Rate', WCST_SLUG ),
				),
			),
			array(
				'name'        => __( 'Hide Template if rating percentage less than', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_smarter_reviews_dont_show_until',
				'desc'        => __( '%', WCST_SLUG ),
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Hide Template if Reviews are Disabled', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_hide_if_disable_comments',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Hyperlink Text with Reviews Area', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_hyperlink_text_review',
				'type'       => 'radio_inline',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
				'options'    => array(
					'yes' => __( 'Yes', WCST_SLUG ),
					'no'  => __( 'No', WCST_SLUG ),
				)
			),
			array(
				'name'       => __( 'Text color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_smarter_reviews_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Font size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_smarter_reviews_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_smarter_reviews_position',
				'type'             => 'select',
				'after_row'        => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
				'show_option_none' => false,
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_smarter_reviews_mode',
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
		$wcst_smarter_reviews_arr = $data;
		foreach ( $wcst_smarter_reviews_arr as $trigger_key => $smarter_reviews_single ) {
			$badge_position = $smarter_reviews_single['position'];
			if ( $badge_position == $position ) {
				WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );

				$this->output_html( $trigger_key, $smarter_reviews_single, $productInfo, "product" );
			}
		}
	}

	public function get_supported_product_type() {
		$parent = parent::get_supported_product_type();

		array_push( $parent, 'external' );
		array_push( $parent, 'grouped' );
		array_push( $parent, 'booking' );

		return $parent;
	}

	public function get_title() {
		return __( 'Smarter Reviews', WCST_SLUG );
	}

	public function output_html( $trigger_key, $smarter_reviews_single, $productInfo, $page = '', $helper_args = array() ) {
		$classes             = apply_filters( 'wcst_html_classes', '', $this->slug );
		$classes             .= " wcst_on_" . $page . " ";
		$smarter_reviews_arr = $productInfo->get_smarter_review_array();

		$smarter_reviews_single_arr = array();

		$out_put_content = apply_filters( 'wcst_smarter_reviews_display_content_before_data', '', $smarter_reviews_single, $productInfo, $trigger_key, $this );

		if ( $out_put_content !== "" ) {
			echo $out_put_content;

			return;
		}

		if ( is_array( $smarter_reviews_arr ) && isset( $smarter_reviews_arr['rating'] ) ) {
			$template                    = $smarter_reviews_single['template'];
			$satisfaction_rate_label     = $smarter_reviews_single['satisfaction_rate_label'];
			$rating_greater_than_4_label = $smarter_reviews_single['rating_greater_than_4_label'];
			$dont_show_until             = $smarter_reviews_single['dont_show_until'];
			$switch_to_max               = $smarter_reviews_single['switch_to_max'];
			if ( $smarter_reviews_single['hide_if_disable_comments'] == 'yes' ) {
				if ( $productInfo->wcst_product_comment_status == 'closed' ) {
					return false;
				}
			}
			if ( $template ) {
				$smarter_reviews_single_arr['final_display'] = $template;
			}

			$final_display_temp = '';
			if ( isset( $smarter_reviews_arr['rating'] ) && is_array( $smarter_reviews_arr['rating'] ) ) {
				$product_rating = $smarter_reviews_arr['rating'];
				if ( $switch_to_max != 'yes' ) {
					if ( $template == 'satisfaction_rate' ) {
						// satisfaction_rate
						$total_rating_sum = array();
						foreach ( $product_rating as $value => $count ) {
							$total_rating_sum[] = $value * $count;
						}
						$satisfaction_rate_value = array_sum( $total_rating_sum ) / array_sum( $product_rating );
						$satisfaction_rate_value = $satisfaction_rate_value * 20;
						if ( strpos( $satisfaction_rate_value, '.' ) !== false ) {
							$decimal_count = strlen( substr( strrchr( $satisfaction_rate_value, "." ), 1 ) );
							if ( $decimal_count > 1 ) {
								$satisfaction_rate_value = round( $satisfaction_rate_value, 2 );
							}
						}
						$final_display_temp = 'satisfaction_rate';
						if ( $satisfaction_rate_value >= $dont_show_until ) {
							$final_display                                            = $satisfaction_rate_value;
							$final_display_temp                                       = 'satisfaction_rate';
							$satisfaction_rate_display                                = str_replace( '{{rating_percentage}}', $satisfaction_rate_value . '%', $satisfaction_rate_label );
							$satisfaction_rate_display                                = str_replace( '{{number_of_review}}', $productInfo->get_comment_count(), $satisfaction_rate_display );
							$smarter_reviews_single_arr['satisfaction_rate']['value'] = $satisfaction_rate_value;
							$smarter_reviews_single_arr['satisfaction_rate']['html']  = $satisfaction_rate_display;
							$smarter_reviews_single_arr['satisfaction_rate']['html']  = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $smarter_reviews_single_arr['satisfaction_rate']['html'], $this->slug ) );

						}
					} elseif ( $template == 'rating_greater_than_4' ) {
						// rating_greater_than_4
						$user_count_gt_4 = 0;
						$user_count_gt_4 += $product_rating[4];
						$user_count_gt_4 += $product_rating[5];
						if ( $user_count_gt_4 > 0 ) {
							$user_count_gt_4_value = ( $user_count_gt_4 / array_sum( $product_rating ) ) * 100;
							if ( strpos( $user_count_gt_4_value, '.' ) !== false ) {
								$decimal_count = strlen( substr( strrchr( $user_count_gt_4_value, "." ), 1 ) );
								if ( $decimal_count > 1 ) {
									$user_count_gt_4_value = round( $user_count_gt_4_value, 2 );
								}
							}
							$final_display_temp = 'rating_greater_than_4';
							if ( $user_count_gt_4_value >= $dont_show_until ) {
								$final_display                                                = $user_count_gt_4_value;
								$final_display_temp                                           = 'rating_greater_than_4';
								$user_count_gt_4_display                                      = str_replace( '{{positive_feedback_percentage}}', $user_count_gt_4_value . '%', $rating_greater_than_4_label );
								$user_count_gt_4_display                                      = str_replace( '{{number_of_review}}', $productInfo->get_comment_count(), $user_count_gt_4_display );
								$smarter_reviews_single_arr['rating_greater_than_4']['value'] = $user_count_gt_4_value;
								$smarter_reviews_single_arr['rating_greater_than_4']['html']  = $user_count_gt_4_display;
								$smarter_reviews_single_arr['rating_greater_than_4']['html']  = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $smarter_reviews_single_arr['rating_greater_than_4']['html'], $this->slug ) );

							}
						}
					}
				} else {
					$final_display = 0;

					// satisfaction_rate
					$total_rating_sum = array();
					foreach ( $product_rating as $value => $count ) {
						$total_rating_sum[] = $value * $count;
					}
					$satisfaction_rate_value = array_sum( $total_rating_sum ) / array_sum( $product_rating );
					$satisfaction_rate_value = $satisfaction_rate_value * 20;
					if ( strpos( $satisfaction_rate_value, '.' ) !== false ) {
						$decimal_count = strlen( substr( strrchr( $satisfaction_rate_value, "." ), 1 ) );
						if ( $decimal_count > 1 ) {
							$satisfaction_rate_value = round( $satisfaction_rate_value, 2 );
						}
					}
					$final_display_temp = 'satisfaction_rate';
					if ( $satisfaction_rate_value >= $dont_show_until ) {
						if ( $satisfaction_rate_value > $final_display ) {
							$final_display      = $satisfaction_rate_value;
							$final_display_temp = 'satisfaction_rate';
						}
						$satisfaction_rate_display = str_replace( '{{rating_percentage}}', $satisfaction_rate_value . '%', $satisfaction_rate_label );

						$satisfaction_rate_display                                = str_replace( '{{number_of_review}}', $productInfo->get_comment_count(), $satisfaction_rate_display );
						$smarter_reviews_single_arr['satisfaction_rate']['value'] = $satisfaction_rate_value;
						$smarter_reviews_single_arr['satisfaction_rate']['html']  = $satisfaction_rate_display;
						$smarter_reviews_single_arr['satisfaction_rate']['html']  = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $smarter_reviews_single_arr['satisfaction_rate']['html'], $this->slug ) );
					}

					// rating_greater_than_4
					$user_count_gt_4 = 0;
					$user_count_gt_4 += $product_rating[4];
					$user_count_gt_4 += $product_rating[5];
					if ( $user_count_gt_4 > 0 ) {
						$user_count_gt_4_value = ( $user_count_gt_4 / array_sum( $product_rating ) ) * 100;
						if ( strpos( $user_count_gt_4_value, '.' ) !== false ) {
							$decimal_count = strlen( substr( strrchr( $user_count_gt_4_value, "." ), 1 ) );
							if ( $decimal_count > 1 ) {
								$user_count_gt_4_value = round( $user_count_gt_4_value, 2 );
							}
						}
						if ( $user_count_gt_4_value >= $dont_show_until ) {
							if ( $user_count_gt_4_value > $final_display ) {
								$final_display      = $user_count_gt_4_value;
								$final_display_temp = 'rating_greater_than_4';
							}
							$user_count_gt_4_display                                      = str_replace( '{{positive_feedback_percentage}}', $user_count_gt_4_value . '%', $rating_greater_than_4_label );
							$user_count_gt_4_display                                      = str_replace( '{{number_of_review}}', $productInfo->get_comment_count(), $user_count_gt_4_display );
							$smarter_reviews_single_arr['rating_greater_than_4']['value'] = $user_count_gt_4_value;
							$smarter_reviews_single_arr['rating_greater_than_4']['html']  = $user_count_gt_4_display;
							$smarter_reviews_single_arr['rating_greater_than_4']['html']  = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $smarter_reviews_single_arr['rating_greater_than_4']['html'], $this->slug ) );

						}
					}
				}
				$smarter_reviews_single_arr['final_display'] = $final_display_temp;
			}

			$smarter_reviews_single_arr_mod = apply_filters( 'wcst_smarter_reviews_single_arr', $smarter_reviews_single_arr, $productInfo->product->get_id(), $smarter_reviews_single );

			$review_html_start = '<a href="#reviews" class="woocommerce-review-link wcst_review_link" rel="nofollow">';
			$review_html_end   = '</a>';
			if ( $productInfo->wcst_product_comment_status == 'closed' || $smarter_reviews_single['hyperlink_text_review'] != 'yes' || $page !== "product" ) {
				$review_html_start = '';
				$review_html_end   = '';
			}

			ob_start();
			if ( isset( $smarter_reviews_single_arr_mod['final_display'] ) && $smarter_reviews_single_arr_mod['final_display'] != '' && isset( $smarter_reviews_single_arr_mod[ $smarter_reviews_single_arr_mod['final_display'] ] ) ) {
				?>
                <div
                        class="<?php echo trim( $classes ) ?> wcst_smarter_reviews wcst_smarter_reviews_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> wcst_smarter_reviews_<?php echo $smarter_reviews_single_arr_mod['final_display'] ?>" data-trigger-id="<?php echo $trigger_key ?>">
					<?php echo $review_html_start . '<span>' . $smarter_reviews_single_arr_mod[ $smarter_reviews_single_arr_mod['final_display'] ]['html'] . '</span>' . $review_html_end; ?>
                </div>
				<?php
			}

			$get_html = ob_get_clean();
			$get_html = apply_filters( 'wcst_smarter_reviews_display_content', $get_html, $smarter_reviews_single_arr_mod, $review_html_start, $trigger_key, $productInfo );
			echo $get_html;
		}
	}

	public function handle_cart_request( $data, $productInfo, $variationID = 0, $cart_item = array() ) {
		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_deal_expiry_arr = $data;
		foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
			$this->output_html( $trigger_key, $deal_expiry_single, $productInfo, "cart" );
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
		$wcst_smarter_reviews_arr = $data;
		foreach ( $wcst_smarter_reviews_arr as $trigger_key => $smarter_reviews_single ) {
			$badge_position = $smarter_reviews_single['position'];

			$this->output_html( $trigger_key, $smarter_reviews_single, $productInfo, $page );

		}
	}

	public function handle_grid_request( $data, $productInfo ) {

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		$wcst_deal_expiry_arr = $data;
		foreach ( $wcst_deal_expiry_arr as $trigger_key => $deal_expiry_single ) {
			$this->output_html( $trigger_key, $deal_expiry_single, $productInfo, "grid" );
		}
	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_smarter_reviews_arr = $data;
		$wcst_wp_head_css         = "";

		if ( ! $productInfo->product ) {
			return "";
		}
		global $_flatsome_theme_mod_site_url;

		foreach ( $wcst_smarter_reviews_arr as $trigger_key => $smarter_reviews_single ) {

			$show_default_reviews_text = WCST_Common::wcst_get_global_setting( 'wcst_global_show_reviews_text' );
			$smarter_reviews_css       = '';
			ob_start();
			echo ( 'no' == $show_default_reviews_text ) ? 'body .woocommerce-product-rating .woocommerce-review-link:nth-child(2) { display: none; }' : '';
			?>
            body .wcst_smarter_reviews.wcst_smarter_reviews_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> span { <?php
			echo isset( $smarter_reviews_single['text_color'] ) ? 'color: ' . $smarter_reviews_single['text_color'] . ';' : '';
			echo isset( $smarter_reviews_single['font_size'] ) ? 'font-size: ' . $smarter_reviews_single['font_size'] . '; line-height: 1.4;' : '';
			?> }
			<?php
			$smarter_reviews_css = ob_get_clean();
			$wcst_wp_head_css    .= $smarter_reviews_css;
		}
		if ( $_flatsome_theme_mod_site_url && $_flatsome_theme_mod_site_url !== "" ) {
			ob_start();
			?>
            body .wcst_on_product.wcst_smarter_reviews .wcst_review_link {
            opacity: 1;
            position: static;
            }
			<?php
			$smarter_reviews_css = ob_get_clean();
			$wcst_wp_head_css    .= $smarter_reviews_css;
		}

		return $wcst_wp_head_css;
	}


}

WCST_Triggers::register( new WCST_Trigger_Smarter_Reviews() );