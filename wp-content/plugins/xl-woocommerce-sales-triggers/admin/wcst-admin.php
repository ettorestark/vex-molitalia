<?php

class WCST_Admin {

	protected static $instance = null;
	protected static $default;

	/**
	 * WCST_Admin constructor.
	 */
	public function __construct() {
		$this->setup_default();
		$this->includes();
		$this->hooks();
	}

	/**
	 *
	 */
	public static function setup_default() {
		self::$default = WCST_Common::get_default_settings();
	}

	/**
	 * Include files
	 */
	public function includes() {
		/**
		 * Loading dependencies
		 */
		include_once $this->get_admin_uri() . "includes/cmb2/init.php";

		include_once $this->get_admin_uri() . "includes/cmb2-addons/tabs/CMB2-WCST-Tabs.php";
		include_once $this->get_admin_uri() . "includes/cmb2-addons/switch/switch.php";

		include_once $this->get_admin_uri() . "includes/cmb2-addons/conditional/cmb2-conditionals.php";

		/**
		 * Loading custom classes for product and option page.
		 */
		include_once $this->get_admin_uri() . "includes/wcst-admin-cmb2-support.php";
		include_once $this->get_admin_uri() . "includes/wcst-admin-product-options.php";
		include_once $this->get_admin_uri() . "includes/wcst-admin-wcst-options.php";
	}

	/**
	 * Get Admin path
	 * @return string plugin admin path
	 */
	public function get_admin_uri() {
		return plugin_dir_path( WCST_PLUGIN_FILE ) . "/admin/";
	}

