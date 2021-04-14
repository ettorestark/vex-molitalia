<?php

class WCST_Trigger_Guarantee extends WCST_Base_Trigger {
	public $slug = 'guarantee';
	public $parent_slug = 'wcst_guarantee_settings';
	public $title = '';
	public $default_priority = 9;

	public function get_defaults() {
		return array(
			'box_bg_color' => '#f4f5f5',
			'border_color' => '#ececec',
			'guarantee'    => array(
				0 => array( 'style_mode' => 'none', 'heading' => __( 'Hassle Free Returns', WCST_SLUG ), 'text' => __( 'No questions asked, 30 days return policy.', WCST_SLUG ) ),
				1 => array( 'style_mode' => 'none', 'heading' => __( 'Fast Shipping', WCST_SLUG ), 'text' => __( 'All orders are shipped in 1-3 business days.', WCST_SLUG ) ),
				2 => array( 'style_mode' => 'none', 'heading' => __( 'Secure Checkout', WCST_SLUG ), 'text' => __( 'SSL Enabled Secure Checkout', WCST_SLUG ) ),
			),
			'text_color'   => '#252525',
			'font_size'    => 16,
			'alignment'    => 'left',
			'position'     => 5,
		);
	}

	public function register_settings() {
		$this->settings = array(
			array(
				'id'         => '_wcst_data_wcst_guarantee_html',
				'type'       => 'wcst_html_content_field',
				'content'    => '<div class="wcst_desc_before_row">' . __( 'You can set it up to display a guarantee box with icon and text as shown in preview.', WCST_SLUG ) . '</div>',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
			),
			/**             * *** REPEATER ************** */
			array(
				'id'           => '_wcst_data_wcst_guarantee_guarantee',
				'type'         => 'group',
				'before_group' => array( 'WCST_Admin_CMB2_Support', 'cmb2_wcst_before_call' ),
				'after_group'  => array( 'WCST_Admin_CMB2_Support', 'cmb2_wcst_after_call' ),
				'repeatable'   => true, // use false if you want non-repeatable group
				'attributes'   => array(
					'class'                  => 'wcst_guarantee_group',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
				'options'      => array(
					'group_title'   => __( 'Guarantee', WCST_SLUG ), // since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add Guarantee', WCST_SLUG ),
					'remove_button' => __( 'Remove Guarantee', WCST_SLUG ),
					'sortable'      => true, // true to enable sorting
					'closed'        => true, // true to have the groups closed by default
				),
				'fields'       => array(
					array(
						'name' => __( 'Guarantee Heading', WCST_SLUG ),
						'id'   => 'heading',
						'type' => 'text',
					),
					array(
						'name'       => __( 'Guarantee Text', WCST_SLUG ),
						'id'         => 'text',
						'type'       => 'textarea',
						'desc'       => __( ' <a href="javascript:void(0);" onclick="wcst_show_tb(\'Merge Tags For Guarantee Trigger\',\'wcst_guarantee_merge_tags_help\');">Click here to learn to set up dynamic merge tags in this trigger</a>', WCST_SLUG ),
						'attributes' => array(
							'rows' => '8',
						),
					),
					array(
						'name'    => __( 'Icon', WCST_SLUG ),
						'id'      => 'style_mode',
						'type'    => 'radio_inline',
						'options' => array(
							'icon'  => __( 'Built-in', WCST_SLUG ),
							'image' => __( 'Custom', WCST_SLUG ),
							'none'  => __( 'None', WCST_SLUG )
						),
					),
					array(
						'name'             => __( 'Built-in Icon', WCST_SLUG ),
						'id'               => 'icon',
						'type'             => 'select',
						'after_field'      => array( 'WCST_Admin_CMB2_Support', 'wcst_after_row_icon_preview' ),
						'show_option_none' => false,
						'attributes'       => array(
							'class'                  => 'cmb2_select wcst_icon_select',
							'data-conditional-id'    => json_encode( array( '_wcst_data_wcst_guarantee_guarantee', 'style_mode' ) ),
							'data-conditional-value' => 'icon',
						),
						'options'          => WCST_Common::wcst_ecom_icons(),
					),
					array(
						'name'       => 'Custom',
						'desc'       => 'Upload an icon.',
						'id'         => 'image',
						'type'       => 'file',
						'attributes' => array(
							'data-conditional-id'    => json_encode( array( '_wcst_data_wcst_guarantee_guarantee', 'style_mode' ) ),
							'data-conditional-value' => 'image',
						),
						'options'    => array(
							'url' => false, // Hide the text input for the url
						),
						'text'       => array(
							'add_upload_file_text' => 'Add Icon' // Change upload button text. Default: "Add or Upload File"
						),
					)
				)
			),
			/*             * ******** END REPEATER ****************** */
			array(
				'name'       => __( 'Background Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_box_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
			),
			array(
				'name'       => __( 'Border Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_border_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
			),
			array(
				'name'       => __( 'Heading Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_heading_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_guarantee_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
			),
			array(
				'name'       => __( 'Box Alignment', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_alignment',
				'type'       => 'radio_inline',
				'options'    => array(
					'left'   => __( 'Left', WCST_SLUG ),
					'center' => __( 'Center', WCST_SLUG ),
					'right'  => __( 'Right', WCST_SLUG ),
				),
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_choose_trigger',
					'data-conditional-value' => 'guarantee',
				),
			),
		);
	}

	public function get_post_settings() {
		return array(
			/*             * ************** IMAGE ICON STARTS ************************ */
			array(
				'name'                     => __( 'Trigger', WCST_SLUG ),
				'desc'                     => __( 'You can set it up to display a guarantee box with icon and text as shown in preview.', WCST_SLUG ),
				'id'                       => '_wcst_data_wcst_guarantee_mode',
				'type'                     => 'wcst_switch',
				'before_row'               => array( 'WCST_Admin_CMB2_Support', 'cmb_before_row_cb' ),
				'wcst_accordion_title'     => __( 'Guarantee Box', WCST_SLUG ),
				'wcst_is_accordion_opened' => true,
				'default'                  => 0,
				'label'                    => array( 'on' => __( 'Activate', WCST_SLUG ), 'off' => __( 'Deactivate', WCST_SLUG ) )
			),
			/*             * **** REPEATER ************** */
			array(
				'id'           => '_wcst_data_wcst_guarantee_guarantee',
				'type'         => 'group',
				'before_group' => array( 'WCST_Admin_CMB2_Support', 'cmb2_wcst_before_call' ),
				'after_group'  => array( 'WCST_Admin_CMB2_Support', 'cmb2_wcst_after_call' ),
				'repeatable'   => true, // use false if you want non-repeatable group
				'attributes'   => array(
					'class'                  => 'wcst_guarantee_group',
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
					'data-conditional-value' => '1',
				),
				'options'      => array(
					'group_title'   => __( 'Guarantee', WCST_SLUG ), // since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add Guarantee', WCST_SLUG ),
					'remove_button' => __( 'Remove Guarantee', WCST_SLUG ),
					'sortable'      => true, // true to enable sorting
					'closed'        => true, // true to have the groups closed by default
				),
				'fields'       => array(
					array(
						'name' => __( 'Guarantee Heading', WCST_SLUG ),
						'id'   => 'heading',
						'type' => 'text',
					),
					array(
						'name'       => __( 'Guarantee Text', WCST_SLUG ),
						'desc'       => __( ' <a href="javascript:void(0);" onclick="wcst_show_tb(\'Merge Tags For Guarantee Trigger\',\'wcst_guarantee_merge_tags_help\');">Click here to learn to set up dynamic merge tags in this trigger</a>', WCST_SLUG ),
						'id'         => 'text',
						'type'       => 'textarea',
						'attributes' => array(
							'rows' => '4',
						),
					),
					array(
						'name'    => __( 'Icon', WCST_SLUG ),
						'id'      => 'style_mode',
						'type'    => 'radio_inline',
						'options' => array(
							'icon'  => __( 'Built-in', WCST_SLUG ),
							'image' => __( 'Custom', WCST_SLUG ),
							'none'  => __( 'None', WCST_SLUG )
						),
					),
					array(
						'name'             => __( 'Built-in Icon', WCST_SLUG ),
						'id'               => 'icon',
						'type'             => 'select',
						'after_field'      => array( 'WCST_Admin_CMB2_Support', 'wcst_after_row_icon_preview' ),
						'show_option_none' => false,
						'attributes'       => array(
							'class'                  => 'cmb2_select wcst_icon_select',
							'data-conditional-id'    => json_encode( array( '_wcst_data_wcst_guarantee_guarantee', 'style_mode' ) ),
							'data-conditional-value' => 'icon',
						),
						'options'          => WCST_Common::wcst_ecom_icons(),
					),
					array(
						'name'       => 'Custom',
						'desc'       => 'Upload an icon.',
						'id'         => 'image',
						'type'       => 'file',
						'attributes' => array(
							'data-conditional-id'    => json_encode( array( '_wcst_data_wcst_guarantee_guarantee', 'style_mode' ) ),
							'data-conditional-value' => 'image',
						),
						'options'    => array(
							'url' => false, // Hide the text input for the url
						),
						'text'       => array(
							'add_upload_file_text' => 'Add Icon' // Change upload button text. Default: "Add or Upload File"
						),
					)
				)
			),
			/*             * ******** END REPEATER ****************** */
			array(
				'name'       => __( 'Background Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_box_bg_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Border Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_border_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
					'data-conditional-value' => '1',
				),
			),

			array(
				'name'       => __( 'Heading Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_heading_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'       => __( 'Text Color', WCST_SLUG ),
				'id'         => '_wcst_data_wcst_guarantee_text_color',
				'type'       => 'colorpicker',
				'attributes' => array(
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Font Size', WCST_SLUG ),
				'desc'        => __( 'px', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_guarantee_font_size',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'text',
				'attributes'  => array(
					'type'                   => 'number',
					'min'                    => '0',
					'pattern'                => '\d*',
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'        => __( 'Box Alignment', WCST_SLUG ),
				'id'          => '_wcst_data_wcst_guarantee_alignment',
				'row_classes' => array( 'wcst_field_inline_desc' ),
				'type'        => 'radio_inline',
				'options'     => array(
					'left'   => __( 'Left', WCST_SLUG ),
					'center' => __( 'Center', WCST_SLUG ),
					'right'  => __( 'Right', WCST_SLUG ),
				),
				'attributes'  => array(
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
					'data-conditional-value' => '1',
				),
			),
			array(
				'name'             => __( 'Position', WCST_SLUG ),
				'id'               => '_wcst_data_wcst_guarantee_position',
				'show_option_none' => false,
				'type'             => 'select',
				'after_row'        => array( 'WCST_Admin_CMB2_Support', 'cmb_after_row_cb' ),
				'attributes'       => array(
					'data-conditional-id'    => '_wcst_data_wcst_guarantee_mode',
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
				)
			),
		);
	}

	public function handle_single_product_request( $data, $productInfo, $position ) {

		if ( ! in_array( $productInfo->product_type, $this->get_supported_product_type() ) ) {
			return;
		}
		foreach ( $data as $trigger_key => $guarantee_single ) {
			$badge_position = $guarantee_single['position'];
			if ( $badge_position == $position ) {
				WCST_Common::insert_log( "Single Product Request For " . $productInfo->product->get_id() . "-- " . $this->get_title(), $this->slug );

				$this->output_html( $trigger_key, $guarantee_single, $productInfo, 'product' );
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
		return __( 'Guarantee', WCST_SLUG );
	}

	public function output_html( $trigger_key, $guarantee_single, $productInfo, $page = '', $helper_args = array() ) {

		$classes = apply_filters( 'wcst_html_classes', '', $this->slug );

		$classes .= " wcst_on_" . $page;
		$classes .= " wcst_guarantee_box_" . $guarantee_single['alignment'];
//        if ($template == 'guarantee') {
		$gurantee_html = '';

		if ( isset( $guarantee_single['guarantee'] ) && is_array( $guarantee_single['guarantee'] ) && count( $guarantee_single['guarantee'] ) > 0 ) {

			ob_start();
			echo '<div class="' . trim( $classes ) . ' wcst_guarantee_box wcst_guarantee_box_key_' . $productInfo->product->get_id() . '_' . $trigger_key . '" data-trigger-id="' . $trigger_key . '">';
			foreach ( $guarantee_single['guarantee'] as $val ) {
				if ( ( isset( $val['heading'] ) && $val['heading'] != '' ) || ( isset( $val['text'] ) && $val['text'] != '' ) ) {
					$gurantee_class = 'wcst_no_padding';
					echo '<div class="wcst_guarantee_box_row wcst_clear">';
					if ( isset( $val['icon'] ) || isset( $val['image'] ) ) {
						echo '<div class="wcst_guarantee_box_icon">';
						if ( isset( $val['icon'] ) ) {
							echo '<i class="wcst_custom_icon wcst-ecommerce' . $this->wcst_icon_prefix( $val['icon'] ) . ' x2"></i>';
						} elseif ( isset( $val['image'] ) ) {
							echo '<img src="' . $val['image'] . '" />';
						}
						echo '</div>';
						$gurantee_class = '';
					}
					?>
                    <div class="wcst_guarantee_box_text <?php echo $gurantee_class; ?>">
						<?php
						echo ( isset( $val['heading'] ) && $val['heading'] ) ? '<h5>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $val['heading'], $this->slug ) ) . '</h5>' : '';
						if ( isset( $val['text'] ) && $val['text'] != '' ) {
							$mod_value = nl2br( $val['text'] );
							echo '<p>' . do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( $mod_value, $this->slug ) ) . '</p>';
						}
						?>
                    </div>
					<?php
					echo '</div>';
				}
			}
			echo '</div><div class="wcst_clear"></div>';
			$gurantee_html = ob_get_clean();
		}
		echo apply_filters( 'wcst_guarantee_display_content', $gurantee_html, $guarantee_single, $trigger_key, $productInfo, $this );
//        }
	}

	private function wcst_icon_prefix( $icon_num ) {
		if ( empty( $icon_num ) ) {
			return 000;
		}
		if ( strlen( $icon_num ) == 3 ) {
			return $icon_num;
		} elseif ( strlen( $icon_num ) == 2 ) {
			return '0' . $icon_num;
		} elseif ( strlen( $icon_num ) == 1 ) {
			return '00' . $icon_num;
		}
	}

	public function output_dynamic_css( $data, $productInfo ) {
		$wcst_guarantee_arr = $data;
		$gurantee_css       = '';
		if ( ! $productInfo->product ) {
			return "";
		}
		foreach ( $wcst_guarantee_arr as $trigger_key => $guarantee_single ) {
			ob_start();
			$box_bg_color       = $guarantee_single['box_bg_color'];
			$border_color       = $guarantee_single['border_color'];
			$text_color         = $guarantee_single['text_color'];
			$font_size          = $guarantee_single['font_size'];
			$text_heading_color = ( isset( $guarantee_single['heading_color'] ) && ! empty( $guarantee_single['heading_color'] ) ) ? $guarantee_single['heading_color'] : $text_color;
			if ( $box_bg_color != '' || $border_color != '' ) {
				?>
                .wcst_guarantee_box.wcst_guarantee_box_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> { <?php
				echo $box_bg_color ? ' background: ' . $box_bg_color . ';' : '';
				echo $border_color ? ' border: 1px solid ' . $border_color . ';' : '';
				?>}
                .wcst_guarantee_box.wcst_guarantee_box_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> .wcst_guarantee_box_text, .wcst_guarantee_box.wcst_guarantee_box_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> .wcst_guarantee_box_text p{ <?php
				echo $text_color ? ' color: ' . $text_color . ';' : '';
				echo $font_size ? ' font-size: ' . $font_size . '; line-height: 1.4;' : '';
				?>}
                .wcst_guarantee_box.wcst_guarantee_box_key_<?php echo $productInfo->product->get_id() ?>_<?php echo $trigger_key ?> .wcst_guarantee_box_text h5 {
				<?php
				echo $text_heading_color ? ' color: ' . $text_heading_color . ';' : '';
				?>}
				<?php
				unset( $box_bg_color );
				unset( $border_color );
			}
			$gurantee_css .= ob_get_clean();
		}


		return $gurantee_css;
	}

	public function wcst_on_footer() {

		$date_obj = new DateTime();
		//  $date_obj->setTimezone(new DateTimeZone(WCST_Common::get_site_timezone_string()));
		$date_obj->setTimestamp( current_time( 'timestamp' ) );
		$date_cutoff = $date_obj->modify( "- 5 minutes" )->format( "h:i a" );
		ob_start();
		?>
        <div style="display:none;" class="wcst_tb_content" id="wcst_guarantee_merge_tags_help">
            <!--            <div class="regex_def">-->
            <!---->
            <!--                A regular expression is a special text string for describing a search pattern. You can think of regular-->
            <!--                expressions as wildcards on steroids.-->
            <!--            </div>-->

            <br/>
            <div class="regex_help_text">


                Copy & Paste One or more merge tags to show advance messages under guarantee.
            </div>
            <br/>
            <div style="font-size: 1.3em; margin: 5px;"><strong>{{current_date}}</strong> accepts parameters <i>adjustment, cutoff & format.</i></div>


            <table class="table widefat">

                <thead>
                <tr>
                    <td>Output text</td>
                    <td>Input text</td>


                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>
                        Estimated Shipping Date:
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="F j"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        Estimated Shipping Date: <i>{{current_date format="F j"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        Estimated Shipping Date:
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="F j" adjustment="+1 days" cutoff="' . $date_cutoff . '"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        Estimated Shipping Date: <i>{{current_date format="F j" adjustment="+1 days" cutoff="<?php echo $date_cutoff; ?>"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        Estimated Delivery Date:
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date adjustment="+4 days" format="F j"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        Estimated Delivery Date: <i>{{current_date adjustment="+4 days" format="F j"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        Estimated Delivery Date:
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date adjustment="+5 days" cutoff="' . $date_cutoff . '" format="F j"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        Estimated Delivery Date: <i>{{current_date adjustment="+5 days" cutoff="<?php echo $date_cutoff; ?>" format="F
                            j"}}</i>
                    </td>

                </tr>
                </tbody>
            </table>
            <div style="font-style: italic; margin-top: 5px; margin-bottom: 20px;">
                Note: Scroll down to bottom to know about how you can change date formats.
            </div>

            <br/> <br/>

            <div style="font-size: 1.3em; margin: 5px;"><strong>{{current_day}}</strong> accepts parameters <i>adjustment & cutoff.</i></div>


            <table class="table widefat">

                <thead>
                <tr>
                    <td>Output text</td>
                    <td>Input text</td>


                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        Want it by
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_day adjustment="+4 days"}}', $this->slug ) ); ?></i>?
                        Order Now
                    </td>
                    <td>
                        Want it by <i>{{current_day adjustment="+4 days"}}</i>? Order Now

                    </td>

                </tr>

                </tbody>
            </table>
            <br/> <br/>

            <div style="font-size: 1.3em; margin: 5px;"><strong>{{today}}</strong> accepts parameter <i>cutoff</i>.</div>


            <table class="table widefat">

                <thead>
                <tr>
                    <td>Output text</td>
                    <td>Input text</td>


                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        Want it
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{today}}', $this->slug ) ); ?></i>?
                        Order Now
                    </td>
                    <td>
                        Want it <i>{{today}}</i>? Order Now

                    </td>

                </tr>
                <tr>
                    <td>
                        Want it
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{today cutoff="' . $date_cutoff . '"}}', $this->slug ) ); ?></i>?
                        Order Now
                    </td>
                    <td>
                        Want it <i>{{today cutoff="<?php echo $date_cutoff; ?>"}}</i>? Order Now

                    </td>

                </tr>

                </tbody>
            </table>
            <br/> <br/>


            <br/> <br/>

            <div style="font-size: 1.3em; margin: 5px;"><strong>{{cutoff_time_left}}</strong> accepts parameters <i>adjustment, cutoff & format.</i></div>


            <table class="table widefat">

                <thead>
                <tr>
                    <td>Output text</td>
                    <td>Input text</td>


                </tr>
                </thead>
                <tbody>


                <tr>
                    <td>
                        Want
                        it <?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{today cutoff="5:20 pm"}}', $this->slug ) ); ?>
                        ,
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="F j" cutoff="5:20 pm"}}', $this->slug ) ); ?> </i>?
                        Order within
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{cutoff_time_left cutoff="05:20 pm" format="%H hrs %i mins %s secs" timer="on"}}', $this->slug ) ); ?>
                            <i>.</td>
                    <td>
                        Want it <i>{{today cutoff="5:20 pm"}}</i>, <i>{{current_date cutoff="5:20 pm" format="F j"}}</i>?
                        Order within<i>{{cutoff_time_left
                            cutoff="05:20 pm" format="%H hrs %i mins %s secs" timer="on"}}</i>.
                    </td>

                </tr>

                <tr>
                    <td>
                        Next business day shipping if you order within
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{cutoff_time_left cutoff="05:20 pm" format="%H hrs %i mins %s secs" timer="on"}}', $this->slug ) ); ?>
                            <i>
                    <td>
                        Next business day shipping if you order within {{cutoff_time_left cutoff="05:20 pm" format="%H
                        hrs %i mins %s secs" timer="on"}}<i>

                    </td>

                </tr>
                <tr>
                    <td>
                        Independence Day Special Free Shipping for Next
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{cutoff_time_left cutoff="2017-05-03 11:59 pm" format="%D Days %H hrs and %i minutes" timer="on"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        Independence Day Special Free Shipping for Next <i>{{cutoff_time_left cutoff="2017-05-03 11:59
                            pm"
                            format="%D Days %H hrs and %i minutes" timer="on" }}</i>
                    </td>


                </tr>
                <tr>

                    <td>
                        Ships today if you order in next
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{cutoff_time_left cutoff="07:00 pm" format="%H hours"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>

                        Ships today if you order in next <i>{{cutoff_time_left cutoff="07:00 pm" format="%H hours"}}</i>
                    </td>
                </tr>

                <tr>
                    <td>
                        Order in the next
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{cutoff_time_left cutoff="07:00 pm" format="%H hrs %i mins %s secs" timer="on"}}', $this->slug ) ); ?></i>
                        and get it by
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date adjustment="+4 days"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        Order in the next <i>{{cutoff_time_left cutoff="07:00 pm" format="%H hrs %i mins %s secs"
                            timer="on"}}</i> and get it by <i>{{current_date adjustment="+4 days"}}</i>
                    </td>

                </tr>

                <tr>
                    <td>
                        Order in the next
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{cutoff_time_left cutoff="07:00 pm" format="%H hrs %i mins %s secs" timer="on"}}', $this->slug ) ); ?> </i>and
                        get it by
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_time adjustment="+24 hours"}}', $this->slug ) ); ?></i>
                        Tomorrow
                    </td>
                    <td>
                        Order in the next <i>{{cutoff_time_left cutoff="07:00 pm" format="%H hrs %i mins %s secs"
                            timer="on"}}</i> and get it by <i>{{current_time adjustment="+24 hours"}}</i> Tomorrow

                    </td>

                </tr>

                <tr>
                    <td>
                        Want it tomorrow,
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date adjustment="+1 days" format="F j"}}', $this->slug ) ); ?> </i>?
                        Order within
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{cutoff_time_left cutoff="05:20 pm" format="%H hrs %i mins %s secs" timer="on"}}', $this->slug ) ); ?>
                            <i> and choose One-Day Shipping
                                at checkout.</td>
                    <td>
                        Want it tomorrow, <i>{{current_date adjustment="+1 days" format="F j"}}</i>? Order in the next
                        <i>{{cutoff_time_left
                            cutoff="05:20 pm" format="%H hrs %i mins %s secs" timer="on"}}</i> and choose One-Day
                        Shipping at checkout.
                    </td>

                </tr>


                </tbody>
            </table>
            <br/><br/>
            <div style="font-size: 1.3em; margin: 5px;"><strong>Date Formats</strong></div>


            <table class="table widefat">

                <thead>
                <tr>
                    <td>Output text</td>
                    <td>Input text</td>


                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="F j"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="F j"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="F jS"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="F jS"}}</i>
                    </td>

                </tr>


                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="j M"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="j M"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="jS M"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="jS M"}}</i>
                    </td>

                </tr>


                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="M j"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="M j"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="M jS"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="M jS"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="M j Y"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="M j Y"}}</i>
                    </td>

                </tr>
                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="M jS Y"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="M jS Y"}}</i>
                    </td>

                </tr>


                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="d/m/Y"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="d/m/Y"}}</i>
                    </td>

                </tr>

                <tr>
                    <td>
                        <i><?php echo do_shortcode( WCST_Merge_Tags::maybe_parse_merge_tags( '{{current_date format="d-m-Y"}}', $this->slug ) ); ?></i>
                    </td>
                    <td>
                        <i>{{current_date format="d-m-Y"}}</i>
                    </td>

                </tr>

                </tbody>
            </table>


            <!--            <div style="font-style: italic; margin-top: 5px; margin-bottom: 20px;">-->
            <!--                <a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Click here</a> to know-->
            <!--                about date/time formats.-->
            <!--            </div>-->


        </div>
		<?php
		echo ob_get_clean();
	}

}

WCST_Triggers::register( new WCST_Trigger_Guarantee() );
                                                            