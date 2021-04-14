<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// product types - simple, grouped, external, variable
class XL_WCST_Product
{

    public static $instances = array();
    protected static $instance = null;

    /**
     * @var WC_Product
     */
    public $product;
    public $wcst_product_comment_status;
    public $wcst_product_is_in_stock;
    public $product_comment_count = null;
    public $variationID = 0;
    public $smarter_reviews_arr = null;
    public $product_type;
    public $stock_status;
    public $backorder_status;
    public $is_manage_stock;

    public function __construct($post_ID)
    {


        $this->product = wc_get_product($post_ID);

        if( !$this->product) {
            return false;
        }
        $this->product_type = (($this->product->get_type()) ? $this->product->get_type() : null);

        $this->wcst_product_comment_status = get_post($post_ID)->comment_status;


    }

    public static function get_instance($post_ID)
    {


        if (!isset(self::$instances[$post_ID])) {
            self::$instance = new self($post_ID, 0);

            return self::$instances[$post_ID] = self::$instance;


        } else {
            return self::$instances[$post_ID];
        }


        return self::$instance;
    }

    public function get_stock_status()
    {

        if (!$this->stock_status) {

            $this->stock_status = get_post_meta($this->product->get_id(), '_stock_status', true);

        }

        return $this->stock_status;

    }

    public function get_backorder_status()
    {

        if (!$this->backorder_status) {

            $this->backorder_status = get_post_meta($this->product->get_id(), '_backorders', true);

        }

        return $this->backorder_status;

    }

    public function is_manage_stock()
    {
        global $woocommerce;
        if (version_compare($woocommerce->version, '3.0.0', ">=")) {
            return (bool) $this->product->get_manage_stock();
        }else{
            if($this->product->manage_stock == "yes") {
                return true;
            }else{
               return false;
            }
        }

    }

    public function sales_date_for_product_variations()
    {
        global $woocommerce;
        $wcst_prod_variation_end = array();
        $wcst_prod_variation = array();

        if (in_array($this->product_type, WCST_Common::wcst_woocommerce_product_type_variations())) {
            $handle = new WC_Product_Variable($this->product->get_id());
            $variations = $handle->get_children();
            if (is_array($variations) && count($variations) > 0) {
                foreach ($variations as $value) {



                    $sale_price_from = 0;
                    $sale_price_to = 0;
                    if (version_compare($woocommerce->version, '3.0.0', ">=")) {
                        // if version greater or equal to 3.0.0
                        $variation_prod = wc_get_product($value);
                        $sale_price_from_obj = $variation_prod->get_date_on_sale_from();
                        if ($sale_price_from_obj != NULL) {
                            $sale_price_from = $sale_price_from_obj->getTimestamp();
                        }
                        $sale_price_to_obj = $variation_prod->get_date_on_sale_to();
                        if ($sale_price_to_obj != NULL) {
                            $sale_price_to = $sale_price_to_obj->getTimestamp();
                        }
                    } else {
                        // for older version
                        $sale_price_from = get_post_meta($value, '_sale_price_dates_from', true);
                        if ($sale_price_from && $sale_price_from != '') {
                            $sale_price_from = $sale_price_from - (WCST_Common::get_timezone_difference());
                        }
                        $sale_price_to = get_post_meta($value, '_sale_price_dates_to', true);
                        if ($sale_price_to && $sale_price_to != '') {
                            $sale_price_to = $sale_price_to - (WCST_Common::get_timezone_difference());
                        }
                    }

                    $wcst_prod_variation[$value] = array('from' => $sale_price_from, 'to' => $sale_price_to);

                    if (!empty($sale_price_to)) {
                        $wcst_prod_variation_end[$value] = date(WCST_Common::wcst_get_date_format(), $sale_price_to);
                    }


                }
            }
        }

        return array('end' => $wcst_prod_variation_end, 'variations' => $wcst_prod_variation);


    }

    public function get_smarter_review_array()
    {
        if (is_null($this->smarter_reviews_arr)) {
            $smarter_reviews_arr = array();
            $smarter_reviews_arr['comment_status'] = $this->wcst_product_comment_status;
            $smarter_reviews_arr['comment_count'] = $this->get_comment_count();

            $product_rating = get_post_meta($this->product->get_id(), '_wc_rating_count', true);

            if (is_array($product_rating) && count($product_rating) > 0) {
                $product_rating[1] = isset($product_rating[1]) ? $product_rating[1] : 0;
                $product_rating[2] = isset($product_rating[2]) ? $product_rating[2] : 0;
                $product_rating[3] = isset($product_rating[3]) ? $product_rating[3] : 0;
                $product_rating[4] = isset($product_rating[4]) ? $product_rating[4] : 0;
                $product_rating[5] = isset($product_rating[5]) ? $product_rating[5] : 0;
                $smarter_reviews_arr['rating'] = $product_rating;
            }
            $this->smarter_reviews_arr = $smarter_reviews_arr;

        }
        return $this->smarter_reviews_arr;
    }

    public function get_comment_count()
    {

        if (is_null($this->product_comment_count)) {

            $comment_count = get_post_meta($this->product->get_id(), '_wc_review_count', true);
            $this->product_comment_count = $comment_count ? $comment_count : 0;

        }


        return $this->product_comment_count;

    }

    public function get_in_stock()
    {

        if ($this->wcst_product_is_in_stock == null) {
            if (in_array($this->product_type, WCST_Common::wcst_woocommerce_product_type_variations())) {
                $pro = XL_WCST_Product::get_instance($this->variationID);
                $this->wcst_product_is_in_stock = $pro->is_in_stock();
            } else {

                $status = $this->wcst_product_is_in_stock = $this->product->is_in_stock();
            if($status) {
                $stock_qty = $this->product->get_stock_quantity();
                if($stock_qty < 0)  {
                    $status = $this->wcst_product_is_in_stock = FALSE;
                }
            }
            }
        }

        return $this->wcst_product_is_in_stock;


    }

    public function get_variation_for_grid()
    {
        if (in_array($this->product_type, WCST_Common::wcst_woocommerce_product_type_variations())) {


            $children = $this->product->get_children();

            if ($children && is_array($children) && count($children) > 0) {
                return $children[0];
            } else {
                return false;
            }

        }

    }


}

