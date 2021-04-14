<?php

add_action('wp', 'wcst_theme_helper_hcode');

function wcst_theme_helper_hcode() {
    $wcst_core = WCST_Core::get_instance();
    // removing all positions based action hooks
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11.3);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 21.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 41.3);

    // hooking all positions
    add_action('hcode_woocommerce_product_single_rating_sku', function () {
        echo '<div style="clear:both"></div>';
    }, 45);
    add_action('hcode_woocommerce_product_single_rating_sku', array($wcst_core, 'wcst_position_below_review'), 50);
    add_action('hcode_woocommerce_product_single_rating_sku', array($wcst_core, 'wcst_position_above_title'), 60);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 16);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 20);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 17);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 30);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 60);
}
