<?php

add_action('wp', 'wcst_theme_helper_betheme', 99);
if (!function_exists('wcst_theme_helper_betheme')) {

    function wcst_theme_helper_betheme() {
        $wcst_core = WCST_Core::get_instance();
        // removing duplicate price
//    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        // removing wcst action hooks on theme
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.2);

        add_action('woocommerce_single_product_summary', function() {
            echo '<div class="wcst_clear"></div>';
        }, 16);
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 16);
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 16);

        remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_above_tab_area'), 9.8);
        remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_related_products'), 21.2);

        add_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_above_tab_area'), 8);
        add_action('woocommerce_after_single_product', array($wcst_core, 'wcst_position_below_related_products'), 12);
    }

}