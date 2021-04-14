<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="woocommerce-order">

	<?php if ( $order ) : ?>
		<?php
		//echo '<pre>';
		//$current_user = wp_get_current_user();
		//$userTokenId= get_user_meta( $current_user->ID, '_visaperu_userTokenId', true );
		$transaction = get_post_meta( $order->get_id(), '_operation_visanet', true );
		//print_r( $transaction );
		//echo '</pre>';
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_order_number(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Fecha y hora del pedido:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo $transaction->data->fechayhora_tx; ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Detalle:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo $transaction->data->dsc_cod_accion; ?></strong>
				</li>
			</ul>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_order_number(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Nombre del tarjetahabiente:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Número de Tarjeta:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo $transaction->data->pan; ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Fecha y hora del pedido:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo $transaction->data->fechayhora_tx; ?></strong>
				</li>

				<li class="woocommerce-order-overview__total total">
					<?php _e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Moneda:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo $order->get_currency(); ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Términos y Condiciones:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><a href="<?php echo get_permalink( wc_get_page_id( 'terms' ) ); ?>">Ver</a></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Detalle:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo $transaction->data->dsc_cod_accion; ?></strong>
				</li>

				<li class="woocommerce-order-overview__order order">
					<?php _e( 'Method:', 'woocommerce-gateway-visanetperu' ); ?>
					<strong><?php echo wp_kses_post( $order->get_payment_method() ); ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__total total">
						<?php _e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_billing_email(); ?></strong>
					</li>
				<?php endif; ?>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php _e( 'Payment method:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

	<?php endif; ?>

</div>