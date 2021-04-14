<?php

add_action( 'wp', 'wcst_theme_helper_boxshop', 98 );
if ( ! function_exists( 'wcst_theme_helper_boxshop' ) ) {

	function wcst_theme_helper_boxshop() {
		$wcst_core = WCST_Core::get_instance();

		// removing wcst action hooks on theme
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_above_title' ), 2.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_title' ), 9.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_review' ), 11 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_price' ), 17.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_short_desc' ), 21.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_add_cart' ), 39.2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_meta' ), 41.2 );

		remove_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_above_tab_area' ), 9.8 );
		remove_action( 'woocommerce_after_single_product_summary', array( $wcst_core, 'wcst_position_below_related_products' ), 21.2 );


		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_above_title' ), 1.2 );
		add_action( 'woocommerce_single_product_summary', array( $wcst_core, 'wcst_position_below_title' ), 1.7 );
	}

}

add_action( 'woocommerce_before_template_part', 'wcst_theme_helper_boxshop_before_template_part', 98 );

if ( ! function_exists( 'wcst_theme_helper_boxshop_before_template_part' ) ) {
	function wcst_theme_helper_boxshop_before_template_part( $template_name = '', $template_path = '', $located = '', $args = array() ) {
		$wcst_core = WCST_Core::get_instance();
		if ( empty( $template_name ) ) {
			return '';
		}
		if ( $template_name == 'single-product/tabs/tabs.php' ) {
			echo $wcst_core->wcst_position_above_tab_area();
		}
	}
}

add_action( 'woocommerce_after_template_part', 'wcst_theme_helper_boxshop_after_template_part', 98 );

if ( ! function_exists( 'wcst_theme_helper_boxshop_after_template_part' ) ) {
	function wcst_theme_helper_boxshop_after_template_part( $template_name = '', $template_path = '', $located = '', $args = array() ) {

		$wcst_core = WCST_Core::get_instance();
		if ( empty( $template_name ) ) {
			return '';
		}
		if ( $template_name == 'single-product/short-description.php' ) {
			echo $wcst_core->wcst_position_below_short_desc();
		} elseif ( $template_name == 'single-product/rating.php' ) {
			echo $wcst_core->wcst_position_below_review();
		} elseif ( $template_name == 'single-product/price.php' ) {
			echo $wcst_core->wcst_position_below_price();
		} elseif ( $template_name == 'single-product/meta.php' ) {
			echo $wcst_core->wcst_position_below_meta();
		} elseif ( $template_name == 'single-product/related.php' ) {
			echo $wcst_core->wcst_position_below_related_products();
		}
	}
}

/**
 * Handling for below add to cart position Starts here
 */
add_action( 'woocommerce_after_add_to_cart_form', 'wcst_theme_helper_boxshop_after_add_to_cart_template', 98 );

if ( ! function_exists( 'wcst_theme_helper_boxshop_after_add_to_cart_template' ) ) {
	function wcst_theme_helper_boxshop_after_add_to_cart_template() {
		$wcst_core = WCST_Core::get_instance();
		ob_start();
		echo $wcst_core->wcst_position_below_add_cart();
		$output = ob_get_clean();
		if ( $output !== "" ) {
			echo '<div class="wcst_clear" style="height: 15px;"></div>';
		}
		echo $output;
	}
}