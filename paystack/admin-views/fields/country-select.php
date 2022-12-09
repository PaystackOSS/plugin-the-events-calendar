<?php
$countries = array(
	'GH' => esc_html__( 'Ghana', 'ps-tec-gateway' ),
	'NG' => esc_html__( 'Nigeria', 'ps-tec-gateway' ),
	'ZA' => esc_html__( 'South Africa', 'ps-tec-gateway' ),
);
$default_country_code  = \TEC\Tickets\Commerce\Gateways\PayPal\Location\Country::DEFAULT_COUNTRY_CODE;
$selected_country_code = $country_code;
if ( empty( $selected_country_code ) ) {
	$selected_country_code = $default_country_code;
}
?>

<p
	class="tec-tickets__admin-settings-tickets-commerce-gateway-merchant-country-container"
>
	<select
		name='tec-tickets-commerce-gateway-paystack-merchant-country'
		class="tribe-dropdown"
		data-prevent-clear
		data-dropdown-css-width="false"
		style="width: 100%; max-width: 340px;"
	>
		<option value=""><?php esc_attr_e( 'Select your country of operation', 'ps-tec-gateway' ); ?></option>

		<?php foreach ( $countries as $country_code => $country_label ) : ?>
			<option
				value="<?php echo esc_attr( $country_code ); ?>"
				<?php selected( $country_code === $selected_country_code ); ?>
			>
				<?php echo esc_html( $country_label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
</p>
