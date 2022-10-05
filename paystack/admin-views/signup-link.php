<div class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-settings">
	<?php
		$country_vars = array(
			'country_code' => $merchant->get_prop( 'country' ),
		);
		$this->template( 'paystack/admin-views/fields/country-select', $country_vars );

		$mode_vars = array(
			'selected_mode' => $merchant->get_prop( 'country' ),
		);
		$this->template( 'paystack/admin-views/fields/mode-select', $mode_vars );
	?>

	<?php
	$hide_test_keys = '';
	if ( 'live' === $merchant->get_prop( 'paystack_mode' ) ) {
		$hide_test_keys = 'hidden';
	}
	?>
	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-test-keys <?php echo esc_attr( $hide_test_keys ); ?>">
		<?php
			$secret_test_args = array(
				'name'        => 'secret_key_test',
				'value'       => $merchant->get_prop( 'secret_key_test' ),
				'css_class'   => '',
				'placeholder' => __( 'Secret Key (test)', 'event-tickets' ),
			);
			$this->template( 'paystack/admin-views/fields/input', $secret_test_args );

			$public_test_args = array(
				'name'        => 'public_key_test',
				'value'       => $merchant->get_prop( 'public_key_test' ),
				'css_class'   => '',
				'placeholder' => __( 'Public Key (test)', 'event-tickets' ),
			);
			$this->template( 'paystack/admin-views/fields/input', $public_test_args );
		?>
	</div>

	<?php
	$hide_live_keys = '';
	if ( 'test' === $merchant->get_prop( 'paystack_mode' ) || null === $merchant->get_prop( 'paystack_mode' ) || '' === $merchant->get_prop( 'paystack_mode' ) ) {
		$hide_live_keys = 'hidden';
	}
	?>
	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-live-keys <?php echo esc_attr( $hide_live_keys ); ?>">
		<?php
			$secret_live_args = array(
				'name'        => 'secret_key_live',
				'value'       => $merchant->get_prop( 'secret_key_live' ),
				'css_class'   => '',
				'placeholder' => __( 'Secret Key (live)', 'event-tickets' ),
			);
			$this->template( 'paystack/admin-views/fields/input', $secret_live_args );

			$public_live_args = array(
				'name'        => 'public_key_live',
				'value'       => $merchant->get_prop( 'public_key_live' ),
				'css_class'   => '',
				'placeholder' => __( 'Public Key (live)', 'event-tickets' ),
			);
			$this->template( 'paystack/admin-views/fields/input', $public_live_args );
		?>
	</div>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button">
		<input 
			id="connect_to_paystack" 
			class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button-link" 
			type="submit" 
			name="tribeSaveSettings" 
			value="<?php echo wp_kses( __( 'Start transacting with Paystack', 'event-tickets' ), 'post' ); ?>"
		/>
	</div>
</div>
