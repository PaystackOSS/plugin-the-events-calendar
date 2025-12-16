<?php
/**
 * Handles hooking all the actions and filters used by the module.
 */

namespace paystack\tec\classes;

use paystack\tec\classes\Gateway;


/**
 * Class Hooks.
 *
 * @since   5.1.6
 *
 * @package paystack\tec\classes
 */
class Hooks extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 5.1.6
	 */
	public function register() {
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Adds the actions required by each Tickets Commerce component.
	 *
	 * @since 5.1.6
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
		add_action( 'admin_notices', array( $this, 'filter_admin_notices' ) );
	}

	/**
	 * Adds the filters required by each Tickets Commerce component.
	 *
	 * @since 5.1.6
	 */
	protected function add_filters() {
		add_filter( 'tec_tickets_commerce_gateways', array( $this, 'filter_add_gateway' ), 10, 2 );
	}

	/**
	 * Register the Endpoints from Paystack.
	 *
	 * @since 5.1.9
	 */
	public function register_endpoints() {
		$this->container->make( REST::class )->register_endpoints();
	}

	/**
	 * Add this gateway to the list of available.
	 *
	 * @since 5.1.6
	 *
	 * @param array $gateways List of available gateways.
	 *
	 * @return array
	 */
	public function filter_add_gateway( array $gateways = [] ) {
		return $this->container->make( Gateway::class )->register_gateway( $gateways );
	}

	/**
	 * Filter admin notices.
	 *
	 * @since 5.3.2
	 *
	 * @param array $notices Array of admin notices.
	 *
	 * @return array
	 */
	public function filter_admin_notices() {
		return $this->container->make( Gateway::class )->filter_admin_notices();
	}
}
