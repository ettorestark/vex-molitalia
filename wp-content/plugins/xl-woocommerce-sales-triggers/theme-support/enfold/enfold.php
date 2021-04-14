<?php

add_action( 'wp', 'wcst_theme_helper_enfold', 99 );
if ( ! function_exists( 'wcst_theme_helper_enfold' ) ) {

	function wcst_theme_helper_enfold() {
		$wcst_core = WCST_Core::get_instance();

		// removing wcst action hooks on theme
		remove_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_above_tab_area' ), 9.8 );
		remove_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_below_related_products' ), 21.2 );
	}

}

add_action( 'woocommerce_before_template_part', 'wcst_theme_helper_enfold_before_template_part', 99 );

if ( ! function_exists( 'wcst_theme_helper_enfold_before_template_part' ) ) {
	function wcst_theme_helper_enfold_before_template_part( $template_name = '', $template_path = '', $located = '', $args = array() ) {
		$wcst_core = WCST_Core::get_instance();
		if ( empty( $template_name ) ) {
			return '';
		}
		if ( $template_name == 'single-product/tabs/tabs.php' ) {
			echo $wcst_core->wcst_position_above_tab_area();
		}
	}
}

add_action( 'woocommerce_after_template_part', 'wcst_theme_helper_enfold_after_template_part', 99 );

if ( ! function_exists( 'wcst_theme_helper_enfold_after_template_part' ) ) {
	function wcst_theme_helper_enfold_after_template_part( $template_name = '', $template_path = '', $located = '', $args = array() ) {

		$wcst_core = WCST_Core::get_instance();
		if ( empty( $template_name ) ) {
			return '';
		}
		if ( $template_name == 'single-product/related.php' ) {
			echo $wcst_core->wcst_position_below_related_products();
		}
	}
}