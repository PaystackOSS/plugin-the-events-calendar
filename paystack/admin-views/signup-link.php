<div
	class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-settings"
>
	<?php
		$country_vars = array(
			'country_code' => $merchant->get_prop( 'country' ),
		);
		$this->template( 'paystack/admin-views/fields/country-select', $country_vars );

		$mode_vars = array(
			'selected_mode' => 'test',
		);
		$this->template( 'paystack/admin-views/fields/mode-select', $mode_vars );
	?>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button">
		<a
			target="_blank"
			href="<?php echo esc_url( $url ) ?>"
			id="connect_to_paystack"
			class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button-link"
		>
			<?php echo wp_kses( __( 'Start transacting with <i>Paystack</i>', 'event-tickets' ), 'post' ); ?>
		</a>
	</div>
</div>
