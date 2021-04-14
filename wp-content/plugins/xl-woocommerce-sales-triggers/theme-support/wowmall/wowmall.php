<?php
add_action('wp', 'wcst_theme_helper_wowmall', 99);

function wcst_theme_helper_wowmall() {
    $wcst_core = WCST_Core::get_instance();
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39.2);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_add_cart'), 39);
    remove_action( 'woocommerce_single_product_summary', 'wowmall_wc_single_btns_wrapper_end', 39 );
    add_action( 'woocommerce_single_product_summary', 'wowmall_wc_single_btns_wrapper_end', 38 );

    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.2);

    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 27);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 27);


}
