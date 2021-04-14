<?php
/*
* Plugin Name: XL WooCommerce Sales Triggers
* Plugin URI: https://www.xlplugins.com/xl-woocommerce-sales-triggers/
* Description: A suite of conversion-boosting solutions (a.k.a 'Triggers' ) that get you more sales from your existing product pages.
* Version: 2.6.0
* Author: XLPlugins
* Author URI: https://www.xlplugins.com
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: xl-woocommerce-sales-triggers
* XL: True
* XLTOOLS: True
* Requires at least: 4.2.1
* Tested up to: 4.9.6
* WC requires at least: 2.6.0
* WC tested up to: 3.4.1
*
* XL WooCommerce Sales Triggers is free software.
* You can redistribute it and/or modify it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* any later version.
*
* XL WooCommerce Sales Triggers is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with XL WooCommerce Sales Triggers. If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require 'includes/wcst-logging.php';

/**
 * Collect PHP fatal errors and save it in the log file so that it can be later viewed
 * @see register_shutdown_function
 */
if ( ! function_exists( 'xlplugins_collect_errors' ) ) {
	function xlplugins_collect_errors() {
		$error = error_get_last();
		if ( E_ERROR === $error['type'] ) {
			xlplugins_force_log( $error['message'] . PHP_EOL, 'fatal-errors.txt' );
		}
	}

	register_shutdown_function( 'xlplugins_collect_errors' );
}

/** Defining Constants */
define( 'WCST_VERSION', '2.6.0' );
define( 'WCST_MIN_WC_VERSION', '2.6' );
define( 'WCST_SLUG', 'xl-woocommerce-sales-triggers' );
define( 'WCST_FULL_NAME', 'XL WooCommerce Sales Triggers' );
define( 'WCST_PLUGIN_FILE', __FILE__ );
define( 'WCST_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WCST_PURCHASE', 'xlplugin' );


/** Setting up WooCommerce Dependency Classes */
require_once( 'woo-includes/woo-functions.php' );

/** Setting Up XL Core */
require_once( 'start.php' );

/** Checking WooCommerce Dependencies  */
if ( ! wcst_is_woocommerce_active() ) {
	add_action( 'admin_notices', 'wcst_wc_not_installed_notice' );

	return;
}

/** Hooking action to the activation */
register_activation_hook( __FILE__, 'wcst_activation' );

//register_deactivation_hook( __FILE__, 'wcst_deactivation' );

/** Triggering activation initialization */
function wcst_activation() {
	WCST_Admin::handle_activation();
}

/** Triggering deactivation initialization */
function wcst_deactivation() {
	require_once( 'start.php' );
	XL_Common::include_xl_core();

	$response = XL_API::post_deactivation_data();

	if ( ! is_wp_error( $response ) ) {
		delete_option( 'xl_uninstall_reasons' );
	}
}

/** Initializing Functionality */
add_action( 'plugins_loaded', 'wcst_init', 0 );

/** Redirecting Plugin to the settings page after activation */
add_action( 'activated_plugin', 'wcst_settings_redirect' );

/** Initialize Localization */
add_action( 'init', 'wcst_init_localization' );

function wcst_init_localization() {
	load_plugin_textdomain( WCST_SLUG, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

/** Including XL Cache, transient or File API files in case not included once */
add_action( 'xl_loaded', 'wcst_load_xl_core_require_files', 10, 1 );

function wcst_load_xl_core_require_files( $get_global_path ) {
	if ( file_exists( $get_global_path . 'includes/class-xl-cache.php' ) ) {
		require_once $get_global_path . 'includes/class-xl-cache.php';
	}
	if ( file_exists( $get_global_path . 'includes/class-xl-transients.php' ) ) {
		require_once $get_global_path . 'includes/class-xl-transients.php';
	}
	if ( file_exists( $get_global_path . 'includes/class-xl-file-api.php' ) ) {
		require_once $get_global_path . 'includes/class-xl-file-api.php';
	}
}


include_once plugin_dir_path( WCST_PLUGIN_FILE ) . 'WCST_EDD_License_Handler.php';

/** Loading Common Class */
require 'includes/wcst-common.php';

/** Removing Builders button on Trigger post */
require_once 'includes/class-wcst-builder.php';
require 'includes/wcst-xl-support.php';
require 'includes/wcst-triggers.php';
require_once 'includes/wcst-merge-tags.php';
require 'includes/wcst-product.php';
require 'includes/class-wcst-compatibility.php';


/** Firing init basic Functions */
WCST_Common::init();

/* ----------------------------------------------------------------------------*
* Dashboard and Administrative Functionality
* ---------------------------------------------------------------------------- */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once( plugin_dir_path( WCST_PLUGIN_FILE ) . 'admin/wcst-admin.php' );
}

/**
 * Checking WooCommerce dependency and then loads further
 * @return bool false on failure
 */
function wcst_init() {
	if ( wcst_is_woocommerce_active() && class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( ! version_compare( $woocommerce->version, WCST_MIN_WC_VERSION, '>=' ) ) {
			add_action( 'admin_notices', 'wcst_wc_version_check_notice' );
		}

		if ( isset( $_GET['wcst_disable'] ) && 'yes' == $_GET['wcst_disable'] && is_user_logged_in() && current_user_can( 'administrator' ) ) {
			return;
		}

		if ( ! is_admin() ) {
			require 'includes/wcst-triggers-data.php';
			require 'includes/wcst-core.php';
			require 'includes/wcst-themes-helper.php';

		}
	}
}

/** Registering Notices */
function wcst_wc_version_check_notice() {
	?>
    <div class="error">
        <p>
			<?php
			/* translators: %1$s: Min required woocommerce version */
			printf( __( 'Sales Trigger requires WooCommerce version %1$s or greater. Kindly update the WooCommerce plugin.', WCST_SLUG ), WCST_MIN_WC_VERSION );
			?>
        </p>
    </div>
	<?php
}

function wcst_wc_not_installed_notice() {
	?>
    <div class="error">
        <p>
			<?php
			echo __( 'WooCommerce is not installed or activated. Sales Trigger is a WooCommerce Extension and would only work if WooCommerce is activated. Please install the WooCommerce Plugin first.', WCST_SLUG );
			?>
        </p>
    </div>
	<?php
}

/**
 * Added redirection to plugin activation
 *
 * @param $plugin
 */
function wcst_settings_redirect( $plugin ) {
	if ( wcst_is_woocommerce_active() && class_exists( 'WooCommerce' ) ) {
		if ( plugin_basename( __FILE__ ) == $plugin ) {
			wp_redirect( add_query_arg( array(
				'page' => 'wc-settings',
				'tab'  => 'xl-sales-trigger',
			), admin_url( 'admin.php' ) ) );
			exit;
		}
	}
}

