<?php

class WCST_Admin_Product_Options {

	protected static $options_data = false;

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private static $key = 'wcst_product_option';

	/**
	 * Options page metabox id
	 * @var string
	 */
	private static $metabox_id = 'wcst_product_option_metaboox';

	/**
	 * Setting Up CMB2 Fields
	 */
	public static function setup_fields() {
		add_filter( 'wcst_modify_field_config_products', array( __CLASS__, 'add_extra_tab_for_need_help' ), 98 );
		add_filter( 'wcst_modify_field_config_product', array( __CLASS__, 'modify_field_config_products' ), 99, 2 );

		$tabs_setting['tabs'] = array();

		$get_triggers = apply_filters( 'wcst_some_hook_name_here_2', WCST_Triggers::get_triggers() );

		$current = 0;
		foreach ( $get_triggers as $key => $triggers ) {


			$tabs_setting['tabs'][ $current ] = array(
				'id'     => $key,
				'title'  => $triggers['name'],
				'fields' => array()
			);
			foreach ( $triggers['triggers'] as $trigger ) {


				$get_trigger_object = WCST_Triggers::get( $trigger['slug'] );

				foreach ( $get_trigger_object->get_post_settings() as $fields ) {

					$tabs_setting['tabs'][ $current ]['fields'][] = apply_filters( 'wcst_modify_field_config_product', $fields, $get_trigger_object );
				}
			}
			$current ++;
		}
		// set tabs
		$tabs_setting           = apply_filters( 'wcst_modify_field_config_products', $tabs_setting );
		$tabs_setting_key_value = array();
		foreach ( $tabs_setting['tabs'] as $key1 => $value1 ) {
			$tabs_setting_key_value[ $value1['id'] ] = array(
				'label' => __( $value1['title'], 'cmb2' ),
			);
		}

		$box_options = array(
			'id'           => 'wcst_product_option_tabs',
			'title'        => __( 'Trigger Settings', WCST_SLUG ),
			'classes'      => 'wcst_cmb2_wrapper wcst_options_common',
			'object_types' => array( 'product' ),
			'show_names'   => true,
			'wcst_tabs'    => $tabs_setting_key_value,
			'tab_style'    => 'default',
		);
		$cmb         = new_cmb2_box( $box_options );


		foreach ( $tabs_setting['tabs'] as $key1 => $value1 ) {
			if ( is_array( $value1['fields'] ) && count( $value1['fields'] ) > 0 ) {
				foreach ( $value1['fields'] as $key2 => $value2 ) {
					$value2['tab'] = $value1['id'];

					if ( 'group' === $value2['type'] ) {
						$value2['render_row_cb'] = array( 'CMB2_WCST_Tabs', 'tabs_render_group_row_cb' );

					} else {
						$value2['render_row_cb'] = array( 'CMB2_WCST_Tabs', 'tabs_render_row_cb' );

					}
					$cmb->add_field( $value2 );
				}
			}
		}
	}

	/**
	 * Hooked over `wcst_modify_field_config_products`
	 * Modify field args before passing it to cmb2
	 *
	 * @param $tabs_settings Field Arguments
	 *
	 * @return mixed
	 */
	public static function modify_field_config_products( $field_settings, $trigger ) {
		$clone_settings = $field_settings;

		$get_defaults = $trigger->get_defaults();

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

	public static function add_extra_tab_for_need_help( $tabs ) {
		$need_help_content = '<div class="wcst_acc_head_custom_tab"><div class="custom_inner">' . __( 'Need Help?', WCST_SLUG ) . '</div></div>';
		ob_start();
		?>
        <p>Need help with plugin set-up? <a
                    href="https://xlplugins.com/documentation/woocommerce-sales-trigger/?utm_source=wpplugin&utm_campaign=woo_sales_triggers&utm_medium=resources&utm_term=documentation"
                    target="_blank">Read Documentation</a>.</p>
        <p>Figured out a bug or need help with set up? <a
                    href="https://xlplugins.com/support/?pro=xl-woocommerce-sales-triggers&utm_source=wpplugin&utm_campaign=woo_sales_triggers&utm_medium=resources&utm_term=support"
                    target="_blank">Raise A Ticket</a>.</p>
        <p>Got an idea or a feature request? <a
                    href="https://xlplugins.com/contact/?pro=xl-woocommerce-sales-triggers&utm_source=wpplugin&utm_campaign=woo_sales_triggers&utm_medium=resources&utm_term=new-feature"
                    target="_blank">Get in touch</a>.</p>
		<?php

		$need_help_content .= ob_get_clean();
		array_push( $tabs['tabs'], array(
			'id'       => 'wcst_need_help',
			'title'    => __( 'Need Help?', WCST_SLUG ),
			'position' => 99,
			'fields'   => array(
				array(
					'id'      => '_wcst_data_need_help_content',
					'type'    => 'wcst_html_content_field',
					'content' => $need_help_content,
				),
			)
		) );

		return $tabs;
	}

	/**
	 * Setting up property `options_data` by options data saved.
	 */
	public static function prepere_default_config() {
		self::$options_data = WCST_Common::get_default_settings();
	}

}
