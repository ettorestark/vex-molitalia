<?php

@session_start();

class WPPKPO_View_Frontend {

    public function __construct() {
        
    }

    public function wp_pkpo_order_table_details($order) {
        require_once WPPKPO_PATH . '/templates/frontend/pickup-order-details.php';
    }

    public function wp_pickup_location_map($wp_pkpo_pickup_points, $wp_pkpo_settings) {
        ob_start();
        require WPPKPO_PATH . '/templates/frontend/wp-pickup-location-map.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function wp_pkpo_pickup_location_info($wp_pkpo_pickup_points) {
        require_once WPPKPO_PATH . '/templates/frontend/checkout-pickup-information.php';
    }

}
