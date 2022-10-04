<?php
/*
 * Plugin Name:	Paystack Gateway for The Events Calendar
 * Plugin URI:	https://github.com/PaystackOSS/plugin-the-events-calendar
 * Description:	Allow users to pay for events via Paystack
 * Author:		LightSpeed
 * Version: 	1.0.0
 * Author URI: 	https://www.lsdev.biz/
 * License: 	GPL3
 * Text Domain: ps-tec-integration
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PS_TEC_PATH', plugin_dir_path( __FILE__ ) );
define( 'PS_TEC_CORE', __FILE__ );
define( 'PS_TEC_URL', plugin_dir_url( __FILE__ ) );
define( 'PS_TEC_VER', '1.0.0' );

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