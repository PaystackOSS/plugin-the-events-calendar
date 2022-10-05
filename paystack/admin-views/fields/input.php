<?php
$selected = '';
if ( null !== $value && '' !== $value ) {
	$selected = $value;
}
?>
<p
	class="tec-tickets__admin-settings-tickets-commerce-gateway-merchant-country-container"
>
	<input
		name='tec-tickets-commerce-gateway-paystack-merchant-<?php echo esc_attr( $name ); ?>'
		class="tribe-input <?php echo esc_attr( $css_class ); ?>"
		style="width: 100%; max-width: 340px;"
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
		value="<?php echo esc_attr( $selected ); ?>"
	/>
</p>