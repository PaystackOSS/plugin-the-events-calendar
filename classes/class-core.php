<?php
namespace paystack\tec\classes;

/**
 * This class loads the other classes and function files
 *
 * @package paystack-tec-integration
 */
class Core {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \paystack\tec\classes\Core()
	 */
	protected static $instance = null;

	/**
	 * @var object \paystack\tec\classes\Setup();
	 */
	public $setup;

	/**
	 * @var object \paystack\tec\classes\Admin();
	 */
	public $admin;

	/**
	 * @var object \paystack\tec\classes\Frontend();
	 */
	public $frontend;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_classes' ) );
		//$this->load_includes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \paystack\tec\classes\Core()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	public function load_classes() {
		if ( function_exists( 'tribe' ) ) {
			$container = tribe();

			require_once( PS_TEC_PATH . '/classes/class-provider.php' );
			$provider = new Provider( $container );
			$provider->register();
		}
	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_includes() {
	}
}
