<?php

add_action('wp', 'wcst_theme_helper_claue', 99);

function wcst_theme_helper_claue() {
    $wcst_core = WCST_Core::get_instance();
    $options = get_post_meta(get_the_ID(), '_custom_wc_options', true);

    // Get product single style
    $style = (is_array($options) && $options['wc-single-style']) ? $options['wc-single-style'] : (cs_get_option('wc-single-style') ? cs_get_option('wc-single-style') : '1');
    if ($style != "3") {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        remove_action('woocommerce_single_product_summary', 'jas_claue_wc_before_price', 5);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.3);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.2);

        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 18);
        add_action('woocommerce_single_product_summary', 'jas_claue_wc_before_price', 7);
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 5.2);
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 15);
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 18.2);
        add_action('woocommerce_after_single_product_summary', function() {
            echo '<div class="wcst_clear wcst_clear_20"></div>';
        }, 20.8);
    } else {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);        
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11.3);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.3);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39.3);

        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 7);        
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 5);
        add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 6);
        add_action('woocommerce_after_add_to_cart_button', array($wcst_core, 'wcst_position_below_add_cart'), 99);
        add_action('woocommerce_after_add_to_cart_button', function() {
            echo '<div class="wcst_clear wcst_clear_20"></div>';
        }, 98);
        add_action('woocommerce_after_single_product_summary', function() {
            echo '<div class="wcst_clear wcst_clear_20"></div>';
        }, 20.8);
    }
}
