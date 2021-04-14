<?php

add_action( 'wp', 'wcst_theme_helper_sober', 99 );
if ( ! function_exists( 'wcst_theme_helper_sober' ) ) {

	function wcst_theme_helper_sober() {
		$wcst_core = WCST_Core::get_instance();

		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 17.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 21.2 );

		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 11.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 16.2 );

		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_short_desc' ), 21.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_short_desc' ), 11.2 );
	}
}
