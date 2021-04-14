<?php

add_action('wp', 'wcst_theme_helper_shopkeeper');

function wcst_theme_helper_shopkeeper() {

    $wcst_core = WCST_Core::get_instance();

    // removing duplicate price
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

    // removing below price and below add to cart buttton action hook of plugin
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 21.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 41.2);
    remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_above_tab_area'), 9.8);
    remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_related_products'), 21.2);

    // hooking below functions for 'shopkeeper' theme
    add_action('woocommerce_single_product_summary_single_title', array($wcst_core, 'wcst_position_above_title'), 2);
    add_action('woocommerce_single_product_summary_single_title', array($wcst_core, 'wcst_position_below_title'), 9);
    add_action('woocommerce_single_product_summary_single_title', array($wcst_core, 'wcst_position_below_review'), 1);
    add_action('woocommerce_single_product_summary_single_price', array($wcst_core, 'wcst_position_below_price'), 19);
    add_action('woocommerce_single_product_summary_single_excerpt', array($wcst_core, 'wcst_position_below_short_desc'), 21);
    add_action('woocommerce_single_product_summary_single_add_to_cart', array($wcst_core, 'wcst_position_below_add_cart'), 31);
    add_action('woocommerce_single_product_summary_single_meta', array($wcst_core, 'wcst_position_below_meta'), 41);

    add_action('woocommerce_after_single_product_summary_data_tabs', array($wcst_core, 'wcst_position_above_tab_area'), 10);

    add_action('woocommerce_after_single_product_summary_related_products', array($wcst_core, 'wcst_position_below_related_products'), 21);
}
