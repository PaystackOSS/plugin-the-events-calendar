<?php
namespace paystack\tec\classes;

/**
 * Frontend Classes
 *
 * @package paystack-tec-integration
 */
class Frontend {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \paystack\tec\classes\Frontend()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \paystack\tec\classes\Frontend()    A single instance of this class.
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
}
