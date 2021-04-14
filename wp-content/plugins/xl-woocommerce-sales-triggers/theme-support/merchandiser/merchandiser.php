<?php

add_action('wp', 'wcst_theme_helper_merchandiser', 99);
if (!function_exists('wcst_theme_helper_merchandiser')) {

    function wcst_theme_helper_merchandiser() {
        $wcst_core = WCST_Core::get_instance();

        // removing wcst action hooks on theme
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.2);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.2);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.2);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 21.2);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39.2);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 41.2);
        
        remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_above_tab_area'), 9.8);
        remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_related_products'), 21.2);

        add_action('woocommerce_single_product_summary_single_title', array($wcst_core, 'wcst_position_above_title'), 3);
        add_action('woocommerce_single_product_summary_single_title', array($wcst_core, 'wcst_position_below_title'), 6);
        add_action('woocommerce_single_product_summary_single_excerpt', array($wcst_core, 'wcst_position_below_review'), 16);
        add_action('woocommerce_single_product_summary_single_excerpt', array($wcst_core, 'wcst_position_below_price'), 17);
        add_action('woocommerce_single_product_summary_single_excerpt', array($wcst_core, 'wcst_position_below_short_desc'), 21);
        add_action('woocommerce_single_product_summary_single_add_to_cart', array($wcst_core, 'wcst_position_below_add_cart'), 31);
        add_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 8);
        
        add_action('woocommerce_after_single_product_summary_data_tabs', array($wcst_core, 'wcst_position_above_tab_area'), 8);
        
        add_action('woocommerce_after_single_product_summary_related_products', function() { echo '<div class="row"><div class="large-8 large-centered columns">';}, 22);
        add_action('woocommerce_after_single_product_summary_related_products', array($wcst_core, 'wcst_position_below_related_products'), 23);
        add_action('woocommerce_after_single_product_summary_related_products', function() { echo '</div></div>';}, 24);
    }

}