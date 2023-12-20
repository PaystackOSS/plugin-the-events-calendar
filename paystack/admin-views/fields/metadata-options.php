<?php
$chosen = array();
if ( null !== $selected ) {
	$chosen = $selected;
}

$options = array(
	'order_id' => esc_html__( 'Order ID', 'paystack-for-events-calendar' ),
	'customer_name' => esc_html__( 'Customer Name', 'paystack-for-events-calendar' ),
	//'customer_surname' => esc_html__( 'Customer Surname', 'paystack-for-events-calendar' ),
	'cart_details' => esc_html__( 'Cart Details', 'paystack-for-events-calendar' ),
);
?>

<fieldset style="width: 100%;" id="tec-tickets-commerce-gateway-paystack-merchant-metadata" class="tribe-field tribe-field-checkbox_list tribe-size-medium">
	<legend class="tribe-field-label"><?php echo esc_html__( 'Metadata', 'paystack-for-events-calendar' ); ?></legend>
	<div class="tribe-field-wrap">
		<?php
		foreach ( $options as $key => $label ) {
			$checked = '';
			if ( in_array( $key, $chosen ) ) {
				$checked = 'checked="checked"';
			}
			?>
				<label>
					<input type="checkbox" name="tec-tickets-commerce-gateway-paystack-merchant-metadata[]" value="<?php echo esc_attr( $key ); ?>" <?php echo $checked; ?>>
					<?php echo esc_attr( $label ); ?>
				</label>
			<?php
		}
		?>
	</div>
</fieldset>