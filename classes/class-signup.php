<?php
namespace paystack\tec\classes;

use TEC\Tickets\Commerce\Gateways\Contracts\Abstract_Signup;
use TEC\Tickets\Commerce\Gateways\Paystack\Location\Country;
use TEC\Tickets\Commerce\Settings;
use Tribe__Utils__Array as Arr;

/**
 * Class Signup
 *
 * @package paystack\tec\classes
 */
class Signup extends Abstract_Signup {

	/**
	 * @inheritDoc
	 */
	public $template_folder = 'paystack/admin-views';

	/**
	 * Request the signup link
	 *
	 * @since 5.1.9
	 *
	 * @param string $country Which country code we are generating the URL for.
	 * @param bool   $force   It prevents the system from using the cached version of the URL.
	 *
	 * @return string|false
	 */
	public function generate_url( $country, $force = false ) {
		$signup_url = PS_TEC_URL;
		return $signup_url;
	}

	/**
	 * Gets the content for the template used for the sign up link that paystack creates.
	 *
	 * @since 5.1.9
	 *
	 * @return false|string
	 */
	public function get_link_html() {
		$merchant      = tribe( Merchant::class );
		$admin_views   = tribe( 'tickets.admin.views' );
		$template_vars = array(
			'admin_views' => $admin_views,
			'merchant'    => $merchant,
		);
		return $this->get_template()->template( 'paystack/admin-views/signup-link', $template_vars, false );
	}
}
