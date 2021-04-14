<?php

class WCST_XL_Support {

	public $full_name = "XL WooCommerce Sales Triggers";
	public $is_license_needed = true;
	public $license_instance;
	public $expected_url;

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = 'Sales Triggers';
	protected $slug = 'wcst';
	protected $encoded_basename = '';

	public function __construct() {
		$this->expected_url     = admin_url( 'admin.php?page=xlplugins' );
		$this->encoded_basename = sha1( WCST_PLUGIN_BASENAME );

		/**
		 * XL CORE HOOKS
		 */
		add_filter( "xl_optin_notif_show", array( $this, 'wcst_xl_show_optin_pages' ), 10, 1 );


		add_action( 'admin_init', array( $this, 'wcst_xl_expected_slug' ), 9.1 );
		add_action( 'maybe_push_optin_notice_state_action', array( $this, 'wcst_try_push_notification_for_optin' ), 10 );

		add_action( 'admin_init', array( $this, 'modify_api_args_if_wcst_dashboard' ), 20 );
		add_filter( 'extra_plugin_headers', array( $this, 'extra_woocommerce_headers' ) );

		add_action( 'xl_before_dashboard_page', array( $this, 'add_css_for_wcst_xl_dashboard_pages' ) );
		add_filter( 'add_menu_classes', array( $this, 'modify_menu_classes' ) );
		add_action( 'admin_init', array( $this, 'wcst_xl_parse_request_and_process' ), 15 );

		add_action( 'admin_init', array( $this, 'init_edd_licensing' ), 1 );
		add_filter( 'xl_plugins_license_needed', array( $this, 'register_support' ) );
		add_action( 'xl_licenses_submitted', array( $this, 'process_licensing_form' ) );
		add_action( 'xl_deactivate_request', array( $this, 'maybe_process_deactivation' ) );

		add_filter( 'xl_dashboard_tabs', array( $this, 'wcst_modify_tabs' ), 999, 1 );
		add_filter( 'xl_after_license_table_notice', array( $this, 'wcst_after_license_table_notice' ), 999, 1 );

		add_action( 'xl_support_right_area', array( $this, 'wcst_add_right_section_on_xlpages' ) );
		add_action( 'xl_licenses_right_content', array( $this, 'wcst_add_right_section_on_xlpages' ) );

		add_action( 'wcst_options_page_right_content', array( $this, 'wcst_options_page_right_content' ), 10 );
		add_action( 'wcst_options_page_right_content', array( $this, 'wcst_xl_other_products' ), 9 );

		add_action( 'admin_menu', array( $this, 'add_menus' ), 80.1 );
		add_action( 'admin_menu', array( $this, 'add_wcst_menu' ), 85.1 );
		add_action( 'xl_tabs_modal_licenses', array( $this, 'schedule_license_check' ), 1 );
		add_filter( 'xl_global_tracking_data', array( $this, 'xl_add_administration_emails' ) );
		add_filter( 'xl_api_call_agrs', array( $this, 'xl_add_license_data_on_deactivation' ) );
		add_action( 'admin_init', array( $this, 'download_tools_settings' ), 2 );
		add_action( 'xl_tools_after_content', array( $this, "export_tools_after_content" ) );
		add_action( 'xl_tools_after_content', array( $this, "export_xl_tools_right_area" ) );
		add_action( "xl_fetch_tools_data", array( $this, "xl_fetch_tools_data" ), 10, 2 );
	}

	public function wcst_xl_show_optin_pages( $is_show ) {
		return true;
	}

