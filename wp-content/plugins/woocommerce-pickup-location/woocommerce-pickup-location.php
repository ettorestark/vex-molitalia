<?php
/*
  Plugin Name: Woocommerce Pickup Location
  Plugin URI:  http://pdocrud.com/wp-pickup-location/demo/
  Description: Woocommerce Pickup Location allows the customer to pick up the order themselves by selecting pickup location during order placement.
  Version: 2.4.1
  Author: Pritesh Gupta
  Author URI: http://codecanyon.net/user/ddeveloper
  Text Domain: woocommerce-pickup-location
  Domain Path: /languages/
  Copyright 2017 Pritesh gupta
 */

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

add_action('admin_notices', 'wp_pkpo_admin_notice');
function wp_pkpo_admin_notice() {
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

        echo '<div class="error"><p>' . __('WooCommerce pickup location requires woocoomerce plugin to be installed before..', 'woocommerce-pickup-location') . '</p></div>';

        deactivate_plugins('woocommerce-pickup-location/woocommerce-pickup-location.php');
    }
}

define('WPPKPO_VERSION', '2.2.0');
define('WPPKPO_PATH', dirname(__FILE__));
define('WPPKPO_FILE', __FILE__);

if (!defined('WPPKPO_PLUGIN_DIR'))
    define('WPPKPO_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));

if (!defined('WPPKPO_PLUGIN_URL'))
    define('WPPKPO_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));

if (!defined('WPPKPO_PLUGIN_BASENAME'))
    define('WPPKPO_PLUGIN_BASENAME', untrailingslashit(plugin_basename(__FILE__)));

require_once WPPKPO_PLUGIN_DIR . '/includes/helper/WPPKPO_Shipping_Method.php';

add_action('plugins_loaded', 'wp_pkpo_plugin_loaded');

function wp_pkpo_plugin_loaded() {
    load_plugin_textdomain('woocommerce-pickup-location', false, WPPKPO_PLUGIN_DIR . '/languages');
}

spl_autoload_register('wp_pkpo_classes');

function wp_pkpo_classes($class) {

    if (file_exists(WPPKPO_PATH . '/includes/admin/controller/' . $class . '.php'))
        require_once WPPKPO_PATH . '/includes/admin/controller/' . $class . '.php';

    if (file_exists(WPPKPO_PATH . '/includes/admin/view/' . $class . '.php'))
        require_once WPPKPO_PATH . '/includes/admin/view/' . $class . '.php';

    if (file_exists(WPPKPO_PATH . '/includes/frontend/controller/' . $class . '.php'))
        require_once WPPKPO_PATH . '/includes/frontend/controller/' . $class . '.php';

    if (file_exists(WPPKPO_PATH . '/includes/frontend/view/' . $class . '.php'))
        require_once WPPKPO_PATH . '/includes/frontend/view/' . $class . '.php';
}

register_activation_hook(__FILE__, 'wp_pkpo_install_settings');

function wp_pkpo_install_settings() {
    global $wpdb;
    $marker_table_name = $wpdb->prefix . 'pkpo_markers';
    $sql = "CREATE TABLE $marker_table_name (
		`id` int NOT NULL AUTO_INCREMENT,
		`name` varchar(60) NOT NULL,
                `address` text NOT NULL,
		`lat` float(10,6) NOT NULL,
                `lng` float(10,6)  NOT NULL,
                `post_id` int NOT NULL,
		PRIMARY KEY  (`id`)
	);";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    $default_options = array(
        'wp_pkpo_marker_image' => '',
        'wp_pkpo_default_position' => '',
        'wp_pkpo_pickup_admin_map_zoom' => '3',
        'wp_pkpo_pickup_checkout_map_zoom' => '3',
        'wp_pkpo_shortcode_map_zoom' => '3',
        'wp_pkpo_pickup_selection_type' => 'by_dropdown',
        'wp_pkpo_pickup_date_formate' => 'yy-mm-dd',
        'wp_pkpo_map_disable' => 'enable',
        'wp_pkpo_shipping_hide_details' => 'enable',
        'wp_pkpo_billing_hide_details' => 'enable',
        'wp_pkpo_send_info_in_email' => 'enable',
        'wp_pkpo_hide_checkout_time' => 'enable',
        'wp_pkpo_hide_checkout_date' => 'enable',
        'wp_pickup_default_datepicker_lang' => '',
        'wp_pickup_default_time_formate' => "24",
        'wp_pickup_wrong_time' => 'Pickup is not available for this time. Please select different option.',
        'wp_pickup_min_time_msg' => 'It will take %s hours to prepare your product, please chose pickup time accordingly.',
        'wp_pickup_max_time_msg' => 'Your product will be available till max %s hours, please chose pickup time accordingly',
        'wp_pickup_wrong_date' => 'Pickup is not available for this day. Please select different option.',
        'wp_pickup_default_date_text' => 'Pickup Date',
        'wp_pickup_default_time_text' => 'Pickup Time',
        'wp_pickup_map_position' => 'top',
        'wp_pickup_order_list_show_pickup' => 'enable',
        'wp_pickup_address_display' => 'enable',
        'wp_pickup_email_display' => 'enable',
        'wp_pickup_city_display' => 'disable',
        'wp_pickup_state_display' => 'disable',
        'wp_pickup_country_display' => 'disable',
        'wp_pickup_zip_display' => 'disable',
        'wp_pickup_load_pickup_later' => 'no',
        'wp_pickup_default_shipping_pickup' => 'yes',
        'wp_pickup_hide_pickup_shipping_based_on_order' => '',
        'wp_pickup_radius_search' => 'disable',
        'wp_pickup_auto_select_pickup' => 'enable',
        'wp_pickup_map_search' => 'enable',
        'wp_pickup_location_based_product' => 'disable',
        'wp_pickup_location_select2' => 'enable',
        'wp_pickup_map_api_key' => '',
        'wp_pickup_user_roles' => array()
    );

    update_option('wp_pkpo_options', $default_options, '', 'no');
}

