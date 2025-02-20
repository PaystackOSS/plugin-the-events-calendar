<?php
/*
 * Plugin Name:	Paystack for The Events Calendar
 * Plugin URI:	https://github.com/PaystackOSS/plugin-the-events-calendar
 * Description:	Add-on for The Event Calendar that allows you to accept payments for event tickets via Paystack
 * Author:		Paystack
 * Version: 	1.0.7
 * Author URI: 	https://paystack.com/
 * License: 	GPL3
 * Text Domain: paystack-for-events-calendar
 * Domain Path: /languages/
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PS_TEC_PATH', plugin_dir_path( __FILE__ ) );
define( 'PS_TEC_CORE', __FILE__ );
define( 'PS_TEC_URL', plugin_dir_url( __FILE__ ) );
define( 'PS_TEC_VER', '1.0.7' );

/* ======================= Below is the Plugin Class init ========================= */
require_once PS_TEC_PATH . '/classes/class-core.php';

/**
 * Plugin kicks off with this function.
 *
 * @return void
 */
function ps_tec() {
	return \paystack\tec\classes\Core::get_instance();
}
ps_tec();
