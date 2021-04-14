<?php

class WCST_Compatibility_YOAST {

	public function __construct() {
		add_filter( 'wcst_best_seller_badge_cat_id', array( $this, 'setup_primary_cat_badge' ), 99, 2 );
        add_filter( 'wcst_static_badge_cat_id', array( $this, 'setup_primary_cat_badge' ), 99, 2 );
	}

	/**
	 * Check if yoast installed and product has primary category
	 *
	 * @param $term_id
	 * @param $product_info
	 *
	 * @return int
	 */
	public function setup_primary_cat_badge( $term_id, $product_info ) {
		if ( defined( 'YOAST_ENVIRONMENT' ) ) {
			$primary_cat = get_post_meta( $product_info->product->get_id(), '_yoast_wpseo_primary_product_cat', true );
			if ( absint( $primary_cat ) > 0 ) {
				$term_id = absint( $primary_cat );
			}
		}

		return $term_id;
	}
}

new WCST_Compatibility_YOAST();
