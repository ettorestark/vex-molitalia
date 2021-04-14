<?php

add_action( 'wp', 'wcst_theme_helper_aurum', 99 );
if ( ! function_exists( 'wcst_theme_helper_aurum' ) ) {

	function wcst_theme_helper_aurum() {
		$wcst_core = WCST_Core::get_instance();

		// removing wcst action hooks on theme
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 17.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 27 );
	}
}