<?php
$countries = array(
	'GH' => esc_html__( 'Ghana', 'event-tickets' ),
	'NG' => esc_html__( 'Nigeria', 'event-tickets' ),
	'ZA' => esc_html__( 'South Africa', 'event-tickets' ),
);
$default_country_code  = \TEC\Tickets\Commerce\Gateways\PayPal\Location\Country::DEFAULT_COUNTRY_CODE;
$selected_country_code = $country_code;
if ( empty( $selected_country_code ) ) {
	$selected_country_code = $default_country_code;
}
?>
<div
	class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-settings"
>
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
			<option value="0"><?php esc_attr_e( 'Select your country of operation', 'event-tickets' ); ?></option>

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

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button">
		<a
			target="_blank"
			data-paystack-onboard-complete="tecTicketsCommerceGatewayPayPalSignupCallback"
			href="<?php echo esc_url( $url ) ?>&displayMode=minibrowser"
			data-paystack-button="true"
			id="connect_to_paystack"
			class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button-link"
		>
			<?php echo wp_kses( __( 'Start transacting with <i>Paystack</i>', 'event-tickets' ), 'post' ); ?>
		</a>
	</div>
</div>
