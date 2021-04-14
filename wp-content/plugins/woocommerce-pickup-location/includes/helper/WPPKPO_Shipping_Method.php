<?php

/* * ***********custom shipping method************* */

function pickup_location_method_init() {
    
    /* code for checking user roles */
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
                return;
        }
    }

    if (!class_exists('WC_Pickup_Location_Method')) {

        class WC_Pickup_Location_Method extends WC_Shipping_Method {

            /**

             * Constructor for your shipping class

             *

             * @access public

             * @return void

             */
            public function __construct() {

                $this->id = 'pickup-location-method'; // Id for your shipping method. Should be uunique.

                $this->method_title = __('Pickup Point Shipping'); // Title shown in admin

                $this->method_description = __('Description of Pickup Point Shipping'); // Description shown in admin

                $this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled

                $this->title = "CALL FOR QUOTE"; // This can be added as an setting but for this example its forced.
                //$this->init_form_fields();

                $this->init();
            }

            /**

             * Init your settings

             *

             * @access public

             * @return void

             */
            function init() {
                // Load the settings API
                $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
                $this->cost = $this->get_option('cost');
                $this->title = $this->get_option('title');
                $this->min_order_amount = $this->get_option('min_order_amount');
                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }

            /**
             * Initialise Gateway Settings Form Fields
             */
            function init_form_fields() {
                $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable/Disable', 'woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Enable Pickup Location', 'woocommerce'),
                        'default' => 'false'
                    ),
                    'title' => array(
                        'title' => __('Title', 'woocommerce'),
                        'type' => 'text',
                        'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                        'default' => __('Pickup Location', 'woocommerce')
                    ),
                    'description' => array(
                        'title' => __('Description', 'woocommerce'),
                        'type' => 'textarea',
                        'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
                        'default' => __("Pickup Location Description", 'woocommerce')
                    ),
                    'cost' => array(
                        'title' => __('Default Cost', 'woocommerce'),
                        'type' => 'number',
                        'custom_attributes' => array(
                            'step' => 'any',
                            'min' => '0'
                        ),
                        'description' => __('Cost excluding tax. Enter an amount, e.g. 2.50.', 'woocommerce'),
                        'default' => '',
                        'desc_tip' => true,
                        'placeholder' => '0.00'
                    ),
                    'min_order_amount' => array(
                        'title' => __('Min. order amount', 'woocommerce'),
                        'type' => 'number',
                        'custom_attributes' => array(
                            'step' => 'any',
                            'min' => '0'
                        ),
                        'description' => __('Min order amount after which Free Shipping will be applied (leave empty if not required)', 'woocommerce'),
                        'default' => '',
                        'desc_tip' => true,
                        'placeholder' => '0.00'
                    )
                );
            }

            /**
             * calculate_shipping function.
             *
             * @access public
             * @param mixed $package
             * @return void
             */
            public function calculate_shipping($package = array()) {
                $order_amount = $package["contents_cost"];
                if (isset($this->min_order_amount)  && $order_amount > $this->min_order_amount && ($this->min_order_amount > 0)) {
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => 0,
                        'calc_tax' => 'per_order'
                    );
                } else {
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $this->cost,
                        'calc_tax' => 'per_order'
                    );
                }

                // Register the rate
                $this->add_rate($rate);
            }

        }

    }
}

add_action('woocommerce_shipping_init', 'pickup_location_method_init');

function add_pickup_location_method($methods) {   
    $methods['pickup_location_method'] = 'WC_Pickup_Location_Method';
    return $methods;
    
}

add_filter('woocommerce_shipping_methods', 'add_pickup_location_method');
