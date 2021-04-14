<div class="wp_pickup_lat_lng">
    <div class="wp_pkpo_form_row">
        <label><?php _e('Latitude', 'woocommerce-pickup-location'); ?></label>
        <input type="text" name="wp_pkpo_lat" value="<?php echo $wp_pkpo_lat; ?>"/>
    </div>

    <div class="wp_pkpo_form_row">
        <label><?php _e('Longitude', 'woocommerce-pickup-location'); ?></label>
        <input type="text" name="wp_pkpo_long" value="<?php echo $wp_pkpo_long; ?>"/>
    </div>
    
    <div class="wp_pkpo_form_row">
        <label><?php _e('Search', 'woocommerce-pickup-location'); ?></label>
        <input id="pac-input" class="controls" type="text" placeholder="<?php _e("Search Box", 'woocommerce-pickup-location'); ?>">
    </div>
</div>
<?php 
$wp_pkpo_default_lat = '';
$wp_pkpo_default_lang = '';
$wp_pkpo_pickup_admin_map_zoom = '';
if(isset($settings['wp_pkpo_default_position']) && $settings['wp_pkpo_default_position']!='')
{
	$wp_pkpo_default_position =  explode(",",$settings['wp_pkpo_default_position']);
	$wp_pkpo_default_lat = $wp_pkpo_default_position[0];
	$wp_pkpo_default_lang = $wp_pkpo_default_position[1];
}
if(isset($settings['wp_pkpo_pickup_admin_map_zoom']) && $settings['wp_pkpo_pickup_admin_map_zoom']!='')
{
	$wp_pkpo_pickup_admin_map_zoom = $settings['wp_pkpo_pickup_admin_map_zoom'];
}	
?>
<input type="hidden" id="wp_pkpo_settings_lat" value="<?php echo $wp_pkpo_default_lat;?>"/>
<input type="hidden" id="wp_pkpo_settings_lang" value="<?php echo $wp_pkpo_default_lang;?>"/>
<input type="hidden" id="wp_pkpo_pickup_admin_map_zoom" value="<?php echo $wp_pkpo_pickup_admin_map_zoom;?>"/>
<div>
    <div id="pickup_map"></div>
</div>
