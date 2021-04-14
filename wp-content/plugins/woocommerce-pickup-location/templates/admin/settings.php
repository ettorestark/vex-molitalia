<div class="wrap">
    <?php if (isset($wp_pkpo_msg) && ($wp_pkpo_msg != '')) { ?>
        <div class="updated notice">
            <p><?php echo $wp_pkpo_msg; ?></p>
        </div>
    <?php } ?>

    <h1><?php _e('Pickup Point Settings', 'woocommerce-pickup-location'); ?></h1>

    <form class="wp_pkpo_setting_form" method="post" action="" novalidate="novalidate" enctype="multipart/form-data">
        <table class="widefat">
            <tbody>
                <tr>
                    <th scope="row"><label for="wp_pkpo_marker_image"><?php _e('Marker Image', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <?php
                        if (isset($settings['wp_pkpo_marker_image']) && $settings['wp_pkpo_marker_image'] !== '') {
                            echo '<img class="wp_pkpo_marker_image" src="' . $settings['wp_pkpo_marker_image'] . '" />';
                        }
                        ?>
                        <input name="wp_pkpo_marker_image" id="wp_pkpo_marker_image" class="regular-text wp_pkpo_file_input" type="file">
                        <input name="wp_pkpo_old_image_url" id="wp_pkpo_old_image_url" class="regular-text" type="hidden" 
                        value="<?php if (isset($settings['wp_pkpo_marker_image'])) echo $settings['wp_pkpo_marker_image'];?>">
                        <p class="description"><?php _e('Upload marker image to be displayed on map.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="wp_pkpo_pickup_selection_type"><?php _e('Pickup Selection Type', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_pickup_selection_type" value="by_radio" <?php if (isset($settings['wp_pkpo_pickup_selection_type']) && $settings['wp_pkpo_pickup_selection_type'] === 'by_radio'){ ?> checked=checked <?php }?>/><?php _e('By Radio Button', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_pickup_selection_type" value="by_dropdown" <?php if (isset($settings['wp_pkpo_pickup_selection_type']) && $settings['wp_pkpo_pickup_selection_type'] === 'by_dropdown'){ ?> checked=checked <?php }?>/><?php _e('By Dropdown', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Show pickup point as dropdown list or radio button.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                 <tr>
                    <th scope="row"><label for="wp_pickup_auto_select_pickup"><?php _e('Auto Select first location on checkout', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_auto_select_pickup" value="enable" <?php if (isset($settings['wp_pickup_auto_select_pickup']) && $settings['wp_pickup_auto_select_pickup'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_auto_select_pickup" value="disable" <?php if (isset($settings['wp_pickup_auto_select_pickup']) && $settings['wp_pickup_auto_select_pickup'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to select first entry on load on checkout page or force user to select entry ', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="wp_pkpo_default_position"><?php _e('Default Position In Map', 'woocommerce-pickup-location'); ?></label></th>
                    <td><input class="wp_pkpo_text_input" name="wp_pkpo_default_position" id="wp_pkpo_default_position" placeholder="<?php _e('eg. Lat,Lang.', 'woocommerce-pickup-location'); ?>" class="regular-text" value="<?php if (isset($settings['wp_pkpo_default_position']))
                                   echo $settings['wp_pkpo_default_position'];?>" type="text">
                        <p class="description"><?php _e('Set default position of map.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_pickup_date_formate"><?php _e('Pickup Date Formate', 'woocommerce-pickup-location'); ?></label></th>
                    <td>			
                        <input class="wp_pkpo_text_input" type="text" name="wp_pkpo_pickup_date_formate" value="<?php if (isset($settings['wp_pkpo_pickup_date_formate']) && $settings['wp_pkpo_pickup_date_formate'] !== '')
                                   echo $settings['wp_pkpo_pickup_date_formate'];
                        ?>" />
                        <p class="description"><?php _e('Set default date format.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_map_disable"><?php _e('Map Display', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_map_disable" value="enable" <?php if (isset($settings['wp_pkpo_map_disable']) && $settings['wp_pkpo_map_disable'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Show', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_map_disable" value="disable" <?php if (isset($settings['wp_pkpo_map_disable']) && $settings['wp_pkpo_map_disable'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Hide', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide map on checkout page.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_pickup_admin_map_zoom"><?php _e('Admin Map Zoom', 'woocommerce-pickup-location'); ?></label></th>
                    <td>			
                        <input class="wp_pkpo_text_input" type="text" name="wp_pkpo_pickup_admin_map_zoom" value="<?php if (isset($settings['wp_pkpo_pickup_admin_map_zoom']) && $settings['wp_pkpo_pickup_admin_map_zoom'] !== '')
                                   echo $settings['wp_pkpo_pickup_admin_map_zoom'];
                        ?>" />
                        <p class="description"><?php _e('Set default map zoom in admin map display.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_pickup_checkout_map_zoom"><?php _e('Checkout Map Zoom', 'woocommerce-pickup-location'); ?></label></th>
                    <td>			
                        <input class="wp_pkpo_text_input" type="text" name="wp_pkpo_pickup_checkout_map_zoom" value="<?php if (isset($settings['wp_pkpo_pickup_checkout_map_zoom']) && $settings['wp_pkpo_pickup_checkout_map_zoom'] !== '')
                                   echo $settings['wp_pkpo_pickup_checkout_map_zoom'];
                        ?>" />
                        <p class="description"><?php _e('Set default map zoom in checkout map display.', 'woocommerce-pickup-location'); ?>
                            .</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_shortcode_map_zoom"><?php _e('Shortcode Map Zoom', 'woocommerce-pickup-location'); ?></label></th>
                    <td>			
                        <input class="wp_pkpo_text_input" type="text" name="wp_pkpo_shortcode_map_zoom" value="<?php if (isset($settings['wp_pkpo_shortcode_map_zoom']) && $settings['wp_pkpo_shortcode_map_zoom'] !== '')
                                   echo $settings['wp_pkpo_shortcode_map_zoom'];
                        ?>" />
                        <p class="description"><?php _e('Set default map zoom in Shortcode map display', 'woocommerce-pickup-location'); ?>
                            .</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_map_position"><?php _e('Map Position', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_map_position" value="side" <?php if (isset($settings['wp_pickup_map_position']) && $settings['wp_pickup_map_position'] === 'side')
                                   echo 'checked=checked';
                        ?>/><?php _e('Side by side to pickup details', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_map_position" value="top" <?php if (isset($settings['wp_pickup_map_position']) && $settings['wp_pickup_map_position'] === 'top')
                                   echo 'checked=checked';
                        ?>/><?php _e('Above the pickup details ', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show map side by side or above the pickup location (best for responsive) ', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
               
                <tr>
                    <th scope="row"><label for="wp_pkpo_shipping_hide_details"><?php _e('Shipping Field Display', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_shipping_hide_details" value="enable" <?php if (isset($settings['wp_pkpo_shipping_hide_details']) && $settings['wp_pkpo_shipping_hide_details'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_shipping_hide_details" value="disable" <?php if (isset($settings['wp_pkpo_shipping_hide_details']) && $settings['wp_pkpo_shipping_hide_details'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('whether to show/hide shipping fields. Please note that once it is hidden, it will not show for other shipping method also', 'woocommerce-pickup-location'); ?>
                           </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wp_pkpo_billing_hide_details"><?php _e('Billing Field Display', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_billing_hide_details" value="enable" <?php if (isset($settings['wp_pkpo_billing_hide_details']) && $settings['wp_pkpo_billing_hide_details'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_billing_hide_details" value="disable" <?php if (isset($settings['wp_pkpo_billing_hide_details']) && $settings['wp_pkpo_billing_hide_details'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Allows customers to checkout without creating an account.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_hide_checkout_time"><?php _e('Show/Hide Pickup Time', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_hide_checkout_time" value="enable" <?php if (isset($settings['wp_pkpo_hide_checkout_time']) && $settings['wp_pkpo_hide_checkout_time'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_hide_checkout_time" value="disable" <?php if (isset($settings['wp_pkpo_hide_checkout_time']) && $settings['wp_pkpo_hide_checkout_time'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup time on checkout page.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_hide_checkout_date"><?php _e('Show/Hide Pickup Date', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_hide_checkout_date" value="enable" <?php if (isset($settings['wp_pkpo_hide_checkout_date']) && $settings['wp_pkpo_hide_checkout_date'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_hide_checkout_date" value="disable" <?php if (isset($settings['wp_pkpo_hide_checkout_date']) && $settings['wp_pkpo_hide_checkout_date'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup date on checkout page.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_order_list_show_pickup"><?php _e('Show Pickup Location on order list page', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_order_list_show_pickup" value="enable" <?php if (isset($settings['wp_pickup_order_list_show_pickup']) && $settings['wp_pickup_order_list_show_pickup'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_order_list_show_pickup" value="disable" <?php if (isset($settings['wp_pickup_order_list_show_pickup']) && $settings['wp_pickup_order_list_show_pickup'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup location information on order list page', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pkpo_send_info_in_email"><?php _e('Send Pickup Info. in order emails', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_send_info_in_email" value="enable" <?php if (isset($settings['wp_pkpo_send_info_in_email']) && $settings['wp_pkpo_send_info_in_email'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pkpo_send_info_in_email" value="disable" <?php if (isset($settings['wp_pkpo_send_info_in_email']) && $settings['wp_pkpo_send_info_in_email'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Include the pickup information in WooCommerce order emails?', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="wp_pickup_default_time_formate"><?php _e('Timepicker Formate', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_default_time_formate" value="12" <?php if (isset($settings['wp_pickup_default_time_formate']) && $settings['wp_pickup_default_time_formate'] === '12')
                                   echo 'checked=checked';
                        ?>/><?php _e('12 Hours', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_default_time_formate" value="24" <?php if (isset($settings['wp_pickup_default_time_formate']) && $settings['wp_pickup_default_time_formate'] === '24')
                                   echo 'checked=checked';
                        ?>/><?php _e('24 Hours', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Allows customers to change time formate.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="wp_pickup_wrong_date"><?php _e('Wrong Date Selected Message', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_wrong_date" value="<?php if (isset($settings['wp_pickup_wrong_date']) && $settings['wp_pickup_wrong_date'] !== '') echo $settings['wp_pickup_wrong_date']; ?>"/>
                        <p class="description"><?php _e('Allows customers to change Wrong Date Message.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="wp_pickup_wrong_time"><?php _e('Wrong Time Selected Message', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_wrong_time" value="<?php if (isset($settings['wp_pickup_wrong_time']) && $settings['wp_pickup_wrong_time'] !== '') echo $settings['wp_pickup_wrong_time']; ?>"/>
                        <p class="description"><?php _e('Allows customers to change Wrong Time Message.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_min_time_msg"><?php _e('Min Time Criteria Message', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_min_time_msg" value="<?php if (isset($settings['wp_pickup_min_time_msg']) && $settings['wp_pickup_min_time_msg'] !== '') echo $settings['wp_pickup_min_time_msg']; ?>"/>
                        <p class="description"><?php _e('Message to be displayed when pickup time is less than min time', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_max_time_msg"><?php _e('Max Time Criteria Message', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_max_time_msg" value="<?php if (isset($settings['wp_pickup_max_time_msg']) && $settings['wp_pickup_max_time_msg'] !== '') echo $settings['wp_pickup_max_time_msg']; ?>"/>
                        <p class="description"><?php _e('Message to be displayed when pickup time is greater than max time', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                 <tr>
                    <th scope="row"><label for="wp_pickup_default_date_text"><?php _e('Pickup date text', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_default_date_text" value="<?php if (isset($settings['wp_pickup_default_date_text']) && $settings['wp_pickup_default_date_text'] !== '') echo $settings['wp_pickup_default_date_text']; ?>"/>
                        <p class="description"><?php _e('Text to be appear on pickup date textbox placeholder', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_default_time_text"><?php _e('Pickup time text', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_default_time_text" value="<?php if (isset($settings['wp_pickup_default_time_text']) && $settings['wp_pickup_default_time_text'] !== '') echo $settings['wp_pickup_default_time_text']; ?>"/>
                        <p class="description"><?php _e('Text to be appear on pickup time textbox placeholder', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

              <tr>
                    <th scope="row"><label for="wp_pickup_address_display"><?php _e('Show Pickup address', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_address_display" value="enable" <?php if (isset($settings['wp_pickup_address_display']) && $settings['wp_pickup_address_display'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_address_display" value="disable" <?php if (isset($settings['wp_pickup_address_display']) && $settings['wp_pickup_address_display'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup address information', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_email_display"><?php _e('Show Pickup Branch Email', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_email_display" value="enable" <?php if (isset($settings['wp_pickup_email_display']) && $settings['wp_pickup_email_display'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_email_display" value="disable" <?php if (isset($settings['wp_pickup_email_display']) && $settings['wp_pickup_email_display'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup email information', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_city_display"><?php _e('Show Pickup City Name', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_city_display" value="enable" <?php if (isset($settings['wp_pickup_city_display']) && $settings['wp_pickup_city_display'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_city_display" value="disable" <?php if (isset($settings['wp_pickup_city_display']) && $settings['wp_pickup_city_display'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup city information', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_state_display"><?php _e('Show Pickup State Name', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_state_display" value="enable" <?php if (isset($settings['wp_pickup_state_display']) && $settings['wp_pickup_state_display'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_state_display" value="disable" <?php if (isset($settings['wp_pickup_state_display']) && $settings['wp_pickup_state_display'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup state information', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_country_display"><?php _e('Show Pickup Country Name', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_country_display" value="enable" <?php if (isset($settings['wp_pickup_country_display']) && $settings['wp_pickup_country_display'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_country_display" value="disable" <?php if (isset($settings['wp_pickup_country_display']) && $settings['wp_pickup_country_display'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup country information', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_zip_display"><?php _e('Show Pickup Zipcode Name', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_zip_display" value="enable" <?php if (isset($settings['wp_pickup_zip_display']) && $settings['wp_pickup_zip_display'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_zip_display" value="disable" <?php if (isset($settings['wp_pickup_zip_display']) && $settings['wp_pickup_zip_display'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to show/hide pickup zipcode information', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_load_pickup_later"><?php _e('Load Pickup points later', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_load_pickup_later" value="yes" <?php if (isset($settings['wp_pickup_load_pickup_later']) && $settings['wp_pickup_load_pickup_later'] === 'yes')
                                   echo 'checked=checked';
                        ?>/><?php _e('Yes', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_load_pickup_later" value="no" <?php if (isset($settings['wp_pickup_load_pickup_later']) && $settings['wp_pickup_load_pickup_later'] === 'no')
                                   echo 'checked=checked';
                        ?>/><?php _e('No', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to load pickup points later after loading of checkout page', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_default_shipping_pickup"><?php _e('Set Pickup Location as default shipping', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_default_shipping_pickup" value="yes" <?php if (isset($settings['wp_pickup_default_shipping_pickup']) && $settings['wp_pickup_default_shipping_pickup'] === 'yes')
                                   echo 'checked=checked';
                        ?>/><?php _e('Yes', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_default_shipping_pickup" value="no" <?php if (isset($settings['wp_pickup_default_shipping_pickup']) && $settings['wp_pickup_default_shipping_pickup'] === 'no')
                                   echo 'checked=checked';
                        ?>/><?php _e('No', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to set default shipping as pickup location on checkout page', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                
                <tr>
                    <th scope="row"><label for="wp_pickup_radius_search"><?php _e('Enable Radius Search', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_radius_search" value="enable" <?php if (isset($settings['wp_pickup_radius_search']) && $settings['wp_pickup_radius_search'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_radius_search" value="disable" <?php if (isset($settings['wp_pickup_radius_search']) && $settings['wp_pickup_radius_search'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to enable radius search (it works with dropdown only)', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_hide_pickup_shipping_based_on_order"><?php _e('Hide pickup shipping based on order amount', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_hide_pickup_shipping_based_on_order" value="<?php if (isset($settings['wp_pickup_hide_pickup_shipping_based_on_order']) && $settings['wp_pickup_hide_pickup_shipping_based_on_order'] !== '') echo $settings['wp_pickup_hide_pickup_shipping_based_on_order']; ?>"/>
                        <p class="description"><?php _e('Hide pickup shipping untill order amount reaches this amount. Leave empty if not required. Please clear cache using wc->system status->tools', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_map_api_key"><?php _e('Google map api key', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_text_input" type="text" name="wp_pickup_map_api_key" value="<?php if (isset($settings['wp_pickup_map_api_key']) && $settings['wp_pickup_map_api_key'] !== '') echo $settings['wp_pickup_map_api_key']; ?>"/>
                        <p class="description"><?php _e('Please enter your google map api key.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_map_search"><?php _e('Hide search box in map', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_map_search" value="enable" <?php if (isset($settings['wp_pickup_map_search']) && $settings['wp_pickup_map_search'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_map_search" value="disable" <?php if (isset($settings['wp_pickup_map_search']) && $settings['wp_pickup_map_search'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to hide or show search box in map', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_location_based_product"><?php _e('Enable product location based pickups? ', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_location_based_product" value="enable" <?php if (isset($settings['wp_pickup_location_based_product']) && $settings['wp_pickup_location_based_product'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_location_based_product" value="disable" <?php if (isset($settings['wp_pickup_location_based_product']) && $settings['wp_pickup_location_based_product'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to enable porduct location based pickup? If enabled, you can set product location in woo product pages. If no location specified for product in cart then it will show all locations', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="wp_pickup_location_select2"><?php _e('Enable autosuggest dropdown(select2) in pickup location selection? ', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_location_select2" value="enable" <?php if (isset($settings['wp_pickup_location_select2']) && $settings['wp_pickup_location_select2'] === 'enable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Enable', 'woocommerce-pickup-location'); ?>
                        <input class="wp_pkpo_radio_input" type="radio" name="wp_pickup_location_select2" value="disable" <?php if (isset($settings['wp_pickup_location_select2']) && $settings['wp_pickup_location_select2'] === 'disable')
                                   echo 'checked=checked';
                        ?>/><?php _e('Disable', 'woocommerce-pickup-location'); ?>
                        <p class="description"><?php _e('Whether to enable autosuggest dropdown(select2) on checkout page for pickup location selection? ', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="wp_pickup_user_roles"><?php _e('Select user roles to allow pickup locations ', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        
                        <select class="wp_pkpo_select_input" name="wp_pickup_user_roles[]" multiple="multiple">
                       <?php 
                       $roles = get_editable_roles();
                       foreach($roles as $role_name => $role_info){
                           $selected = "";
                           if(isset($settings['wp_pickup_user_roles']) && is_array($settings['wp_pickup_user_roles']) && in_array($role_name, $settings['wp_pickup_user_roles']))
                                   $selected = "selected=selected";
                           ?>
                            <option <?php echo $selected;?> value="<?php echo $role_name;?>"><?php echo $role_name;?></option>
                       <?php }
                       ?>
                        </select>
                        <p class="description"><?php _e('Select list of user roles that will allow pickup location. If no role selected, then it will show for all roles.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="wp_pickup_default_datepicker_lang"><?php _e('Default Datepicker Language', 'woocommerce-pickup-location'); ?></label></th>
                    <td>
                        <select id="wp_pickup_default_datepicker_lang" name="wp_pickup_default_datepicker_lang">
                            <option <?php if (isset($settings['wp_pickup_default_datepicker_lang']) && $settings['wp_pickup_default_datepicker_lang'] === '')
                                    echo 'selected="selected"';
                        ?> value="">English</option>
                            <option <?php if (isset($settings['wp_pickup_default_datepicker_lang']) && $settings['wp_pickup_default_datepicker_lang'] === 'ar')
                                    echo 'selected="selected"';
                        ?> value="ar">Arabic </option>
                            <option <?php if (isset($settings['wp_pickup_default_datepicker_lang']) && $settings['wp_pickup_default_datepicker_lang'] === 'zh-TW')
                                    echo 'selected="selected"';
                        ?> value="zh-TW">Chinese Traditional</option>
                            <option <?php if (isset($settings['wp_pickup_default_datepicker_lang']) && $settings['wp_pickup_default_datepicker_lang'] === 'fr')
                                    echo 'selected="selected"';
                        ?> value="fr">French</option>
                            <option <?php if (isset($settings['wp_pickup_default_datepicker_lang']) && $settings['wp_pickup_default_datepicker_lang'] === 'he')
                                    echo 'selected="selected"';
                        ?> value="he">Hebrew</option>
                        </select>
                        <p class="description"><?php _e('Allows customers to change timepicker default language.', 'woocommerce-pickup-location'); ?></p>
                    </td>
                </tr>

            </tbody>
        </table>
        <p class="submit">
            <input name="submit" id="submit" class="button button-primary" value="<?php _e('Save', 'woocommerce-pickup-location'); ?>" type="submit">
        </p>
    </form>
</div>