if (is_admin()) {
    new WPPKPO_Controller_Admin();
} else {
    new WPPKPO_Controller_Frontend();
}

add_action('init', 'create_pickup_taxonomies');

function create_pickup_taxonomies() {
    $labels = array(
        'name' => _x('Pickup Days', 'Pickup Days', 'woocommerce-pickup-location'),
        'singular_name' => _x('Pickup Day', 'Pickup Day', 'woocommerce-pickup-location'),
        'search_items' => __('Search Pickup Days', 'woocommerce-pickup-location'),
        'all_items' => __('All Pickup Days', 'woocommerce-pickup-location'),
        'parent_item' => __('Parent Pickup Days', 'woocommerce-pickup-location'),
        'parent_item_colon' => __('Parent Pickup Days:', 'woocommerce-pickup-location'),
        'edit_item' => __('Edit Pickup Days', 'woocommerce-pickup-location'),
        'update_item' => __('Update Pickup Days', 'woocommerce-pickup-location'),
        'add_new_item' => __('Add New Pickup Days', 'woocommerce-pickup-location'),
        'new_item_name' => __('New Pickup Days Name', 'woocommerce-pickup-location'),
        'menu_name' => __('Pickup Days', 'woocommerce-pickup-location'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'pickup_days'),
    );

    register_taxonomy('pickup_days', array('pickup_location'), $args);

    wp_insert_term('Sunday', 'pickup_days');
    wp_insert_term('Monday', 'pickup_days');
    wp_insert_term('Tuesday', 'pickup_days');
    wp_insert_term('Wednesday', 'pickup_days');
    wp_insert_term('Thursday', 'pickup_days');
    wp_insert_term('Friday', 'pickup_days');
    wp_insert_term('Saturday', 'pickup_days');
}