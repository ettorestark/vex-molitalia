<?php

add_action('wp', 'wcst_theme_helper_float', 99);

function wcst_theme_helper_float() {

    $wcst_core = WCST_Core::get_instance();
    // removing all positions based action hooks
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_short_desc'), 21.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39.2);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 41.2);
    remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_above_tab_area'), 9.8);
    remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_related_products'), 21.2);


    add_action('wcst_float_theme_above_title', array($wcst_core, 'wcst_position_above_title'), 10);
    add_action('wcst_float_theme_below_title', array($wcst_core, 'wcst_position_below_title'), 10);
    add_action('wcst_float_theme_below_review', array($wcst_core, 'wcst_position_below_review'), 10);
    add_action('wcst_float_theme_below_price', array($wcst_core, 'wcst_position_below_price'), 10);
    add_action('wcst_float_theme_below_short_desc', array($wcst_core, 'wcst_position_below_short_desc'), 10);
    add_action('woocommerce_after_add_to_cart_form', array($wcst_core, 'wcst_position_below_add_cart'), 10);
    add_action('wcst_float_theme_below_meta', array($wcst_core, 'wcst_position_below_meta'), 10);
    add_action('wcst_float_theme_above_tab_area', array($wcst_core, 'wcst_position_above_tab_area'), 10);
    add_action('wcst_float_theme_below_related_products', array($wcst_core, 'wcst_position_below_related_products'), 10);
}

add_action('woocommerce_before_template_part', 'wcst_theme_helper_float_before_template_part', 99);

function wcst_theme_helper_float_before_template_part($template_name = '', $template_path = '', $located = '', $args = array()) {
    if (empty($template_name)) {
        return '';
    }
    if ($template_name == 'single-product/title.php') {
        do_action('wcst_float_theme_above_title');
    } elseif ($template_name == 'single-product/tabs/tabs.php') {
        do_action('wcst_float_theme_above_tab_area');
    }
}

add_action('woocommerce_after_template_part', 'wcst_theme_helper_float_after_template_part', 99);

function wcst_theme_helper_float_after_template_part($template_name = '', $template_path = '', $located = '', $args = array()) {
    if (empty($template_name)) {
        return '';
    }
    if ($template_name == 'single-product/title.php') {
        do_action('wcst_float_theme_below_title');
    } elseif ($template_name == 'single-product/short-description.php') {
        do_action('wcst_float_theme_below_short_desc');
    } elseif ($template_name == 'single-product/rating.php') {
        do_action('wcst_float_theme_below_review');
    } elseif ($template_name == 'single-product/price.php') {
        do_action('wcst_float_theme_below_price');
    } elseif ($template_name == 'single-product/meta.php') {
        do_action('wcst_float_theme_below_meta');
    } elseif ($template_name == 'single-product/related.php') {
        do_action('wcst_float_theme_below_related_products');
    }
}
