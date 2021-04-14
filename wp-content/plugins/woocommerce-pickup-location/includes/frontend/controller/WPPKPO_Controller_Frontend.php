<?php
@session_start();

class WPPKPO_Controller_Frontend {

    private $wppkpo_view;

    public function __construct() {
        $wp_pkpo_settings_option = get_option('wp_pkpo_options');
        $this->wppkpo_view = new WPPKPO_View_Frontend();
        add_action('woocommerce_locate_template', array($this, 'wp_pkpo_templates'), 20, 5);
        add_action('wp_enqueue_scripts', array($this, 'wp_pkpo_enqueue_scripts'));
        if($wp_pkpo_settings_option['wp_pkpo_send_info_in_email']==='enable')
            add_action('woocommerce_email_after_order_table', array($this, 'wp_pkpo_order_email_pickup_location'), 10, 2);

        add_action('woocommerce_checkout_update_order_meta', array($this, 'wp_pkpo_checkout_field_update_order_meta'));
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'wp_pkpo_checkout_field_display_admin_order_meta'), 10, 1);
        add_action('woocommerce_order_details_after_order_table', array($this, 'wp_pkpo_order_table_details'), 10, 1);
        add_filter('woocommerce_email_recipient_new_order', array($this, 'add_pikcup_owner_email'), 10, 2);
        add_shortcode('wp-pickup-location', array($this, 'wp_pickup_location_map'));
        add_action('wp_head', array($this, 'wp_pkpo_js_variables'));
        add_filter('woocommerce_before_checkout_shipping_form', array($this, 'wp_pickup_shipping_method'));
        add_filter( 'woocommerce_package_rates', array($this, 'wp_pkpo_hide_shipping_based_on_order_price'), 10, 2 ); 


        if (isset($wp_pkpo_settings_option['wp_pkpo_billing_hide_details']) && $wp_pkpo_settings_option['wp_pkpo_billing_hide_details'] === 'disable')
            add_action('woocommerce_checkout_fields', array($this, 'wp_pickup_hide_billing_address'));

        if (isset($wp_pkpo_settings_option['wp_pkpo_shipping_hide_details']) && $wp_pkpo_settings_option['wp_pkpo_shipping_hide_details'] === 'disable')
            add_action('woocommerce_checkout_fields', array($this, 'wp_pickup_hide_shipping_address'));
    }

    public function wp_pkpo_order_email_pickup_location($order, $is_admin_email) {

        $pickup_date = get_post_meta($order->id, 'pickup_date', true);
        $pickup_time = get_post_meta($order->id, 'pickup_time', true);
        $pickup_name = get_post_meta($order->id, 'pickup_name', true);
        $pickup_address = get_post_meta($order->id, 'pickup_address', true);
        $pickup_city = get_post_meta($order->id, 'pickup_city', true);
        $pickup_state = get_post_meta($order->id, 'pickup_state', true);
        $pickup_country = get_post_meta($order->id, 'pickup_country', true);
        $pickup_zipcode = get_post_meta($order->id, 'pickup_zipcode', true);
        $pickup_email = get_post_meta($order->id, 'pickup_email', true);
        $email_order_str = '';
        $email_order_str ='<p><h2 style="color:#e21923;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:16px 0 8px;text-align:left">'.__('Pickup Information', 'woocommerce-pickup-location').'</h2>';
        
        $email_order_str .='<ul>';

            if (isset($pickup_name) && (trim($pickup_name) != '')) {
                $email_order_str .= '<li><b>'.__('Pickup Location', 'woocommerce-pickup-location').'</b> : '.$pickup_name.'</li>';
            }if (isset($pickup_address) && (trim($pickup_address) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup address', 'woocommerce-pickup-location').'</b> : '.$pickup_address.'</li>';
            }if (isset($pickup_city) && (trim($pickup_city) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup city', 'woocommerce-pickup-location').'</b> : '.$pickup_city.'</li>';
            }if (isset($pickup_state) && (trim($pickup_state) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup state', 'woocommerce-pickup-location').'</b> : '.$pickup_state.'</li>';
            }if (isset($pickup_country) && (trim($pickup_country) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup country', 'woocommerce-pickup-location').'</b> : '.$pickup_country.'</li>';
            }if (isset($pickup_zipcode) && (trim($pickup_zipcode) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup zip code', 'woocommerce-pickup-location').'</b> : '.$pickup_zipcode.'</li>';
            }if (isset($pickup_email) && (trim($pickup_email) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup Email', 'woocommerce-pickup-location').'</b> : '.$pickup_email.'</li>';
            } if (isset($pickup_time) && (trim($pickup_time) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup Time', 'woocommerce-pickup-location').'</b> : '.$pickup_time.'</li>';
            } if (isset($pickup_date) && (trim($pickup_date) != '')) { 
                $email_order_str .= '<li><b>'.__('Pickup Date', 'woocommerce-pickup-location').'</b> :'.$pickup_date.'</li>';
            } 
         $email_order_str .='</ul></p>';
         
         echo $email_order_str;
    }

    public function wp_pickup_hide_billing_address($address_fields) {
        $shipping_method = WC()->session->get('chosen_shipping_methods');
        if (trim($shipping_method[0]) === 'pickup-location-method') {
            unset($address_fields['billing']['billing_first_name']);
            unset($address_fields['billing']['billing_last_name']);
            unset($address_fields['billing']['billing_company']);
            unset($address_fields['billing']['billing_address_1']);
            unset($address_fields['billing']['billing_address_2']);
            unset($address_fields['billing']['billing_city']);
            unset($address_fields['billing']['billing_postcode']);
            unset($address_fields['billing']['billing_country']);
            unset($address_fields['billing']['billing_state']);
            unset($address_fields['billing']['billing_phone']);
            unset($address_fields['order']['order_comments']);
            unset($address_fields['billing']['billing_address_2']);
            unset($address_fields['billing']['billing_postcode']);
            unset($address_fields['billing']['billing_company']);
            unset($address_fields['billing']['billing_last_name']);
            unset($address_fields['billing']['billing_email']);
            unset($address_fields['billing']['billing_city']);
            return $address_fields;
        }
    }

    public function wp_pickup_hide_shipping_address($address_fields) {
        $shipping_method = WC()->session->get('chosen_shipping_methods');
        if (trim($shipping_method[0]) === 'pickup-location-method') {
            unset($address_fields['shipping']['shipping_first_name']);
            unset($address_fields['shipping']['shipping_last_name']);
            unset($address_fields['shipping']['shipping_company']);
            unset($address_fields['shipping']['shipping_address_1']);
            unset($address_fields['shipping']['shipping_address_2']);
            unset($address_fields['shipping']['shipping_city']);
            unset($address_fields['shipping']['shipping_postcode']);
            unset($address_fields['shipping']['shipping_country']);
            unset($address_fields['shipping']['shipping_state']);
            unset($address_fields['shipping']['shipping_phone']);
            unset($address_fields['order']['order_comments']);
            unset($address_fields['shipping']['shipping_address_2']);
            unset($address_fields['shipping']['shipping_postcode']);
            unset($address_fields['shipping']['shipping_company']);
            unset($address_fields['shipping']['shipping_last_name']);
            unset($address_fields['shipping']['shipping_email']);
            unset($address_fields['shipping']['shipping_city']);
            return $address_fields;
        }
    }

    public function wp_pickup_shipping_method() {
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        if(isset($wp_pkpo_settings["wp_pickup_default_shipping_pickup"]) && $wp_pkpo_settings["wp_pickup_default_shipping_pickup"] === "yes")
            WC()->session->set('chosen_shipping_methods', array('pickup-location-method'));
    }

    public function wp_pkpo_templates($template, $template_name, $template_path) {
        global $product;
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        
        if (isset($wp_pkpo_settings["wp_pickup_user_roles"]) && is_array($wp_pkpo_settings["wp_pickup_user_roles"]) && count($wp_pkpo_settings["wp_pickup_user_roles"]) > 0) {
            if (is_user_logged_in()) {
                $roles_matched = false;
                $user = wp_get_current_user();
                foreach ($wp_pkpo_settings["wp_pickup_user_roles"] as $role) {
                    if (in_array($role, (array) $user->roles)) {
                        $roles_matched = true;
                    }
                }
                if(!$roles_matched)
                    return $template;
            }
        }

        if (strstr($template, 'review-order.php')) {

            $template = locate_template(
                    array(
                        WPPKPO_PLUGIN_DIR . '/templates/checkout/review-order.php',
                        $template_name
                    )
            );

            if (!$template) {
                $template = WPPKPO_PLUGIN_DIR . '/templates/frontend/checkout-map-section.php';
            }
        }

        return $template;
    }

    public function wp_pickup_location_map() {
        $wp_pkpo_args = array(
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'pickup_location',
            'post_status' => 'publish'
        );
        $wp_pkpo_pickup_points = get_posts($wp_pkpo_args, ARRAY_A);
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        return $this->wppkpo_view->wp_pickup_location_map($wp_pkpo_pickup_points, $wp_pkpo_settings);
    }

    public function add_pikcup_owner_email($recipient, $object) {
        $pickup_title = get_post_meta($object->id, 'pickup_name', true);
        if (isset($pickup_title) && $pickup_title != '') {
            $pickup_id = get_page_by_title($pickup_title, ARRAY_A, 'pickup_location');
            $customer_email = get_post_meta($pickup_id['ID'], 'wp_pkpo_contact_email', true);
            $recipient = $recipient . ', ' . $customer_email;
            return $recipient;
        }
        return $recipient;
    }

    function wp_pkpo_order_table_details($order) {
        $this->wppkpo_view->wp_pkpo_order_table_details($order);
    }

    function wp_pkpo_checkout_field_display_admin_order_meta($order) {
        echo '<p><strong>' . __('Pickup Information', 'woocommerce-pickup-location') . ':</strong> ' . get_post_meta($order->id, 'Pickup Information', true) . '</p>';
    }

    function wp_pkpo_checkout_field_update_order_meta($order_id) {

        if (!empty($_POST['pickup_point_date_time']))
            update_post_meta($order_id, 'pickup_date', sanitize_text_field($_POST['pickup_point_date_time']));
        if (!empty($_POST['pickup_point_time']))
            update_post_meta($order_id, 'pickup_time', sanitize_text_field($_POST['pickup_point_time']));
        if (!empty($_POST['pickup_point_name']))
            update_post_meta($order_id, 'pickup_name', sanitize_text_field($_POST['pickup_point_name']));
        if (!empty($_POST['wp_pickup_id'])){
            $wp_pkpo_settings = get_option('wp_pkpo_options');
            $wp_pickup =  get_post($_POST['wp_pickup_id']);
            
            if($wp_pkpo_settings["wp_pickup_address_display"] === "enable")
                update_post_meta($order_id, 'pickup_address', $wp_pickup->post_content);
            
            if($wp_pkpo_settings["wp_pickup_email_display"] === "enable")
                update_post_meta($order_id, 'pickup_email', get_post_meta($_POST['wp_pickup_id'],"wp_pkpo_contact_email", true ));
            
            if($wp_pkpo_settings["wp_pickup_city_display"] === "enable")
                update_post_meta($order_id, 'pickup_city', get_post_meta($_POST['wp_pickup_id'],"wp_pickup_city_display", true ));
            
            if($wp_pkpo_settings["wp_pickup_state_display"] === "enable")
                update_post_meta($order_id, 'pickup_state', get_post_meta($_POST['wp_pickup_id'],"wp_pickup_state_display", true ));
            
            if($wp_pkpo_settings["wp_pickup_country_display"] === "enable")
                update_post_meta($order_id, 'pickup_country', get_post_meta($_POST['wp_pickup_id'],"wp_pickup_country_display", true ));
            
            if($wp_pkpo_settings["wp_pickup_zip_display"] === "enable")
                update_post_meta($order_id, 'pickup_zipcode', get_post_meta($_POST['wp_pickup_id'],"wp_pickup_zip_display", true ));
        }
    }

    public function wp_pkpo_enqueue_scripts() {

        wp_enqueue_style('jquery-ui-style', WPPKPO_PLUGIN_URL . '/assets/css/jquery-ui.css');
        wp_enqueue_style('wp_pkpo_pickup_css', WPPKPO_PLUGIN_URL . '/assets/css/woocommerce_pickup_frontend.css');
        if (!isset($wp_pkpo_settings["wp_pickup_location_select2"])) {
            wp_enqueue_style('select2', WPPKPO_PLUGIN_URL . '/assets/css/select2.css');
        } else if (isset($wp_pkpo_settings["wp_pickup_location_select2"]) && $wp_pkpo_settings["wp_pickup_location_select2"] === "enable") {
            wp_enqueue_style('select2', WPPKPO_PLUGIN_URL . '/assets/css/select2.css');
        }
        wp_enqueue_style('wp_pkpo_timepicker_css', WPPKPO_PLUGIN_URL . '/assets/css/wickedpicker.min.css');

        wp_enqueue_script('jquery', WPPKPO_PLUGIN_URL . '/assets/js/jquery.min.js');
        wp_enqueue_script('jquery-ui', WPPKPO_PLUGIN_URL . '/assets/js/jquery-ui.js', array("jquery"));
        
        $wp_pkpo_settings = get_option('wp_pkpo_options');
         if (!empty($wp_pkpo_settings["wp_pickup_default_datepicker_lang"]))
            wp_enqueue_script('jquery-ui-i18n', WPPKPO_PLUGIN_URL . '/assets/js/jquery-ui-i18n.min.js');
        if ($wp_pkpo_settings["wp_pickup_default_datepicker_lang"] == "ar")
            wp_enqueue_script('datepicker-ar', WPPKPO_PLUGIN_URL . '/assets/js/datepicker-ar.js');
        if ($wp_pkpo_settings["wp_pickup_default_datepicker_lang"] == "fr")
            wp_enqueue_script('datepicker-fr', WPPKPO_PLUGIN_URL . '/assets/js/datepicker-fr.js');
        if ($wp_pkpo_settings["wp_pickup_default_datepicker_lang"] == "he")
            wp_enqueue_script('datepicker-he', WPPKPO_PLUGIN_URL . '/assets/js/datepicker-he.js');
        if ($wp_pkpo_settings["wp_pickup_default_datepicker_lang"] == "zh-TW")
            wp_enqueue_script('datepicker-zh-TW', WPPKPO_PLUGIN_URL . '/assets/js/datepicker-zh-TW.js');

        wp_enqueue_script('wp_pkpo_widgetpicker', WPPKPO_PLUGIN_URL . '/assets/js/widgetpicker.js', array("jquery"));
        if (!isset($wp_pkpo_settings["wp_pickup_location_select2"])) {
            wp_enqueue_style('select2', WPPKPO_PLUGIN_URL . '/assets/css/select2.css');
        } else if (isset($wp_pkpo_settings["wp_pickup_location_select2"]) && $wp_pkpo_settings["wp_pickup_location_select2"] === "enable") {
            wp_enqueue_script('select2_js', WPPKPO_PLUGIN_URL . '/assets/js/select2.js', array("jquery"));
        }
        wp_enqueue_script('wp_pkpo_pickup_js', WPPKPO_PLUGIN_URL . '/assets/js/woocommerce_pickup_frontend.js', array("jquery"));
        if (isset($wp_pkpo_settings["wp_pickup_map_api_key"]) && !empty($wp_pkpo_settings["wp_pickup_map_api_key"]))
            wp_enqueue_script('wp_pkpo_pickup_map_js', 'https://maps.googleapis.com/maps/api/js?key='.$wp_pkpo_settings["wp_pickup_map_api_key"].'&libraries=places', array("jquery"));
        else
            wp_enqueue_script('wp_pkpo_pickup_map_js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDxbNaqDprpp9EDiMsb-6zUELbpQjGjrm4&libraries=places', array("jquery"));
    }
    
    public function wp_pkpo_hide_shipping_based_on_order_price($rates, $package) {
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        
        if (isset($wp_pkpo_settings["wp_pickup_hide_pickup_shipping_based_on_order"]) && !empty($wp_pkpo_settings["wp_pickup_hide_pickup_shipping_based_on_order"])) {
            if ($package["contents_cost"] < $wp_pkpo_settings["wp_pickup_hide_pickup_shipping_based_on_order"] && isset($rates['pickup-location-method'])) {
                unset($rates['pickup-location-method']);
            }
        }

        return $rates;
    }

    public function wp_pkpo_js_variables() {
        $wp_pkpo_settings = get_option('wp_pkpo_options');
        if (isset($wp_pkpo_settings['wp_pkpo_pickup_date_formate'])) {
            ?>
            <script type="text/javascript">
                var wp_pkpo_date_formate = '<?php echo $wp_pkpo_settings['wp_pkpo_pickup_date_formate']; ?>';
                var wp_pkpo_timepikcer_lang = '';
                var wp_pkpo_datepicker_lang = '';
                var wp_pkpo_timepicker_formate = '';
                var wp_pkpo_map_status = '';
                var admin_url = '<?php echo admin_url('admin-ajax.php'); ?>';
                var wp_pickup_wrong_date = ''
                var wp_pickup_wrong_time = '';
                var wp_pickup_hide_time = '';
                var wp_pickup_hide_date = '';
                var wp_pickup_pickup_msg = '<?php echo __("Please select pickup location", 'woocommerce-pickup-location'); ?>';
                var wp_pickup_date_msg = '<?php echo __("Please enter pickup date", 'woocommerce-pickup-location'); ?>';
                var wp_pickup_time_msg = '<?php echo __("Please enter pickup time", 'woocommerce-pickup-location'); ?>';
            <?php
            if (isset($wp_pkpo_settings['wp_pickup_default_timepicker_lang']) && $wp_pkpo_settings['wp_pickup_default_timepicker_lang'] !== '') {
                ?>
                        wp_pkpo_timepikcer_lang = '<?php echo $wp_pkpo_settings['wp_pickup_default_timepicker_lang']; ?>';

            <?php } if (isset($wp_pkpo_settings['wp_pickup_default_datepicker_lang']) && $wp_pkpo_settings['wp_pickup_default_datepicker_lang'] !== '') { ?>

                    wp_pkpo_datepicker_lang = '<?php echo $wp_pkpo_settings['wp_pickup_default_datepicker_lang']; ?>';

            <?php } ?>

        <?php } if (isset($wp_pkpo_settings['wp_pickup_default_time_formate']) && $wp_pkpo_settings['wp_pickup_default_time_formate'] !== '') { ?>

                wp_pkpo_timepicker_formate = '<?php echo $wp_pkpo_settings['wp_pickup_default_time_formate']; ?>';

        <?php } if (isset($wp_pkpo_settings['wp_pkpo_map_disable']) && $wp_pkpo_settings['wp_pkpo_map_disable'] !== '') { ?>

                wp_pkpo_map_status = '<?php echo $wp_pkpo_settings['wp_pkpo_map_disable']; ?>';

        <?php
        } if (isset($wp_pkpo_settings['wp_pkpo_hide_checkout_time']) && $wp_pkpo_settings['wp_pkpo_hide_checkout_time'] !== '') { ?>

                wp_pickup_hide_time = '<?php echo $wp_pkpo_settings['wp_pkpo_hide_checkout_time']; ?>';

        <?php
        }
        if (isset($wp_pkpo_settings['wp_pkpo_hide_checkout_date']) && $wp_pkpo_settings['wp_pkpo_hide_checkout_date'] !== '') { ?>

                wp_pickup_hide_date = '<?php echo $wp_pkpo_settings['wp_pkpo_hide_checkout_date']; ?>';

        <?php
        }
        if (isset($wp_pkpo_settings['wp_pickup_wrong_time']) && $wp_pkpo_settings['wp_pickup_wrong_time'] !== '') {
            ?>

                wp_pickup_wrong_time = '<?php echo $wp_pkpo_settings['wp_pickup_wrong_time']; ?>';

        <?php } if (isset($wp_pkpo_settings['wp_pickup_wrong_date']) && $wp_pkpo_settings['wp_pickup_wrong_date'] !== '') { ?>

                wp_pickup_wrong_date = '<?php echo $wp_pkpo_settings['wp_pickup_wrong_date']; ?>';

        <?php } ?>

            jQuery(document).ready(function () {

        <?php if (isset($wp_pkpo_settings['wp_pkpo_billing_hide_details']) && $wp_pkpo_settings['wp_pkpo_billing_hide_details'] === 'disable') { ?>

                    jQuery('.woocommerce-billing-fields').find('h3').css('display', 'none');

        <?php } if (isset($wp_pkpo_settings['wp_pkpo_shipping_hide_details']) && $wp_pkpo_settings['wp_pkpo_shipping_hide_details'] === 'disable') { ?>

                    jQuery('.woocommerce-shipping-fields').find('h3').css('display', 'none');

        <?php } ?>
            });

        </script>
        <?php
    }

}
