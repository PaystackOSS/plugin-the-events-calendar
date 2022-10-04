<?php
/**
 * The Template for displaying the Tickets Commerce Paystack Settings when inactive (not connected).
 *
 * @var Tribe__Tickets__Admin__Views                  $this                  [Global] Template object.
 * @var string                                        $plugin_url            [Global] The plugin URL.
 * @var paystack\tec\classes\Merchant $merchant              [Global] The merchant class.
 * @var paystack\tec\classes\Signup   $signup                [Global] The Signup class.
 * @var bool                                          $is_merchant_active    [Global] Whether the merchant is active or not.
 * @var bool                                          $is_merchant_connected [Global] Whether the merchant is connected or not.
 */

if ( ! empty( $is_merchant_connected ) ) {
	return;
}

?>

<h2 class="tec-tickets__admin-settings-tickets-commerce-gateway-title">
	<?php esc_html_e( 'Accept online payments!', 'event-tickets' ); ?>
</h2>

<div class="tec-tickets__admin-settings-tickets-commerce-gateway-description">
	<p>
		<?php esc_html_e( 'Start selling tickets to your events today with Paystack. Attendees can purchase tickets directly on your site using debit or credit cards with no additional fees.', 'event-tickets' ); ?>
	</p>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-links">
		<?php echo $signup->get_link_html(); // phpcs:ignore ?>
	</div>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-links">
		<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link">
			<?php $this->template( 'components/icons/lightbulb' ); ?>
			<a
				href="https://www.youtube.com/watch?v=gWfoN_OydHE"
				target="_blank"
				rel="noopener noreferrer"
				class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link-url"
			><?php esc_html_e( 'Learn more about configuring Paystack', 'event-tickets' ); ?></a>
		</div>

		<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link">
			<?php $this->template( 'components/icons/lightbulb' ); ?>
			<a
				href="https://www.youtube.com/watch?v=gWfoN_OydHE"
				target="_blank"
				rel="noopener noreferrer"
				class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link-url"
			><?php esc_html_e( 'Learn more about configuring Paystack', 'event-tickets' ); ?></a>
		</div>
	</div>
</div>
