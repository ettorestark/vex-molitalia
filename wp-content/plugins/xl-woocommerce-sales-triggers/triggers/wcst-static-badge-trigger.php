<?php

class WCST_Trigger_Static_Badge extends WCST_Base_Trigger {
	public $slug = 'static_badge';
	public $parent_slug = 'wcst_badges_settings';
	public $title = '';
	public $default_priority = 10;

	public function get_defaults() {
		return array(
			'label'            => __( 'Static Badge', WCST_SLUG ),
			'badge_style'      => 1,
			'badge_bg_color'   => '#dd3333',
			'badge_text_color' => '#fff',
			'position'         => 2,
			'text_next'        => '{{product_category}}'
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_static_badge_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can set it up to display static badge that shows a static text.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'static_badge',
				),
			),
			array(
				'name'                     => __( 'Badge Text', WCST_SLUG ),
				'desc'                     => __( 'Displays a static badge', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_static_badge_label',
				'wcst_is_accordion_opened' => true,
				'wcst_accordion_title'     => __( 'Static Badge', WCST_SLUG ),
				'type'                     => 'textarea',
				'attributes'               => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'static_badge',
				),
			),
			array(
				'name'       => __( 'Text next to Badge', WCST_SLUG ),
				'desc'       => __( 'Append text next to static badge text <br/><i>{{product_category}}</i> outputs the top category name of the product.<br/><i>{{product_category_link}}</i> outputs the top category link of the product.<br/><i>{{product_category id="12"}}</i> outputs the category name of the given category id.<br/><i>{{product_category_link id="12"}}</i> outputs the category link of the given category id.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_text_next',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'static_badge',
				),
			),
			array(
				'name'       => __( 'Badge Style', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_badge_style',
				'type'       => 'radio',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'static_badge',
				),
				'options'    => array(
					'1' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_1.jpg" />',
					'2' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_2.jpg" />',
					'3' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_3.jpg" />',
					'4' => '<img src="' . plugin_dir_url( WCST_PLUGIN_FILE ) . '/assets/img/badge_4.jpg" />',
				),
			),

			array(
				'name'       => __( 'Badge Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_badge_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'static_badge',
				),
			),
			array(
				'name'       => __( 'Badge Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_badge_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'static_badge',
				),
			)
		);
	}

	public function get_post_settings() {
		return array(
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can set it up to display static badge that shows a static text.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_static_badge_mode',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Static Badge', WCST_SLUG ),
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
				'desc'       => 'Displays a static text',
				'id'         => '_wcst_data_wcst_static_badge_label',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_static_badge_mode',
					'data-conditional-value' => '1',
				),
			),

			array(
				'name'       => __( 'Text next to Badge', WCST_SLUG ),
				'desc'       => __( 'Append text next to static badge text <br/><i>{{product_category}}</i> outputs the top category name of the product.<br/><i>{{product_category_link}}</i> outputs the top category link of the product.<br/><i>{{product_category id="12"}}</i> outputs the category name of the given category id.<br/><i>{{product_category_link id="12"}}</i> outputs the category link of the given category id.', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_text_next',
				'type'       => 'textarea',
				'attributes' => array(
					'rows'                   => '3',
					'data-conditional-id'    => '_wcst_data_wcst_static_badge_mode',
					'data-conditional-value' => '1',
				),
			),

			array(
				'name'       => __( 'Badge Style', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_badge_style',
				'type'       => 'radio',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_static_badge_mode',
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
				'name'       => __( 'Badge Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_badge_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_static_badge_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Badge Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_static_badge_badge_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_static_badge_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_static_badge_position',
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
					'data-conditional-id'    => '_wcst_data_wcst_static_badge_mode',
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
			$wcst_static_badge_arr = $data;
			foreach ( $wcst_static_badge_arr as $trigger_key => $static_badge_single ) {
				$badge_position = $static_badge_single['position'];
				if ( $badge_position == $position ) {
					WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );
					$this->output_html( $trigger_key, $static_badge_single, $productInfo, "product" );
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
		return __( 'Static Badge', WCST_SLUG );
	}

	public function output_html( $trigger_key, $single, $productInfo, $page = '', $helper_args = array() ) {
		$classes               = apply_filters( 'wcst_html_classes', '', $this->slug );
		$hard_text_array       = WCST_Common::get_hard_text();
		$classes               .= " wcst_on_" . $page . " ";
		$static_badge_settings = $single;

		$template_output  = $single["label"];
		$text_next_output = $single['text_next'];

		if ( empty( $template_output ) && empty( $text_next_output ) ) {
			return;
		}

		$text_next_output = do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $text_next_output, $this->slug ) );

		ob_start(); ?>
        <div class="<?php echo trim( $classes ); ?> wcst_best_sellers_badge wcst_best_sellers_badge_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> wcst_best_sellers_badge_<?php echo $static_badge_settings['badge_style'] ?>" data-trigger-id="<?php echo $trigger_key ?>">
			<?php
			echo $template_output ? '<span class="wcst_best_sellers_badge_span_one"> <span>' . $template_output . '</span></span>' : '';
			echo $text_next_output ? $text_next_output : '';
			?>
        </div>
		<?php
		$wcst_static_badge = ob_get_clean();
		echo apply_filters( 'wcst_static_badge_display_content', $wcst_static_badge, $template_output, $productInfo, $trigger_key, $static_badge_settings );

	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_wp_head_css = "";
		if ( ! $productInfo->product ) {
			return "";
		}
		if ( $productInfo->product_type != 'external' ) {
			$wcst_static_badge_arr = $data;

			foreach ( $wcst_static_badge_arr as $trigger_key => $static_badge_single ) {
				$badge_style      = $static_badge_single['badge_style'];
				$badge_bg_color   = $static_badge_single['badge_bg_color'];
				$badge_text_color = $static_badge_single['badge_text_color'];
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

WCST_Triggers::register( new WCST_Trigger_Static_Badge() );
