<?php

add_action( 'wp', 'wcst_theme_helper_techmarket', 98 );
if ( ! function_exists( 'wcst_theme_helper_techmarket' ) ) {

	function wcst_theme_helper_techmarket() {
		$wcst_core = WCST_Core::get_instance();

		// removing wcst action hooks on theme
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_title' ), 9.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 11 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 17.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_add_cart' ), 39.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_meta' ), 41.2 );
		remove_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_above_tab_area' ), 9.8 );

		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_title' ), 7.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 12.2 );
		add_action( 'techmarket_single_product_action', array( $wcst_core, 'wcst_position_below_price' ), 32.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_add_cart' ), 51.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_meta' ), 8.2 );
		add_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_above_tab_area' ), 9.2 );

		add_action( 'wp_head', function() {
			echo '<style>.single-product .product-actions .wcst_on_product {width:100%;}</style>';
		} );
	}
}
