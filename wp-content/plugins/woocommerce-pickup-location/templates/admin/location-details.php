<div class="wp_pkpo_time" style="height:100px;">
    <label><?php _e('Details', 'woocommerce-pickup-location'); ?></label>
    <textarea id="wp_pkpo_contact_details" type="text" name="wp_pkpo_contact_details"><?php echo $wp_pkpo_contact_details; ?></textarea>
</div>

<div class="wp_pkpo_time">
    <label><?php _e('Email', 'woocommerce-pickup-location'); ?></label>
    <input value="<?php echo $wp_pkpo_contact_email; ?>" type="text" id="wp_pkpo_contact_email" name="wp_pkpo_contact_email" />
</div>

<?php if(isset($settings["wp_pickup_city_display"]) && $settings["wp_pickup_city_display"] === "enable") { ?>
<div class="wp_pkpo_time">
    <label><?php _e('City', 'woocommerce-pickup-location'); ?></label>
    <input value="<?php echo $wp_pickup_city; ?>" type="text" id="wp_pickup_city_display" name="wp_pickup_city_display" />
</div>
<?php }?>

<?php if(isset($settings["wp_pickup_state_display"]) && $settings["wp_pickup_state_display"] === "enable") { ?>
<div class="wp_pkpo_time">
    <label><?php _e('State', 'woocommerce-pickup-location'); ?></label>
    <input value="<?php echo $wp_pickup_state; ?>" type="text" id="wp_pickup_state_display" name="wp_pickup_state_display" />
</div>
<?php }?>

<?php if(isset($settings["wp_pickup_country_display"]) && $settings["wp_pickup_country_display"] === "enable") { ?>
<div class="wp_pkpo_time">
    <label><?php _e('Country', 'woocommerce-pickup-location'); ?></label>
    <input value="<?php echo $wp_pickup_country; ?>" type="text" id="wp_pickup_country_display" name="wp_pickup_country_display" />
</div>
<?php }?>

<?php if(isset($settings["wp_pickup_zip_display"]) && $settings["wp_pickup_zip_display"] === "enable") { ?>
<div class="wp_pkpo_time">
    <label><?php _e('Zip', 'woocommerce-pickup-location'); ?></label>
    <input value="<?php echo $wp_pickup_zip; ?>" type="text" id="wp_pickup_zip_display" name="wp_pickup_zip_display" />
</div>
<?php }?>