<?php
/**
 * The Template for displaying the Tickets Commerce Paystack Settings when inactive (not connected).
 *
 * @var Tribe__Tickets__Admin__Views                  $this                  [Global] Template object.
 * @var paystack\tec\classes\Merchant $merchant              [Global] The merchant class.
 * @var paystack\tec\classes\Signup   $signup                [Global] The Signup class.
 */
?>

<h2 class="tec-tickets__admin-settings-tickets-commerce-gateway-title">
	<?php esc_html_e( 'Accept online payments!', 'ps-tec-gateway' ); ?>
</h2>

<div class="tec-tickets__admin-settings-tickets-commerce-gateway-description">
	<p>
		<?php esc_html_e( 'Start selling tickets to your events today with Paystack and let your customers pay you with their choice of payment methods.', 'ps-tec-gateway' ); ?>
	</p>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-links">
		<?php echo $signup->get_link_html(); // phpcs:ignore ?>
	</div>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-links">
		<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link">
			<?php $this->template( 'components/icons/lightbulb' ); ?>
			<a
				href="https://dashboard.paystack.co/#/settings/developer"
				target="_blank"
				rel="noopener noreferrer"
				class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link-url"
			><?php esc_html_e( 'Get your API Keys here', 'ps-tec-gateway' ); ?></a>
		</div>

		<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link">
			<?php $this->template( 'components/icons/lightbulb' ); ?>
			<a
				href="https://www.youtube.com/watch?v=gWfoN_OydHE"
				target="_blank"
				rel="noopener noreferrer"
				class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link-url"
			><?php esc_html_e( 'Learn more about configuring Paystack', 'ps-tec-gateway' ); ?></a>
		</div>
	</div>
</div>
