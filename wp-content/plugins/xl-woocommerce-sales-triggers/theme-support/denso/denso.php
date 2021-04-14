<?php

add_action('wp', 'wcst_theme_helper_denso');

function wcst_theme_helper_denso() {
    $wcst_core = WCST_Core::get_instance();
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    // removing wcst action hooks on theme
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 21.3);

    // adding wcst action hooks on theme
    add_action('denso_woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.3);
    add_action('denso_woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.2);
    add_action('denso_woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11.3);
    add_action('denso_woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.3);
    add_action('denso_woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 26.3);
}
