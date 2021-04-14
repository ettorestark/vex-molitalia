<?php

add_action('wp', 'wcst_theme_helper_as', 99);

function wcst_theme_helper_as() {
    $wcst_core = WCST_Core::get_instance();
    // removing duplicate price
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 15);

    // removing wcst action hooks on theme
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 21.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 41.2);

    // adding wcst action hooks on theme
    add_action('accessories_shop_woocommerce_above_title', array($wcst_core, 'wcst_position_above_title'), 10);
    add_action('accessories_shop_woocommerce_below_title', array($wcst_core, 'wcst_position_below_title'), 10);
    add_action('accessories_shop_woocommerce_below_review', array($wcst_core, 'wcst_position_below_review'), 10);
    add_action('accessories_shop_woocommerce_below_price', array($wcst_core, 'wcst_position_below_price'), 10);
    add_action('accessories_shop_woocommerce_below_short_desc', array($wcst_core, 'wcst_position_below_short_desc'), 10);
    add_action('woocommerce_product_meta_end', array($wcst_core, 'wcst_position_below_meta'), 10);
}
