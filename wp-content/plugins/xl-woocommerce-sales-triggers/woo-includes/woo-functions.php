<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Functions used by plugins
 */
if (!class_exists('WCST_Dependencies'))
    require_once 'class-wc-dependencies.php';

/**
 * WC Detection
 */
if (!function_exists('wcst_is_woocommerce_active')) {

    function wcst_is_woocommerce_active() {
        return WCST_Dependencies::woocommerce_active_check();
    }

}