	public function wcst_xl_expected_slug() {
		if ( isset( $_GET['page'] ) && ( $_GET['page'] == "xlplugins" || $_GET['page'] == "xlplugins-support" || $_GET['page'] == "xlplugins-addons" ) ) {
			XL_dashboard::set_expected_slug( $this->slug );
		}
		XL_dashboard::set_expected_url( $this->expected_url );

		/**
		 * Pushing notifications for invalid licenses found in ecosystem
		 */
		$licenses         = XL_licenses::get_instance()->get_data();
		$invalid_licenses = array();
		if ( $licenses && count( $licenses ) > 0 ) {
			foreach ( $licenses as $key => $license ) {

				if ( $license['product_status'] == "invalid" ) {
					$invalid_licenses[] = $license['plugin'];
				}
			}
		}


		if ( ! XL_admin_notifications::has_notification( 'license_needs_attention' ) && count( $invalid_licenses ) > 0 ) {

			$get_license_data = get_option( $this->edd_slugify_module_name( $this->full_name ) . "license_data" );


			if ( $get_license_data && is_object( $get_license_data ) && ( ( isset( $get_license_data->license ) && $get_license_data->license === "expired" ) || isset( $get_license_data->error ) && $get_license_data->error === "expired" ) ) {
				$license_invalid_text = sprintf( __( '<p>Your 6 months support for <strong>%s</strong> has expired. You loose Availability of the author to answer questions and automatic dashboard upgrades. However, you will continue to receive manual updates via Envato. <a href="%s" target="_blank">Learn More</a></p> <a type="button" class="notice-dismiss" href="%s"><span class="screen-reader-text">Dismiss this notice.</span></a>', WCST_SLUG ), $this->full_name, 'https://xlplugins.com/documentation/woocommerce-sales-trigger/licensing/', admin_url( "admin.php?page=wc-settings&tab=xl-sales-trigger&wcst_remove=license_needs_attention" ) );

				XL_admin_notifications::add_notification( array(
					'license_needs_attention_' . WCST_SLUG => array(
						'type'           => 'error xl_dismiss',
						'is_dismissible' => true,
						'content'        => $license_invalid_text,
					)
				) );
			} else {
				$license_invalid_text = sprintf( __( '<p>You are <strong>not receiving</strong> Latest Updates, New Features, Security Updates &amp; Bug Fixes for <strong>%s</strong>. <a href="%s">Click Here To Fix This</a>.</p>', WCST_SLUG ), current( $invalid_licenses ), add_query_arg( array( 'tab' => 'licenses' ), $this->expected_url ) );

				XL_admin_notifications::add_notification( array(
					'license_needs_attention' => array(
						'type'           => 'error',
						'is_dismissible' => false,
						'content'        => $license_invalid_text,
					)
				) );
			}
		}
	}

	public function wcst_metabox_always_open( $classes ) {
		if ( ( $key = array_search( 'closed', $classes ) ) !== false ) {
			unset( $classes[ $key ] );
		}

		return $classes;
	}

	public function modify_api_args_if_wcst_dashboard() {
		if ( XL_dashboard::get_expected_slug() == $this->slug ) {
			add_filter( 'xl_api_call_agrs', array( $this, 'modify_api_args_for_gravityxl' ) );
			XL_dashboard::register_dashboard( array( 'parent' => array( 'woocommerce' => "WooCommerce Add-ons" ), 'name' => $this->slug ) );
		}
	}

	public function xlplugins_page() {
		if ( ! isset( $_GET['tab'] ) ) {
			XL_dashboard::$selected = "licenses";
		}
		XL_dashboard::load_page();
	}

	public function xlplugins_support_page() {
		if ( ! isset( $_GET['tab'] ) ) {
			XL_dashboard::$selected = "support";
		}
		XL_dashboard::load_page();
	}

	public function xlplugins_plugins_page() {
		XL_dashboard::$selected = "plugins";
		XL_dashboard::load_page();
	}

	public function modify_api_args_for_gravityxl( $args ) {
		if ( isset( $args['edd_action'] ) && $args['edd_action'] == "get_xl_plugins" ) {
			$args['attrs']['tax_query'] = array(
				array(
					'taxonomy' => 'xl_edd_tax_parent',
					'field'    => 'slug',
					'terms'    => 'woocommerce',
					'operator' => 'IN'
				)
			);
		}
		$args['purchase'] = WCST_PURCHASE;

		return $args;
	}

	public function wcst_try_push_notification_for_optin() {
		if ( ! XL_admin_notifications::has_notification( 'xl_optin_notice' ) ) {
			XL_admin_notifications::add_notification( array(
					'xl_optin_notice' => array(
						'type'    => 'info',
						'content' => sprintf( '<p style=\'font-size: 22px;margin-bottom: 0px;\'>How can we make your experience better? </p>
        <p>At <a target="_blank" href=\'%s\'>XLPlugins</a>, we are determined to ensure that <a target="_blank" href=\'%s\'>WooCommerce Sales Trigger</a> becomes a valuable addition to your WooCommerce store.<p>But we can\'t do it alone. We need your help.</p>
       <p>Click the button \'Allow\' to send <a target="_blank"  href=\'%s\'>non-sensitive data</a>. If you skip this, that\'s okay! Our Plugins will still work just fine.</p>     <p><a href=\'%s\' class=\'button button-primary\'>Allow</a> <a href=\'%s\' class=\'button button-secondary\'>No thanks</a> <a style="float: right;" target="_blank" href=\'%s\'>Know More</a></p> ', esc_url( "https://xlplugins.com/" ), esc_url( "https://xlplugins.com/xl-woocommerce-sales-triggers/" ), esc_url( "https://xlplugins.com/data-collection-policy/" ), esc_url( wp_nonce_url( add_query_arg( array(
							'xl-optin-choice' => 'yes',
							'ref'             => filter_input( INPUT_GET, 'page' )
						) ), 'xl_optin_nonce', '_xl_optin_nonce' ) ), esc_url( wp_nonce_url( add_query_arg( 'xl-optin-choice', 'no' ), 'xl_optin_nonce', '_xl_optin_nonce' ) ), esc_url( "https://xlplugins.com/data-collection-policy/" ) )
					)
				)
			);
		}
	}

