<?php
/**
 * Tickets Commerce: Checkout - Hidden Fields
 */
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
