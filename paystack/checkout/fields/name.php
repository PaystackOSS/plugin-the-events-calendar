<?php
/**
 * Tickets Commerce: Checkout - First name field
 */
$first_name = '';
if ( is_user_logged_in() ) {
	$user_data = get_userdata( get_current_user_id() );
	if ( isset( $user_data->first_name ) ) {
		$first_name = $user_data->first_name;
	}
}

$label_classes = [
	'tribe-tickets__form-field-label',
	'tribe-tickets__commerce-checkout-purchaser-info-name-field-label',
];

$field_classes = [
	'tribe-tickets__commerce-checkout-purchaser-info-form-field',
	'tribe-tickets__commerce-checkout-purchaser-info-form-field-name',
	'tribe-common-form-control-text__input',
	'tribe-tickets__form-field-input',
];
?>
<div class="tribe-tickets__commerce-checkout-purchaser-info-field tribe-tickets__form-field tribe-tickets__form-field--text">
	<input
		type="text"
		id="tec-tc-purchaser-name"
		name="purchaser-name"
		autocomplete="off"
		<?php tribe_classes( $field_classes ); ?>
		placeholder="<?php esc_attr_e( 'Name', 'paystack-for-events-calendar' ); ?>"
		required
		value="<?php echo esc_attr( $first_name ); ?>"
	/>
</div>
