<?php

add_action( 'wp', 'wcst_theme_helper_thegem', 99 );
if ( ! function_exists( 'wcst_theme_helper_thegem' ) ) {

	function wcst_theme_helper_thegem() {
		$wcst_core = WCST_Core::get_instance();

		// removing & setting wcst action hooks on handle 'woocommerce_single_product_summary'
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_above_title' ), 2.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_title' ), 9.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 11.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 17.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_short_desc' ), 21.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_add_cart' ), 39.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_meta' ), 41.2 );

		add_action( 'thegem_woocommerce_single_product_right', array( $wcst_core, 'wcst_position_above_title' ), 8.2 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcst_core, 'wcst_position_below_title' ), 12.2 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcst_core, 'wcst_position_below_review' ), 22.2 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcst_core, 'wcst_position_below_price' ), 32.2 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcst_core, 'wcst_position_below_short_desc' ), 37.2 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcst_core, 'wcst_position_below_add_cart' ), 47.2 );
		add_action( 'thegem_woocommerce_single_product_left', array( $wcst_core, 'wcst_position_below_meta' ), 17.2 );


		// removing & setting wcst action hooks on handle 'woocommerce_after_single_product_summary'
		remove_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_above_tab_area' ), 9.8 );
		remove_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_below_related_products' ), 21.2 );

		add_action( 'thegem_woocommerce_single_product_bottom', array( $wcst_core, 'wcst_position_above_tab_area' ), 3.2 );
		add_action( 'thegem_woocommerce_after_single_product', array( $wcst_core, 'wcst_position_below_related_products' ), 7.2 );
	}

}
