<?php
namespace paystack\tec\classes;

/**
 * Setup Class
 *
 * @package paystack-tec-integration
 */
class Setup {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \paystack\tec\classes\Setup()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'tribe_template_path_list', array( $this, 'filter_template_path_list' ), 15, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \paystack\tec\classes\Setup()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Filters the list of folders TEC will look up to find templates to add the ones defined by Tickets.
	 *
	 * @since 4.10.9
	 *
	 * @param array           $folders  The current list of folders that will be searched template files.
	 * @param Tribe__Template $template Which template instance we are dealing with.
	 *
	 * @return array The filtered list of folders that will be searched for the templates.
	 */
	public function filter_template_path_list( array $folders, \Tribe__Template $template ) {
		/** @var Plugin $main */
		$path = (array) rtrim( PS_TEC_PATH, '/' );

		// Pick up if the folder needs to be added to the public template path.
		$folder = array( '' );

		if ( ! empty( $folder ) ) {
			$path = array_merge( $path, $folder );
		}

		$folders['event-tickets-paystack'] = array(
			'id'        => 'event-tickets-paystack',
			'namespace' => 'paystack\tec',
			'priority'  => 20,
			'path'      => implode( DIRECTORY_SEPARATOR, $path ),
		);

		return $folders;
	}
}
