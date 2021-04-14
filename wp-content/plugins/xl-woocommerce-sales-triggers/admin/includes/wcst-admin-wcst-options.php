<?php

class WCST_Admin_WCST_Post_Options {

	protected static $options_data = false;

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private static $key = 'wcst_post_option';

	/**
	 * Options page metabox id
	 * @var string
	 */
	private static $metabox_id = 'wcst_post_option_metabox';

	/**
	 * Setting Up CMB2 Fields
	 */
	public static function setup_fields() {

		add_filter( 'wcst_modify_field_config_wcst', array( __CLASS__, 'modify_field_config_wcst_post' ), 99, 2 );

		$box_options = array(
			'id'           => 'wcst_post_option',
			'title'        => __( 'Trigger Settings', WCST_SLUG ),
			'classes'      => 'wcst_cmb2_wrapper wcst_options_common',
			'object_types' => array( WCST_Common::get_trigger_post_type_slug() ),
			'show_names'   => true,
		);

		// Setup meta box
		$cmb = new_cmb2_box( $box_options );


		$box_options2 = array(
			'id'           => 'wcst_post_showon_option_',
			'title'        => __( 'Visibility', WCST_SLUG ),
			'classes'      => 'wcst_cmb2_wrapper',
			'context'      => 'side', //  'normal', 'advanced', or 'side'
			'priority'     => '',
			'object_types' => array( WCST_Common::get_trigger_post_type_slug() ),
			'show_names'   => true,
		);

		// Setup meta box
		$cmb2 = new_cmb2_box( $box_options2 );

		$cmb2->add_field( array(
			'name'    => __( 'Show On Single Product', WCST_SLUG ),
			'id'      => '_wcst_data_showon_product',
			'type'    => 'radio_inline',
			'default' => 'yes',
			'options' => array(
				'yes' => __( 'Yes ', WCST_SLUG ),
				'no'  => __( 'No', WCST_SLUG ),
			)

		) );
		$cmb2->add_field( array(
			'name'             => __( 'Position', WCST_SLUG ),
			'id'               => '_wcst_data_position',
			'show_option_none' => false,
			'type'             => 'select',
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

			'attributes' => array(
				'data-conditional-id'    => '_wcst_data_showon_product',
				'data-conditional-value' => 'yes',

			)

		) );
		$cmb2->add_field( array(
				'name'        => __( '', WCST_SLUG ),
				'id'          => '_wcst_data_position_help',
				'type'        => 'wcst_html_content_field',
				'row_classes' => array( 'wcst_p0' ),
				'content'     => __( "  <p class='cmb2-metabox-description'>Unable to get positions to work? <a target='_blank' href='https://xlplugins.com/support/?pro=xl-woocommerce-sales-triggers&utm_source=wpplugin&utm_campaign=woo_sales_triggers&utm_medium=resources&utm_term=support'>Raise a support ticket</a> </p>", WCST_SLUG ),
				'options'     => array(),
				'attributes'  => array(
					'data-conditional-id'    => '_wcst_data_showon_product',
					'data-conditional-value' => 'yes',

				)
			)

		);
//        $cmb2->add_field(array(
//            'name' => __('Show On Cart', WCST_SLUG),
//            'id' => '_wcst_data_showon_cart',
//            'type' => 'radio_inline',
//            'default' => 'no',
//            'options' => array(
//                'yes' => __('Yes ', WCST_SLUG),
//                'no' => __('No', WCST_SLUG),
//            )
//
//        ));

//        $cmb2->add_field(
//            array(
//                'name' => __('Show On Grid', WCST_SLUG),
//                'id' => '_wcst_data_showon_grid',
//                'type' => 'radio_inline',
//                'desc' => __('We have rigorously tested this feature but some themes may heavily modify native WooCommerce grid structure.Some triggers may not render on Product Listing Pages.You can place shortcodes in your template file usually found under Theme> woocommerce> content-product.php. If you do not know how to place this shortcode please <a href="https://xlplugins.com/support/?pro=xl-woocommerce-sales-triggers&utm_source=wpplugin&utm_campaign=woo_sales_triggers&utm_medium=resources&utm_term=support" target="_blank">raise a support ticket</a> ',WCST_SLUG),
//
////                'desc' => __('Note: Some themes may have customized design for product grids. <br/> If you found any issues on grid  <a href="https://xlplugins.com/support/?pro=xl-woocommerce-sales-triggers&utm_source=wpplugin&utm_campaign=woo_sales_triggers&utm_medium=resources&utm_term=support" target="_blank">Raise A Ticket</a>.',WCST_SLUG),
//                'default' => 'no',
//                'options' => array(
//                    'yes' => __('Yes ', WCST_SLUG),
//                    'no' => __('No', WCST_SLUG),
//                )
//
//            ));


		$box_options3 = array(
			'id'           => 'wcst_post_shortcode_',
			'title'        => __( 'Shortcode', WCST_SLUG ),
			'classes'      => 'wcst_cmb2_wrapper',
			'context'      => 'side', //  'normal', 'advanced', or 'side'
			'priority'     => '',
			'object_types' => array( WCST_Common::get_trigger_post_type_slug() ),
			'show_names'   => true,
		);
		$cmb3         = new_cmb2_box( $box_options3 );
		$cmb3->add_field( array(
			'name'        => __( '', WCST_SLUG ),
			'id'          => '_wcst_data_choose_trigger',
			'description' => __( '', WCST_SLUG ),
			'type'        => 'wcst_html_content_field',
			'content'     => "<p class='cmb2-metabox-description'>
  <a class= \"button button-primary\" style=' font-style: normal; color: #fff;' href=\"javascript:void(0);\" onclick=\"wcst_show_tb('Shortcode','wcst_shortcode_help_box');\">Generate ShortCodes</a> </p><p class='cmb2-metabox-description'>Click the button to get the shortcode for the Trigger. Learn more about <a href='https://xlplugins.com/documentation/woocommerce-sales-trigger/shortcodes/#usage' target='_blank'>shortcode usage</a>.</p>
 ",
			'options'     => array(),
		) );


		$box_options4 = array(
			'id'           => 'wcst_post_menu_order_',
			'title'        => __( 'Trigger Priority', WCST_SLUG ),
			'classes'      => '',
			'classes'      => 'wcst_cmb2_wrapper',
			'context'      => 'side', //  'normal', 'advanced', or 'side'
			'priority'     => '',
			'object_types' => array( WCST_Common::get_trigger_post_type_slug() ),
			'show_names'   => true,
		);
		$cmb4         = new_cmb2_box( $box_options4 );
		$cmb4->add_field( array(
			'name'       => __( 'Priority No.', WCST_SLUG ),
			'id'         => '_wcst_data_menu_order',
			'desc'       => __( "Priority works in ascending order. Lower the priority, higher the chance for campaign to work. <br/> For Eg: If there are two campaigns A & B with respective priority of 1 & 2, then campaign A will be executed before campaign B.  ", WCST_SLUG ),
			'default'    => 0,
			'type'       => 'text',
			'options'    => array(),
			'attributes' => array(
				'type'    => 'number',
				'min'     => '0',
				'pattern' => '\d*',

			),
		) );


		$cmb->add_field( array(
			'name'             => __( 'Choose Your trigger', WCST_SLUG ),
			'id'               => '_wcst_data_choose_trigger',
			'show_option_none' => false,
			'type'             => 'select',

			'options' => array(),
		) );


		$get_triggers = apply_filters( 'wcst_some_hook_name_here_1', WCST_Triggers::get_all() );

		foreach ( $get_triggers as $key => $trigger ) {

			foreach ( $trigger->get_instance_settings() as $fields ) {
				$cmb->add_field( apply_filters( 'wcst_modify_field_config_wcst', $fields, $trigger ) );
			}
		}

		$cmb = new_cmb2_box( array(
			'id'         => 'wcst_global_options_box',
			'hookup'     => false,
			'cmb_styles' => false,
			'wcst_tabs'  => array(
				'text'         => array(
					'label' => __( 'Translation Texts', WCST_SLUG ),
				),
				'order_status' => array(
					'label' => __( 'Order Status', WCST_SLUG ),
				),
				'settings'     => array(
					'label' => __( 'Miscellaneous', WCST_SLUG ),
				),
			),
			'tab_style'  => 'default',
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'wcst' )
			),
		) );

		$cmb->add_field( array(
			'id'            => '_wcst_gb_settings_head1',
			'type'          => 'wcst_html_content_field',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'content'       => '<h3 style="margin-top:0" class="wcst_heading_mb">' . __( 'Change text strings in your preferred language.', WCST_SLUG ) . '</h3>',
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "from"', WCST_SLUG ),
			'desc'          => __( 'Kiera <strong>from</strong> Golborne & 1 other bought this item recently.', WCST_SLUG ),
			'id'            => 'wcst_global_replace_from',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'from'
		) );

		$cmb->add_field( array(
			'name'          => __( 'Text "in"', WCST_SLUG ),
			'desc'          => __( '#2 Best Seller <strong>in</strong> Clothing', WCST_SLUG ),
			'id'            => 'wcst_global_replace_in',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'in'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "&"', WCST_SLUG ),
			'desc'          => __( 'Kiera from Golborne <strong>&</strong> 1 other bought this item recently.', WCST_SLUG ),
			'id'            => 'wcst_global_replace_&',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => '&'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "Someone"', WCST_SLUG ),
			'desc'          => __( '<strong>Someone</strong> from Golborne & 1 other bought this item recently.', WCST_SLUG ),
			'id'            => 'wcst_global_replace_someone',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'Someone'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "other" singular', WCST_SLUG ),
			'desc'          => __( 'Kiera from Golborne & 1 <strong>other</strong> bought this item recently.', WCST_SLUG ),
			'id'            => 'wcst_global_replace_other',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'other'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "others" plural', WCST_SLUG ),
			'desc'          => __( 'Kiera from Golborne & 2 <strong>others</strong> bought this item recently.', WCST_SLUG ),
			'id'            => 'wcst_global_replace_others',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'others'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "month" singular', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1 <strong>month</strong> ', WCST_SLUG ),
			'id'            => 'wcst_global_replace_month',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'month'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "months" plural', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 2 <strong>months</strong> ', WCST_SLUG ),
			'id'            => 'wcst_global_replace_months',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'months'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "week" singular', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1 <strong>week</strong> ', WCST_SLUG ),
			'id'            => 'wcst_global_replace_week',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'week'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "weeks" plural', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 2 <strong>weeks</strong> ', WCST_SLUG ),
			'id'            => 'wcst_global_replace_weeks',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'weeks'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "day" singular', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1 <strong>day</strong>', WCST_SLUG ),
			'id'            => 'wcst_global_replace_day',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'day'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "days" plural', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 2 <strong>days</strong>', WCST_SLUG ),
			'id'            => 'wcst_global_replace_days',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'days'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "hr" singular', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1 <strong>hr</strong> 32 mins 01 secs', WCST_SLUG ),
			'id'            => 'wcst_global_replace_hr',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'hr'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "hrs" plural', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 2 <strong>hrs</strong> 32 mins 01 secs', WCST_SLUG ),
			'id'            => 'wcst_global_replace_hrs',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'hrs'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "min" singular', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1hr 01<strong>min</strong> 01 secs', WCST_SLUG ),
			'id'            => 'wcst_global_replace_min',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'mins'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "mins" plural', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1hr 02<strong>mins</strong> 01 secs', WCST_SLUG ),
			'id'            => 'wcst_global_replace_mins',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'mins'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "sec" singular', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1hr 32 mins 01<strong>sec</strong>', WCST_SLUG ),
			'id'            => 'wcst_global_replace_sec',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'sec'
		) );
		$cmb->add_field( array(
			'name'          => __( 'Text "secs" plural', WCST_SLUG ),
			'desc'          => __( 'Sale Ends in 1hr 32 mins 03<strong>secs</strong>', WCST_SLUG ),
			'id'            => 'wcst_global_replace_secs',
			'type'          => 'text_small',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'default'       => 'secs'
		) );
		$cmb->add_field( array(
			'name'          => __( 'nonce', WCST_SLUG ),
			'id'            => '_wpnonce',
			'type'          => 'hidden',
			'tab'           => 'text',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'attributes'    => array(
				'value' => wp_create_nonce( 'woocommerce-settings' )
			)
		) );

		$cmb->add_field( array(
			'id'            => '_wcst_gb_tab2_head',
			'type'          => 'wcst_html_content_field',
			'tab'           => 'order_status',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'content'       => '<h3 style="margin-top:0" class="wcst_heading_mb">' . __( 'Select order status to include in order\'s calculation for Sales Snippet or Count trigger.', WCST_SLUG ) . '</h3>',
		) );
		$cmb->add_field( array(
			'id'            => 'wcst_global_replace_order_status',
			'type'          => 'multicheck',
			'tab'           => 'order_status',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'options_cb'    => array( __CLASS__, 'wcst_oder_status_checkboxes_callback_function' ),
			'default'       => WCST_Common::wcst_set_default_wc_states(),
		) );

		$cmb->add_field( array(
			'id'            => '_wcst_gb_tab3_head',
			'type'          => 'wcst_html_content_field',
			'tab'           => 'settings',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'content'       => '<h3 style="margin-top:0" class="wcst_heading_mb">' . __( 'Miscellaneous Settings', WCST_SLUG ) . '</h3>',
		) );
		$cmb->add_field( array(
			'name'          => __( 'Show Default Reviews Count text', WCST_SLUG ),
			'desc'          => __( 'Text aside product star rating on single product page<br/>(xx customer reviews)', WCST_SLUG ),
			'id'            => 'wcst_global_show_reviews_text',
			'type'          => 'radio_inline',
			'tab'           => 'settings',
			'render_row_cb' => array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' ),
			'options'       => array(
				'yes' => __( 'Yes', WCST_SLUG ),
				'no'  => __( 'No', WCST_SLUG ),
			),
			'default'       => 'no',
		) );

	}

	/**
	 * Rendering order statuses
	 */
	public static function wcst_oder_status_checkboxes_callback_function() {
		return wc_get_order_statuses();
	}

	/**
	 * Hooked over `wcst_modify_field_config_products`
	 * Modify field args before passing it to cmb2
	 *
	 * @param $tabs_settings Field Arguments
	 *
	 * @return mixed
	 */
	public static function modify_field_config_wcst_post( $field_settings, $trigger ) {

		$clone_settings = $field_settings;
		$get_defaults   = $trigger->get_defaults();


		foreach ( $get_defaults as $key => $value ) {


			$get_key = $trigger->get_meta_prefix() . $key;

			if ( $clone_settings['id'] == $get_key ) {
				$clone_settings['default'] = $value;
			}
		}


		return $clone_settings;
	}

	/**
	 * Getting Default config from the saved values in wp_options
	 * Getter function for config for each field
	 *
	 * @param $key
	 * @param int $index
	 *
	 * @return string
	 */
	public static function get_default_config( $key, $index = 0 ) {

		if ( is_array( $key ) ) {

			if ( $key[1] == "mode" ) {
				return "0";
			}

			return ( isset( self::$options_data[ $key[0] ][ $index ][ $key[1] ] ) ) ? self::$options_data[ $key[0] ][ $index ][ $key[1] ] : "";
		} else {
			if ( $key == "mode" ) {
				return "0";
			}

			return ( isset( self::$options_data[ $key ] ) ) ? self::$options_data[ $key ] : "";
		}
	}

	/**
	 * Setting up property `options_data` by options data saved.
	 */
	public static function prepere_default_config() {
		self::$options_data = WCST_Common::get_default_settings();
	}


	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== 'wcst' || empty( $updated ) ) {
			return;
		}
		add_settings_error( 'wcst-notices', '', __( 'Settings updated.', WCST_SLUG ), 'updated' );
		settings_errors( 'wcst-notices' );
	}

}
