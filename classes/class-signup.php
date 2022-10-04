<?php
namespace paystack\tec\classes;

use TEC\Tickets\Commerce\Gateways\Contracts\Abstract_Signup;
use TEC\Tickets\Commerce\Gateways\PayPal\Location\Country;
use TEC\Tickets\Commerce\Settings;
use Tribe__Utils__Array as Arr;

/**
 * Class Signup
 *
 * @package paystack\tec\classes
 */
class Signup extends Abstract_Signup {

	/**
	 * Holds the transient key used to store hash passed to paystack.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	public static $signup_hash_meta_key = 'tec_tc_paystack_signup_hash';

	/**
	 * Holds the transient key used to link paystack to this site.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	public static $signup_data_meta_key = 'tec_tc_paystack_signup_data';

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
		$country       = tribe( Country::class )->get_setting();
		$template_vars = [
			'url'          => $this->generate_url( $country ),
			'country_code' => $country,
		];
		return $this->get_template()->template( 'paystack/admin-views/signup-link', $template_vars, false );
	}
}
