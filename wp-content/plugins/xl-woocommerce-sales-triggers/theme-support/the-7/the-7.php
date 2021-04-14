<?php

add_action('wp', 'wcst_theme_helper_the7');

function wcst_theme_helper_the7() {
    $wcst_core = WCST_Core::get_instance();
    //handling above and below title
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.3);

    add_filter('presscore_page_title', 'wcst_presscore_location_below_and_above_title', 10, 1);

    function wcst_presscore_location_below_and_above_title($content) {
        $wcst_core = WCST_Core::get_instance();
        ob_start();
        echo '<div class="wf-td">';
        $wcst_core->wcst_position_above_title();
        echo '</div>';
        echo $content;
        echo '<div class="wf-td">';
        $wcst_core->wcst_position_below_title();
        echo '</div>';
        return ob_get_clean();
    }

    //handling price and review hooks
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 12);

    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 11.3);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_review'), 12.2);
    
    add_action('woocommerce_after_single_product_summary', function() {
        echo '<div class="wcst_clear wcst_clear_20"></div>';
    }, 20);
}