	/**
	 * Adding Pugworhty Header to tell wordpress to read one extra params while reading plugin's header info/. <br/>
	 * Hooked over `extra_plugin_headers`
	 * @since 1.0.0
	 *
	 * @param array $headers already registered arrays
	 *
	 * @return type
	 */
	public function extra_woocommerce_headers( $headers ) {
		array_push( $headers, 'XL' );

		return $headers;
	}

	public function add_css_for_wcst_xl_dashboard_pages() {
		if ( $this->slug == XL_dashboard::get_expected_slug() ) {
			/*
			  ?>
			  <style>
			  .wp-filter {
			  display: none !important;
			  }
			  </style>
			  <?php
			 */
		}
	}

	public function modify_menu_classes( $menu ) {
		return $menu;
	}

	public function wcst_xl_parse_request_and_process() {
		$instance_support = XL_Support::get_instance();

		if ( $this->slug == XL_dashboard::get_expected_slug() && isset( $_POST['xl_submit_support'] ) ) {

			if ( filter_input( INPUT_POST, 'choose_addon' ) == "" || filter_input( INPUT_POST, 'comments' ) == "" ) {
				$instance_support->validation = false;
				XL_admin_notifications::add_notification( array(
					'support_request_failure' => array(
						'type'           => 'error',
						'is_dismissable' => true,
						'content'        => __( '<p> Unable to submit your request.All fields are required. Please ensure that all the fields are filled out.</p>', WCST_SLUG ),
					)
				) );
			} else {
				$instance_support->xl_maybe_push_support_request( $_POST );
			}
		}
	}

	public function register_support( $plugins ) {
		$status = "invalid";
		$renew  = "Please Activate";
		if ( get_option( '' . $this->edd_slugify_module_name( $this->full_name ) . '_license_active' ) == "valid" ) {

			$status      = "active";
			$licensedata = get_option( $this->edd_slugify_module_name( $this->full_name ) . "license_data" );


			$renew = $licensedata->expires;
		}


		$plugins[ $this->encoded_basename ] = array(
			'plugin'            => $this->full_name,
			'product_version'   => WCST_VERSION,
			'product_status'    => $status,
			'license_expiry'    => $renew,
			'product_file_path' => $this->encoded_basename,
			'license_info'      => get_option( $this->edd_slugify_module_name( $this->full_name ) . 'license_data' ),
			'existing_key'      => get_option( 'xl_licenses_' . $this->edd_slugify_module_name( $this->full_name ) )
		);

		return $plugins;
	}

