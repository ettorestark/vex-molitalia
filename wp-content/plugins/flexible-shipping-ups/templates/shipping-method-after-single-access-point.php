<tr class="shipping flexible-shipping-ups-shipping">
    <td colspan="2">
	    <h4><?php _e( 'UPS Access Point', 'flexible-shipping-ups' ); ?></h4>
		<?php if ( count( $select_options ) ) : ?>
			<input type="hidden" name="ups_access_point" value="<?php echo esc_attr( $selected_access_point ); ?>" />
			<?php echo $select_options[ $selected_access_point ]; // WPCS: XSS ok. ?>
		<?php else : ?>
			<strong class="no-access-points"><?php _e( 'Access point unavailable for selected shipping address!', 'flexible-shipping-ups' ); ?></strong>
		<?php endif; ?>
    </td>
</tr>
