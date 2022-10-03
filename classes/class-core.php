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
		$this->load_includes();
		$this->load_classes();
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
	private function load_classes() {
	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_includes() {
	}
}
