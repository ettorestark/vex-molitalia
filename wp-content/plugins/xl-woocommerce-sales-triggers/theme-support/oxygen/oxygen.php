<?php

add_action('wp', 'wcst_theme_helper_oxygen', 99);
if (!function_exists('wcst_theme_helper_oxygen')) {

    function wcst_theme_helper_oxygen() {
        $wcst_core = WCST_Core::get_instance();

        // removing wcst action hooks on theme        
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.2);
        remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_related_products'), 21.2);

        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 1);
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 27);
        add_action('woocommerce_after_single_product', array($wcst_core, 'wcst_position_below_related_products'), 22);
    }

}