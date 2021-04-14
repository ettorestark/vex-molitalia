<div class="wp_product_locations">
    <ul class="wp_product_locations_list">
        <?php foreach ($wp_pkpo_locations as $location) { 
            $selected = "";
            if(is_array($wp_pkpo_selected_locations) && count($wp_pkpo_selected_locations) && in_array($location->ID, $wp_pkpo_selected_locations))
                    $selected = "checked=checked";
            ?>
            <li id="product_location_<?php echo $location->ID; ?>">
                <label>  
                    <input value="<?php echo $location->ID; ?>" <?php echo $selected; ?> type="checkbox" name="wp_pkpo_product_locations[]" id="in-product_location_-<?php echo $location->ID; ?>">
                    <?php echo $location->post_title; ?>
                </label>
            </li>   
        <?php } ?>
    </ul>
</div>