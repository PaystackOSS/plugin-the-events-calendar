<?php
/**
 * Handles registering and setup for assets on Ticket Commerce.
 *
 * @since   5.1.6
 *
 * @package TEC\Tickets\Commerce\Gateways\PayPal
 */
namespace paystack\tec\classes;

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
