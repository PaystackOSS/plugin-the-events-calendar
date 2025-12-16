<?php
$selected = 'test';
$modes    = array(
	'test' => esc_html__( 'Test Mode', 'paystack-for-events-calendar' ),
	'live' => esc_html__( 'Live Mode', 'paystack-for-events-calendar' ),
);
if ( null !== $selected_mode && '' !== $selected_mode ) {
	$selected = $selected_mode;
}
?>
<p
	class="tec-tickets__admin-settings-tickets-commerce-gateway-merchant-country-container"
>
	<select
		name='tec-tickets-commerce-gateway-paystack-merchant-paystack_mode'
		class="tribe-dropdown"
		data-prevent-clear
		data-dropdown-css-width="false"
		style="width: 100%; max-width: 340px;"
	>
		<?php foreach ( $modes as $mode_key => $mode_label ) : ?>
			<option
				value="<?php echo esc_attr( $mode_key ); ?>"
				<?php selected( $mode_key === $selected ); ?>
			>
				<?php echo esc_html( $mode_label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
</p>
