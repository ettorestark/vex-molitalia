<?php

add_action('wp', 'wcst_theme_helper_uncode', 99);

function wcst_theme_helper_uncode() {

    $wcst_core = WCST_Core::get_instance();

    // removing below price and below add to cart buttton action hook of plugin
    remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_above_tab_area'), 9.8);
    remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_related_products'), 21.2);

    // hooking below functions for 'uncode' theme
    add_action('wcst_uncode_theme_above_tab_area', function () {
        echo '<div class="row row-parent limit-width">';
    }, 9);
    add_action('wcst_uncode_theme_above_tab_area', array($wcst_core, 'wcst_position_above_tab_area'), 10);
    add_action('wcst_uncode_theme_above_tab_area', function () {
        echo '</div>';
    }, 11);
    add_action('wcst_uncode_theme_below_related_products', array($wcst_core, 'wcst_position_below_related_products'), 10);
}

add_action('woocommerce_single_product_summary', function() {
    echo '<div style="clear:both;"></div>';
}, 10);

add_action('woocommerce_before_template_part', 'wcst_theme_helper_uncode_before_template_part', 99);

function wcst_theme_helper_uncode_before_template_part($template_name = '', $template_path = '', $located = '', $args = array()) {
    if (empty($template_name)) {
        return '';
    }
    if ($template_name == 'single-product/tabs/tabs.php') {
        do_action('wcst_uncode_theme_above_tab_area');
    }
}

add_action('woocommerce_after_template_part', 'wcst_theme_helper_uncode_after_template_part', 99);

function wcst_theme_helper_uncode_after_template_part($template_name = '', $template_path = '', $located = '', $args = array()) {
    if (empty($template_name)) {
        return '';
    }
    if ($template_name == 'single-product/related.php') {
        do_action('wcst_uncode_theme_below_related_products');
    }
}