	/**
	 * License management helper function to create a slug that is friendly with edd
	 *
	 * @param type $name
	 *
	 * @return type
	 */
	public function edd_slugify_module_name( $name ) {
		return preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $name ) ) );
	}

	public function process_licensing_form( $posted_data ) {
		if ( isset( $posted_data['license_keys'][ $this->encoded_basename ] ) ) {
			$shortname = $this->edd_slugify_module_name( $this->full_name );
			update_option( 'xl_licenses_' . $shortname, $posted_data['license_keys'][ $this->encoded_basename ], false );
			$this->license_instance->activate_license( $posted_data['license_keys'][ $this->encoded_basename ] );
		}
	}

	/**
	 * Validate is it is for email product deactivation
	 *
	 * @param type $posted_data
	 */
	public function maybe_process_deactivation( $posted_data ) {
		if ( isset( $posted_data['filepath'] ) && $posted_data['filepath'] == $this->encoded_basename ) {
			$this->license_instance->deactivate_license();
			wp_safe_redirect( 'admin.php?page=' . $posted_data['page'] . "&tab=" . $posted_data['tab'] );
		}
	}

	public function wcst_modify_tabs( $tabs ) {
		if ( $this->slug == XL_dashboard::get_expected_slug() ) {
			return array();
		}

		return $tabs;
	}

	public function wcst_after_license_table_notice( $notice ) {
		return 'Note: You need to have a valid license key to receiving updates for these plugins. Click here to get your <a href="https://xlplugins.com/manage-licenses/" target="_blank">License Keys</a>.';
	}

	public function wcst_add_right_section_on_xlpages() {
		?>
        <div class="postbox ">
            <div class="inside">
                <h3>Generate License Key for Envato Users</h3>
                <p><strong>Envato Purchase Code is not a License Key. Follow below steps to generate a License Key.</strong></p>
                <ul>
                    <li>1. Visit <a href="https://xlplugins.com/manage-licenses/" target="_blank">License Activation</a> page.</li>
                    <li>2. Login to Authenticate with Envato.</li>
                    <li>3. Select Item Purchased & Generate License Key.</li>
                    <li>4. Enter License Key on left side & Press Activate License.</li>
                </ul>
            </div>
        </div>
		<?php
	}

	public function init_edd_licensing() {
		if ( is_admin() && class_exists( 'WCST_EDD_License' ) && $this->is_license_needed ) {
			$this->license_instance = new WCST_EDD_License( WCST_PLUGIN_FILE, WCST_FULL_NAME, WCST_VERSION, 'xlplugins', null, apply_filters( "wcst_edd_api_url", "https://xlplugins.com" ) );
		}
	}

	/**
	 * Adding WooCommerce sub-menu for global options
	 */
	public function add_menus() {
		if ( ! XL_dashboard::$is_core_menu ) {

			add_menu_page( __( 'XLPlugins', WCST_SLUG ), __( 'XLPlugins', WCST_SLUG ), 'manage_woocommerce', 'xlplugins', array( $this, 'xlplugins_page' ), null, '59.5' );
			add_submenu_page( 'xlplugins', __( 'Licenses', WCST_SLUG ), __( 'License', WCST_SLUG ), 'manage_woocommerce', 'xlplugins' );
			XL_dashboard::$is_core_menu = true;
		}
	}

	public function add_wcst_menu() {
		add_submenu_page( 'xlplugins', __( 'Sales Triggers', WCST_SLUG ), __( 'Sales Triggers', WCST_SLUG ), 'manage_woocommerce', 'admin.php?page=wc-settings&tab=' . WCST_Common::get_wc_settings_tab_slug() . '', false );
	}

	public function wcst_xl_other_products() {
		$other_products = array();
		if ( ! class_exists( 'WCCT_Core' ) ) {
			$finale_link              = add_query_arg( array(
				'utm_source'   => 'sales-trigger',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'finale',
			), 'https://xlplugins.com/finale-woocommerce-sales-countdown-timer-discount-plugin/' );
			$other_products['finale'] = array(
				'image' => 'finale.png',
				'link'  => $finale_link,
				'head'  => 'Finale WooCommerce Sales Countdown Timer',
				'desc'  => 'Run Urgency Marketing Campaigns On Your Store And Move Buyers to Make A Purchase',
			);
		}
		if ( ! defined( 'WCST_SLUG' ) ) {
			$sales_trigger_link              = add_query_arg( array(
				'utm_source'   => 'sales-trigger',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'sales-trigger',
			), 'https://xlplugins.com/woocommerce-sales-triggers/' );
			$other_products['sales_trigger'] = array(
				'image' => 'sales-trigger.png',
				'link'  => $sales_trigger_link,
				'head'  => 'XL WooCommerce Sales Triggers',
				'desc'  => 'Use 7 Built-in Sales Triggers to Optimise Single Product Pages For More Conversions',
			);
		}
		if ( ! class_exists( 'XLWCTY_Core' ) ) {
			$nextmove_link              = add_query_arg( array(
				'utm_source'   => 'sales-trigger',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'nextmove',
			), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
			$other_products['nextmove'] = array(
				'image' => 'nextmove.png',
				'link'  => $nextmove_link,
				'head'  => 'NextMove WooCommerce Thank You Pages',
				'desc'  => 'Get More Repeat Orders With 17 Plug n Play Components',
			);
		}
		if ( is_array( $other_products ) && count( $other_products ) > 0 ) {
			?>
            <h3>Checkout Our Other Plugins:</h3>
			<?php
			foreach ( $other_products as $product_short_name => $product_data ) {
				?>
                <div class="postbox wcst_side_content wcst_xlplugins wcst_xlplugins_<?php echo $product_short_name ?>">
                    <a href="<?php echo $product_data['link'] ?>" target="_blank"></a>
                    <img src="<?php echo plugin_dir_url( WCST_PLUGIN_FILE ) . 'admin/assets/img/' . $product_data['image']; ?>"/>
                    <div class="wcst_plugin_head"><?php echo $product_data['head'] ?></div>
                    <div class="wcst_plugin_desc"><?php echo $product_data['desc'] ?></div>
                </div>
				<?php
			}
		}
	}

	public function wcst_options_page_right_content() {
		$license_active = get_option( $this->edd_slugify_module_name( $this->full_name ) . '_license_active' );
		if ( $license_active != 'valid' ) {
			$license_head     = '<div class="wcst_status_error"></div>Your License is Inactive';
			$license_desc     = '<p>You are <strong>not receiving:</strong></p>';
			$license_ul_class = 'icon_cross';
		} else {
			$license_head     = '<div class="wcst_status_success"></div>Your License is Active';
			$license_desc     = '<p>You are <strong>receiving:</strong></p>';
			$license_ul_class = 'icon_tick';
		}

		if ( isset( $_GET['section'] ) && ( 'settings' == $_GET['section'] ) ) {
			?>
            <div class="postbox wcst_side_content">
                <div class="inside">
                    <p>Sales Trigger use transients and cache to optimize performance. In case, data for triggers, particularly 'Best Seller' & 'Sales Snippet' triggers does not change, hit below
                        remove transients all button.</p>
                    <a class="button-primary" href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=xl-sales-trigger&section=settings&wcst_all_transient_remove=yes' ); ?>">Reset All
                        Transients</a>
					<?php
					if ( true === WCST_Common::$is_transient_removed ) {
						echo '<p><u>Transients removed succesfully!</u></p>';
					}
					?>
                </div>
            </div>
			<?php
		}
		$get_license_data = get_option( $this->edd_slugify_module_name( $this->full_name ) . "license_data" );

		if ( $get_license_data && is_object( $get_license_data ) && isset( $get_license_data->license ) && $get_license_data->license === "expired" ) {

			$license_head     = '<div class="wcst_status_error"></div>Your 6 months item support license from Envato has expired';
			$license_desc     = '<p>Support includes:</p>';
			$license_ul_class = 'icon_cross';
			$get_notices      = get_option( 'xl_admin_notices', array() );
			if ( ! in_array( 'wcst_upgrade_license', $get_notices ) ) {
				?>
                <div class="postbox wcst_side_content">
                    <div class="inside">
                        <h1 class="wcst_l_status"><?php echo $license_head; ?></h1>
						<?php echo $license_desc ?>
                        <ul class="<?php echo $license_ul_class ?>">
                            <li>Availability of the author to answer questions</li>
                            <li>Get assistance with reported bugs and issues</li>
                            <li>Help with included 3rd party assets</li>
                            <li>Automatic Dashboard Upgrades</li>
                        </ul>
                        <p>You would continue to receive manual updates via Envato dashboard.</p>
                        <p>Want author support and automatic upgrades?</p>
                        <p>
                        <center><a class="button-primary" target="_blank" href="<?php echo "https://xlplugins.com/documentation/woocommerce-sales-trigger/licensing/"; ?>">Get
                                Extended
                                Support</a><a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=xl-sales-trigger&wcst_remove=wcst_upgrade_license' ); ?>" style="margin-left: 5px;" class="button">Hide</a>
                        </center>
                        </p>


                    </div>
                </div>
				<?php
			}
		} else {
			if ( $license_active != 'valid' ) {
				?>
                <div class="postbox wcst_side_content">
                    <div class="inside">
                        <h1 class="wcst_l_status"><?php echo $license_head; ?></h1>
						<?php echo $license_desc ?>
                        <ul class="<?php echo $license_ul_class ?>">
                            <li>Automatic Plugin Updates</li>
                            <li>New Features</li>
                            <li>Security Upgrades</li>
                            <li>Bug Fixes</li>
                            <li>Notifications from XLPlugins</li>
                        </ul>
                        <p>With an active license you get fast support. <a href="https://xlplugins.com/documentation/woocommerce-sales-trigger/getting-started/#license" target="_blank">Watch this
                                2-Minute video to activate your license in 3 easy steps.</a></p>
                        <p>
                        <center><a class="button-primary" href="<?php echo add_query_arg( array( 'tab' => 'licenses' ), $this->expected_url ); ?>">Activate Your License</a></center>
                        </p>
                    </div>
                </div>
				<?php
			}
		}
		$support_banner_link = add_query_arg( array(
			'pro'          => 'xl-woocommerce-sales-triggers',
			'utm_source'   => 'sales-trigger',
			'utm_medium'   => 'banner-click',
			'utm_campaign' => 'resource',
			'utm_term'     => 'support',
		), 'https://xlplugins.com/support' );
		$support_link        = add_query_arg( array(
			'pro'          => 'xl-woocommerce-sales-triggers',
			'utm_source'   => 'sales-trigger',
			'utm_medium'   => 'text-click',
			'utm_campaign' => 'resource',
			'utm_term'     => 'support',
		), 'https://xlplugins.com/support' );
		$demo_link           = add_query_arg( array(
			'utm_source'   => 'sales-trigger',
			'utm_medium'   => 'text-click',
			'utm_campaign' => 'resource',
			'utm_term'     => 'demo',
		), 'http://demo.xlplugins.com/xl-woocommerce-sales-triggers' );
		$doc_link            = add_query_arg( array(
			'utm_source'   => 'sales-trigger',
			'utm_medium'   => 'text-click',
			'utm_campaign' => 'resource',
			'utm_term'     => 'documentation',
		), 'https://xlplugins.com/documentation/woocommerce-sales-trigger/' );
		$request_trigger     = add_query_arg( array(
			'pro'          => 'xl-woocommerce-sales-triggers',
			'utm_source'   => 'sales-trigger',
			'utm_medium'   => 'text-click',
			'utm_campaign' => 'resource',
			'utm_term'     => 'request-trigger',
		), 'https://xlplugins.com/contact/' );

		$site_url = site_url();
		$img_url  = add_query_arg( array(
			'v' => WCST_VERSION,
			'u' => WCST_Common::String2Hex( $site_url ),
		), 'https://xlplugins.com/assets/support.jpg' );
		?>
        <div class="postbox wcst_side_content">
            <div class="inside">
                <h3>Resources</h3>
                <p><a href="<?php echo $support_banner_link ?>" target="_blank"><img src="<?php echo $img_url ?>" width="100%"/></a></p>
                <ul>
                    <li><a href="<?php echo $demo_link ?>" target="_blank">Demo</a></li>
                    <li><a href="<?php echo $support_link ?>" target="_blank">Support</a></li>
                    <li><a href="<?php echo $doc_link ?>" target="_blank">Documentation</a></li>
                    <li><a href="<?php echo $request_trigger ?>" target="_blank">Request a trigger</a></li>
                </ul>
            </div>
        </div>
		<?php
	}

	public function schedule_license_check() {
		wp_schedule_single_event( time() + 10, 'wcst_maybe_schedule_check_license' );
	}

	public function xl_add_administration_emails( $data ) {

		if ( isset( $data['admins'] ) ) {
			return $data;
		}
		$users = get_users( array( 'role' => 'administrator', 'fields' => array( 'user_email', 'user_nicename' ) ) );

		$data['admins'] = $users;

		return $data;
	}

	public function xl_add_license_data_on_deactivation( $args ) {

		if ( isset( $args['edd_action'] ) && $args['edd_action'] !== "get_deactivation_data" ) {
			return $args;
		}

		$licenses = XL_licenses::get_instance()->get_data();


		if ( $licenses && count( $licenses ) > 0 ) {
			foreach ( $licenses as $key => $license ) {

				if ( $key !== WCST_PLUGIN_BASENAME ) {
					continue;
				}
				$args['licenses'] = $license;
			}
		}


		return $args;
	}

	public function export_tools_after_content( $model ) {
		$system_info = XL_Support::get_instance()->prepare_system_information_report() . "\r" . $this->xl_support_system_info();
		?>
        <div class="xl_core_tools" style="width:80%;background: #fff;">
            <h2><?php echo __( 'Sales Trigger' ); ?></h2>
            <form method="post">
                <div class="xl_core_tools_inner" style="min-height: 300px;">
                    <textarea name="xl_tools_system_info" readonly style="width:100%;height: 280px;"><?php echo $system_info ?></textarea>
                </div>
                <div style="clear: both;"></div>
                <div class="xl_core_tools_button" style="margin-bottom: 10px;">
                    <div class="xl_download_buttons"><a class="button button-primary button-large xl_core_tools_btn" href="<?php echo add_query_arg( array(
							"content"  => "wcst_trigger",
							"download" => "true"
						), admin_url( "export.php" ) ) ?>"><?php echo __( "Download Triggers", WCST_SLUG ) ?></a>
                        <button type="submit" class="button button-primary button-large xl_core_tools_btn" name="xl_tools_export_setting" value="wcst_trigger"><?php echo __( "Download Settings", WCST_SLUG ) ?></button>
                    </div>
                </div>
                <br>
            </form>
        </div>
		<?php
	}

	public function export_xl_tools_right_area() {
//		echo "Hello right content";
	}

	public function xl_support_system_info( $return = false ) {
		$setting_report   = array();
		$setting_report[] = "\n\n#### Sales Trigger Settings ####\n\n*** Gloabl Settings ***";

		$global_options = WCST_Common::get_global_options();
		$global_default = WCST_Common::get_default_global();
		$global_options = wp_parse_args( $global_options, $global_default );

		if ( isset( $global_options["wcst_global_replace_other"] ) && $global_options["wcst_global_replace_other"] != "" ) {
			$setting_report[] = "Replace \"other\" with {$global_options["wcst_global_replace_other"]}";
		}
		if ( isset( $global_options["wcst_global_replace_others"] ) && $global_options["wcst_global_replace_others"] != "" ) {
			$setting_report[] = "Replace \"others\" with {$global_options["wcst_global_replace_others"]}";
		}
		if ( isset( $global_options["wcst_global_replace_form"] ) && $global_options["wcst_global_replace_form"] != "" ) {
			$setting_report[] = "Replace \"from\" with {$global_options["wcst_global_replace_form"]}";
		}
		if ( isset( $global_options["wcst_global_replace_in"] ) && $global_options["wcst_global_replace_in"] != "" ) {
			$setting_report[] = "Replace \"in\" with {$global_options["wcst_global_replace_in"]}";
		}
		if ( isset( $global_options["wcst_global_replace_month"] ) && $global_options["wcst_global_replace_month"] != "" ) {
			$setting_report[] = "Replace \"month\" with {$global_options["wcst_global_replace_month"]}";
		}
		if ( isset( $global_options["wcst_global_replace_months"] ) && $global_options["wcst_global_replace_months"] != "" ) {
			$setting_report[] = "Replace \"months\" with {$global_options["wcst_global_replace_months"]}";
		}
		if ( isset( $global_options["wcst_global_replace_week"] ) && $global_options["wcst_global_replace_week"] != "" ) {
			$setting_report[] = "Replace \"week\" with {$global_options["wcst_global_replace_week"]}";
		}


		if ( isset( $global_options["wcst_global_replace_weeks"] ) && $global_options["wcst_global_replace_weeks"] != "" ) {
			$setting_report[] = "Replace \"weeks\" with {$global_options["wcst_global_replace_weeks"]}";
		}
		if ( isset( $global_options["wcst_global_replace_day"] ) && $global_options["wcst_global_replace_day"] != "" ) {
			$setting_report[] = "Replace \"day\" with {$global_options["wcst_global_replace_day"]}";
		}
		if ( isset( $global_options["wcst_global_replace_days"] ) && $global_options["wcst_global_replace_days"] != "" ) {
			$setting_report[] = "Replace \"days\" with {$global_options["wcst_global_replace_days"]}";
		}
		if ( isset( $global_options["wcst_global_replace_hrs"] ) && $global_options["wcst_global_replace_hrs"] != "" ) {
			$setting_report[] = "Replace \"hrs\" with {$global_options["wcst_global_replace_hrs"]}";
		}
		if ( isset( $global_options["wcst_global_replace_hr"] ) && $global_options["wcst_global_replace_hr"] != "" ) {
			$setting_report[] = "Replace \"hr\" with {$global_options["wcst_global_replace_hr"]}";
		}
		if ( isset( $global_options["wcst_global_replace_min"] ) && $global_options["wcst_global_replace_min"] != "" ) {
			$setting_report[] = "Replace \"min\" with {$global_options["wcst_global_replace_min"]}";
		}
		if ( isset( $global_options["wcst_global_replace_mins"] ) && $global_options["wcst_global_replace_mins"] != "" ) {
			$setting_report[] = "Replace \"mins\" with {$global_options["wcst_global_replace_mins"]}";
		}

		if ( isset( $global_options["wcst_global_replace_sec"] ) && $global_options["wcst_global_replace_sec"] != "" ) {
			$setting_report[] = "Replace \"sec\" with {$global_options["wcst_global_replace_sec"]}";
		}
		if ( isset( $global_options["wcst_global_replace_secs"] ) && $global_options["wcst_global_replace_secs"] != "" ) {
			$setting_report[] = "Replace \"secs\" with {$global_options["wcst_global_replace_secs"]}";
		}
		if ( isset( $global_options["wcst_global_replace_&"] ) && $global_options["wcst_global_replace_&"] != "" ) {
			$setting_report[] = "Replace \"&\" with {$global_options["wcst_global_replace_&"]}";
		}


		$orders = $this->get_last_10_order();
		if ( count( $orders ) > 0 ) {
			$global_options["orders"] = $orders;
			$setting_report[]         = "\r*** Last 10 Orders ***";
			foreach ( $orders as $order_id => $order ) {
				$setting_report[] = "Order id  - {$order_id}";
				if ( is_array( $order ) && count( $order ) > 0 ) {
					foreach ( $order as $item_id => $item ) {
						$setting_report[] = "\tItem id - {$item_id} ";
						$setting_report[] = "\tProduct id - {$item["product_id"]} ";
						$setting_report[] = "\tProduct name - {$item["product_name"]} ";
						$setting_report[] = "\tQuantity - {$item["quantity"]}";
						$setting_report[] = "\tLine Total - {$item["line_total"]} \r";
					}
				}

			}
		}
		$setting_report[] = "#### Sales Trigger Settings ####";
		if ( $return ) {
			return array( "sales_trigger_settings" => $global_options );

		}

		return implode( "\r", $setting_report );
	}

	public function download_tools_settings() {
		if ( isset( $_POST["xl_tools_export_setting"] ) && $_POST["xl_tools_export_setting"] == "wcst_trigger" && isset( $_POST["xl_tools_system_info"] ) && $_POST["xl_tools_system_info"] != '' ) {
			$system_info = XL_Support::get_instance()->prepare_system_information_report( true ) + $this->xl_support_system_info( true );
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=sales_trigger.json' );
			echo wp_json_encode( $system_info );
			exit;
		}
	}

	public function get_shipping_method() {
		global $wpdb;
		$output     = array();
		$freeMethod = $wpdb->get_results( "select * from {$wpdb->prefix}woocommerce_shipping_zone_methods where method_id='free_shipping'", ARRAY_A );
		if ( is_array( $freeMethod ) && count( $freeMethod ) > 0 ) {
			foreach ( $freeMethod as $method ) {
				$free_shipping = get_option( "woocommerce_free_shipping_{$method["method_order"]}_settings", array() );
				if ( count( $free_shipping ) > 0 ) {
					$output[] = $free_shipping;
				}
			}
		}

		return $output;

	}

	public function get_free_shipping_coupon() {
		global $wpdb;
		$free_coupon = $wpdb->get_results( "select p.id,p.post_title from {$wpdb->prefix}postmeta as m join {$wpdb->prefix}posts as p on m.post_id=p.id where m.meta_key='free_shipping' and m.meta_value='yes' and p.post_type='shop_coupon' and p.post_status='publish' order by p.post_date desc limit 10 ", ARRAY_A );
		if ( is_array( $free_coupon ) && count( $free_coupon ) > 0 ) {
			foreach ( $free_coupon as $key => $value ) {
				$date_expires                        = get_post_meta( $value['id'], "date_expires", true );
				$expiry_date                         = get_post_meta( $value['id'], "expiry_date", true );
				$free_coupon[ $key ]["date_expires"] = $date_expires;
				$free_coupon[ $key ]["expiry_date"]  = $expiry_date;
				$post_title                          = $free_coupon[ $key ]["post_title"];
				unset( $free_coupon[ $key ]["post_title"] );
				$free_coupon[ $key ]["coupon_code"] = $post_title;
			}
		}

		return $free_coupon;
	}

	public function get_last_10_order() {
		$output = array();
		$orders = wc_get_orders( array( "posts_per_page" => 10 ) );
		if ( count( $orders ) > 0 ) {
			foreach ( $orders as $order ) {
				if ( $order instanceof WC_Order ) {
					$oid   = $order->get_id();
					$items = $order->get_items( 'line_item' );
					if ( count( $items ) > 0 ) {
						foreach ( $items as $key => $item ) {
							$pid     = $item->get_product_id();
							$item_id = $item->get_id();

							$product = wc_get_product( $pid );
							if ( $product instanceof WC_Product ) {
								$output[ $oid ][ $item_id ] = array(
									"product_id"   => $pid,
									"product_name" => $product->get_title(),
									"line_total"   => $item->get_subtotal(),
									"quantity"     => $item->get_quantity()
								);
							}
						}
					}
				}
			}
		}

		return $output;
	}

	public function xl_fetch_tools_data( $file, $post ) {

		if ( $file == "xl-woocommerce-sales-triggers.php" ) {
			$xl_support_url = "";
			$system_info    = XL_Support::get_instance()->prepare_system_information_report( true ) + $this->xl_support_system_info( true );
			$upload_dir     = wp_upload_dir();
			$basedir        = $upload_dir["basedir"];
			$baseurl        = $upload_dir["baseurl"];
			if ( is_writable( $basedir ) ) {
				$xl_support     = $basedir . "/xl_support";
				$xl_support_url = $baseurl . "/xl_support";
				if ( ! file_exists( $xl_support ) ) {
					mkdir( $xl_support, 0755, true );
				}
				if ( count( $system_info ) > 0 ) {
					$xl_support_file_path = $xl_support . "/sales-trigger-support.json";
					$success              = file_put_contents( $xl_support_file_path, json_encode( $system_info ) );
					if ( $success ) {
						$xl_support_url .= "/sales-trigger-support.json";
					}
				}
			}
			echo $xl_support_url;
		}
	}

}

new WCST_XL_Support();