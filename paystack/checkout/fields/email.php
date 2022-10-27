<?php
/**
 * Tickets Commerce: Checkout - Email Address field
 */

$label_classes = [
	'tribe-common-b3',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field-label',
];

$field_classes = [
	'email_address_field',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field--email-address',
];

$email_address = '';
if ( is_user_logged_in() ) {
	$user_data = get_userdata( get_current_user_id() );
	if ( isset( $user_data->user_email ) ) {
		$email_address = $user_data->user_email;
	}
}
?>
<div class="tribe-tickets__commerce-checkout-paystack-form-field-wrapper">
	<label for="tec-paystack-email-address" <?php tribe_classes( $label_classes ); ?>>
		<?php esc_html_e( 'Email Address', 'event-tickets' ); ?>
	</label>
	<input
		type="text"
		id="tec-paystack-email-address"
		name="paystack-email-address"
		autocomplete="off"
		<?php tribe_classes( $field_classes ); ?>
		placeholder="<?php esc_attr_e( 'Email Address', 'event-tickets' ); ?>"
		required
		value="<?php echo esc_attr( $email_address ); ?>"
	/>
</div>
