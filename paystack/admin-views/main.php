<?php
/**
 * The Template for displaying the Tickets Commerce PayPal Settings.
 *
 * @version 5.3.0
 *
 * @since   5.1.10
 * @since   5.3.0 Using generic CSS classes for gateway instead of PayPal.
 *
 * @var Tribe__Tickets__Admin__Views                  $this                  [Global] Template object.
 * @var string                                        $plugin_url            [Global] The plugin URL.
 * @var paystack\tec\classes\Merchant $merchant       [Global] The merchant class.
 * @var paystack\tec\classes\Signup   $signup         [Global] The Signup class.
 * @var bool                                          $is_merchant_active    [Global] Whether the merchant is active or not.
 * @var bool                                          $is_merchant_connected [Global] Whether the merchant is connected or not.
 */

$classes = array(
	'tec-tickets__admin-settings-tickets-commerce-gateway',
	'tec-tickets__admin-settings-tickets-commerce-gateway--connected' => $is_merchant_connected,
)
?>

<div <?php tribe_classes( $classes ); ?>>
	<div id="tec-tickets__admin-settings-tickets-commerce-gateway-connect" class="tec-tickets__admin-settings-tickets-commerce-gateway-connect">
		<?php $this->template( 'paystack/admin-views/connect/inactive' ); ?>
	</div>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-logo">
		<?php $image_src = PS_TEC_URL . 'icon.png'; ?>

		<img
			src="<?php echo esc_url( $image_src ); ?>"
			alt="<?php esc_attr_e( 'Paystack Logo Image', 'event-tickets' ); ?>"
			class="tec-tickets__admin-settings-tickets-commerce-gateway-logo-image"
		>

		<ul>
			<li>
				<?php esc_html_e( 'Credit and debit card payments', 'event-tickets' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Accept payments from around the world', 'event-tickets' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Supports 3D Secure payments', 'event-tickets' ); ?>
			</li>
		</ul>
	</div>

</div>

<?php $this->template( 'paystack/admin-views/connect/active' ); ?>

<?php $this->template( 'paystack/admin-views/modal/signup-complete' ); ?>
