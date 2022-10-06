<?php
$selected = '';
$modes      = array(
	''      => esc_html__( 'Select a transaction type', 'event-tickets' ),
	'split' => esc_html__( 'Split Code', 'event-tickets' ),
	'sub'   => esc_html__( 'Subaccount', 'event-tickets' ),
);
if ( null !== $selected_mode && '' !== $selected_mode ) {
	$selected = $selected_mode;
}
?>
<p
	class="tec-tickets__admin-settings-tickets-commerce-gateway-merchant-transaction_type-container"
>
<fieldset 
	id="tribe-field-tickets-commerce-transaction_type"
	class="tribe-field tribe-field-dropdown tribe-size-medium tribe-dependent"
	data-depends="#tickets_commerce_enabled-input"
	data-condition-is-checked="">

	<legend class="tribe-field-label"><?php echo esc_html__( 'Transaction type', 'event-tickets' ); ?></legend>
	<div class="tribe-field-wrap">
		<select name="tec-tickets-commerce-gateway-paystack-merchant-transaction_type" id="tickets-commerce-transaction_type-select" class="tribe-dropdown" data-prevent-clear="true">
			<?php foreach ( $modes as $mode_key => $mode_label ) : ?>
				<option
					value="<?php echo esc_attr( $mode_key ); ?>"
					<?php selected( $mode_key === $selected ); ?>
				>
					<?php echo esc_html( $mode_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
</fieldset>
