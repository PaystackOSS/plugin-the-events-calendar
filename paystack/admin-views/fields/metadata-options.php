<?php
$chosen = array();
if ( null !== $selected ) {
	$chosen = $selected;
}

$options = array(
	'order_id' => esc_html__( 'Order ID', 'ps-tec-gateway' ),
	'plugin' => esc_html__( 'Plugin', 'ps-tec-gateway' ),
	'customer_name' => esc_html__( 'Customer Name', 'ps-tec-gateway' ),
	'customer_surname' => esc_html__( 'Customer Surname', 'ps-tec-gateway' ),
	'cart_details' => esc_html__( 'Cart Details', 'ps-tec-gateway' ),
);
?>

<fieldset style="width: 100%;" id="tec-tickets-commerce-gateway-paystack-merchant-metadata" class="tribe-field tribe-field-checkbox_list tribe-size-medium">
	<legend class="tribe-field-label"><?php echo esc_html__( 'Metadata', 'ps-tec-gateway' ); ?></legend>
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