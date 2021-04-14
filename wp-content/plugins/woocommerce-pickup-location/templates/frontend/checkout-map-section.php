<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */
if (!defined('ABSPATH')) {
    exit;
}
$wp_pkpo_settings = get_option('wp_pkpo_options');
$include = "";
if (isset($wp_pkpo_settings["wp_pickup_location_based_product"]) && $wp_pkpo_settings["wp_pickup_location_based_product"] === "enable") {
    $location_ids = array();
    foreach (WC()->cart->get_cart() as $cart_item) {
        $products_id = $cart_item['product_id'];
        $wp_pkpo_product_locations = get_post_meta($products_id, 'wp_pkpo_product_locations', true);
        if (is_array($wp_pkpo_product_locations) && count($wp_pkpo_product_locations) > 0)
            $location_ids = array_merge($location_ids, $wp_pkpo_product_locations);
    }
    $include =  implode(",", $location_ids);
}
global $product;

$wp_pkpo_args = array(
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'post_type' => 'pickup_location',
    'post_status' => 'publish',
    'include'=> $include
);
$wp_pkpo_pickup_points = get_posts($wp_pkpo_args, ARRAY_A);

?>

<table class="shop_table woocommerce-checkout-review-order-table">
    <thead>
        <tr>		 
            <th class="product-name"><?php _e('Product', 'woocommerce'); ?></th>
            <th class="product-total"><?php _e('Total', 'woocommerce'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        do_action('woocommerce_review_order_before_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                ?>
                <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                    <td class="product-name">
                        <?php echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key) . '&nbsp;'; ?>
                        <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times; %s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); ?>
                        <?php echo WC()->cart->get_item_data($cart_item); ?>
                    </td>
                    <td class="product-total">
                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                    </td>
                </tr>
                <?php
            }
        }

        do_action('woocommerce_review_order_after_cart_contents');
        ?>
    </tbody>
    <tfoot>

        <tr class="cart-subtotal">
            <th><?php _e('Subtotal', 'woocommerce'); ?></th>
            <td><?php wc_cart_totals_subtotal_html(); ?></td>
        </tr>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

            <?php do_action('woocommerce_review_order_before_shipping'); ?>

            <?php wc_cart_totals_shipping_html(); ?>

            <?php do_action('woocommerce_review_order_after_shipping'); ?>

        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <tr class="fee">
                <th><?php echo esc_html($fee->name); ?></th>
                <td><?php wc_cart_totals_fee_html($fee); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                    <tr class="tax-rate tax-rate-<?php echo sanitize_title($code); ?>">
                        <th><?php echo esc_html($tax->label); ?></th>
                        <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="tax-total">
                    <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                    <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        global $woocommerce;
        $check_map = false;
        $colspan = 2;
        $map_position = "top";
        if (isset($wp_pkpo_settings['wp_pickup_map_position'])) {
            $map_position = $wp_pkpo_settings['wp_pickup_map_position'];
        }
        if (isset($wp_pkpo_settings['wp_pkpo_map_disable']) && ($wp_pkpo_settings['wp_pkpo_map_disable'] === 'enable')) {
            $check_map = true;
            $colspan = 1;
        }
        
        $checkout_js_template = WPPKPO_PLUGIN_DIR . '/templates/frontend/checkout-map-js-without-radius.php';
        
        if(isset($wp_pkpo_settings["wp_pickup_radius_search"]) && $wp_pkpo_settings["wp_pickup_radius_search"] === "enable"){
          $checkout_js_template = WPPKPO_PLUGIN_DIR . '/templates/frontend/checkout-map-js.php';
        }
        $chosen_methods = WC()->session->get('chosen_shipping_methods');
        $chosen_shipping = $chosen_methods[0];
        
        if ($chosen_shipping === 'pickup-location-method') {
           
            ?>
            <tr class="wp_pkpo_checkout_column">

                <?php if ($check_map) { ?>
                    
                    <?php if($map_position==="top"){
                        echo "<td colspan='2'>";
                    }
                    else{
                        echo "<th>";
                    }
                    
                if(isset($wp_pkpo_settings["wp_pickup_radius_search"]) && $wp_pkpo_settings["wp_pickup_radius_search"] === "enable"){
                    ?>
                    <div class="wp-pickup-checkout-map">
                        <div class="wp-pickup-search-section">
                            <input type="text" id="pac-input" size="15" placeholder="<?php _e("Search Box", 'woocommerce-pickup-location'); ?>" style="display: none"/>
                            <label for="wp_pickup_radius"><?php _e("Radius:", 'woocommerce-pickup-location'); ?></label>
                            <select id="wp_pickup_radius" label="<?php _e("Radius:", 'woocommerce-pickup-location'); ?>">
                                <option value="100" selected>100 <?php _e("kms", 'woocommerce-pickup-location'); ?></option>
                                <option value="50" >50 <?php _e("kms", 'woocommerce-pickup-location'); ?></option>
                                <option value="30">30 <?php _e("kms", 'woocommerce-pickup-location'); ?></option>
                                <option value="20">20 <?php _e("kms", 'woocommerce-pickup-location'); ?></option>
                                <option value="10">10 <?php _e("kms", 'woocommerce-pickup-location'); ?></option>
                            </select>
                            <input type="button" id="wp_pickup_search_btn" value="<?php _e("Search", 'woocommerce-pickup-location'); ?>"/>
                        </div>
                        <div class="wp-pickup-location-section">
                            <select id="wp_pickup_location_select" style="width: 30%; visibility: hidden"></select>
                        </div>
                        <div class="wp-pickup-map-container wp-pickup-map-container" id="map-canvas" style="height:400px;"></div>
                    </div>
                <?php } else{ ?>
                <div>
                    <?php 
                    $seachbox_style ="";
                    if(isset($wp_pkpo_settings["wp_pickup_map_search"]) && $wp_pkpo_settings["wp_pickup_map_search"] === "disable"){
                        $seachbox_style ="wp_pkpo_hide";
                    }
                    ?>
                    <input id="pac-input" class="controls <?php echo $seachbox_style;?>" type="text" placeholder="<?php _e('Search Box', 'woocommerce-pickup-location'); ?>" style="display: none">
                    <div class="wp-pickup-map-container wp-pickup-map-container" id="map-canvas" style="height:400px;"></div>
                </div>
               <?php  }
                
            if (isset($wp_pkpo_pickup_points) && $wp_pkpo_pickup_points != '') {
                foreach ($wp_pkpo_pickup_points as $wp_pkpo_pickup) {
                    echo '<div style="display:none" id="wp_pkpo_content_' . $wp_pkpo_pickup->ID . '">' . nl2br($wp_pkpo_pickup->post_content) . '</div>';
                }
            }

            $wp_pkpo_default_position = $wp_pkpo_settings['wp_pkpo_default_position'];
            $wp_pkpo_marker_image = $wp_pkpo_settings['wp_pkpo_marker_image'];
            $wp_pickup_load_pickup_later = "";
            $wp_pkpo_default_lat = "";
            $wp_pkpo_default_lang = "";
            $wp_pkpo_pickup_checkout_map_zoom = "";
            
            if(isset($wp_pkpo_settings['wp_pickup_load_pickup_later']))
                $wp_pickup_load_pickup_later = $wp_pkpo_settings['wp_pickup_load_pickup_later'];

            if (isset($wp_pkpo_default_position) && $wp_pkpo_default_position != '') {
                $wp_pkpo_settings_explode = explode(",", $wp_pkpo_settings['wp_pkpo_default_position']);
                $wp_pkpo_default_lat = $wp_pkpo_settings_explode[0];
                $wp_pkpo_default_lang = $wp_pkpo_settings_explode[1];
            }

            if (isset($wp_pkpo_settings['wp_pkpo_pickup_checkout_map_zoom']) && $wp_pkpo_settings['wp_pkpo_pickup_checkout_map_zoom'] != '') {
                $wp_pkpo_pickup_checkout_map_zoom = $wp_pkpo_settings['wp_pkpo_pickup_checkout_map_zoom'];
            }
            
             if($map_position==="top"){
                        echo "</td></tr>";
                    }
                    else {
                        echo "</th>";
                    }
                    ?>
        
            <?php  if ($map_position === "top") {
                    echo '<tr class="wp_pkpo_checkout_column">';
                    $colspan = 2;
                }
             
            }      
    
                ?>
    <td colspan="<?php echo $colspan; ?>">
        <script type="text/javascript">

            jQuery(document).ready(function () {
                setTimeout(function () {
                    <?php if(isset($wp_pkpo_settings["wp_pickup_radius_search"])&& $check_map && $wp_pkpo_settings["wp_pickup_radius_search"] === "enable"){?>
                    searchLocationsNear() ;
                    <?php }
                    else {
                    ?>
                    if (jQuery('select[name="pickup_point_name"]').length > 0){
                        <?php if (!isset($wp_pkpo_settings["wp_pickup_location_select2"])) { ?>
                            jQuery("#wp_pkpo_form_select").select2();
                        <?php } 
                        if(isset($wp_pkpo_settings["wp_pickup_location_select2"]) && $wp_pkpo_settings["wp_pickup_location_select2"] === "enable"){?>
                            jQuery("#wp_pkpo_form_select").select2();
                         <?php } ?>
                    }
                    <?php } ?>
                    jQuery('.pickup_point_date_time').datepicker({
                        dateFormat: wp_pkpo_date_formate,
                        changeMonth: true,
                        changeYear: true,
                        minDate: 0
                    });

                    if (wp_pkpo_timepicker_formate === '24')
                        jQuery('input[name="pickup_point_time"]').timepicker({
                            'timeFormat': 'H:i:s',
                            'minTime': '8:00am',
                            'maxTime': '6:00pm'
                        });
                    else
                        jQuery('input[name="pickup_point_time"]').timepicker({
                            'timeFormat': 'h:i A',
                            'minTime': '8:00am',
                            'maxTime': '6:00pm'
                        });

                }, 2000);

                if (wp_pickup_hide_time === 'disable'){
                    jQuery('input[name="pickup_point_time"]').remove();
                }
                if (wp_pickup_hide_date === 'disable'){
                    jQuery('input[name="pickup_point_date_time"]').remove();
                }

            });

        </script>
        <h3><?php echo __("Select Pickup Point", 'woocommerce-pickup-location'); ?></h3>
        <div class="row">
            
        </div>
        <?php
        $to_text = __(" To ", 'woocommerce-pickup-location');
        if(isset($wp_pkpo_settings['wp_pkpo_pickup_selection_type']) && $wp_pkpo_settings['wp_pkpo_hide_checkout_time'] === 'disable')
            $to_text = "";
        
        if (isset($wp_pkpo_pickup_points) && $wp_pkpo_pickup_points != '') {
            $wp_pkpo_pickup_selection_type = $wp_pkpo_settings['wp_pkpo_pickup_selection_type'];
            if ($wp_pkpo_pickup_selection_type === 'by_radio') {
                $wp_pkpo_pickup_loop = 0;
                echo "<ul id='pickup_points'>";
                foreach ($wp_pkpo_pickup_points as $wp_pkpo_pickup) {
                    $wp_pkpo_pickup_selected = '';
                    $wp_pkpo_opening_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_opening_hours', true);
                    $wp_pkpo_closing_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_closing_hours', true);
                    $wp_pkpo_min_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_min_time', true);
                    $wp_pkpo_max_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_max_time', true);
                    $term_list = wp_get_post_terms($wp_pkpo_pickup->ID, 'pickup_days', array("fields" => "all"));
                    $pickup_week_days = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
                    $pickup_terms_week_days = array();
                    $pickup_location_days = '';

                    if (isset($term_list[0])) {
                        foreach ($term_list as $terms) {
                            $pickup_terms_week_days[] = $terms->slug;
                        }
                    }

                    if (isset($pickup_week_days[0])) {
                        foreach ($pickup_week_days as $pickup_week_day) {
                            $pickup_week_day = strtolower($pickup_week_day);

                            if (!in_array(trim($pickup_week_day), $pickup_terms_week_days)) {
                                if ($pickup_week_day === 'sunday')
                                    $pickup_location_days .= '0,';
                                if ($pickup_week_day === 'monday')
                                    $pickup_location_days .= '1,';
                                if ($pickup_week_day === 'tuesday')
                                    $pickup_location_days .= '2,';
                                if ($pickup_week_day === 'wednesday')
                                    $pickup_location_days .= '3,';
                                if ($pickup_week_day === 'thursday')
                                    $pickup_location_days .= '4,';
                                if ($pickup_week_day === 'friday')
                                    $pickup_location_days .= '5,';
                                if ($pickup_week_day === 'saturday')
                                    $pickup_location_days .= '6,';
                            }
                        }
                    }

                    $left_trim_pickupdays = ltrim($pickup_location_days, ",");
                    $right_trim_pickupdays = rtrim($left_trim_pickupdays, ",");

                    if ($wp_pkpo_pickup_loop === 0 && isset($wp_pkpo_settings['wp_pickup_auto_select_pickup']) && $wp_pkpo_settings['wp_pickup_auto_select_pickup'] === "enable")
                        $wp_pkpo_pickup_selected = 'checked=checked';
                    
                    $time_str = "";
                    $wp_pkpo_closing_time = trim($wp_pkpo_closing_time);
                    if(!empty($wp_pkpo_closing_time) && isset($wp_pkpo_settings['wp_pkpo_pickup_selection_type']) && $wp_pkpo_settings['wp_pkpo_hide_checkout_time'] === 'enable')
                        $time_str =  '(' . $wp_pkpo_opening_time . $to_text . $wp_pkpo_closing_time . ')'; 
                    
                    echo '<li><input wp_pkpo_min_time="'.$wp_pkpo_min_time.'" wp_pkpo_max_time="'.$wp_pkpo_max_time.'" wp_pkpo_working_days="' . $right_trim_pickupdays . '" ' . $wp_pkpo_pickup_selected . ' type="radio" pickup_id="' . $wp_pkpo_pickup->ID . '" value="' . $wp_pkpo_pickup->post_title . '"  name="pickup_point_name" id="pickup_point_name' . $wp_pkpo_pickup_loop . '" class="select2-chosen pickup_point_name_radio" loop_index="' . $wp_pkpo_pickup_loop . '" wp_pkpo_opening_time="' . $wp_pkpo_opening_time . '" wp_pkpo_closing_time="' . $wp_pkpo_closing_time . '" wp_pkpo_title="' . $wp_pkpo_pickup->post_title . '"/> '
                            . '<label for="pickup_point_name' . $wp_pkpo_pickup_loop . '">' . $wp_pkpo_pickup->post_title  . " ". $time_str. '</label></li>';

                    $wp_pkpo_pickup_loop++;
                }
                echo "</ul><br/>";
            }
            else if ($wp_pkpo_pickup_selection_type === 'by_dropdown') {

                echo '<select required="required" name="pickup_point_name" id="wp_pkpo_form_select" class="wp_pkpo_form_select pickup_point_name_select">';
                if(isset($wp_pkpo_settings['wp_pickup_auto_select_pickup']) && $wp_pkpo_settings['wp_pickup_auto_select_pickup'] === "disable")
                    echo '<option value="-1" selected="selected" id="-1">'. __("Select Pickup location", 'woocommerce-pickup-location').'</option>';
                $wp_pkpo_pickup_loop = 0;
                foreach ($wp_pkpo_pickup_points as $wp_pkpo_pickup) {
                    $wp_pkpo_pickup_selected = '';
                    $wp_pkpo_opening_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_opening_hours', true);
                    $wp_pkpo_closing_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_closing_hours', true);
                    $wp_pkpo_min_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_min_time', true);
                    $wp_pkpo_max_time = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_max_time', true);                    
                    $term_list = wp_get_post_terms($wp_pkpo_pickup->ID, 'pickup_days', array("fields" => "all"));
                    $pickup_week_days = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
                    $pickup_terms_week_days = array();
                    $pickup_location_days = '';

                    if (isset($term_list[0])) {
                        foreach ($term_list as $terms) {
                            $pickup_terms_week_days[] = $terms->slug;
                        }
                    }

                    if (isset($pickup_week_days[0])) {
                        foreach ($pickup_week_days as $pickup_week_day) {
                            $pickup_week_day = strtolower($pickup_week_day);

                            if (!in_array(trim($pickup_week_day), $pickup_terms_week_days)) {
                                if ($pickup_week_day === 'sunday')
                                    $pickup_location_days .= '0,';
                                if ($pickup_week_day === 'monday')
                                    $pickup_location_days .= '1,';
                                if ($pickup_week_day === 'tuesday')
                                    $pickup_location_days .= '2,';
                                if ($pickup_week_day === 'wednesday')
                                    $pickup_location_days .= '3,';
                                if ($pickup_week_day === 'thursday')
                                    $pickup_location_days .= '4,';
                                if ($pickup_week_day === 'friday')
                                    $pickup_location_days .= '5,';
                                if ($pickup_week_day === 'saturday')
                                    $pickup_location_days .= '6,';
                            }
                        }
                    }

                    $left_trim_pickupdays = ltrim($pickup_location_days, ",");
                    $right_trim_pickupdays = rtrim($left_trim_pickupdays, ",");                        
                    $time_str = "";
                    $wp_pkpo_closing_time = trim($wp_pkpo_closing_time);
                    if(!empty($wp_pkpo_closing_time) && isset($wp_pkpo_settings['wp_pkpo_pickup_selection_type']) && $wp_pkpo_settings['wp_pkpo_hide_checkout_time'] === 'enable')
                        $time_str =  '(' . $wp_pkpo_opening_time . $to_text . $wp_pkpo_closing_time . ')'; 
                    
                    echo '<option wp_pkpo_min_time="'.$wp_pkpo_min_time.'" wp_pkpo_max_time="'.$wp_pkpo_max_time.'" wp_pkpo_working_days="' . $right_trim_pickupdays . '" wp_pkpo_opening_time="' . $wp_pkpo_opening_time . '" wp_pkpo_closing_time="' . $wp_pkpo_closing_time . '" loop_index="' . $wp_pkpo_pickup_loop . '" value="' . $wp_pkpo_pickup->post_title . '" id="' . $wp_pkpo_pickup->ID . '">' . $wp_pkpo_pickup->post_title . " ". $time_str.' </option>';

                    $wp_pkpo_pickup_loop++;
                }
                echo '</select><br/>';
                ?>
                <?php
            }

            $pickup_date_text = __("pickup date", 'woocommerce-pickup-location');
            if (isset($wp_pkpo_settings['wp_pickup_default_date_text']) && !empty($wp_pkpo_settings['wp_pickup_default_date_text']))
                $pickup_date_text = $wp_pkpo_settings['wp_pickup_default_date_text'];
            $wp_pickup_id = 0; 
            if (isset($wp_pkpo_pickup_points[0]->ID))
                $wp_pickup_id =$wp_pkpo_pickup_points[0]->ID;
            //echo '<input required="required" type="text" placeholder="' . $pickup_date_text . '" name="pickup_point_date_time" class="pickup_point_date_time"/>';
            
            $json = file_get_contents('https://cementos.vexecommerce.com/ubigeo/regions.json');
            $obj = json_decode($json);

            $pickup_time_text = __("pickup time", 'woocommerce-pickup-location');

            
            if (isset($wp_pkpo_settings['wp_pickup_default_time_text']) && !empty($wp_pkpo_settings['wp_pickup_default_time_text']))
                $pickup_time_text = $wp_pkpo_settings['wp_pickup_default_time_text'];
            //echo $obj->access_token;
            
            /*echo "<p class='form-row form-row-wide' data-priority='40'>
                    <select class='depa' required='required' style='padding-right: 30px;margin-left:15px'>";
                        foreach ($obj as $ob) {
                            echo "<option value='$ob->id'>$ob->name</option>";
                        }
            echo    "
                    </select>
                </p>";*/
            $pickup_date_text = __("Fecha y hora de recojo", 'woocommerce-pickup-location');

            if (isset($wp_pkpo_settings['wp_pickup_default_date_text']) && !empty($wp_pkpo_settings['wp_pickup_default_date_text']))
                $pickup_date_text = $wp_pkpo_settings['wp_pickup_default_date_text'];
            $wp_pickup_id = 0; 
            if (isset($wp_pkpo_pickup_points[0]->ID))
                $wp_pickup_id =$wp_pkpo_pickup_points[0]->ID;
            echo '<p  class="form-row-first"><input required="required" style="" type="text" placeholder="' . $pickup_date_text . '" name="pickup_point_date_time" class="pickup_point_date_time"/>
            </p>';

            echo '<p  class="form-row-last">
                <input required="required" class="form-row-last" type="text" placeholder="' . $pickup_time_text . '" name="pickup_point_time" class="pickup_point_time"/>
                </p>';
            /*echo "<p class='form-row form-row-wide address-field update_totals_on_change validate-required woocommerce-validated' id='billing_country_field' data-priority='40'>
                    <select required='required' name='' class='select fl-select' tabindex='-1' aria-hidden='true' style='    padding-right: 30px;'>
                        <option value='Barranca'>Barranca</option>
                    </select>
                </p>";
            echo "<p class='form-row form-row-wide address-field update_totals_on_change validate-required woocommerce-validated' id='billing_country_field' data-priority='40'>
                    <select required='required' name='' class='select fl-select' tabindex='-1' aria-hidden='true' style='    padding-right: 30px;'>
                        <option value='Paramonga'>Paramonga</option>
                    </select>
                </p>";*/
            
            echo '<input type="hidden" name="wp_pickup_id" id="wp_pickup_id" class="wp_pickup_id" value="'.$wp_pickup_id.'" />';
        }
        ?>
    </td>
    </tr>
    <tr class="wp_pkpo_checkout_pickup_point"><th><?php _e('Recojo en tienda', 'woocommerce-pickup-location'); ?></th>
        <?php if (isset($wp_pkpo_pickup_points[0]->post_title)) { ?>
            <th class="pick_up_name"><?php _e($wp_pkpo_pickup_points[0]->post_title, 'woocommerce-pickup-location'); ?></th>
        <?php } else { ?>
            <th class="pick_up_name"><?php _e('No Pickup Point Selected', 'woocommerce-pickup-location'); ?></th>
    <?php } ?></tr>
<?php if($check_map)
     require_once $checkout_js_template; 
?>
<?php } ?>

<?php do_action('woocommerce_review_order_before_order_total'); ?>

<tr class="order-total">
    <th><?php _e('Total', 'woocommerce'); ?></th>
    <td><?php wc_cart_totals_order_total_html(); ?></td>
</tr>

<?php do_action('woocommerce_review_order_after_order_total'); ?>
</tfoot>
</table>
