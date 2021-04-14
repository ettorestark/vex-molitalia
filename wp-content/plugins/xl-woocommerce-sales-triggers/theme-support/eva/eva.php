<?php

add_action('wp', 'wcst_theme_helper_eva', 99);

function wcst_theme_helper_eva() {
    $wcst_core = WCST_Core::get_instance();
    // removing duplicate price
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    
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

    // adding wcst action hooks on theme
    add_action('woocommerce_single_product_summary_single_title', array($wcst_core, 'wcst_position_above_title'), 4);
    add_action('woocommerce_single_product_summary_single_title', array($wcst_core, 'wcst_position_below_title'), 6);
    add_action('woocommerce_single_product_summary_single_rating', array($wcst_core, 'wcst_position_below_review'), 12);
    add_action('woocommerce_single_product_summary_single_price', array($wcst_core, 'wcst_position_below_price'), 12);
    add_action('woocommerce_single_product_summary_single_excerpt', array($wcst_core, 'wcst_position_below_short_desc'), 22);
    add_action('woocommerce_single_product_summary_single_add_to_cart', array($wcst_core, 'wcst_position_below_add_cart'), 32);
    add_action('woocommerce_single_product_summary_single_meta', array($wcst_core, 'wcst_position_below_meta'), 42);
    
    add_action('woocommerce_after_single_product_summary_data_tabs', array($wcst_core, 'wcst_position_above_tab_area'), 8);
    add_action('woocommerce_after_single_product_summary_related_products', array($wcst_core, 'wcst_position_below_related_products'), 22);
}
