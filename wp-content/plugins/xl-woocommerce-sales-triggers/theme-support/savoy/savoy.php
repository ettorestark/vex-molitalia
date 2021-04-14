<?php

add_action( 'wp', 'wcst_theme_helper_savoy', 99 );
if ( ! function_exists( 'wcst_theme_helper_savoy' ) ) {

	function wcst_theme_helper_savoy() {
		$wcst_core = WCST_Core::get_instance();

		// removing wcst action hooks on theme
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 11.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 22 );

		add_action( 'woocommerce_after_single_product_summary', function() {
			echo '<div class="wcst_clear_20"></div><div class="nm-row"><div class="col-xs-12">';
		}, 8.8 );
		add_action( 'woocommerce_after_single_product_summary', function() {
			echo '<div class="nm-row"><div class="col-xs-12">';
		}, 20.2 );
		add_action( 'woocommerce_after_single_product_summary', function() {
			echo '</div></div>';
		}, 10.8 );
		add_action( 'woocommerce_after_single_product_summary', function() {
			echo '</div></div>';
		}, 22.2 );
	}
}