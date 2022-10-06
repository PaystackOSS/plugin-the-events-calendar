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
		$plugin = \Tribe__Tickets__Main::instance();
		/**
		 * This file is intentionally enqueued on every page of the administration.
		 */

		tribe_asset(
			$plugin,
			'tec-tickets-commerce-gateway-paystack-global-admin-styles',
			PS_TEC_URL . 'assets/css/admin-settings.css',
			[],
			'admin_enqueue_scripts',
			[]
		);

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
			),
			'tec-tickets-commerce-checkout-shortcode-assets',
			array(
				'groups'       => array(
					'tec-tickets-commerce-gateway-paystack',
				),
				'conditionals' => array( $this, 'should_enqueue_assets' ),
				'localize'     => array(
					'name' => 'tecTicketsCommerceGatewayPayPalCheckout',
					'data' => static function () {
						return array(
							//'orderEndpoint' => tribe( Order_Endpoint::class )->get_route_url(),
							'advancedPayments' => array(
								'fieldPlaceholders' => array(
									'cvv' => esc_html__( 'E.g.: 123', 'event-tickets' ),
									'expirationDate' => esc_html__( 'E.g.: 03/26', 'event-tickets' ),
									'number' => esc_html__( 'E.g.: 4111 1111 1111 1111', 'event-tickets' ),
									'zipCode' => esc_html__( 'E.g.: 01020', 'event-tickets' ),
								),
							),
						);
					},
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
