<?php
$selected = 'popup';
$modes    = array(
	'popup'    => esc_html__( 'Popup', 'ps-tec-gateway' ),
	'redirect' => esc_html__( 'Redirect', 'ps-tec-gateway' ),
);
if ( null !== $selected_mode && '' !== $selected_mode ) {
	$selected = $selected_mode;
}
?>

<fieldset 
	id="tribe-field-tickets-commerce-checkout_mode"
	class="tribe-field tribe-field-dropdown tribe-size-medium tribe-dependent"
	data-depends="#tickets_commerce_enabled-input"
	data-condition-is-checked="">

	<legend class="tribe-field-label"><?php echo esc_html__( 'Checkout Mode', 'ps-tec-gateway' ); ?></legend>
	<div class="tribe-field-wrap">
		<select name="tec-tickets-commerce-gateway-paystack-merchant-checkout_mode" id="tickets-commerce-checkout_mode-select" class="tribe-dropdown" data-prevent-clear="true">
			<?php foreach ( $modes as $mode_key => $mode_label ) : ?>
				<option
					value="<?php echo esc_attr( $mode_key ); ?>"
					<?php selected( $mode_key === $selected ); ?>
				>
					<?php echo esc_html( $mode_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>

		<label class="screen-reader-text"><?php echo esc_html__( 'Pop up or redirect.', 'ps-tec-gateway' ); ?></label>
		<p class="tooltip description"><?php echo esc_html__( 'Pop up or redirect.', 'ps-tec-gateway' ); ?></p>
	</div>
</fieldset>