	public function hooks() {


		/**
		 * Adding meta info to product when no meta available for repeaters only
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'wcst_add_repeater_meta_on_first_load' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wcst_add_global_settings_on_first_load' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'wcst_post_wcst_load_assets' ), 100 );

		/**
		 * Running product meta info setup
		 */
		add_filter( 'cmb2_init', array( $this, 'wcst_add_options_product_metabox' ) );
		add_filter( 'cmb2_init', array( $this, 'wcst_add_cmb2_multiselect' ) );

		/**
		 * Running product meta info setup
		 */
		add_filter( 'cmb2_init', array( $this, 'wcst_add_options_wcst_metabox' ) );

		/**
		 * Running order status info checkbox setup
		 */
		// add_filter( 'cmb2_init', array( $this, 'wcst_add_order_status_options_wcst_metabox' ) );


		/**
		 * Loading js and css
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'wcst_enqueue_admin_assets' ), 20 );


		/**
		 * Loading cmb2 assets
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'cmb2_load_toggle_button_assets' ), 20 );

		/**
		 * Allowing conditionals to work on custom page
		 */
		add_filter( 'xl_cmb2_add_conditional_script_page', array( 'WCST_Admin_CMB2_Support', 'wcst_push_support_form_cmb_conditionals' ) );


		/**
		 * Handle tabs ordering
		 */
		add_filter( 'wcst_cmb2_modify_field_tabs', array( $this, 'wcst_admin_reorder_tabs' ), 99 );

		/**
		 * Adds HTML field to cmb2 config
		 */
		add_action( 'cmb2_render_wcst_html_content_field', array( $this, 'wcst_html_content_fields' ), 10, 5 );
		add_action( 'cmb2_render_wcst_multiselect', array( $this, 'wcst_multiselect' ), 10, 5 );

		/**
		 * Keeping meta box open
		 */
		add_filter( 'postbox_classes_product_wcst_product_option_tabs', array( $this, 'wcst_metabox_always_open' ) );

		/**
		 * Pushing Deactivation For XL Core
		 */
		add_filter( 'plugin_action_links_' . WCST_PLUGIN_BASENAME, array( $this, 'wcst_plugin_actions' ) );

		/**
		 * Adding New Tab in WooCommerce Settings API
		 */
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'modify_woocommerce_settings' ), 99 );

		/**
		 * Adding Customer HTML On setting page for WooCommerce
		 */
		add_action( 'woocommerce_settings_' . WCST_Common::get_wc_settings_tab_slug(), array( $this, 'wcst_woocommerce_options_page' ) );

		/**
		 * Modifying Publish meta box for our posts
		 */
		add_action( 'post_submitbox_misc_actions', array( $this, 'wcst_post_publish_box' ) );

		/**
		 * Adding `Return To` Notice Out Post Pages
		 */
		add_action( 'edit_form_top', array( $this, 'wcst_edit_form_top' ) );

		/**
		 * Adding Optgroup to trigger selects
		 */
		add_filter( 'cmb2_select_attributes', array( 'WCST_Admin_CMB2_Support', 'cmb_opt_groups' ), 10, 4 );

		/**
		 * Modifying Post update messages
		 */
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

		/**
		 * Hooks to check if activation and deactivation request for post.
		 */
		add_action( 'admin_init', array( $this, 'maybe_activate_post' ) );
		add_action( 'admin_init', array( $this, 'maybe_deactivate_post' ) );
		add_action( 'admin_init', array( $this, 'maybe_delete_all' ) );

		add_action( 'admin_init', array( $this, 'maybe_duplicate_post' ) );


		/**
		 * Saving priority order
		 */
		add_action( 'save_post', array( $this, 'save_post_trigger_callback' ), 10, 2 );

		/**
		 * handling for transients on save post
		 */
		add_action( 'wcst_cpt_save_post', array( $this, 'reset_transients' ), 10 );

		/**
		 * handling for transients on save post
		 */
		add_action( 'wcst_cpt_product_save_post', array( $this, 'wc_product_save' ), 10, 2 );


		/**
		 * Add text for guarantee help popup
		 */
		add_action( 'admin_footer', array( $this, 'wcst_add_guarantee_text' ) );

		add_action( 'admin_init', array( $this, 'wcst_remove_plugin_update_transient' ), 10 );

		add_action( 'delete_post', array( $this, 'clear_transients_on_delete' ), 10, 1 );

		/** Admin notification when plugin update is available. */
		add_filter( 'admin_notices', array( $this, 'maybe_show_advanced_update_notification' ), 999 );

		/**
		 * CMB2 AFTER SAVE METADATA HOOK
		 */
		add_action( 'cmb2_save_post_fields_wcst_post_option', array( $this, 'clear_transients' ), 1000 );
	}

	/**
	 * Return an instance of this class.
	 * @since     1.0.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		if ( ! is_super_admin() ) {
			return;
		}
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Hooked over Activation
	 * Checks and insert plugin options(data)  in wp_options
	 */
	public static function handle_activation() {

		/** Optin true on plugin activation */
		update_option( 'xl_is_opted', 'yes', false );

		$ids = get_option( 'wcst_posts_ids', array() );

		if ( ! is_array( $ids ) ) {
			return;
		}
		$ids_array = array();
		$i         = 0;
		foreach ( WCST_Triggers::get_triggers() as $triggers ) {
			foreach ( $triggers['triggers'] as $trigger ) {

				if ( $i !== 0 ) {
					continue;
				}
				if ( $ids && is_array( $ids ) && array_key_exists( $trigger['slug'], $ids ) ) {
					continue;
				}
				$trigger_obj = WCST_Triggers::get( $trigger['slug'] );
				$id          = wp_insert_post( array(
					'post_type'   => WCST_Common::get_trigger_post_type_slug(),
					'post_title'  => __( $trigger['name'], WCST_SLUG ),
					'post_status' => WCST_Common::get_trigger_post_type_slug() . 'disabled',
					'menu_order'  => $trigger_obj->default_priority
				) );


				$get_defaults = $trigger_obj->get_defaults();
				foreach ( $get_defaults as $key => $val ) {

					if ( 'position' == $key ) {
						continue;
					}
					update_post_meta( $id, $trigger_obj->get_meta_prefix() . $key, $val );
				}

				update_post_meta( $id, '_wcst_data_choose_trigger', $trigger['slug'] );
				update_post_meta( $id, '_wcst_data_showon_grid', 'no' );
				update_post_meta( $id, '_wcst_data_showon_cart', 'no' );
				update_post_meta( $id, '_wcst_data_showon_product', 'yes' );
				update_post_meta( $id, '_wcst_data_position', $get_defaults['position'] );
				update_post_meta( $id, '_wcst_data_menu_order', $trigger_obj->default_priority );

				$ids_array[ $trigger['slug'] ] = $id;
			}
		};

		if ( count( $ids_array ) > 0 ) {
			$combined = array_merge( $ids, $ids_array );
			update_option( 'wcst_posts_ids', array_unique( $combined ), false );
		}
	}

	/**
	 * Sorter function to sort array by internal key called priority
	 *
	 * @param type $a
	 * @param type $b
	 *
	 * @return int
	 */
	public static function _sort_by_priority( $a, $b ) {

		if ( $a['position'] == $b['position'] ) {
			return 0;
		}

		return ( $a['position'] < $b['position'] ) ? - 1 : 1;
	}

	public static function add_metaboxes() {
		add_meta_box( 'wcst_rules', 'Rules', array( __CLASS__, 'rules_metabox' ), WCST_Common::get_trigger_post_type_slug(), 'normal', 'high' );
	}

	public static function rules_metabox() {
		include_once 'views/metabox-rules.php';
	}

	/**
	 * Registering metaboxes config on init
	 */
	public function wcst_add_options_product_metabox() {
		wcst_Admin_Product_Options::prepere_default_config();
		wcst_Admin_Product_Options::setup_fields();
	}

	/**
	 * Registering metaboxes config on init
	 */
	public function wcst_add_options_wcst_metabox() {
		WCST_Admin_WCST_Post_Options::prepere_default_config();
		WCST_Admin_WCST_Post_Options::setup_fields();
	}

	/**
	 * Registering metabox config on cmb2_init to create order status checkboxes
	 */

	/*    public function wcst_add_options_wcst_metabox() {
			WCST_Admin_WCST_Post_Options::wcst_add_order_status_options_wcst_metabox();
			WCST_Admin_WCST_Post_Options::setup_fields();
		}*/

	/**
	 * Render options for woocommerce custom option page
	 */
	public function wcst_woocommerce_options_page() {


		if ( filter_input( INPUT_GET, 'section' ) == "settings" ) {
			?>
            <div class="notice">
                <p><?php _e( 'Back to <a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) . '">XL Sales Triggers</a> listing.', WCST_SLUG ); ?></p>
            </div>
            <div class="wrap wcst_global_option">
                <h1 class="wp-heading-inline"><?php echo __( "Settings", WCST_SLUG ); ?></h1>
                <div id="poststuff">
                    <div class="inside">
                        <div class="wcst_options_page_col2_wrap">
                            <div class="wcst_options_page_left_wrap">
                                <div class="postbox">
                                    <div class="inside">
										<?php cmb2_metabox_form( 'wcst_global_options_box', "wcst_global_options" ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="wcst_options_page_right_wrap">
								<?php do_action( 'wcst_options_page_right_content' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		} else {
			require_once( $this->get_admin_uri() . 'includes/wcst-post-table.php' );
			?>
            <style>
                body {
                    position: relative;
                    height: auto;
                }
            </style>
            <div class="wrap cmb2-options-page wcst_global_option wcst_listing_view">
                <h1 class="wp-heading-inline"><?php echo __( "Sales Triggers", WCST_SLUG ); ?></h1>
                <a href="<?php echo admin_url( 'post-new.php?post_type=' . WCST_Common::get_trigger_post_type_slug() ) ?>"
                   class="page-title-action">Add New Trigger</a>
                <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=xl-sales-trigger&section=settings' ); ?>"
                   class="page-title-action">Settings</a>
                <br/>
                <div id="poststuff">
                    <div class="inside">
                        <div class="inside">
                            <div class="wcst_options_page_col2_wrap">
                                <div class="wcst_options_page_left_wrap">
									<?php
									WCST_Admin_CMB2_Support::render_trigger_nav();
									$table       = new WCST_Post_Table();
									$table->data = WCST_Common::get_post_table_data( WCST_Admin_CMB2_Support::get_current_trigger() );
									$table->prepare_items();
									$table->display();
									?>
                                </div>
                                <div class="wcst_options_page_right_wrap">
									<?php do_action( 'wcst_options_page_right_content' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	/**
	 * Loading additional assets for toggle/switch button
	 */
	public function cmb2_load_toggle_button_assets() {
		wp_enqueue_style( 'cmb2_switch-css', $this->get_admin_url() . 'includes/cmb2-addons/switch/switch_metafield.css', false, WCST_VERSION );
		//CMB2 Switch Styling
		wp_enqueue_script( 'cmb2_switch-js', $this->get_admin_url() . 'includes/cmb2-addons/switch/switch_metafield.js', '', WCST_VERSION, true );
	}

	/**
	 * Get Admin path
	 * @return string plugin admin path
	 */
	public function get_admin_url() {
		return plugin_dir_url( WCST_PLUGIN_FILE ) . "admin/";
	}

	/**
	 * Hooked over `admin_enqueue_scripts`
	 * Enqueue scripts and css to wp-admin
	 */
	public function wcst_enqueue_admin_assets() {

		$screen = get_current_screen();

		if ( is_object( $screen ) && ( $screen->base == 'woocommerce_page_wc-settings' || ( $screen->base == 'post' && ( $screen->post_type == WCST_Common::get_trigger_post_type_slug() || $screen->post_type == 'product' ) ) ) ) {
			wp_enqueue_style( 'wcst_admin-css', $this->get_admin_url() . 'assets/css/wcst_admin_style.css', false, WCST_VERSION );
			wp_enqueue_style( 'wcst_ecom_fonts', plugin_dir_url( WCST_PLUGIN_FILE ) . 'assets/css/woothemes_ecommerce.css', array(), WCST_VERSION );
			$frontUrl = plugin_dir_url( WCST_PLUGIN_FILE );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-sortable' );

			wp_enqueue_script( 'wcst_countdown', $frontUrl . 'assets/js/jquery.countdown.min.js', array( 'jquery' ), '2.2.0', true );
			wp_enqueue_script( 'wcst_humanized_time', $frontUrl . 'assets/js/humanized_time_span.js', array( 'jquery' ), '1.0.0', true );

			wp_enqueue_script( 'wcst_admin-js', $this->get_admin_url() . 'assets/js/wcst_admin.min.js', array( 'jquery', 'cmb2-scripts', 'wcst-cmb2-conditionals' ), WCST_VERSION, true );
		}


		if ( is_object( $screen ) && ( $screen->base == 'woocommerce_page_wc-settings' ) && filter_input( INPUT_GET, 'tab' ) == "xl-sales-trigger" ) {
			?>
            <style>
                .wrap.woocommerce p.submit {
                    display: none
                }

                #WCST_MB_ajaxContent ol {
                    font-weight: bold
                }
            </style>
			<?php
		}
	}

	/**
	 * Hooked over `wcst_cmb2_modify_field_tabs`
	 * Sorts Tabs for settings
	 *
	 * @param $tabs Array of tabs
	 *
	 * @return mixed Sorted array
	 */
	public function wcst_admin_reorder_tabs( $tabs ) {
		usort( $tabs, array( $this, '_sort_by_priority' ) );

		return $tabs;
	}

	/**
	 * Hooked over `cmb2_render_wcst_html_content_field`
	 * Render Html for `wcst_html_content` Field
	 *
	 * @param $field CMB@ Field object
	 * @param $escaped_value Value
	 * @param $object_id object ID
	 * @param $object_type Obeect Type
	 * @param $field_type_object Field Tpe Object
	 */
	public function wcst_html_content_fields( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		$switch            = "";
		$conditional_value = ( isset( $field->args['attributes']['data-conditional-value'] ) ? 'data-conditional-value="' . esc_attr( $field->args['attributes']['data-conditional-value'] ) . '"' : '' );
		$conditional_id    = ( isset( $field->args['attributes']['data-conditional-id'] ) ? ' data-conditional-id="' . esc_attr( $field->args['attributes']['data-conditional-id'] ) . '"' : '' );


		$wcst_conditional_value = ( isset( $field->args['attributes']['data-wcst-conditional-value'] ) ? 'data-wcst-conditional-value="' . esc_attr( $field->args['attributes']['data-wcst-conditional-value'] ) . '"' : '' );
		$wcst_conditional_id    = ( isset( $field->args['attributes']['data-wcst-conditional-id'] ) ? ' data-wcst-conditional-id="' . esc_attr( $field->args['attributes']['data-wcst-conditional-id'] ) . '"' : '' );


		$switch = '<div ' . $conditional_value . $conditional_id . $wcst_conditional_value . $wcst_conditional_id . ' class="cmb2-wcst_html" id="' . $field->args['id'] . '">';
		$switch .= ( $field->args['content'] );

		$switch .= '</div>';
		echo $switch;
	}


	/**
	 * Hooked over `cmb2_render_wcst_multiselect`
	 * Render Html for `wcst_multiselect` Field
	 *
	 * @param $field CMB@ Field object
	 * @param $escaped_value Value
	 * @param $object_id object ID
	 * @param $object_type Obeect Type
	 * @param $field_type_object Field Tpe Object
	 */
	public function wcst_multiselect( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		$field_obj = new CMB2_Type_WCST_MultiSelect( $field_type_object );
		echo $field_obj->render();
	}

	/**
	 * Hooked over `postbox_classes_product_wcst_product_option_tabs`
	 * Always open for meta boxes
	 * removing closed class
	 *
	 * @param $classes classes
	 *
	 * @return mixed array of classes
	 */
	public function wcst_metabox_always_open( $classes ) {
		if ( ( $key = array_search( 'closed', $classes ) ) !== false ) {
			unset( $classes[ $key ] );
		}

		return $classes;
	}

	/**
	 * Adds meta for the repeaters for CMB2,
	 * Works as default value, repeaters cannot take default values
	 */
	public function wcst_add_repeater_meta_on_first_load() {
		global $post;
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		if ( $screen_id == "product" || $screen_id == WCST_Common::get_trigger_post_type_slug() ) {
			$get_group_state        = get_post_meta( $post->ID, "_wcst_data_wcst_guarantee_guarantee", true );
			$get_heading_color_meta = get_post_meta( $post->ID, "_wcst_data_wcst_guarantee_heading_color", true );
			if ( ! $get_group_state ) {
				$default_arr = array(
					0 => array( 'style_mode' => 'none', 'heading' => __( 'Hassle Free Returns', WCST_SLUG ), 'text' => __( 'No questions asked, 30 days return policy.', WCST_SLUG ) ),
					1 => array( 'style_mode' => 'none', 'heading' => __( 'Fast Shipping', WCST_SLUG ), 'text' => __( 'All orders are shipped in 1-3 business days.', WCST_SLUG ) ),
					2 => array( 'style_mode' => 'none', 'heading' => __( 'Secure Checkout', WCST_SLUG ), 'text' => __( 'SSL Enabled Secure Checkout', WCST_SLUG ) ),
				);
				update_post_meta( $post->ID, "_wcst_data_wcst_guarantee_guarantee", $default_arr );
			}

			if ( '' === $get_heading_color_meta || false === $get_heading_color_meta ) {
				$get_defaults        = WCST_Common::get_default_settings();
				$get_text_color_meta = get_post_meta( $post->ID, "_wcst_data_wcst_guarantee_text_color", true );

				update_post_meta( $post->ID, "_wcst_data_wcst_guarantee_heading_color", ( $get_text_color_meta ) ? $get_text_color_meta : $get_defaults['_wcst_data_wcst_guarantee_text_color'] );
			}


		}
	}

	/**
	 * Hooked over 'plugin_action_links_{PLUGIN_BASENAME}' wordpress hook to add deactivate popup support
	 *
	 * @param array $links array of existing links
	 *
	 * @return array modified array
	 */
	public function wcst_plugin_actions( $links ) {
		$links['settings']   = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=xl-sales-trigger' ) . '" class="edit">Settings</a>';
		$links['deactivate'] .= '<i class="xl-slug" data-slug="' . WCST_PLUGIN_BASENAME . '"></i>';

		return $links;
	}

	/**
	 * Hooked to `woocommerce_settings_tabs_array`
	 * Adding new tab in woocommerce settings
	 *
	 * @param $settings
	 *
	 * @return mixed
	 */
	public function modify_woocommerce_settings( $settings ) {

		$settings[ WCST_Common::get_wc_settings_tab_slug() ] = __( "Sales Triggers: XLPlugins", WCST_SLUG );

		return $settings;
	}

	/*     * ******** Functions For Rules Functionality Starts ************************************* */

	/**
	 * Loading assets for Rules functionality
	 *
	 * @param $handle handle current page
	 */
	public function wcst_post_wcst_load_assets( $handle ) {
		global $post_type, $woocommerce;


		$screen = get_current_screen();

		wp_enqueue_style( 'wcst-admin-all', $this->get_admin_url() . '/assets/css/wcst-admin-all.css' );

		if ( is_object( $screen ) && ( $screen->base == 'woocommerce_page_wc-settings' || ( $screen->base == 'post' && ( $screen->post_type == WCST_Common::get_trigger_post_type_slug() || $screen->post_type == 'product' ) ) ) ) {
			CMB2_hookup::enqueue_cmb_css();
			wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . 'assets/css/admin.css' );
			wp_enqueue_style( 'wcst-admin-app', $this->get_admin_url() . 'assets/css/wcst-admin-app.css' );


			wp_enqueue_style( 'xl-chosen-css', $this->get_admin_url() . 'assets/css/chosen.css' );

			wp_register_script( 'xl-chosen', $this->get_admin_url() . 'assets/js/chosen/chosen.jquery.min.js', array( 'jquery' ), WCST_VERSION );
			wp_register_script( 'xl-ajax-chosen', $this->get_admin_url() . 'assets/js/chosen/ajax-chosen.jquery.min.js', array( 'jquery', 'xl-chosen' ), WCST_VERSION );
			wp_register_script( 'jquery-masked-input', $this->get_admin_url() . 'assets/js/jquery.maskedinput.min.js', array( 'jquery' ), WCST_VERSION );
			wp_register_script( 'wcst-modal', $this->get_admin_url() . 'assets/js/wcst_modal.min.js', array( 'jquery' ), WCST_VERSION );
			wp_register_style( 'wcst-modal', $this->get_admin_url() . 'assets/css/wcst_modal.css', null, WCST_VERSION );

			wp_enqueue_script( 'xl-ajax-chosen' );
			wp_enqueue_script( 'jquery-masked-input' );
			wp_enqueue_script( 'wcst-modal' );
			wp_enqueue_style( 'wcst-modal' );

			wp_enqueue_script( 'wcst-admin-app', $this->get_admin_url() . 'assets/js/wcst-admin-app.min.js', array( 'jquery', 'jquery-ui-datepicker', 'underscore', 'backbone', 'xl-ajax-chosen' ) );


			$data = array(
				'ajax_nonce'            => wp_create_nonce( 'wcstaction-admin' ),
				'plugin_url'            => plugin_dir_url( WCST_PLUGIN_FILE ),
				'ajax_url'              => admin_url( 'admin-ajax.php' ),
				'ajax_chosen'           => wp_create_nonce( 'json-search' ),
				'search_products_nonce' => wp_create_nonce( 'search-products' ),
				'text_or'               => __( 'or', WCST_SLUG ),
				'text_apply_when'       => __( 'Apply this Trigger when these conditions are matched', WCST_SLUG ),
				'remove_text'           => __( 'Remove', WCST_SLUG )
			);

			wp_localize_script( 'wcst-admin-app', 'WCSTParams', $data );
		}
	}


	public function wcst_post_publish_box() {
		global $post;

		if ( WCST_Common::get_trigger_post_type_slug() != $post->post_type ) {
			return;
		}

		$trigger_status = 'Activated';
		if ( $post->post_status == 'trash' || $post->post_status == WCST_Common::get_trigger_post_type_slug() . 'disabled' ) {
			$trigger_status = 'Deactivated';
		}
		if ( $post->post_date ) {
			$date_format  = get_option( 'date_format' );
			$date_format  = $date_format ? $date_format : 'M d, Y';
			$publich_date = date( $date_format, strtotime( $post->post_date ) );
		}
		if ( $post->post_status != 'auto-draft' ) {
			?>
            <div class="misc-pub-section misc-pub-post-status wcst_always_show">
                Status: <span id="post-status-display"><?php echo $trigger_status ?></span>
            </div>
			<?php
		}
		if ( $post->post_date ) {
			?>
            <div class="misc-pub-section curtime misc-pub-curtime wcst_always_show">
                <span id="timestamp">Added on: <b><?php echo $publich_date ?></b></span>
            </div>
			<?php
		}
	}

	public function wcst_edit_form_top() {
		global $post;

		if ( WCST_Common::get_trigger_post_type_slug() != $post->post_type ) {
			return;
		}
		?>
        <div class="notice">
            <p><?php _e( 'Back to <a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) . '">XL Sales Triggers</a> listing.', WCST_SLUG ); ?></p>
        </div>
		<?php
	}

	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages[ WCST_Common::get_trigger_post_type_slug() ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Trigger updated.', WCST_SLUG ), admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) ),
			2  => __( 'Custom field updated.', WCST_SLUG ),
			3  => __( 'Custom field deleted.', WCST_SLUG ),
			4  => sprintf( __( 'Trigger updated. ', WCST_SLUG ), admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Trigger restored to revision from %s', WCST_SLUG ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Trigger updated. ', WCST_SLUG ), admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) ),
			7  => sprintf( __( 'Trigger saved. ', WCST_SLUG ), admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) ),
			8  => sprintf( __( 'Trigger updated. ', WCST_SLUG ), admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) ),
			9  => sprintf( __( 'Trigger scheduled for: <strong>%1$s</strong>.', WCST_SLUG ), date_i18n( __( 'M j, Y @ G:i', WCST_SLUG ), strtotime( $post->post_date ) ) ),
			10 => __( 'Trigger draft updated.', WCST_SLUG ),
			11 => sprintf( __( 'Trigger updated. ', WCST_SLUG ), admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '' ) ),
		);

		return $messages;
	}

	public function maybe_activate_post() {
		if ( isset( $_GET['action'] ) && $_GET['action'] == "wcst-post-activate" ) {
			if ( wp_verify_nonce( $_GET['_wpnonce'], 'wcst-post-activate' ) ) {

				$postID  = filter_input( INPUT_GET, 'postid' );
				$section = filter_input( INPUT_GET, 'trigger' );
				if ( $postID ) {
					wp_update_post( array( 'ID' => $postID, 'post_status' => 'publish' ) );

					wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '&section=' . $section ) );
				}
			} else {
				die( __( "Unable to Activate", WCST_SLUG ) );
			}
		}
	}

	public function maybe_deactivate_post() {
		if ( isset( $_GET['action'] ) && $_GET['action'] == "wcst-post-deactivate" ) {
			if ( wp_verify_nonce( $_GET['_wpnonce'], 'wcst-post-deactivate' ) ) {

				$postID  = filter_input( INPUT_GET, 'postid' );
				$section = filter_input( INPUT_GET, 'trigger' );
				if ( $postID ) {
					wp_update_post( array( 'ID' => $postID, 'post_status' => WCST_Common::get_trigger_post_type_slug() . 'disabled' ) );

					wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '&section=' . $section ) );
				}
			} else {
				die( __( "Unable to Deactivate", WCST_SLUG ) );
			}
		}
	}

	public function maybe_delete_all() {
		if ( isset( $_GET['action'] ) && $_GET['action'] == "wcst-post-delete-force" ) {

			$posts = get_posts( array(
				'post_type'   => WCST_Common::get_trigger_post_type_slug(),
				'showposts'   => - 1,
				'post_status' => array( 'publish', WCST_Common::get_trigger_post_type_slug() . 'disabled' )
			) );


			foreach ( $posts as $post ) {
				wp_delete_post( $post->ID, true );
			}

			wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() ) );
		}

		if ( isset( $_GET['action'] ) && $_GET['action'] == "wcst-remove-ids" ) {
			delete_option( 'wcst_posts_ids' );

			wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() ) );
		}
	}

	public function maybe_duplicate_post() {
		global $wpdb;
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'wcst-duplicate' ) {

			if ( wp_verify_nonce( $_GET['_wpnonce'], 'wcst-duplicate' ) ) {

				$original_id = filter_input( INPUT_GET, 'postid' );
				$section     = filter_input( INPUT_GET, 'trigger' );
				if ( $original_id ) {

					// Get the post as an array
					$duplicate = get_post( $original_id, 'ARRAY_A' );

					$settings = $defaults = array(
						'status'                => WCST_Common::get_trigger_post_type_slug() . 'disabled',
						'type'                  => 'same',
						'timestamp'             => 'current',
						'title'                 => __( 'Copy', 'post-duplicator' ),
						'slug'                  => 'copy',
						'time_offset'           => false,
						'time_offset_days'      => 0,
						'time_offset_hours'     => 0,
						'time_offset_minutes'   => 0,
						'time_offset_seconds'   => 0,
						'time_offset_direction' => 'newer',
					);

					// Modify some of the elements
					$appended                = ( $settings['title'] != '' ) ? ' ' . $settings['title'] : '';
					$duplicate['post_title'] = $duplicate['post_title'] . ' ' . $appended;
					$duplicate['post_name']  = sanitize_title( $duplicate['post_name'] . '-' . $settings['slug'] );

					// Set the status
					if ( $settings['status'] != 'same' ) {
						$duplicate['post_status'] = $settings['status'];
					}

					// Set the type
					if ( $settings['type'] != 'same' ) {
						$duplicate['post_type'] = $settings['type'];
					}

					// Set the post date
					$timestamp     = ( $settings['timestamp'] == 'duplicate' ) ? strtotime( $duplicate['post_date'] ) : current_time( 'timestamp', 0 );
					$timestamp_gmt = ( $settings['timestamp'] == 'duplicate' ) ? strtotime( $duplicate['post_date_gmt'] ) : current_time( 'timestamp', 1 );

					if ( $settings['time_offset'] ) {
						$offset = intval( $settings['time_offset_seconds'] + $settings['time_offset_minutes'] * 60 + $settings['time_offset_hours'] * 3600 + $settings['time_offset_days'] * 86400 );
						if ( $settings['time_offset_direction'] == 'newer' ) {
							$timestamp     = intval( $timestamp + $offset );
							$timestamp_gmt = intval( $timestamp_gmt + $offset );
						} else {
							$timestamp     = intval( $timestamp - $offset );
							$timestamp_gmt = intval( $timestamp_gmt - $offset );
						}
					}
					$duplicate['post_date']         = date( 'Y-m-d H:i:s', $timestamp );
					$duplicate['post_date_gmt']     = date( 'Y-m-d H:i:s', $timestamp_gmt );
					$duplicate['post_modified']     = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
					$duplicate['post_modified_gmt'] = date( 'Y-m-d H:i:s', current_time( 'timestamp', 1 ) );

					// Remove some of the keys
					unset( $duplicate['ID'] );
					unset( $duplicate['guid'] );
					unset( $duplicate['comment_count'] );

					// Insert the post into the database
					$duplicate_id = wp_insert_post( $duplicate );

					// Duplicate all the taxonomies/terms
					$taxonomies = get_object_taxonomies( $duplicate['post_type'] );
					foreach ( $taxonomies as $taxonomy ) {
						$terms = wp_get_post_terms( $original_id, $taxonomy, array( 'fields' => 'names' ) );
						wp_set_object_terms( $duplicate_id, $terms, $taxonomy );
					}

					// Duplicate all the custom fields
					$custom_fields = get_post_custom( $original_id );
					foreach ( $custom_fields as $key => $value ) {
						if ( is_array( $value ) && count( $value ) > 0 ) {
							foreach ( $value as $i => $v ) {
								$result = $wpdb->insert( $wpdb->prefix . 'postmeta', array(
									'post_id'    => $duplicate_id,
									'meta_key'   => $key,
									'meta_value' => $v,
								) );
							}
						}
					}

					do_action( 'wcst_post_duplicated', $original_id, $duplicate_id, $settings );

					wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '&section=' . $section ) );
				}
			} else {
				die( __( 'Unable to Duplicate', WCST_SLUG ) );
			}
		}
	}

	public function save_post_trigger_callback( $post_id, $post ) {
		//Check it's not an auto save routine
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		//Perform permission checks! For example:
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check your nonce!
		// If calling wp_update_post, unhook this function so it doesn't loop infinitely
		remove_action( 'save_post', array( $this, 'save_post_trigger_callback' ), 10, 2 );

		if ( $post && $post->post_type == WCST_Common::get_trigger_post_type_slug() ) {
			if ( isset( $_POST['_wcst_data_menu_order'] ) ) {
				$post_title = $_POST['post_title'];
				if ( isset( $_POST['_wcst_data_choose_trigger'] ) && isset( $_POST['post_title'] ) && empty( $_POST['post_title'] ) ) {
					$post_title = $_POST['_wcst_data_choose_trigger'];
				}
				wp_update_post( array(
					'ID'         => $post_id,
					'post_title' => $post_title,
					'post_name'  => sanitize_title( $_POST['post_title'] ) . "_" . $post_id,
					'menu_order' => $_POST['_wcst_data_menu_order']
				) );
			}

			do_action( 'wcst_cpt_save_post', $post_id, $post );
		} elseif ( $post && $post->post_type == 'product' ) {
			// single product save;
			do_action( 'wcst_cpt_product_save_post', $post_id, $post );
		}

		// re-hook this function
		add_action( 'save_post', array( $this, 'save_post_trigger_callback' ), 10, 2 );
	}

	public function update_menu_order() {
		global $wpdb;

		parse_str( $_POST['order'], $data );

		if ( ! is_array( $data ) ) {
			return false;
		}

		// get objects per now page
		$id_arr = array();
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = $id;
			}
		}

		// get menu_order of objects per now page
		$menu_order_arr = array();
		foreach ( $id_arr as $key => $id ) {
			$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval( $id ) );
			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->menu_order;
			}
		}

		// maintains key association = no
		sort( $menu_order_arr );

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[ $position ] ), array( 'ID' => intval( $id ) ) );
				$wpdb->update( $wpdb->postmeta, array( '_wcst_data_menu_order' => $menu_order_arr[ $position ] ), array( 'post_id' => intval( $id ) ) );
			}
		}
	}

	/**
	 * called under `save_post`
	 * run when post type 'sales trigger'
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function reset_transients() {

		// flushing object cache
		//wp_cache_flush();

		// deleting sales trigger query transient data
		if ( class_exists( 'XL_Transient' ) ) {
			$xl_transient_obj = XL_Transient::get_instance();
			$xl_transient_obj->delete_all_transients( 'sales-trigger' );
		}
	}

	/**
	 * called under `save_post`
	 * run when post type `product`
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function wc_product_save( $post_id, $post ) {
		// flushing object cache

		// deleting sales trigger query transient data
		if ( class_exists( 'XL_Transient' ) ) {
			$xl_transient_obj = XL_Transient::get_instance();

			$key = 'wcst_product_meta_' . $post_id;
			$xl_transient_obj->delete_transient( $key, 'sales-trigger' );
		}
	}

	public function wcst_add_guarantee_text() {

		$get_trigger_guarantee = WCST_Triggers::get( 'guarantee' );
		$screen                = get_current_screen();

		if ( is_object( $screen ) && ( $screen->base == 'woocommerce_page_wc-settings' || ( $screen->base == 'post' && ( $screen->post_type == WCST_Common::get_trigger_post_type_slug() || $screen->post_type == 'product' ) ) ) ) {

			if ( is_object( $get_trigger_guarantee ) ) {

				$get_trigger_guarantee->wcst_on_footer();

				$shortcode_countdown_timer = "[wcst_{TRIGGER_SLUG} trigger_ids=\"{TRIGGER_ID}\"]";

				$shortcode_countdown_timer_any = "[wcst_{TRIGGER_SLUG} trigger_ids=\"{TRIGGER_ID}\" skip_rules=\"no\"]"; ?>
                <div class='' id="wcst_shortcode_help_box" style="display: none;">

                    <h3>Shortcode</h3>
                    <div style="font-size: 1.1em; margin: 5px;">To show <strong>this trigger</strong> use shortcode</i> </div>
                    <table class="table widefat">


                        <tbody>

                        <tr>
                            <td>
                                Trigger ShortCode
                            </td>
                            <td>
                                <input class="wcst_input_replace_id" type="text" style="width: 75%;" readonly
                                       value='<?php echo $shortcode_countdown_timer; ?>'/>
                            </td>

                        </tr>


                        </tbody>
                    </table>
                    <br/>


                    <div style="font-size: 1.1em; margin: 5px; margin-bottom: 10px;"> To show <strong>any trigger</strong> use shortcode. Usually used when you want to display Elements in Grids Or
                        Page Builders. <strong>product_id</strong> must be available on the page.
                    </div>
                    <table class="table widefat">


                        <tbody>

                        <tr>
                            <td>
                                Trigger ShortCode
                            </td>
                            <td>
                                <input class="wcst_input_replace_id" type="text" style="width: 75%;" readonly
                                       value='<?php echo $shortcode_countdown_timer_any; ?>'/>
                            </td>

                        </tr>


                        </tbody>
                    </table>
                    <br/>


                    <h3>Other Attributes:</h3>

                    <p>
                        <strong>product_id</strong>: to get respective Element for the specific product, use product_id
                        attribute. Usually used when you are building landing page of your specific product.
                        <br/>
                    </p>


                    <p>
                        <strong>skip_rules</strong>: to skip the rule check while displaying Element.
                        <br/>Example: skip_rules="no" or skip_rules="yes" (preferred)

                    </p>
                    <p>
                        <strong>debug</strong>: Only used for the purpose of troubleshooting . <br/>
                        In case you are unable to see output of your shortcode then set debug ='yes' and system will try to
                        find out why shortcode is not rendering the output.
                    <p/>


                </div>
				<?php
			}
		}
	}

	/**
	 * Hooked over `admin_enqueue_scripts`
	 * Force remove Plugin update transient
	 */
	public function wcst_remove_plugin_update_transient() {
		if ( isset( $_GET['remove_update_transient'] ) && $_GET['remove_update_transient'] == '1' ) {
			delete_option( '_site_transient_update_plugins' );
			wp_safe_redirect( admin_url( 'plugins.php' ) );
		}
	}

	/**
	 * Adds meta for the repeaters for CMB2,
	 * Works as default value, repeaters cannot take default values
	 */
	public function wcst_add_global_settings_on_first_load() {
		global $post;
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		if ( filter_input( INPUT_GET, 'page' ) == "wc-settings" && filter_input( INPUT_GET, 'tab' ) == "xl-sales-trigger" && filter_input( INPUT_GET, 'section' ) == "settings" ) {

			$global_settings = get_option( 'wcst_global_options', array() );

			if ( empty( $global_settings ) ) {

				$default_settings = WCST_Common::get_default_global();

				update_option( 'wcst_global_options', $default_settings, false );
			}
		}
	}


	public function wcst_add_cmb2_multiselect() {
		include_once $this->get_admin_uri() . "includes/cmb2-addons/multiselect/CMB2_Type_MultiSelect.php";
	}

	/**
	 * @hooked over `cmb2 after field save`
	 *
	 * @param $post_id
	 */
	public function clear_transients( $post_id ) {
		if ( class_exists( 'XL_Transient' ) ) {
			$xl_transient_obj = XL_Transient::get_instance();
			$xl_transient_obj->delete_all_transients( 'finale' );
		}

		// flushing object cache
		//wp_cache_flush();
	}


	/**
	 * Resetting transients while a trigger is deleted
	 *
	 * @param int $post_id post id deleted
	 */
	public function clear_transients_on_delete( $post_id ) {
		$post = false;
		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );
		}

		if ( $post && $post instanceof WP_Post && $post->post_type === WCST_Common::get_trigger_post_type_slug() ) {
			$this->reset_transients();
		}

	}


	/**
	 * Check the screen and check if plugins update available to show notification to the admin to update the plugin
	 */
	public function maybe_show_advanced_update_notification() {
		$screen = get_current_screen();
		if ( is_object( $screen ) && ( 'plugins.php' == $screen->parent_file || 'index.php' == $screen->parent_file || WCST_Common::get_wc_settings_tab_slug() == filter_input( INPUT_GET, 'tab' ) ) ) {
			$plugins = get_site_transient( 'update_plugins' );
			if ( isset( $plugins->response ) && is_array( $plugins->response ) ) {
				$plugins = array_keys( $plugins->response );
				if ( is_array( $plugins ) && count( $plugins ) > 0 && in_array( WCST_PLUGIN_BASENAME, $plugins ) ) {
					?>
                    <div class="notice notice-warning is-dismissible">
                        <p>
							<?php
							_e( sprintf( 'Attention: There is an update available of <strong>%s</strong> plugin. &nbsp;<a href="%s" class="">Go to updates</a>', WCST_FULL_NAME, admin_url( 'plugins.php?s=sales%20trigger&plugin_status=all' ) ), WCST_SLUG );
							?>
                        </p>
                    </div>
					<?php
				}
			}
		}
	}


}

new WCST_Admin();
