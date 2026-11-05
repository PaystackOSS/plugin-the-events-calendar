<div class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-settings">
	<?php
		$mode_vars = array(
			'selected_mode' => $merchant->get_prop( 'paystack_mode' ),
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
				'placeholder' => __( 'Test Secret Key', 'paystack-for-events-calendar' ),
				'type'        => 'password',
			);
			$this->template( 'paystack/admin-views/fields/input', $secret_test_args );

			$public_test_args = array(
				'name'        => 'public_key_test',
				'value'       => $merchant->get_prop( 'public_key_test' ),
				'css_class'   => '',
				'placeholder' => __( 'Test Public Key', 'paystack-for-events-calendar' ),
				'type'        => 'text',
			);
			$this->template( 'paystack/admin-views/fields/input', $public_test_args );

			$webhook_secret_test_args = array(
				'name'        => 'webhook_secret_test',
				'value'       => $merchant->get_prop( 'webhook_secret_test' ),
				'css_class'   => '',
				'placeholder' => __( 'Test Webhook Signing Secret', 'paystack-for-events-calendar' ),
				'type'        => 'password',
			);
			$this->template( 'paystack/admin-views/fields/input', $webhook_secret_test_args );
		?>
		<p class="description"><?php esc_html_e( 'Find your webhook signing secret in your Paystack Dashboard under Settings > API Keys & Webhooks. It is required if you use a restricted key (rk_).', 'paystack-for-events-calendar' ); ?></p>
		<?php
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
				'placeholder' => __( 'Live Secret Key', 'paystack-for-events-calendar' ),
				'type'        => 'password',
			);
			$this->template( 'paystack/admin-views/fields/input', $secret_live_args );

			$public_live_args = array(
				'name'        => 'public_key_live',
				'value'       => $merchant->get_prop( 'public_key_live' ),
				'css_class'   => '',
				'placeholder' => __( 'Live Public Key', 'paystack-for-events-calendar' ),
				'type'        => 'text',
			);
			$this->template( 'paystack/admin-views/fields/input', $public_live_args );

			$webhook_secret_live_args = array(
				'name'        => 'webhook_secret_live',
				'value'       => $merchant->get_prop( 'webhook_secret_live' ),
				'css_class'   => '',
				'placeholder' => __( 'Live Webhook Signing Secret', 'paystack-for-events-calendar' ),
				'type'        => 'password',
			);
			$this->template( 'paystack/admin-views/fields/input', $webhook_secret_live_args );
		?>
		<p class="description"><?php esc_html_e( 'Find your webhook signing secret in your Paystack Dashboard under Settings > API Keys & Webhooks. It is required if you use a restricted key (rk_).', 'paystack-for-events-calendar' ); ?></p>
		<?php
		?>
	</div>

	<?php
		if ( ! $merchant->is_connected() ) {
			?>
			<div class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button">
				<input 
					id="connect_to_paystack" 
					class="tec-tickets__admin-settings-tickets-commerce-gateway-connect-button-link" 
					type="submit" 
					name="tribeSaveSettings" 
					value="<?php echo wp_kses( __( 'Start transacting with Paystack', 'paystack-for-events-calendar' ), 'post' ); ?>"
				/>
			</div>
			<?php
		}
	?>

</div>
