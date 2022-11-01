<?php
/**
 * The Template for displaying the Tickets Commerce PayPal Settings when connected.
 *
 * @version 5.3.0
 *
 * @since   5.1.10
 * @since   5.3.0 Using generic CSS classes for gateway instead of PayPal.
 *
 * @var Tribe__Tickets__Admin__Views                  $this                  [Global] Template object.
 * @var string                                        $plugin_url            [Global] The plugin URL.
 * @var \paystack\tec\classes\Merchant $merchant              [Global] The merchant class.
 * @var \paystack\tec\classes\Signup   $signup                [Global] The Signup class.
 * @var bool                                          $is_merchant_active    [Global] Whether the merchant is active or not.
 * @var bool                                          $is_merchant_connected [Global] Whether the merchant is connected or not.
 */

if ( empty( $is_merchant_connected ) ) {
	return;
}
?>
<div class="tec-tickets__admin-settings-tickets-commerce-gateway-connected">
	<h3><?php esc_html_e( 'Additional Settings', 'event-tickets' ); ?></h3>
	<?php
		$checkout_mode_args = array(
			'selected_mode' => $merchant->get_prop( 'checkout_mode' ),
		);
		$this->template( 'paystack/admin-views/fields/checkout-mode', $checkout_mode_args );
	?>
</div>
