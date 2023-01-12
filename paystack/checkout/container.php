<?php
/**
 * Tickets Commerce: Paystack Checkout container
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/commerce/gateway/paystack/container.php
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
 */

if ( $must_login ) {
	return;
}

//	$sub_totals = \TEC\Tickets\Commerce\Utils\Value::build_list( array_filter( wp_list_pluck( $items, 'sub_total' ) ) );
?>
<div class="tribe-tickets__commerce-checkout-gateway tribe-tickets__commerce-checkout-paystack">
	<div class="tec-tc-gateway-paystack-payment-selection">

		<div id="tec-tc-gateway-paystack-payment-element" class="tribe-tickets__commerce-checkout-paytack-payment-element">
			<br />
			<?php
			if ( is_user_logged_in() || $must_login || empty( $items ) ) {
				$this->template( 'paystack/checkout/fields/name' );
				$this->template( 'paystack/checkout/fields/email' );
			}
			$this->template( 'paystack/checkout/fields/hidden' );
			?>
		</div>

		<button id="tec-tc-gateway-stripe-checkout-button" class="tribe-common-c-btn tribe-tickets__commerce-checkout-form-submit-button">
			<div class="spinner hidden" id="spinner"></div>
			<span id="button-text">
				<?php
				printf(
					// Translators: %1$s: Plural `Tickets` label.
					esc_html__( 'Purchase %1$s', 'paystack-for-events-calendar' ),
					esc_html( tribe_get_ticket_label_plural( 'tickets_commerce_checkout_title' ) ),
				);
				?>
			</span>
		</button>

		<div class="tec-tc-gateway-paystack-payment-logos">
			<img 
				src="<?php echo esc_html( PS_TEC_URL . 'assets/images/payment-logos.png' ); ?>"
				alt="><?php echo esc_html__( 'Supported payment methods.', 'paystack-for-events-calendar' ); ?>"
				style="max-height: 72px;margin: 30px auto 0;"
				/>
		</div>
	</div>

	<div id="tec-tc-gateway-paystack-payment-message" class="hidden"></div>

	<div
	id="tec-tc-gateway-payment-errors"
	class="tribe-common-b2"
	role="alert"></div>
</div>





