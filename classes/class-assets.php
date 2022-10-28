<?php
/**
 * Handles registering and setup for assets on Ticket Commerce.
 *
 * @since   5.1.6
 *
 * @package TEC\Tickets\Commerce\Gateways\PayPal
 */
namespace paystack\tec\classes;

use TEC\Tickets\Commerce\Checkout;

/**
 * Class Assets
 *
 * @package paystack\tec\classes;
 */
class Assets extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 5.1.6
	 */
	public function register() {
		$plugin        = \Tribe__Tickets__Main::instance();
		$gateway       = tribe( Gateway::class );
		$currency_code = \TEC\Tickets\Commerce\Utils\Currency::get_currency_code();
		$total         = \TEC\Tickets\Commerce\Utils\Value::create();

		require_once( PS_TEC_PATH . '/classes/REST/Order_Endpoint.php' );

		/**
		 * This file is intentionally enqueued on every page of the administration.
		 */
		tribe_asset(
			$plugin,
			'tec-tickets-commerce-gateway-paystack-global-admin-styles',
			PS_TEC_URL . 'assets/css/admin-settings.css',
			array(),
			'admin_enqueue_scripts',
			array()
		);

		// Paystack Inline JS
		tribe_asset(
			$plugin,
			'tec-tickets-commerce-gateway-paystack-inline',
			'https://js.paystack.co/v1/inline.js',
			array(
				'jquery',
				'tribe-common',
			),
			'tec-tickets-commerce-checkout-shortcode-assets',
			array(
				'groups' => array(
					'tec-tickets-commerce-gateway-paystack',
				),
				'conditionals' => array( $this, 'should_enqueue_assets' ),
			)
		);

		// Get the correct Public key
		$mode = $gateway->get_option( 'paystack_mode' );
		if ( 'test' === $mode ) {
			$public_key = $gateway->get_option( 'public_key_test' );
		} else {
			$public_key = $gateway->get_option( 'public_key_live' );
		}

		tribe_asset(
			$plugin,
			'tec-tickets-commerce-gateway-paystack-checkout',
			PS_TEC_URL . 'assets/js/checkout.js',
			array(
				'jquery',
				'tribe-common',
				'tribe-tickets-loader',
				'tribe-tickets-commerce-js',
				'tribe-tickets-commerce-notice-js',
				'tribe-tickets-commerce-base-gateway-checkout-toggler',
				'tec-tickets-commerce-gateway-paystack-inline',
				'jquery-ui-datepicker',
			),
			'tec-tickets-commerce-checkout-shortcode-assets',
			array(
				'groups' => array(
					'tec-tickets-commerce-gateway-paystack',
				),
				'conditionals' => array( $this, 'should_enqueue_assets' ),
				'localize'     => array(
					'name' => 'tecTicketsPaystackCheckout',
					'data' => array(
						'orderEndpoint' => tribe( \paystack\tec\classes\REST\Order_Endpoint::class )->get_route_url(),
						'publicKey'     => $public_key,
						'currency_code' => $currency_code,
						'errorMessages' => array(
							'first_name'    => esc_html__( 'First name is required', 'event-tickets' ),
							'last_name'     => esc_html__( 'Last name is required', 'event-tickets' ),
							'email_address' => esc_html__( 'A valid email address is required', 'event-tickets' ),
							'connection'    => esc_html__( 'An error has occured, please refresh the page and try again.', 'event-tickets' ),
						),
					),
				),
			)
		);
	}

	/**
	 * Define if the assets for `PayPal` should be enqueued or not.
	 *
	 * @since 5.1.10
	 *
	 * @return bool If the `PayPal` assets should be enqueued or not.
	 */
	public function should_enqueue_assets() {
		return tribe( Checkout::class )->is_current_page() && tribe( Gateway::class )->is_enabled() && tribe( Gateway::class )->is_active();
	}

	/**
	 * Define if the assets for `PayPal` should be enqueued or not.
	 *
	 * @since 5.1.10
	 *
	 * @return bool If the `PayPal` assets should be enqueued or not.
	 */
	public function should_enqueue_assets_payments_tab() {
		return 'paystack' === tribe_get_request_var( 'tc-section' ) && 'payments' === tribe_get_request_var( 'tab' ) && \Tribe\Tickets\Admin\Settings::$settings_page_id === tribe_get_request_var( 'page' );
	}
}
