<?php

namespace paystack\tec\classes;

use WP_REST_Server;

/**
 * Class REST
 *
 * @package paystack\tec\classes
 */
class REST extends \tad_DI52_ServiceProvider {
	public function register() {
		$this->container->singleton( REST\Order_Endpoint::class );
	}

	/**
	 * Register the endpoints for handling webhooks.
	 *
	 */
	public function register_endpoints() {
		$this->container->make( REST\Order_Endpoint::class )->register();
	}
}
