<?php
/**
 * Tickets Commerce: Checkout - Email Address field
 */

$label_classes = [
	'tribe-tickets__form-field-label',
	'tribe-tickets__commerce-checkout-purchaser-info-email-field-label',
];

$field_classes = [
	'tribe-common-b2',
	'tribe-tickets__commerce-checkout-purchaser-info-form-field',
	'tribe-tickets__commerce-checkout-purchaser-info-form-field-email',
	'tribe-common-form-control-text__input',
	'tribe-tickets__form-field-input',
];

$email_address = '';
if ( is_user_logged_in() ) {
	$user_data = get_userdata( get_current_user_id() );
	if ( isset( $user_data->user_email ) ) {
		$email_address = $user_data->user_email;
	}
}
?>
<div class="tribe-tickets__commerce-checkout-purchaser-info-field tribe-tickets__form-field tribe-tickets__form-field--email">
	<div class="tribe-tickets__form-field-input-wrapper">
		<input
			type="email"
			id="tec-tc-purchaser-email"
			name="purchaser-email"
			autocomplete="off"
			<?php tribe_classes( $field_classes ); ?>
			required
			value="<?php echo esc_attr( $email_address ); ?>"
		/>
	</div>
</div>
