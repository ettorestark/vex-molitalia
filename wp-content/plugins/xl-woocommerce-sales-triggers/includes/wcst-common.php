<?php
/**
 * Class WCST_Common
 * Handles Common Functions For Admin as well as front end interface
 */
if ( ! class_exists( 'WCST_Common' ) ) {

	class WCST_Common {

		protected static $default;
		public static $is_force_debug = false;
		public static $is_force_transient = false;
		public static $is_transient_removed = false;
		protected static $global_options = null;

		public static function init() {

			add_action( 'init', array( 'WCST_Common', 'register_post_status' ), 5 );

			/** Necessary Hooks For Rules functionality */
			add_action( 'init', array( 'WCST_Common', 'register_wcst_post_type' ) );

			add_action( 'init', array( 'WCST_Common', 'load_rules_classes' ) );

			add_filter( 'wcst_rules_get_rule_types', array( 'WCST_Common', 'default_rule_types' ), 1 );

			add_action( 'wp_ajax_wcst_change_rule_type', array( 'WCST_Common', 'ajax_render_rule_choice' ) );

			add_action( 'wp_ajax_update-menu-order', array( 'WCST_Common', 'update_menu_order' ) );

			add_action( 'save_post', array( 'WCST_Common', 'save_data' ), 10, 2 );

			/**
			 * Loading XL core
			 */
			add_action( 'init', array( 'WCST_Common', 'wcst_xl_init' ), 8 );


			/**
			 * Checking wcst query params
			 */
			add_action( 'init', array( 'WCST_Common', 'check_query_params' ), 10 );

			/**
			 * Setting up cron for regular license checks
			 */
			add_action( 'wp', array( 'WCST_Common', 'wcst_license_check_schedule' ) );


			/**
			 *
			 */
			add_action( 'wcst_maybe_schedule_check_license', array( 'WCST_Common', 'check_license_state' ) );

			add_action( 'xl_global_tracking_data', array( __CLASS__, 'add_to_xl_tracking_data' ) );

			add_action( 'init', array( __CLASS__, 'hide_admin_hide_elements' ) );


			add_action( 'wp_ajax_wcst_refreshed_times', array( __CLASS__, 'reset_timer_time' ) );
			add_action( 'wp_ajax_nopriv_wcst_refreshed_times', array( __CLASS__, 'reset_timer_time' ) );

			add_action( 'wp_ajax_wcst_reset_current_time', array( __CLASS__, 'reset_current_time' ) );
			add_action( 'wp_ajax_nopriv_wcst_reset_current_time', array( __CLASS__, 'reset_current_time' ) );

			add_action( 'init', array( __CLASS__, 'reset_current_time' ), 1 );

			/**
			 * modifying stock status rule when WC 3.3 or higher
			 */
			add_filter( 'wcst_rule_stock_status', array( __CLASS__, 'wcst_rule_stock_status' ), 10 );
		}

		public static function wcst_ecom_icons() {
			return array(
				0   => __( "None", WCST_SLUG ),
				248 => 'Cart Empty',
				181 => 'Cart Full',
				246 => 'Savings',
				234 => 'Support',
				225 => 'Support 24 hr',
				195 => 'Support 24*7',
				194 => 'Support 24*7 with Phone icon',
				197 => 'Support with Cart',
				152 => 'Support Hands',
				154 => 'Balance Scale',
				182 => 'Gift',
				174 => 'Gift on Hand',
				93  => 'Gift Sale',
				95  => 'Gift Free',
				151 => 'Shield',
				214 => 'Diamond',
				201 => 'Globe',
				179 => 'Globe with Cart',
				20  => 'Announcement',
				156 => 'Announcement 2',
				138 => 'Shipping Van',
				56  => 'Shipping Air',
				131 => 'Shipping Air 2',
				40  => 'Shipping Gift',
				42  => 'Credit Card',
				245 => 'Badge 1',
				244 => 'Badge 2',
				233 => 'Currency Dollar Thick Circle',
				216 => 'Currency Dollar Icon only',
				149 => 'Currency Dollar Filled Circle',
				231 => 'Currency Pound Thick Circle',
				219 => 'Currency Pound Icon only',
				148 => 'Currency Pound Filled Circle',
				196 => 'Bar-Code',
				189 => 'QR Code',
				145 => 'Cash Dollar',
				183 => 'Cash with Scissor',
				118 => 'Checklist',
				188 => 'Tag Open',
				186 => 'Tag Free',
				187 => 'Tag Sale',
				97  => 'Exchange',
				184 => 'Badge New',
				91  => 'Badge Free',
				237 => 'Badge Sale',
				61  => 'Badge Free Light',
				133 => 'Package Box',
				134 => 'Package Box 2',
				80  => 'Bag',
				66  => 'Bag with $ sign',
				67  => 'Certificate',
				85  => 'Heart',
				72  => 'Star',
				55  => 'Recycle',
				22  => 'Like',
			);
		}

		public static function get_default_settings() {
			$date_object = new DateTime();
			$date_object->setTimestamp( current_time( 'timestamp' ) );

			$current_date   = $date_object->format( "Y-m-d" );
			$last_month_day = $date_object->modify( "-30 days" )->format( ( "Y-m-d" ) );

			self::$default = array(
				'_wcst_data_wcst_best_seller_badge_label'                     => __( '#{{rank}} Best Seller ', WCST_SLUG ),
				'_wcst_data_wcst_best_seller_badge_date_limit'                => '1',
				'_wcst_data_wcst_best_seller_badge_date_from'                 => $last_month_day,
				'_wcst_data_wcst_best_seller_badge_date_to'                   => $current_date,
				'_wcst_data_wcst_best_seller_badge_show_badge_if_position'    => '10',
				'_wcst_data_wcst_best_seller_badge_badge_style'               => 1,
				'_wcst_data_wcst_best_seller_badge_hyperlink_category'        => 'yes',
				'_wcst_data_wcst_best_seller_badge_badge_bg_color'            => '#dd3333',
				'_wcst_data_wcst_best_seller_badge_badge_text_color'          => '#fff',
				'_wcst_data_wcst_best_seller_badge_position'                  => '2',
				'_wcst_data_wcst_best_seller_list_date_limit'                 => '1',
				'_wcst_data_wcst_best_seller_list_date_from'                  => $last_month_day,
				'_wcst_data_wcst_best_seller_list_date_to'                    => $current_date,
				'_wcst_data_wcst_best_seller_list_heading'                    => __( 'This item is Best Seller in following categories:', WCST_SLUG ),
				'_wcst_data_wcst_best_seller_list_label'                      => __( '#{{category_rank}} Best Seller in {{category_name}} ', WCST_SLUG ),
				'_wcst_data_wcst_best_seller_list_number_of_list_items'       => 4,
				'_wcst_data_wcst_best_seller_list_show_list_item_if_position' => '100',
				'_wcst_data_wcst_best_seller_list_hyperlink_category'         => 'yes',
				'_wcst_data_wcst_best_seller_list_position'                   => '6',
				'_wcst_data_wcst_low_stock_assurance_label'                   => __( 'In Stock', WCST_SLUG ),
				'_wcst_data_wcst_low_stock_scarcity_label'                    => __( 'Only {{stock_quantity_left}} left in stock. Almost Gone!', WCST_SLUG ),
				'_wcst_data_wcst_low_stock_out_of_stock_label'                => __( 'Just Sold Out. Expect to come in 4-6 days.', WCST_SLUG ),
				'_wcst_data_wcst_low_stock_default_mode'                      => 'assurance',
				'_wcst_data_wcst_low_stock_switch_scarcity_min_stock'         => 5,
				'_wcst_data_wcst_low_stock_assurance_text_color'              => '#77a464',
				'_wcst_data_wcst_low_stock_scarcity_text_color'               => '#dd3333',
				'_wcst_data_wcst_low_stock_out_of_stock_text_color'           => '#dd3333',
				'_wcst_data_wcst_low_stock_font_size'                         => 16,
				'_wcst_data_wcst_low_stock_position'                          => '5',
				'_wcst_data_wcst_savings_label'                               => __( 'You Save: {{savings_value_percentage}}', WCST_SLUG ),
				'_wcst_data_wcst_savings_text_color'                          => '#dd3333',
				'_wcst_data_wcst_savings_font_size'                           => 16,
				'_wcst_data_wcst_savings_position'                            => '4',
				'_wcst_data_wcst_savings_show_below_variation_price'          => 'yes',
				'_wcst_data_wcst_savings_hide_decimal_in_saving_percentage'   => 'no',
				'_wcst_data_wcst_guarantee_box_bg_color'                      => '#f4f5f4',
				'_wcst_data_wcst_guarantee_border_color'                      => '#ececec',
				'_wcst_data_wcst_guarantee_guarantee'                         => array(
					0 => array(
						'style_mode' => 'none',
						'heading'    => __( 'Hassle Free Returns', WCST_SLUG ),
						'text'       => __( 'No questions asked, 30 days return policy.', WCST_SLUG )
					),
					1 => array(
						'style_mode' => 'none',
						'heading'    => __( 'Fast Shipping', WCST_SLUG ),
						'text'       => __( 'All orders are shipped in 1-3 business days.', WCST_SLUG )
					),
					2 => array(
						'style_mode' => 'none',
						'heading'    => __( 'Secure Checkout', WCST_SLUG ),
						'text'       => __( 'SSL Enabled Secure Checkout', WCST_SLUG )
					),
				),
				'_wcst_data_wcst_guarantee_text_color'                        => '#252525',
				'_wcst_data_wcst_guarantee_heading_color'                     => '', //providing backward compatibility
				'_wcst_data_wcst_guarantee_font_size'                         => 16,
				'_wcst_data_wcst_guarantee_alignment'                         => 'left',
				'_wcst_data_wcst_guarantee_position'                          => '5',
				'_wcst_data_wcst_sales_snippet_date_limit'                    => '1',
				'_wcst_data_wcst_sales_snippet_from_date'                     => $last_month_day,
				'_wcst_data_wcst_sales_snippet_to_date'                       => $current_date,
				'_wcst_data_wcst_sales_snippet_label'                         => __( '{{sales_snippet}} bought this item recently.', WCST_SLUG ),
				'_wcst_data_wcst_sales_snippet_restrict'                      => 0,
				'_wcst_data_wcst_sales_snippet_output'                        => 'default',
				'_wcst_data_wcst_sales_snippet_box_bg_color'                  => '#efeddc',
				'_wcst_data_wcst_sales_snippet_border_color'                  => '#efeace',
				'_wcst_data_wcst_sales_snippet_text_color'                    => '#252525',
				'_wcst_data_wcst_sales_snippet_font_size'                     => 16,
				'_wcst_data_wcst_sales_snippet_position'                      => '6',
				'_wcst_data_wcst_sales_count_date_limit'                      => '1',
				'_wcst_data_wcst_sales_count_from_date'                       => $last_month_day,
				'_wcst_data_wcst_sales_count_to_date'                         => $current_date,
				'_wcst_data_wcst_sales_count_label'                           => __( '{{order_count}} orders in last 30 days.', WCST_SLUG ),
				'_wcst_data_wcst_sales_count_box_bg_color'                    => '#ffffff',
				'_wcst_data_wcst_sales_count_border_color'                    => '#ececec',
				'_wcst_data_wcst_sales_count_text_color'                      => '#252525',
				'_wcst_data_wcst_sales_count_font_size'                       => 16,
				'_wcst_data_wcst_sales_count_restrict'                        => 0,
				'_wcst_data_wcst_sales_count_position'                        => '5',
				'_wcst_data_wcst_deal_expiry_reverse_date_label'              => __( 'Sale ends in {{time_left}}', WCST_SLUG ),
				'_wcst_data_wcst_deal_expiry_reverse_timer_label'             => __( 'Hurry up! Sale ends in {{countdown_timer}}', WCST_SLUG ),
				'_wcst_data_wcst_deal_expiry_expiry_date_label'               => __( 'Prices go up after {{end_date}}', WCST_SLUG ),
				'_wcst_data_wcst_deal_expiry_display_mode'                    => 'reverse_date',
				'_wcst_data_wcst_deal_expiry_switch_period'                   => 24,
				'_wcst_data_wcst_deal_expiry_text_color'                      => '#ec1f1f',
				'_wcst_data_wcst_deal_expiry_font_size'                       => 16,
				'_wcst_data_wcst_deal_expiry_position'                        => '4',
				'_wcst_data_wcst_smarter_reviews_template'                    => 'rating_greater_than_4',
				'_wcst_data_wcst_smarter_reviews_satisfaction_rate_label'     => '{{rating_percentage}} buyers satisfaction rate.',
				'_wcst_data_wcst_smarter_reviews_rating_greater_than_4_label' => '{{positive_feedback_percentage}} of buyers gave more than 4 star rating.',
				'_wcst_data_wcst_smarter_reviews_switch_to_max'               => 'yes',
				'_wcst_data_wcst_smarter_reviews_dont_show_until'             => '60',
				'_wcst_data_wcst_smarter_reviews_hide_if_disable_comments'    => 'no',
				'_wcst_data_wcst_smarter_reviews_hyperlink_text_review'       => 'no',
				'_wcst_data_wcst_smarter_reviews_text_color'                  => '#ececec',
				'_wcst_data_wcst_smarter_reviews_font_size'                   => 15,
				'_wcst_data_wcst_smarter_reviews_position'                    => '2',
			);

			return self::$default;
		}

		public static function wcst_get_date_format() {
			$date_format = get_option( 'date_format', true );
			$date_format = $date_format ? $date_format : 'M d, Y';

			return $date_format;
		}

		public static function wcst_get_time_format() {
			$time_format = get_option( 'time_format', true );
			$time_format = $time_format ? $time_format : 'g:i a';

			return $time_format;
		}

		public static function array_flatten( $array ) {
			if ( ! is_array( $array ) ) {
				return false;
			}
			$result = iterator_to_array( new RecursiveIteratorIterator( new RecursiveArrayIterator( $array ) ), false );

			return $result;
		}

		public static function array_flat_mysql_results( $results, $expected_key, $expected_value_key ) {
			$array = array();
			foreach ( $results as $result ) {
				$array[ $result[ $expected_key ] ] = (int) $result[ $expected_value_key ];
			}

			return $array;
		}

		public static function register_wcst_post_type() {
			$menu_name = _x( 'XL Sales Triggers', 'Admin menu name', WCST_SLUG );

			register_post_type( self::get_trigger_post_type_slug(), apply_filters( 'wcst_post_type_args', array(
				'labels'               => array(
					'name'               => __( 'Sales Trigger', WCST_SLUG ),
					'singular_name'      => __( 'Sales Trigger', WCST_SLUG ),
					'add_new'            => __( 'Add Sales Trigger', WCST_SLUG ),
					'add_new_item'       => __( 'Add New Sales Trigger', WCST_SLUG ),
					'edit'               => __( 'Edit', WCST_SLUG ),
					'edit_item'          => __( 'Edit Sales Trigger', WCST_SLUG ),
					'new_item'           => __( 'New Sales Trigger', WCST_SLUG ),
					'view'               => __( 'View Sales Trigger', WCST_SLUG ),
					'view_item'          => __( 'View Sales Trigger', WCST_SLUG ),
					'search_items'       => __( 'Search Sales Trigger', WCST_SLUG ),
					'not_found'          => __( 'No Sales Trigger', WCST_SLUG ),
					'not_found_in_trash' => __( 'No Sales Trigger found in trash', WCST_SLUG ),
					'parent'             => __( 'Parent Sales Trigger', WCST_SLUG ),
					'menu_name'          => $menu_name
				),
				'description'          => __( 'This is where conditional content blocks and their assoicated rules are stored.', WCST_SLUG ),
				'public'               => false,
				'show_ui'              => true,
				'capability_type'      => 'product',
				'map_meta_cap'         => true,
				'publicly_queryable'   => false,
				'exclude_from_search'  => true,
				'show_in_menu'         => false,
				'hierarchical'         => false,
				'show_in_nav_menus'    => false,
				'rewrite'              => false,
				'query_var'            => true,
				'supports'             => array( 'title' ),
				'has_archive'          => false,
				'register_meta_box_cb' => array( 'WCST_Admin', 'add_metaboxes' )
			) ) );
		}

		public static function get_trigger_post_type_slug() {
			return 'wcst_trigger';
		}

		public static function load_rules_classes() {
			//Include the compatibility class
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/class-wcst-compatibility.php';

			//Include our default rule classes
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/base.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/general.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/page.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/date-time.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/products.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/stock.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/sales.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/users.php';

			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/cart.php';
			include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/rules/geo.php';

			if ( is_admin() || defined( 'DOING_AJAX' ) ) {
				//Include the admin interface builder
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/class-wcst-input-builder.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/html-always.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/text.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/date.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/select.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/product-select.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/cart-product-select.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/cart-category-select.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/chosen-select.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/time.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/page-select.php';
				include plugin_dir_path( WCST_PLUGIN_FILE ) . '/rules/inputs/term-select.php';
			}
		}

		/**
		 * Creates an instance of an input object
		 * @global type $wcst_rules_inputs
		 *
		 * @param type $input_type The slug of the input type to load
		 *
		 * @return type An instance of an WCST_Input object type
		 */
		public static function wcst_rules_get_input_object( $input_type ) {
			global $wcst_rules_inputs;

			if ( isset( $wcst_rules_inputs[ $input_type ] ) ) {
				return $wcst_rules_inputs[ $input_type ];
			}

			$class = 'WCST_Input_' . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $input_type ) ) );
			if ( class_exists( $class ) ) {
				$wcst_rules_inputs[ $input_type ] = new $class;
			} else {
				$wcst_rules_inputs[ $input_type ] = apply_filters( 'wcst_rules_get_input_object', $input_type );
			}

			return $wcst_rules_inputs[ $input_type ];
		}

		/**
		 * Ajax and PHP Rendering Functions for Options.
		 *
		 * Renders the correct Operator and Values controls.
		 */
		public static function ajax_render_rule_choice( $options ) {
			// defaults
			$defaults = array(
				'group_id'  => 0,
				'rule_id'   => 0,
				'rule_type' => null,
				'condition' => null,
				'operator'  => null,
			);

			$is_ajax = false;

			if ( isset( $_POST['action'] ) && $_POST['action'] == 'wcst_change_rule_type' ) {
				$is_ajax = true;
			}

			if ( $is_ajax ) {
				if ( ! check_ajax_referer( 'wcstaction-admin', 'security' ) ) {
					die();
				}
				$options = array_merge( $defaults, $_POST );
			} else {
				$options = array_merge( $defaults, $options );
			}

			$rule_object = self::wcst_rules_get_rule_object( $options['rule_type'] );


			if ( ! empty( $rule_object ) ) {
				$values               = $rule_object->get_possibile_rule_values();
				$operators            = $rule_object->get_possibile_rule_operators();
				$condition_input_type = $rule_object->get_condition_input_type();
				// create operators field
				$operator_args = array(
					'input'   => 'select',
					'name'    => 'wcst_rule[' . $options['group_id'] . '][' . $options['rule_id'] . '][operator]',
					'choices' => $operators,
				);

				echo '<td class="operator">';
				if ( ! empty( $operators ) ) {
					WCST_Input_Builder::create_input_field( $operator_args, $options['operator'] );
				} else {
					echo '<input type="hidden" name="' . $operator_args['name'] . '" value="==" />';
				}
				echo '</td>';

				// create values field
				$value_args = array(
					'input'   => $condition_input_type,
					'name'    => 'wcst_rule[' . $options['group_id'] . '][' . $options['rule_id'] . '][condition]',
					'choices' => $values,
				);

				echo '<td class="condition">';
				WCST_Input_Builder::create_input_field( $value_args, $options['condition'] );
				echo '</td>';
			}

			// ajax?
			if ( $is_ajax ) {
				die();
			}
		}

		/**
		 * Creates an instance of a rule object
		 * @global array $wcst_rules_rules
		 *
		 * @param type $rule_type The slug of the rule type to load.
		 *
		 * @return WCST_Rule_Base or superclass of WCST_Rule_Base
		 */
		public static function wcst_rules_get_rule_object( $rule_type ) {
			global $wcst_rules_rules;

			if ( isset( $wcst_rules_rules[ $rule_type ] ) ) {
				return $wcst_rules_rules[ $rule_type ];
			}

			$class = 'WCST_Rule_' . $rule_type;

			if ( class_exists( $class ) ) {
				$wcst_rules_rules[ $rule_type ] = new $class;

				return $wcst_rules_rules[ $rule_type ];
			} else {
				return null;
			}
		}

		/**
		 * Called from the metabox_settings.php screen.  Renders the template for a rule group that has already been saved.
		 *
		 * @param array $options The group config options to render the template with.
		 */
		public static function render_rule_choice_template( $options ) {
			// defaults
			$defaults = array(
				'group_id'  => 0,
				'rule_id'   => 0,
				'rule_type' => null,
				'condition' => null,
				'operator'  => null,
			);


			$options     = array_merge( $defaults, $options );
			$rule_object = self::wcst_rules_get_rule_object( $options['rule_type'] );

			$values               = $rule_object->get_possibile_rule_values();
			$operators            = $rule_object->get_possibile_rule_operators();
			$condition_input_type = $rule_object->get_condition_input_type();

			// create operators field
			$operator_args = array(
				'input'   => 'select',
				'name'    => 'wcst_rule[<%= groupId %>][<%= ruleId %>][operator]',
				'choices' => $operators,
			);

			echo '<td class="operator">';
			if ( ! empty( $operators ) ) {
				WCST_Input_Builder::create_input_field( $operator_args, $options['operator'] );
			} else {
				echo '<input type="hidden" name="' . $operator_args['name'] . '" value="==" />';
			}
			echo '</td>';

			// create values field
			$value_args = array(
				'input'   => $condition_input_type,
				'name'    => 'wcst_rule[<%= groupId %>][<%= ruleId %>][condition]',
				'choices' => $values,
			);

			echo '<td class="condition">';
			WCST_Input_Builder::create_input_field( $value_args, $options['condition'] );
			echo '</td>';
		}

		public static function get_triggers_select() {


			$triggers = WCST_Triggers::get_triggers();

			$create_select_array = array();
			if ( $triggers && is_array( $triggers ) && count( $triggers ) > 0 ) {
				foreach ( $triggers as $triggerlist ) {
					$create_select_array[ $triggerlist['name'] ] = array();

					foreach ( $triggerlist['triggers'] as $triggers_main ) {

						$create_select_array[ $triggerlist['name'] ][ $triggers_main["slug"] ] = $triggers_main['name'];
					}
				}
			}


			return $create_select_array;
		}

		/**
		 * Getting list of declared triggers in hierarchical order
		 * @return array array of triggers
		 */
		public static function get_triggers() {
			return array(
				'wcst_deal_expiry_settings'      => array(
					'name'     => 'Deal Expiry',
					'slug'     => 'deal_expiry',
					'triggers' => array(
						array(
							'title'    => __( 'Deal Expiry', WCST_SLUG ),
							'slug'     => 'deal_expiry',
							'position' => 4
						),
					)
				),
				'wcst_low_stock_settings'        => array(
					'name'     => 'Low Stock',
					'slug'     => 'low_stock',
					'triggers' => array(
						array(
							'title'    => __( 'Low Stock', WCST_SLUG ),
							'slug'     => 'low_stock',
							'position' => 5
						),
					)
				),
				'wcst_savings_settings'          => array(
					'name'     => 'Savings',
					'slug'     => 'savings',
					'triggers' => array(
						array(
							'title'    => __( 'Savings', WCST_SLUG ),
							'slug'     => 'savings',
							'position' => 4
						),
					)
				),
				'wcst_sales_activities_settings' => array(
					'name'     => 'Sales Activities',
					'slug'     => 'sales_activities',
					'triggers' => array(
						array(
							'title'    => __( 'Sales Snippet', WCST_SLUG ),
							'slug'     => 'sales_snippet',
							'position' => 6
						),
						array(
							'title'    => __( 'Sales Count', WCST_SLUG ),
							'slug'     => 'sales_count',
							'position' => 5
						),
					)
				),
				'wcst_guarantee_settings'        => array(
					'name'     => 'Guarantee',
					'slug'     => 'guarantee',
					'triggers' => array(
						array(
							'title'    => __( 'Guarantee', WCST_SLUG ),
							'slug'     => 'guarantee',
							'position' => 5
						),
					)
				),
				'wcst_smarter_reviews_settings'  => array(
					'name'     => 'Smarter Reviews',
					'slug'     => 'smarter_reviews',
					'triggers' => array(
						array(
							'title'    => __( 'Smarter Reviews', WCST_SLUG ),
							'slug'     => 'smarter_reviews',
							'position' => 2
						),
					)
				),
				'wcst_best_sellers_settings'     => array(
					'name'     => 'Best Sellers',
					'slug'     => 'best_sellers',
					'triggers' => array(
						array(
							'title'    => __( 'Best Seller Badge', WCST_SLUG ),
							'slug'     => 'best_seller_badge',
							'position' => 2
						),
						array(
							'title'    => __( 'Best Seller List', WCST_SLUG ),
							'slug'     => 'best_seller_list',
							'position' => 6
						),
					)
				),
			);
		}

		public static function match_groups( $content_id, $product_id = 0 ) {
			$display = false;

			if ( ! $product_id ) {
				return $display;
			}

			do_action( 'wcst_before_apply_rules', $content_id, $product_id );

			//allowing rules to get manipulated using external logic
			$external_rules = apply_filters( 'wcst_modify_rules', true, $content_id, $product_id );
			if ( ! $external_rules ) {
				return false;
			}

			$groups = get_post_meta( $content_id, 'wcst_rule', true );
			if ( $groups && count( $groups ) ) {
				foreach ( $groups as $group_id => $group ) {
					$result = null;

					foreach ( $group as $rule_id => $rule ) {
						$rule_object = self::wcst_rules_get_rule_object( $rule['rule_type'] );
						if ( $rule_object instanceof WCST_Rule_General_Always ) {
							return true;
						}
						if ( is_object( $rule_object ) ) {
							$match = $rule_object->is_match( $rule, $product_id );
							if ( false === $match ) {
								$result = false;
								break;
							}
							$result = ( $result !== null ? ( $result & $match ) : $match );
						}
					}

					if ( $result ) {
						$display = true;
						break;
					}
				}
			} else {
				$display = true; //Always display the content if no rules have been configured.
			}

			do_action( 'wcst_after_apply_rules', $content_id, $product_id );

			return $display;
		}

		public static function get_trigger_nice_name( $slug, $is_parents = false ) {

			$get_all_triggers = WCST_Triggers::get_triggers();


			foreach ( $get_all_triggers as $key => $triggers ) {


				if ( is_array( $triggers ) ) {
					foreach ( $triggers['triggers'] as $trigger ) {


						if ( $trigger['slug'] == $slug ) {
							if ( $is_parents ) {
								return $triggers['name'];
							} else {
								return $trigger['name'];
							}
						}
					}
				}
			}


			return "";
		}

		/**
		 * Hooked into wcst_rules_get_rule_types to get the default list of rule types.
		 *
		 * @param array $types Current list, if any, of rule types.
		 *
		 * @return array the list of rule types.
		 */
		public static function default_rule_types( $types ) {
			$types = array(
				__( 'General', WCST_SLUG )    => array(
					'general_always' => __( 'Always', WCST_SLUG ),
				),
				__( "Product", WCST_SLUG )    => array(
					'product_select'   => __( 'Products', WCST_SLUG ),
					'product_type'     => __( "Product Type", WCST_SLUG ),
					'product_category' => __( "Product Category", WCST_SLUG ),
					'product_tags'     => __( "Product Tags", WCST_SLUG ),
					'product_price'    => __( "Product Price", WCST_SLUG ),
				),
				__( 'Sales', WCST_SLUG )      => array(
					'sale_status' => __( 'Sale Status', WCST_SLUG )
				),
				__( 'Stock', WCST_SLUG )      => array(
					'stock_status' => __( 'Stock Status', WCST_SLUG ),
				),
				__( 'Geography', WCST_SLUG )  => array(
					'geo_country_code' => __( 'Country', WCST_SLUG )
				),
				__( 'Date/Time', WCST_SLUG )  => array(
					'day'  => __( 'Day', WCST_SLUG ),
					'date' => __( 'Date', WCST_SLUG ),
					'time' => __( 'Time', WCST_SLUG )
				),
				__( "Membership", WCST_SLUG ) => array(
					'users_user' => __( "User", WCST_SLUG ),
					'users_role' => __( "Role", WCST_SLUG )
				),
			);

			return $types;
		}

		/**
		 * Saves the data for the wcst post type.
		 *
		 * @param int $post_id Post ID
		 * @param WP_Post Post Object
		 *
		 * @return null
		 */
		public static function save_data( $post_id, $post ) {

			if ( empty( $post_id ) || empty( $post ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( is_int( wp_is_post_revision( $post ) ) ) {
				return;
			}
			if ( is_int( wp_is_post_autosave( $post ) ) ) {
				return;
			}
			if ( $post->post_type != self::get_trigger_post_type_slug() ) {
				return;
			};
//        if ( !WCST_Conditional_Content::verify_nonce( 'admin' ) ) {
//            return;
//        }

			if ( isset( $_POST['wcst_settings_location'] ) ) {
				$location = explode( ':', $_POST['wcst_settings_location'] );
				$settings = array( 'location' => $location[0], 'hook' => $location[1] );

				if ( $settings['hook'] == 'custom' ) {
					$settings['custom_hook']     = $_POST['wcst_settings_location_custom_hook'];
					$settings['custom_priority'] = $_POST['wcst_settings_location_custom_priority'];
				} else {
					$settings['custom_hook']     = '';
					$settings['custom_priority'] = '';
				}

				$settings['type'] = $_POST['wcst_settings_type'];

				update_post_meta( $post_id, '_wcst_settings', $settings );
			}

			if ( isset( $_POST['wcst_rule'] ) ) {
				update_post_meta( $post_id, 'wcst_rule', $_POST['wcst_rule'] );
			} else {
//                delete_post_meta($post_id, 'wcst_rule');
			}
		}

		public static function get_post_table_data( $trigger = 'all' ) {
			if ( $trigger == "all" ) {
				$args = array(
					'post_type'   => self::get_trigger_post_type_slug(),
					'orderby'     => 'menu_order',
					'order'       => 'ASC',
					'post_status' => array( 'publish', WCST_Common::get_trigger_post_type_slug() . 'disabled' ),
					'nopaging'    => true,
				);
			} else {


				$triggers = WCST_Triggers::get_triggers();

				if ( $triggers && is_array( $triggers ) && count( $triggers ) > 0 ) {
					foreach ( $triggers as $triggerlist ) {


						if ( $triggerlist['slug'] == $trigger ) {
							$meta_q = array();
							foreach ( $triggerlist['triggers'] as $trigger_main ) {
								$meta_q[] = array(
									'key'     => '_wcst_data_choose_trigger',
									'value'   => $trigger_main['slug'],
									'compare' => '=',
								);
							}
							$meta_q['relation'] = 'OR';
						} else {
							foreach ( $triggerlist['triggers'] as $trigger_main ) {

								if ( $trigger_main['slug'] == $trigger ) {
									$meta_q = array(
										array(
											'key'     => '_wcst_data_choose_trigger',
											'value'   => $trigger,
											'compare' => '=',
										)
									);
								}
							}
						}
					}
				}


				$args = array(
					'post_type'   => self::get_trigger_post_type_slug(),
					'post_status' => array( 'publish', WCST_Common::get_trigger_post_type_slug() . 'disabled' ),
					'nopaging'    => true,
					'meta_query'  => $meta_q,
					'orderby'     => 'menu_order',
					'order'       => 'ASC',
				);
			}


			$q              = new WP_Query( $args );
			$position_array = array(
				'1'  => __( 'Above the Title', WCST_SLUG ),
				'2'  => __( 'Below the Title', WCST_SLUG ),
				'3'  => __( 'Below the Review Rating', WCST_SLUG ),
				'4'  => __( 'Below the Price', WCST_SLUG ),
				'5'  => __( 'Below Short Description', WCST_SLUG ),
				'6'  => __( 'Below Add to Cart Button', WCST_SLUG ),
				'7'  => __( 'Below SKU & Categories', WCST_SLUG ),
				'8'  => __( 'Above the Tabs', WCST_SLUG ),
				'11' => __( 'Below Related Products', WCST_SLUG ),
			);
			$found_posts    = array();
			if ( $q->have_posts() ) {

				while ( $q->have_posts() ) {
					$q->the_post();
					$status      = get_post_status( get_the_ID() );
					$row_actions = array();

					$deactivation_url = wp_nonce_url( add_query_arg( 'page', 'wc-settings', add_query_arg( 'tab', WCST_Common::get_wc_settings_tab_slug(), add_query_arg( 'action', 'wcst-post-deactivate', add_query_arg( 'postid', get_the_ID(), add_query_arg( 'trigger', $trigger ) ), network_admin_url( 'admin.php' ) ) ) ), 'wcst-post-deactivate' );

					// $exporturl = network_admin_url('export.php') . '?download=true&post_author=0&post_start_date=0&post_end_date=0&post_status=0&page_author=0&page_start_date=0&page_end_date=0&page_status=0&attachment_start_date=0&attachment_end_date=0&content=advanced&query=page&post-ids%5B%5D=' . get_the_ID() . '&submit=Download+Export+File';
					if ( $status == WCST_Common::get_trigger_post_type_slug() . 'disabled' ) {

						$text = __( 'Activate', WCST_SLUG );
						$link = get_post_permalink( get_the_ID() );

						$activation_url = wp_nonce_url( add_query_arg( 'page', 'wc-settings', add_query_arg( 'tab', WCST_Common::get_wc_settings_tab_slug(), add_query_arg( 'action', 'wcst-post-activate', add_query_arg( 'postid', get_the_ID(), add_query_arg( 'trigger', $trigger ) ), network_admin_url( 'admin.php' ) ) ) ), 'wcst-post-activate' );

						$row_actions[] = array(
							'action' => 'activate',
							'text'   => __( 'Activate', WCST_SLUG ),
							'link'   => $activation_url,
							'attrs'  => '',
						);
					} else {
						$row_actions[] = array(
							'action' => 'edit',
							'text'   => __( 'Edit', WCST_SLUG ),
							'link'   => get_edit_post_link( get_the_ID() ),
							'attrs'  => '',
						);

						$row_actions[] = array(
							'action' => 'deactivate',
							'text'   => __( 'Deactivate', WCST_SLUG ),
							'link'   => $deactivation_url,
							'attrs'  => '',
						);
					}
					$row_actions[] = array(
						'action' => 'wcct_duplicate',
						'text'   => __( 'Duplicate', WCST_SLUG ),
						'link'   => wp_nonce_url( add_query_arg( 'page', 'wc-settings', add_query_arg( 'tab', self::get_wc_settings_tab_slug(), add_query_arg( 'action', 'wcst-duplicate', add_query_arg( 'postid', get_the_ID(), add_query_arg( 'trigger', $trigger ) ), network_admin_url( 'admin.php' ) ) ) ), 'wcst-duplicate' ),
						'attrs'  => '',
					);

					$row_actions[] = array(
						'action' => 'delete',
						'text'   => __( 'Delete Permanently', WCST_SLUG ),
						'link'   => get_delete_post_link( get_the_ID(), '', true ),
						'attrs'  => '',
					);
//                    $row_actions[] = array(
//                        'action' => 'export',
//                        'text' => __('Export', WCST_SLUG),
//                        'link' => $exporturl,
//                        'attrs' => '',
//                    );


					$showon = array();
					if ( get_post_meta( get_the_ID(), '_wcst_data_showon_cart', true ) == "yes" ) {
						array_push( $showon, 'Cart' );
					}
					if ( get_post_meta( get_the_ID(), '_wcst_data_showon_grid', true ) == "yes" ) {
						array_push( $showon, 'Grids' );
					}
					if ( get_post_meta( get_the_ID(), '_wcst_data_showon_product', true ) == "yes" ) {


						$position = get_post_meta( get_the_ID(), '_wcst_data_position', true );
						array_push( $showon, 'Product (' . $position_array[ $position ] . ')' );
					}


					array_push( $found_posts, array(
						'id'             => get_the_ID(),
						'trigger_status' => $status,
						'row_actions'    => $row_actions,
						'showon'         => $showon,
						'menu_order'     => get_post_field( 'menu_order', get_the_ID() )
					) );
				}
			}

			return $found_posts;
		}

		public static function get_wc_settings_tab_slug() {
			return 'xl-sales-trigger';
		}

		public static function pr( $arr, $is_exit = false ) {
			echo '<pre>';
			print_r( $arr );
			echo '</pre>';

			if ( $is_exit ) {
				exit;
			}
		}

		public static function register_post_status() {

			register_post_status( WCST_Common::get_trigger_post_type_slug() . 'disabled', array(
				'label'                     => __( 'Disabled', WCST_SLUG ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Disabled <span class="count">(%s)</span>', 'Disabled <span class="count">(%s)</span>', WCST_SLUG ),
			) );
		}

		/*
		 *  register_post_status
		 *
		 *  This function will register custom post statuses
		 *
		 *  @type	function
		 *  @date	22/10/2015
		 *  @since	5.3.2
		 *
		 *  @param	$post_id (int)
		 *  @return	$post_id (int)
		 */

		public static function get_parent_slug( $slug ) {

			foreach ( WCST_Triggers::get_triggers() as $key => $trigger_list ) {


				foreach ( $trigger_list['triggers'] as $trigger ) {

					if ( $trigger['slug'] == $slug ) {
						return $key;
					}
				}
			}
		}

		public static function get_timezone_difference() {
			$date_obj_utc = new DateTime( "now", new DateTimeZone( 'UTC' ) );

			$diff = timezone_offset_get( timezone_open( self::get_site_timezone_string() ), $date_obj_utc );

			return $diff;
		}

		public static function get_site_timezone_string() {


			// if site timezone string exists, return it
			if ( $timezone = get_option( 'timezone_string' ) ) {
				return $timezone;
			}

			// get UTC offset, if it isn't set then return UTC
			if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
				return 'UTC';
			}

			// get timezone using offset manual
			return self::get_timezone_by_offset( $utc_offset );
		}

		/**
		 * Function to get timezone string based on specified offset
		 * @see WCST_Common::get_site_timezone_string()
		 *
		 * @param $offset
		 *
		 * @return string
		 */
		public static function get_timezone_by_offset( $offset ) {
			switch ( $offset ) {
				case '-12':
					return 'GMT-12';
					break;
				case '-11.5':
					return 'Pacific/Niue'; // 30 mins wrong
					break;
				case '-11':
					return 'Pacific/Niue';
					break;
				case '-10.5':
					return 'Pacific/Honolulu'; // 30 mins wrong
					break;
				case '-10':
					return 'Pacific/Tahiti';
					break;
				case '-9.5':
					return 'Pacific/Marquesas';
					break;
				case '-9':
					return 'Pacific/Gambier';
					break;
				case '-8.5':
					return 'Pacific/Pitcairn'; // 30 mins wrong
					break;
				case '-8':
					return 'Pacific/Pitcairn';
					break;
				case '-7.5':
					return 'America/Hermosillo'; // 30 mins wrong
					break;
				case '-7':
					return 'America/Hermosillo';
					break;
				case '-6.5':
					return 'America/Belize'; // 30 mins wrong
					break;
				case '-6':
					return 'America/Belize';
					break;
				case '-5.5':
					return 'America/Belize'; // 30 mins wrong
					break;
				case '-5':
					return 'America/Panama';
					break;
				case '-4.5':
					return 'America/Lower_Princes'; // 30 mins wrong
					break;
				case '-4':
					return 'America/Curacao';
					break;
				case '-3.5':
					return 'America/Paramaribo'; // 30 mins wrong
					break;
				case '-3':
					return 'America/Recife';
					break;
				case '-2.5':
					return 'America/St_Johns';
					break;
				case '-2':
					return 'America/Noronha';
					break;
				case '-1.5':
					return 'Atlantic/Cape_Verde'; // 30 mins wrong
					break;
				case '-1':
					return 'Atlantic/Cape_Verde';
					break;
				case '+1':
					return 'Africa/Luanda';
					break;
				case '+1.5':
					return 'Africa/Mbabane'; // 30 mins wrong
					break;
				case '+2':
					return 'Africa/Harare';
					break;
				case '+2.5':
					return 'Indian/Comoro'; // 30 mins wrong
					break;
				case '+3':
					return 'Asia/Baghdad';
					break;
				case '+3.5':
					return 'Indian/Mauritius'; // 30 mins wrong
					break;
				case '+4':
					return 'Indian/Mauritius';
					break;
				case '+4.5':
					return 'Asia/Kabul';
					break;
				case '+5':
					return 'Indian/Maldives';
					break;
				case '+5.5':
					return 'Asia/Kolkata';
					break;
				case '+5.75':
					return 'Asia/Kathmandu';
					break;
				case '+6':
					return 'Asia/Urumqi';
					break;
				case '+6.5':
					return 'Asia/Yangon';
					break;
				case '+7':
					return 'Antarctica/Davis';
					break;
				case '+7.5':
					return 'Asia/Jakarta'; // 30 mins wrong
					break;
				case '+8':
					return 'Asia/Manila';
					break;
				case '+8.5':
					return 'Asia/Pyongyang';
					break;
				case '+8.75':
					return 'Australia/Eucla';
					break;
				case '+9':
					return 'Asia/Tokyo';
					break;
				case '+9.5':
					return 'Australia/Darwin';
					break;
				case '+10':
					return 'Australia/Brisbane';
					break;
				case '+10.5':
					return 'Australia/Lord_Howe';
					break;
				case '+11':
					return 'Antarctica/Casey';
					break;
				case '+11.5':
					return 'Pacific/Auckland'; // 30 mins wrong
					break;
				case '+12':
					return 'Pacific/Wallis';
					break;
				case '+12.75':
					return 'Pacific/Chatham';
					break;
				case '+13':
					return 'Pacific/Fakaofo';
					break;
				case '+13.75':
					return 'Pacific/Chatham'; // 1 hr wrong
					break;
				case '+14':
					return 'Pacific/Kiritimati';
					break;
				default:
					return 'UTC';
					break;
			}
		}

		public static function get_site_time() {

			//returning UTC 0
			return current_time( 'timestamp', true );

		}

		public static function get_products_sales( $date_limit_choice = "1", $settings ) {

			global $wpdb;
			$xl_transient_obj                 = XL_Transient::get_instance();
			$xl_cache_obj                     = XL_Cache::get_instance();
			$wpdb->woocommerce_order_items    = $wpdb->prefix . 'woocommerce_order_items';
			$wpdb->woocommerce_order_itemmeta = $wpdb->prefix . 'woocommerce_order_itemmeta';

			$dates = self::get_date_from_to_for_query( $date_limit_choice, $settings );

			$date_from = $dates['from'];
			$date_to   = $dates['to'];

			$cache_key = md5( 'wcst_best_seller_' . $date_from . '_' . $date_to );

			/**
			 * Setting xl cache and transient for category query1 result data
			 */
			$cache_data = $xl_cache_obj->get_cache( $cache_key, 'sales-trigger' );
			if ( false !== $cache_data ) {
				$results = $cache_data;
			} else {
				$cache_key_transient = false;
				if ( WCST_Common::$is_force_transient === false ) {
					$cache_key_transient = $xl_transient_obj->get_transient( $cache_key, 'sales-trigger' );
				}
				if ( false !== $cache_key_transient ) {
					$results = $cache_key_transient;
				} else {
					$wc_states = self::wcst_get_wc_states();
					$query     = $wpdb->prepare( "SELECT order_item_meta__product_id.meta_value as product_id,SUM( order_item_meta__qty.meta_value) as order_item_qty FROM `" . $wpdb->posts . "` AS posts INNER JOIN `" . $wpdb->woocommerce_order_items . "` AS order_items ON (posts.ID = order_items.order_id) AND (order_items.order_item_type = 'line_item') INNER JOIN `" . $wpdb->woocommerce_order_itemmeta . "` AS order_item_meta__product_id ON (order_items.order_item_id = order_item_meta__product_id.order_item_id)  AND (order_item_meta__product_id.meta_key = '_product_id') INNER JOIN `" . $wpdb->woocommerce_order_itemmeta . "` AS order_item_meta__qty ON (order_items.order_item_id = order_item_meta__qty.order_item_id)  AND (order_item_meta__qty.meta_key = '_qty') WHERE posts.post_type IN ( 'shop_order','shop_order_refund' ) AND posts.post_status IN ('" . implode( '\',\'', $wc_states ) . "') AND posts.post_date >= %s AND posts.post_date < %s GROUP BY product_id ORDER BY order_item_qty DESC", $date_from . " 00:00:01", $date_to . " 23:59:59" );

					WCST_Common::insert_log( $query );

					$results = $wpdb->get_results( $query, ARRAY_A );

					$xl_transient_obj->set_transient( $cache_key, $results, 3600, 'sales-trigger' );
				}
				$xl_cache_obj->set_cache( $cache_key, $results, 'sales-trigger' );
			}

			return $results;
		}

		public static function get_current_date( $format ) {
			$date_object = new DateTime();
			$date_object->setTimestamp( current_time( 'timestamp' ) );

			return $date_object->format( $format );
		}

		public static function get_date_modified( $mod = false, $format ) {


			$date_object = new DateTime();
			$date_object->setTimestamp( current_time( 'timestamp' ) );

			if ( ! $mod ) {
				return $date_object->format( ( $format ) );
			}

			return $date_object->modify( $mod )->format( ( $format ) );
		}

		public static function insert_log( $string, $filename = "debug" ) {
			wcst_logging( $string, $filename . "_trigger.txt", "a" );
		}

		public static function wcst_xl_init() {
			XL_Common::include_xl_core();
		}

		public static function update_menu_order() {
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
				}
			}
		}

		public static function parse_date( $date, $format = 'mdy' ) {
			$date_info = array();

			$position = substr( $format, 0, 3 );

			if ( is_array( $date ) ) {

				switch ( $position ) {
					case 'mdy' :
						$date_info['month'] = rgar( $date, 0 );
						$date_info['day']   = rgar( $date, 1 );
						$date_info['year']  = rgar( $date, 2 );
						break;

					case 'dmy' :
						$date_info['day']   = rgar( $date, 0 );
						$date_info['month'] = rgar( $date, 1 );
						$date_info['year']  = rgar( $date, 2 );
						break;

					case 'ymd' :
						$date_info['year']  = rgar( $date, 0 );
						$date_info['month'] = rgar( $date, 1 );
						$date_info['day']   = rgar( $date, 2 );
						break;
				}

				return $date_info;
			}

			$date = preg_replace( "|[/\.]|", '-', $date );
			if ( preg_match( '/^(\d{1,4})-(\d{1,2})-(\d{1,4})$/', $date, $matches ) ) {

				if ( strlen( $matches[1] ) == 4 ) {
					//format yyyy-mm-dd
					$date_info['year']  = $matches[1];
					$date_info['month'] = $matches[2];
					$date_info['day']   = $matches[3];
				} else if ( $position == 'mdy' ) {
					//format mm-dd-yyyy
					$date_info['month'] = $matches[1];
					$date_info['day']   = $matches[2];
					$date_info['year']  = $matches[3];
				} else {
					//format dd-mm-yyyy
					$date_info['day']   = $matches[1];
					$date_info['month'] = $matches[2];
					$date_info['year']  = $matches[3];
				}
			}

			return $date_info;
		}

		public static function get_time_directives_for_js( $format ) {

			$format = str_replace( '%h', '%H', $format );
			$format = str_replace( '%H', '%H', $format );
			$format = str_replace( '%a', '%D', $format );
			$format = str_replace( '%i', '%M', $format );
			$format = str_replace( '%I', '%M', $format );
			$format = str_replace( '%s', '%S', $format );


			return $format;
			exit;
		}

		public static function get_date_from_to_for_query( $date_limit_choice = "1", $settings ) {
			$date_to   = self::get_current_date( "Y-m-d" );
			$date_from = self::get_date_modified( "-30 days", "Y-m-d" );
			switch ( $date_limit_choice ) {

				case "-4":
					$date_from = WCST_Common::get_date_modified( "-1 day", "Y-m-d" );
					break;
				case "-3":
					$date_from = WCST_Common::get_date_modified( "-3 days", "Y-m-d" );
					break;
				case "-2":
					$date_from = WCST_Common::get_date_modified( "-7 days", "Y-m-d" );
					break;
				case "-1":
					$date_from = WCST_Common::get_date_modified( "-15 days", "Y-m-d" );
					break;
				case "1":
					$date_from = WCST_Common::get_date_modified( "-30 days", "Y-m-d" );
					break;
				case "2":
					$date_from = WCST_Common::get_date_modified( "-3 months", "Y-m-d" );
					break;
				case "3":
					$date_from = WCST_Common::get_date_modified( "-6 months", "Y-m-d" );
					break;
				case "4":
					$date_from = WCST_Common::get_date_modified( "-12 months", "Y-m-d" );
					break;
				case "5":
					$date_from = $settings['date_from'];
					$date_to   = $settings['date_to'];
					break;
				default:
			}

			return array( 'from' => $date_from, 'to' => $date_to );
		}

		public static function wcst_license_check_schedule() {

			if ( ! wp_next_scheduled( 'wcst_maybe_schedule_check_license' ) ) {
				wp_schedule_event( current_time( 'timestamp' ), 'weekly', 'wcst_maybe_schedule_check_license' );
			}
		}

		public static function check_license_state() {
			$license = new WCST_EDD_License( WCST_PLUGIN_FILE, WCST_FULL_NAME, WCST_VERSION, 'xlplugins', null, apply_filters( "wcst_edd_api_url", "https://xlplugins.com/" ) );


			$license->weekly_license_check();
		}

		public static function check_query_params() {
			global $wpdb;

			$force_debug = filter_input( INPUT_GET, 'wcst_force_debug' );

			if ( $force_debug === "yes" ) {
				self::$is_force_debug = true;
			}

			$force_transients_remove = filter_input( INPUT_GET, 'wcst_force_transients_remove' );

			if ( $force_transients_remove === "yes" ) {
				self::$is_force_transient = true;
			}

			$all_transients_remove = filter_input( INPUT_GET, 'wcst_all_transient_remove' );

			if ( $all_transients_remove === "yes" ) {
				// flushing object cache
				//wp_cache_flush();

				// deleting sales trigger query transient data
				if ( class_exists( 'XL_Transient' ) ) {
					$xl_transient_obj = XL_Transient::get_instance();
					$xl_transient_obj->delete_all_transients( 'sales-trigger' );
				}
			}
		}

		public static function add_to_xl_tracking_data( $data ) {

			if ( ! isset( $data['wcst_trigger_info'] ) ) {

				$info = self::get_post_table_data();

				$data['wcst_trigger_info'] = $info;
			}

			return $data;
		}

		public static function get_hard_text() {
			$array = array(
				'someone' => __( 'Someone', WCST_SLUG ),
				'other'   => __( 'other', WCST_SLUG ),
				'others'  => __( 'others', WCST_SLUG ),
				'from'    => __( 'from', WCST_SLUG ),
				'in'      => __( 'in', WCST_SLUG ),
				'month'   => __( 'month', WCST_SLUG ),
				'months'  => __( 'months', WCST_SLUG ),
				'week'    => __( 'week', WCST_SLUG ),
				'weeks'   => __( 'weeks', WCST_SLUG ),
				'day'     => __( 'day', WCST_SLUG ),
				'days'    => __( 'days', WCST_SLUG ),
				'hr'      => __( 'hr', WCST_SLUG ),
				'hrs'     => __( 'hrs', WCST_SLUG ),
				'min'     => __( 'min', WCST_SLUG ),
				'mins'    => __( 'mins', WCST_SLUG ),
				'sec'     => __( 'sec', WCST_SLUG ),
				'secs'    => __( 'secs', WCST_SLUG ),
				'&'       => __( '&', WCST_SLUG ),
			);


			$options = self::get_global_options();
			foreach ( $array as $key => &$val ) {
				$array[ $key ] = ( isset( $options[ 'wcst_global_replace_' . $key ] ) ) ? $options[ 'wcst_global_replace_' . $key ] : $val;
			}


			/**
			 * Compatibility with WPML
			 */
			if ( function_exists( 'icl_t' ) ) {

				foreach ( $array as $key => &$val ) {
					$array[ $key ] = icl_t( 'admin_texts_wcst_global_options', '[wcst_global_options]' . $key, $val );
				}
			}


			$array = apply_filters( 'wcst_modify_hard_values', $array );

			return $array;
		}

		public static function wcst_get_between( $content, $start, $end ) {
			$r = explode( $start, $content );
			if ( isset( $r[1] ) ) {
				$r = explode( $end, $r[1] );

				return $r[0];
			}

			return '';
		}

		public static function wcst_woocommerce_product_type_variations() {
			return apply_filters( 'wcst_woocommerce_product_type_variations', array(
				'variable',
				'variable-subscription'
			) );
		}

		public static function wcst_set_default_wc_states() {
			return apply_filters( 'wcst_modify_wc_order_states', array(
				'wc-processing',
				'wc-on-hold',
				'wc-completed'
			) );
		}

		public static function wcst_get_wc_states() {

			return self::wcst_get_global_setting( 'wcst_global_replace_order_status' );
		}

		public static function wcst_get_global_setting( $key = '' ) {
			$global_options = self::get_global_options();
			$global_default = self::get_default_global();
			$global_options = wp_parse_args( $global_options, $global_default );

			if ( ! empty( $key ) ) {
				return $global_options[ $key ];
			}

			return $global_options;
		}

		public static function get_global_options() {
			if ( self::$global_options === null ) {
				self::$global_options = get_option( 'wcst_global_options' );
			}

			return self::$global_options;
		}

		public static function get_default_global() {
			$default_settings = array(
				'wcst_global_replace_someone'      => 'someone',
				'wcst_global_replace_other'        => 'other',
				'wcst_global_replace_others'       => 'others',
				'wcst_global_replace_form'         => 'from',
				'wcst_global_replace_in'           => 'in',
				'wcst_global_replace_month'        => 'month',
				'wcst_global_replace_months'       => 'months',
				'wcst_global_replace_week'         => 'week',
				'wcst_global_replace_weeks'        => 'weeks',
				'wcst_global_replace_day'          => 'day',
				'wcst_global_replace_days'         => 'days',
				'wcst_global_replace_hrs'          => 'hrs',
				'wcst_global_replace_hr'           => 'hr',
				'wcst_global_replace_min'          => 'min',
				'wcst_global_replace_mins'         => 'mins',
				'wcst_global_replace_sec'          => 'sec',
				'wcst_global_replace_secs'         => 'secs',
				'wcst_global_replace_&'            => '&',
				'wcst_global_replace_order_status' => WCST_Common::wcst_set_default_wc_states(),
				'wcst_global_show_reviews_text'    => 'no',
			);

			return $default_settings;

		}

		public static function hide_admin_hide_elements() {

			if ( filter_input( INPUT_GET, 'wcst_remove' ) !== null ) {
				$get_notices = get_option( 'xl_admin_notices', array() );
				array_push( $get_notices, filter_input( INPUT_GET, 'wcst_remove' ) );
				update_option( 'xl_admin_notices', $get_notices );
			}

		}


		public static function reset_timer_time() {


			if ( filter_input( INPUT_POST, 'endDate' ) === null ) {
				wp_send_json( array() );
			}
			/**
			 * Comparing end timestamp with the current timestamp
			 * and getting difference
			 */
			$date_obj            = new DateTime();
			$current_Date_object = clone $date_obj;
			$current_Date_object->setTimezone( new DateTimeZone( WCST_Common::get_site_timezone_string() ) );
			$date_obj->setTimezone( new DateTimeZone( WCST_Common::get_site_timezone_string() ) );
			$date_obj->setTimestamp( $_POST['endDate'] );

			$interval = $current_Date_object->diff( $date_obj );
			$x        = $interval->format( '%R' );

			$is_left = $x;
			if ( $is_left == "+" ) {
				$total_seconds_left = 0;
				$total_seconds_left = $total_seconds_left + ( YEAR_IN_SECONDS * $interval->y );
				$total_seconds_left = $total_seconds_left + ( MONTH_IN_SECONDS * $interval->m );
				$total_seconds_left = $total_seconds_left + ( DAY_IN_SECONDS * $interval->d );
				$total_seconds_left = $total_seconds_left + ( HOUR_IN_SECONDS * $interval->h );
				$total_seconds_left = $total_seconds_left + ( MINUTE_IN_SECONDS * $interval->i );
				$total_seconds_left = $total_seconds_left + $interval->s;
			} else {
				$total_seconds_left = 0;
			}


			wp_send_json( array( 'diff' => $total_seconds_left, 'old_diff' => filter_input( INPUT_POST, 'endDate' ) ) );

		}

		public static function reset_current_time() {

			if ( filter_input( INPUT_GET, 'wcstajax' ) == 'yes' && filter_input( INPUT_POST, 'wcst_action' ) == 'wcst_reset_current_time' ) {

				wp_send_json( array( 'utc0_time' => WCST_Common::get_site_time() ) );
			}

		}

		public static function add_license_info( $localized_data ) {
			$licenses      = XL_licenses::get_instance()->get_data();
			$license_state = 'Invalid';
			if ( $licenses && count( $licenses ) > 0 ) {
				foreach ( $licenses as $key => $license ) {
					if ( $key !== sha1( WCST_PLUGIN_BASENAME ) ) {
						continue;
					}
					if ( $license['product_status'] == 'active' ) {
						$license_state = 'Valid';
					}
				}
			}
			$localized_data['l'] = $license_state;

			return $localized_data;
		}

		public static function String2Hex( $string ) {
			$hex = '';
			for ( $i = 0; $i < strlen( $string ); $i ++ ) {
				$hex .= dechex( ord( $string[ $i ] ) );
			}

			return $hex;
		}


		public static function Hex2String( $hex ) {
			$string = '';
			for ( $i = 0; $i < strlen( $hex ) - 1; $i += 2 ) {
				$string .= chr( hexdec( $hex[ $i ] . $hex[ $i + 1 ] ) );
			}

			return $string;
		}

		public static function wcst_rule_stock_status( $options ) {
			if ( WCST_Compatibility::is_wc_version_gt_eq( '3.0' ) ) {
				$options['2'] = __( 'On backorder', 'woocommerce' );
			}

			return $options;
		}

	}

}
