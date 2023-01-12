<?php
/**
 * The Template for displaying the Tickets Commerce Paystack Settings when inactive (not connected).
 *
 * @var Tribe__Tickets__Admin__Views                  $this                  [Global] Template object.
 * @var paystack\tec\classes\Merchant $merchant              [Global] The merchant class.
 * @var paystack\tec\classes\Signup   $signup                [Global] The Signup class.
 */
?>

<h2 class="tec-tickets__admin-settings-tickets-commerce-gateway-title">
	<?php esc_html_e( 'Accept online payments!', 'paystack-for-events-calendar' ); ?>
</h2>

<div class="tec-tickets__admin-settings-tickets-commerce-gateway-description">
	<p>
		<?php esc_html_e( 'Start selling tickets to your events today with Paystack and let your customers pay you with their choice of payment methods.', 'paystack-for-events-calendar' ); ?>
	</p>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-signup-links">
		<?php
		echo wp_kses(
			$signup->get_link_html(),
			array(
				'select' => array(
					'name'  => array(),
					'class' => array(),
					'data-prevent-clear' => array(),
					'data-dropdown-css-width' => array(),
					'style' => array(),
				),
				'option' => array(
					'value' => array(),
					'selected' => array(),
				),
				'div' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
				),
				'span' => array(
					'class' => array(),
					'style' => array(),
				),
				'input' => array(
					'name'  => array(),
					'class' => array(),
					'type' => array(),
					'placeholder' => array(),
					'style' => array(),
					'value' => array(),
					'onclick' => array(),
				),
			)
		);
		?>
	</div>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-links">
		<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link">
			<?php $this->template( 'components/icons/lightbulb' ); ?>
			<a
				href="https://dashboard.paystack.co/#/settings/developer"
				target="_blank"
				rel="noopener noreferrer"
				class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link-url"
			><?php esc_html_e( 'Get your API Keys here', 'paystack-for-events-calendar' ); ?></a>
		</div>

		<div class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link">
			<?php $this->template( 'components/icons/lightbulb' ); ?>
			<a
				href="https://www.youtube.com/watch?v=gWfoN_OydHE"
				target="_blank"
				rel="noopener noreferrer"
				class="tec-tickets__admin-settings-tickets-commerce-gateway-help-link-url"
			><?php esc_html_e( 'Learn more about configuring Paystack', 'paystack-for-events-calendar' ); ?></a>
		</div>
	</div>
</div>
