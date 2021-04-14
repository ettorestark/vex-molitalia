<?php

@session_start();

class WPPKPO_View_Admin {

    public function __construct() {
        
    }

    public function wp_pkpo_pickup_location_callback($wp_pkpo_lat, $wp_pkpo_long, $settings) {
        require_once WPPKPO_PATH . '/templates/admin/map-section.php';
    }

    public function wp_pkpo_pickup_location_csv($wp_pkpo_msg,$link) {
        require_once WPPKPO_PATH . '/templates/admin/upload-csv.php';
    }

    public function wp_pkpo_pickup_location_contacts_callback($wp_pkpo_contact_details, $wp_pkpo_contact_email, $wp_pickup_city, $wp_pickup_state, $wp_pickup_country, $wp_pickup_zip,  $settings) {
        require_once WPPKPO_PATH . '/templates/admin/location-details.php';
    }

    public function wp_pkpo_pickup_location_working_hours_callback($wp_pkpo_opening_hours, $wp_pkpo_closing_hours) {
        require_once WPPKPO_PATH . '/templates/admin/location-working-hours.php';
    }
    
    public function wp_pkpo_pickup_time_limit_callback($wp_pkpo_min_time, $wp_pkpo_max_time) {
        require_once WPPKPO_PATH . '/templates/admin/time-limit.php';
    }

    public function wp_pkpo_settings($settings, $wp_pkpo_msg) {
        require_once WPPKPO_PATH . '/templates/admin/settings.php';
    }

    public function wp_pkpo_admin_order_details($order) {
        require_once WPPKPO_PATH . '/templates/admin/pickup-order-details.php';
    }
    
    public function wp_pkpo_product_locations_callback($wp_pkpo_locations, $wp_pkpo_selected_locations) {
        require_once WPPKPO_PATH . '/templates/admin/product-locations.php';
    }

}
