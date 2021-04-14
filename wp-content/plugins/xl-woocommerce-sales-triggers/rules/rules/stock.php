<?php

class WCST_Rule_Stock_Status extends WCST_Rule_Base {

	public function __construct() {
		parent::__construct( 'stock_status' );
	}

	public function get_possibile_rule_operators() {
		$operators = array(
			'==' => __( "is", WCST_SLUG ),
			'!=' => __( "is not", WCST_SLUG ),
		);

		return $operators;
	}

	public function get_possibile_rule_values() {
		$options = array(
			'0' => __( 'Out of stock', 'woocommerce' ),
			'1' => __( 'In stock', 'woocommerce' ),
		);

		$options = apply_filters( 'wcst_rule_stock_status', $options );

		return $options;
	}

	public function get_condition_input_type() {
		return 'Select';
	}

	public function is_match( $rule_data, $product_id ) {
		$result  = false;
		$product = wc_get_product( $product_id );
		if ( $product && $product instanceof WC_Product && isset( $rule_data['condition'] ) && isset( $rule_data['operator'] ) ) {
			if ( '0' == $rule_data['condition'] || '1' == $rule_data['condition'] ) {
				$in = $product->is_in_stock();
				if ( '==' == $rule_data['operator'] ) {
					$result = ( '1' == $rule_data['condition'] ) ? $in : ! $in;
				} elseif ( '!=' == $rule_data['operator'] ) {
					$result = ! ( ( '1' == $rule_data['condition'] ) ? $in : ! $in );
				}
			} elseif ( '2' == $rule_data['condition'] ) {
				$in = $product->is_on_backorder();
				if ( '==' == $rule_data['operator'] ) {
					$result = ( true === $in ) ? true : false;
				} elseif ( '!=' == $rule_data['operator'] ) {
					$result = ( true === $in ) ? false : true;
				}
			}
		}

		return $this->return_is_match( $result, $rule_data );
	}

}

class WCST_Rule_Stock_Level extends WCST_Rule_Base {

	public function __construct() {
		parent::__construct( 'stock_level' );
	}

	public function get_possibile_rule_operators() {
		$operators = array(
			'==' => __( "is equal to", WCST_SLUG ),
			'!=' => __( "is not equal to", WCST_SLUG ),
			'>'  => __( "is greater than", WCST_SLUG ),
			'<'  => __( "is less than", WCST_SLUG ),
			'>=' => __( "is greater or equal to", WCST_SLUG ),
			'=<' => __( "is less or equal to", WCST_SLUG )
		);

		return $operators;
	}

	public function get_condition_input_type() {
		return 'Text';
	}

	public function is_match( $rule_data, $productID ) {
		global $post;
		$result  = false;
		$product = wc_get_product( $productID );
		if ( $product && isset( $rule_data['condition'] ) && isset( $rule_data['operator'] ) ) {
			$stock = $product->get_stock_quantity();
			$value = (float) $rule_data['condition'];

			switch ( $rule_data['operator'] ) {
				case '==' :
					$result = $stock == $value;
					break;
				case '!=' :
					$result = $stock != $value;
					break;
				case '>' :
					$result = $stock > $value;
					break;
				case '<' :
					$result = $stock < $value;
					break;
				case '>=' :
					$result = $stock >= $value;
					break;
				case '<=' :
					$result = $stock <= $value;
					break;
				default:
					$result = false;
					break;
			}
		}

		return $this->return_is_match( $result, $rule_data );
	}

}


class WCST_Rule_Manage_Stock extends WCST_Rule_Base {

	public function __construct() {
		parent::__construct( 'manage_stock' );
	}

	public function get_possibile_rule_operators() {
		$operators = array(
			'is' => __( "is", WCST_SLUG ),

		);

		return $operators;
	}


	public function get_possibile_rule_values() {
		$options = array(
			'yes' => __( 'Yes', WCST_SLUG ),
			'no'  => __( 'No', WCST_SLUG )
		);

		return $options;
	}

	public function get_condition_input_type() {
		return 'Select';
	}

	public function is_match( $rule_data, $productID ) {
		global $post;
		$result  = false;
		$product = wc_get_product( $productID );


		if ( $product && isset( $rule_data['condition'] ) && isset( $rule_data['operator'] ) ) {

			if ( $rule_data['condition'] == "yes" ) {
				$result = $product->manage_stock == "yes";
			} else {
				$result = $product->manage_stock == "no";
			}

		}

		return $this->return_is_match( $result, $rule_data );
	}

}