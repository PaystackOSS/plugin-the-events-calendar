<?php
/**
 * Tickets Commerce: PayPal Checkout container
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/commerce/gateway/paypal/container.php
 *
 * See more documentation about our views templating system.
 *
 * @link    https://evnt.is/1amp Help article for RSVP & Ticket template files.
 *
 * @since   5.3.0
 *
 * @version 5.3.0
 *
 * @var bool   $must_login               [Global] Whether login is required to buy tickets or not.
 * @var bool   $supports_custom_payments [Global] Determines if this site supports custom payments.
 * @var bool   $active_custom_payments   [Global] Determines if this site supports custom payments.
 * @var string $url                      [Global] Script URL.
 * @var string $client_token             [Global] One time use client Token.
 * @var string $client_token_expires_in  [Global] How much time to when the Token in this script will take to expire.
 * @var string $attribution_id           [Global] What is our PayPal Attribution ID.
 */

if ( $must_login ) {
	return;
}

?>
<div class="tribe-tickets__commerce-checkout-gateway tribe-tickets__commerce-checkout-paystack">

	<div id="tec-tc-gateway-paystack-payment-element" class="tribe-tickets__commerce-checkout-paytack-payment-element">

		<?php 
			$email_address = '';
			$first_name    = '';
			$last_name     = '';

			if ( is_user_logged_in() ) {

			}
		?>

		<div class="form-group">
			<label for="email">Email Address</label>
			<input type="email" id="email-address" required />
		</div>
		<div class="form-group">
			<label for="first-name">First Name</label>
			<input type="text" id="first-name" />
		</div>
		<div class="form-group">
			<label for="last-name">Last Name</label>
			<input type="text" id="last-name" />
		</div>
		
	</div>

	<div class="tec-tc-gateway-paystack-payment-selection">
		<div class="tec-tc-gateway-paystack-payment-logos">
			<img 
				src="<?php echo esc_html( PS_TEC_URL . 'assets/images/payment-logos.png' ); ?>"
				alt="><?php echo esc_html__( 'Supported payment methods.', 'event-tickets' ); ?>"
				/>
		</div>
		<button id="tec-tc-gateway-stripe-checkout-button" class="tribe-common-c-btn tribe-tickets__commerce-checkout-form-submit-button">
			<div class="spinner hidden" id="spinner"></div>
			<span id="button-text">
				<?php
				printf(
					// Translators: %1$s: Plural `Tickets` label.
					esc_html__( 'Purchase %1$s', 'event-tickets' ),
					tribe_get_ticket_label_plural( 'tickets_commerce_checkout_title' ) // phpcs:ignore
				);
				?>
			</span>
		</button>
	</div>

	

	<div id="tec-tc-gateway-paystack-payment-message" class="hidden"></div>

	<div
	id="tec-tc-gateway-payment-errors"
	class="tribe-common-b2"
	role="alert"></div>

	<?php $this->template( 'paystack/checkout/advanced-payments' ); ?>
	<?php //$this->template( 'paystack/checkout/checkout-script' ); ?>
</div>





