<div class="wp_pkpo_min_time" style="height:100px;">
    <label><?php _e('Min Time', 'woocommerce-pickup-location'); ?></label>
    <input value="<?php echo $wp_pkpo_min_time; ?>" type="text" id="wp_pkpo_min_time" name="wp_pkpo_min_time" />
    <p><?php _e('Min time (in hrs) before product can be delivered - leave empty if no time limit ', 'woocommerce-pickup-location'); ?></p>
</div>
<div class="wp_pkpo_max_time">
    <label><?php _e('Max Time', 'woocommerce-pickup-location'); ?></label>
    <input value="<?php echo $wp_pkpo_max_time; ?>" type="text" id="wp_pkpo_max_time" name="wp_pkpo_max_time" />
    <p><?php _e('Max time (in hrs) after that product will not be available - leave empty if no time limit ', 'woocommerce-pickup-location'); ?></p>
</div>