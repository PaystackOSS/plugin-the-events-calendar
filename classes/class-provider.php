<?php
namespace paystack\tec\classes;

/**
 * Service provider for the Paystack Tickets Commerce Integration.
 *
 * @package TEC\Tickets\Commerce\Gateways\PayPal
 */
class Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Register the provider singletons.
	 */
	public function register() {
		require_once( PS_TEC_PATH . '/classes/class-gateway.php' );
		$this->container->singleton( Gateway::class );

		$this->register_hooks();
		//$this->register_assets();

		//$this->container->singleton( Merchant::class, Merchant::class, [ 'init' ] );

		//$this->container->singleton( Refresh_Token::class );
		////$this->container->singleton( Client::class );
		//$this->container->singleton( Signup::class );
		//$this->container->singleton( Status::class );

		//$this->container->singleton( Webhooks::class );
		//$this->container->singleton( Webhooks\Events::class );
		//$this->container->singleton( Webhooks\Handler::class );

		//$this->register_endpoints();
	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for this Service Provider
	 */
	protected function register_assets() {
		$assets = new Assets( $this->container );
		$assets->register();

		$this->container->singleton( Assets::class, $assets );
	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for this Service Provider.
	 */
	protected function register_hooks() {
		require_once( PS_TEC_PATH . '/classes/class-hooks.php' );
		$hooks = new Hooks( $this->container );
		$hooks->register();

		// Allow Hooks to be removed, by having the them registered to the container
		$this->container->singleton( Hooks::class, $hooks );
	}

	/**
	 * Register REST API endpoints.
	 */
	public function register_endpoints() {
		$hooks = new REST( $this->container );
		$hooks->register();

		// Allow Hooks to be removed, by having the them registered to the container
		$this->container->singleton( REST::class, $hooks );
	}
}
