<?php
/**
 * Tickets Commerce: Checkout - Last name field
 */

$label_classes = [
	'tribe-common-b3',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field-label',
];

$field_classes = [
	'last_name_field',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field',
	'tribe-tickets__commerce-checkout-paystack-advanced-payments-form-field--last-name',
];

$last_name = '';
if ( is_user_logged_in() ) {
	$user_data = get_userdata( get_current_user_id() );
	if ( isset( $user_data->last_name ) ) {
		$last_name = $user_data->last_name;
	}
}
?>
<div class="tribe-tickets__commerce-checkout-paystack-form-field-wrapper">
	<label for="tec-paystack-last-name" <?php tribe_classes( $label_classes ); ?>>
		<?php esc_html_e( 'Last Name', 'event-tickets' ); ?>
	</label>
	<input
		type="text"
		id="tec-paystack-last-name"
		name="paystack-last-name"
		autocomplete="off"
		<?php tribe_classes( $field_classes ); ?>
		placeholder="<?php esc_attr_e( 'Last Name', 'event-tickets' ); ?>"
		required
		value="<?php echo esc_attr( $last_name ); ?>"
	/>
</div>
