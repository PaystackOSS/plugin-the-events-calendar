<?php
namespace paystack\tec\classes;

/**
 * Service provider for the Paystack Tickets Commerce Integration.
 */
class Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Register the provider singletons.
	 */
	public function register() {
		require_once( PS_TEC_PATH . '/classes/class-gateway.php' );
		$this->container->singleton( Gateway::class );

		
		// Register Paystack as an available payment gateway
		add_filter( 'tec_tickets_commerce_gateways', array( $this, 'register_paystack_gateway' ) );
		add_action( 'init', array( $this, 'ensure_gateway_availability' ) );

		// Register currencies with both TEC Commerce systems
		add_filter( 'tec_tickets_commerce_currency_provider_currencies', array( $this, 'register_tec_currencies' ) );
		add_filter( 'tribe_commerce_currency_supported_currencies', array( $this, 'register_legacy_currencies' ) );

		$this->register_hooks();
		$this->register_assets();

		require_once( PS_TEC_PATH . '/classes/class-merchant.php' );
		$this->container->singleton( Merchant::class, Merchant::class, array( 'init' ) );

		require_once( PS_TEC_PATH . '/classes/class-settings.php' );
		$this->container->singleton( Settings::class );
add_action( 'tribe_settings_save_tab_paystack', '\paystack\tec\classes\Settings::update_settings', 10, 1 );

		//$this->container->singleton( Refresh_Token::class );

		require_once( PS_TEC_PATH . '/classes/class-client.php' );
		$this->container->singleton( Client::class );

		require_once( PS_TEC_PATH . '/classes/class-signup.php' );
		$this->container->singleton( Signup::class );
		//$this->container->singleton( Status::class );

		//$this->container->singleton( Webhooks::class );
		//$this->container->singleton( Webhooks\Events::class );
		//$this->container->singleton( Webhooks\Handler::class );

		require_once( PS_TEC_PATH . '/classes/REST/Order_Endpoint.php' );
		require_once( PS_TEC_PATH . '/classes/class-rest.php' );
		$this->register_endpoints();
	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for this Service Provider
	 */
	protected function register_assets() {
		require_once( PS_TEC_PATH . '/classes/class-assets.php' );
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

	
	/**
	 * Register Paystack as an available gateway.
	 */
	public function register_paystack_gateway( $gateways ) {
		if ( ! isset( $gateways['paystack'] ) ) {
			$gateways['paystack'] = Gateway::class;
		}
		return $gateways;
	}

	/**
	 * Ensure gateway is available and properly registered.
	 */
	public function ensure_gateway_availability() {
		// Make sure our gateway is properly registered
		add_filter( 'tec_tickets_commerce_available_gateways', function( $available_gateways ) {
			if ( ! isset( $available_gateways['paystack'] ) ) {
				$available_gateways['paystack'] = Gateway::class;
			}
			return $available_gateways;
		});
	}

	/**
	 * Register currencies with the new TEC Commerce system.
	 *
	 * @param array $currencies Existing currencies array.
	 * @return array Modified currencies array with Paystack currencies.
	 */
	public function register_tec_currencies( $currencies ) {
		$paystack_currencies = array(
			'NGN' => array(
				'code'     => 'NGN',
				'symbol'   => '₦',
				'name'     => 'Nigerian Naira',
				'decimals' => 2,
			),
			'GHS' => array(
				'code'     => 'GHS',
				'symbol'   => '₵',
				'name'     => 'Ghanaian Cedi',
				'decimals' => 2,
			),
			'USD' => array(
				'code'     => 'USD',
				'symbol'   => '$',
				'name'     => 'US Dollar',
				'decimals' => 2,
			),
			'KES' => array(
				'code'     => 'KES',
				'symbol'   => 'KSh',
				'name'     => 'Kenyan Shilling',
				'decimals' => 2,
			),
			'ZAR' => array(
				'code'     => 'ZAR',
				'symbol'   => 'R',
				'name'     => 'South African Rand',
				'decimals' => 2,
			),
			'XOF' => array(
				'code'     => 'XOF',
				'symbol'   => 'CFA',
				'name'     => 'West African CFA Franc',
				'decimals' => 0,
			),
			'EGP' => array(
				'code'     => 'EGP',
				'symbol'   => '£',
				'name'     => 'Egyptian Pound',
				'decimals' => 2,
			),
		);

		return array_merge( $currencies, $paystack_currencies );
	}

	/**
	 * Register currencies with the legacy Tribe Commerce system.
	 *
	 * @param array $currencies Existing currencies array.
	 * @return array Modified currencies array with Paystack currencies.
	 */
	public function register_legacy_currencies( $currencies ) {
		$paystack_currencies = array(
			'NGN' => array(
				'code'   => 'NGN',
				'symbol' => '₦',
				'entity' => '&#8358;',
			),
			'GHS' => array(
				'code'   => 'GHS',
				'symbol' => '₵',
				'entity' => '₵',
			),
			'USD' => array(
				'code'   => 'USD',
				'symbol' => '$',
				'entity' => '&#36;',
			),
			'KES' => array(
				'code'   => 'KES',
				'symbol' => 'KSh',
				'entity' => 'KSh',
			),
			'ZAR' => array(
				'code'   => 'ZAR',
				'symbol' => 'R',
				'entity' => 'R',
			),
			'XOF' => array(
				'code'   => 'XOF',
				'symbol' => 'CFA',
				'entity' => 'CFA',
			),
			'EGP' => array(
				'code'   => 'EGP',
				'symbol' => '£',
				'entity' => '&#163;',
			),
		);

		return array_merge( $currencies, $paystack_currencies );
	}
}
