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
<h3><?php _e('Pickup Information', 'woocommerce-pickup-location'); ?></h3>
<p>
    <?php if ($pickup_date != '') {
        _e('Pickup Date', 'woocommerce-pickup-location'); ?> - <?php echo $pickup_date;
    } ?>
        <br/>
    <?php if ($pickup_time != '') {
        _e('Pickup Time', 'woocommerce-pickup-location'); ?> - <?php echo $pickup_time;
    } ?>
        <br/>
    <?php if ($pickup_name != '') {
        _e('Pickup Location Name', 'woocommerce-pickup-location'); ?> - <?php
        echo $pickup_name;
    }
    ?>
        <br/>
    <?php if ($pickup_address != '') {
        _e('Pickup address', 'woocommerce-pickup-location'); ?> - <?php
        echo $pickup_address;
    }
    ?>
        <br/>
    <?php if ($pickup_city != '') {
        _e('Pickup city', 'woocommerce-pickup-location'); ?> - <?php
        echo $pickup_city;
    }
    ?>
        <br/>
    <?php if ($pickup_state != '') {
        _e('Pickup state', 'woocommerce-pickup-location'); ?> - <?php
        echo $pickup_state;
    }
    ?>
        <br/>
    <?php if ($pickup_country != '') {
        _e('Pickup country', 'woocommerce-pickup-location'); ?> - <?php
        echo $pickup_country;
    }
    ?>
        <br/>
    <?php if ($pickup_zipcode != '') {
        _e('Pickup zipcode', 'woocommerce-pickup-location'); ?> - <?php
        echo $pickup_zipcode;
    }
    ?>
        <br/>
    <?php if ($pickup_email != '') {
        _e('Pickup Email', 'woocommerce-pickup-location'); ?> - <?php
        echo $pickup_email;
    }
    ?>
</p>
