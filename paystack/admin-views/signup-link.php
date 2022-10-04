<div
	class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-settings"
>
	<?php
		$template_vars = array(
			'country_code' => $country_code,
		);
		$this->template( 'paystack/admin-views/fields/country-select', $template_vars );
	?>

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
