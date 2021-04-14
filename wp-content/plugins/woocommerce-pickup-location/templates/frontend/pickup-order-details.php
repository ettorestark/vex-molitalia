<?php
$pickup_date = get_post_meta($order->id, 'pickup_date', true);
$pickup_time = get_post_meta($order->id, 'pickup_time', true);
$pickup_name = get_post_meta($order->id, 'pickup_name', true);
$pickup_address = get_post_meta($order->id, 'pickup_address', true);
$pickup_email = get_post_meta($order->id, 'pickup_email', true);
$pickup_city = get_post_meta($order->id, 'pickup_city', true);
$pickup_state = get_post_meta($order->id, 'pickup_state', true);
$pickup_country = get_post_meta($order->id, 'pickup_country', true);
$pickup_zipcode = get_post_meta($order->id, 'pickup_zipcode', true);
?>
    <h2><?php _e('Pickup Information', 'woocommerce-pickup-location'); ?></h2>
    <table class="shop_table order_details">
	 
	 <?php if (($pickup_date != '')) { ?>
        <tr>
            <th><?php _e('Pickup Date', 'woocommerce-pickup-location'); ?></th>
            <td><?php echo $pickup_date; ?></td>
        </tr>
	 <?php } if($pickup_time != '') { ?> 
        <tr>
            <th><?php _e('Pickup Time', 'woocommerce-pickup-location'); ?></th>
            <td><?php echo $pickup_time; ?></td>
        </tr>
	 <?php } if($pickup_name != '') { ?>
        <tr>
            <th><?php _e('Pickup Location Name', 'woocommerce-pickup-location'); ?></th>
            <td>
                <?php
					echo $pickup_name;
                ?></td>
        </tr>
		<?php } 
                if($pickup_address != '') { ?>
        <tr>
            <th><?php _e('Pickup Location address', 'woocommerce-pickup-location'); ?></th>
            <td>
                <?php
					echo $pickup_address;
                ?></td>
        </tr><?php }  
        
        if($pickup_city != '') { ?>
        <tr>
            <th><?php _e('Pickup city', 'woocommerce-pickup-location'); ?></th>
            <td>
                <?php
					echo $pickup_city;
                ?></td>
        </tr><?php }  
        
        if($pickup_state != '') { ?>
        <tr>
            <th><?php _e('Pickup state', 'woocommerce-pickup-location'); ?></th>
            <td>
                <?php
					echo $pickup_state;
                ?></td>
        </tr><?php }
        
        if($pickup_country != '') { ?>
        <tr>
            <th><?php _e('Pickup country', 'woocommerce-pickup-location'); ?></th>
            <td>
                <?php
					echo $pickup_country;
                ?></td>
        </tr><?php }
        
        if($pickup_zipcode != '') { ?>
        <tr>
            <th><?php _e('Pickup zipcode', 'woocommerce-pickup-location'); ?></th>
            <td>
                <?php
					echo $pickup_zipcode;
                ?></td>
        </tr><?php }
        
        
        if($pickup_email != '') { ?>
        <tr>
            <th><?php _e('Pickup Email', 'woocommerce-pickup-location'); ?></th>
            <td>
                <?php
        		echo $pickup_email;
                ?></td>
        </tr><?php } ?>
    </table>
