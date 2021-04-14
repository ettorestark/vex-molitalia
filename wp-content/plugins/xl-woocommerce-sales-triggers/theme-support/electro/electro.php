<?php

add_action('wp', 'wcst_theme_helper_electro');

function wcst_theme_helper_electro() {
    $wcst_core = WCST_Core::get_instance();
    if (is_singular('product')) {
        $_product_layout = electro_get_single_product_layout(); // left-sidebar full-width
        $_product_style = electro_get_single_product_style(); // normal extended
        // removing below price and below add to cart buttton action hook of plugin
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.3);
        remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39.3);

        if ($_product_layout == 'full-width') {
            if ($_product_style == 'extended') { // 3 col structure
                add_action('electro_single_product_action', array($wcst_core, 'wcst_position_below_add_cart'), 32);
                add_action('electro_single_product_action', array($wcst_core, 'wcst_position_below_price'), 22);
            } else {
                add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 28);
                add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 32);
            }
        } else {
            add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 28);
            add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 32);
        }
    }
}
