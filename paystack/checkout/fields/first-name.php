<?php
/**
 * Tickets Commerce: Checkout - First name field
 */

$label_classes = [
	'tribe-common-b3',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field-label',
];

$field_classes = [
	'first_name_field',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field--first-name',
];

$first_name = '';
if ( is_user_logged_in() ) {
	$user_data = get_userdata( get_current_user_id() );
	if ( isset( $user_data->first_name ) ) {
		$first_name = $user_data->first_name;
	}
}
?>
<div class="tribe-tickets__commerce-checkout-paystack-form-field-wrapper">
	<label for="tec-paystack-first-name" <?php tribe_classes( $label_classes ); ?>>
		<?php esc_html_e( 'First Name', 'event-tickets' ); ?>
	</label>
	<input
		type="text"
		id="tec-paystack-first-name"
		name="paystack-first-name"
		autocomplete="off"
		<?php tribe_classes( $field_classes ); ?>
		placeholder="<?php esc_attr_e( 'First Name', 'event-tickets' ); ?>"
		required
		value="<?php echo esc_attr( $first_name ); ?>"
	/>
</div>
