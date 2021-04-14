<?php

add_action( 'wp', 'wcst_theme_helper_porto', 99 );
if ( ! function_exists( 'wcst_theme_helper_porto' ) ) {

	function wcst_theme_helper_porto() {
		$wcst_core = WCST_Core::get_instance();

		// removing duplicate price
//		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

		// removing wcst action hooks on theme
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 17.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 25 );
	}
}