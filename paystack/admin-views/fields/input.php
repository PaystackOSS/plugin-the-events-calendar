<?php
$selected = '';
if ( null !== $value && '' !== $value ) {
	$selected = $value;
}
?>
<p
	class="tec-tickets__admin-settings-tickets-commerce-gateway-merchant-<?php echo esc_attr( $name ); ?>-container"
>
	<input
		type="<?php echo esc_attr( $type ); ?>"
		name='tec-tickets-commerce-gateway-paystack-merchant-<?php echo esc_attr( $name ); ?>'
		class="tribe-input <?php echo esc_attr( $css_class ); ?>"
		style="width: 100%; max-width: 340px;"
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
		value="<?php echo esc_attr( $selected ); ?>"
		<?php
		if ( 'password' === $type ) {
			echo wp_kses_post( 'onclick="paystackAdmin.switchPassword( \'tec-tickets-commerce-gateway-paystack-merchant-' . $name . '\' )"' );
		}
		?>
	/>

	<?php
	if ( 'password' === $type ) {
		echo wp_kses_post( '<span class="dashicons dashicons-visibility" style="margin-top: 12px;margin-left: -40px;cursor: pointer;background-color: white;padding-left: 10px;"></span>' );
	}
	?>
</p>