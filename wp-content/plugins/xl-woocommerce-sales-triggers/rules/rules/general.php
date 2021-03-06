<?php

class WCST_Rule_General_Always extends WCST_Rule_Base {

	public function __construct() {
		parent::__construct( 'general_always' );
	}

	public function get_possibile_rule_operators() {
		return null;
	}

	public function get_possibile_rule_values() {
		return null;
	}

	public function get_condition_input_type() {
		return 'Html_Always';
	}

	public function is_match( $rule_data, $productID ) {
		return true;
	}

}

class WCST_Rule_General_All_Pages extends WCST_Rule_Base {

    public function __construct() {
        parent::__construct( 'general_all_pages' );
    }

    public function get_possibile_rule_operators() {
        return null;
    }

    public function get_possibile_rule_values() {
        return null;
    }

    public function get_condition_input_type() {
        return 'Html_Always';
    }

    public function is_match( $rule_data, $productID ) {
        return is_singular('product');
    }

}

class WCST_Rule_General_All_Product_Cats extends WCST_Rule_Base {

    public function __construct() {
        parent::__construct( 'general_all_product_cats' );
    }

    public function get_possibile_rule_operators() {
        return null;
    }

    public function get_possibile_rule_values() {
        return null;
    }

    public function get_condition_input_type() {
        return 'Html_Always';
    }

    public function is_match( $rule_data, $productID ) {
        return is_tax('product_cat');
    }

}
