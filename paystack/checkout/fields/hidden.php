<?php
/**
 * Tickets Commerce: Checkout - Hidden Fields
 * @var array[]          $sections              [Global] Which events we have tickets for.
 */

// Check for any split or sub accounts

foreach ( $sections as $section ) {
	$has_sub_account = get_post_meta( $section, 'paystack_sub_account', true );
	$has_split_trans = get_post_meta( $section, 'paystack_split_transaction', true );
}

if ( false !== $has_sub_account && '' !== $has_sub_account ) {
	?>
	<div class="tribe-tickets__commerce-checkout-paystack-form-field-wrapper hidden">
		<input
			type="hidden"
			id="tec-paystack-sub-account"
			name="paystack-sub-account"
			autocomplete="off"
			value="<?php echo esc_attr( $has_sub_account ); ?>"
		/>
	</div>
	<?php
}

if ( false !== $has_split_trans && '' !== $has_split_trans ) {
	?>
	<div class="tribe-tickets__commerce-checkout-paystack-form-field-wrapper hidden">
		<input
			type="hidden"
			id="tec-paystack-split-transaction"
			name="paystack-split-transaction"
			autocomplete="off"
			value="<?php echo esc_attr( $has_split_trans ); ?>"
		/>
	</div>
	<?php
}

?>
<div class="tribe-tickets__commerce-checkout-paystack-form-field-wrapper hidden">
	<input
		type="hidden"
		id="tec-paystack-total"
		name="paystack-total"
		autocomplete="off"
		value="<?php echo esc_attr( $total_value->get_decimal() ); ?>"
	/>
</div>
