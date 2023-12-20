<?php
namespace paystack\tec\classes;

use TEC\Tickets\Commerce\Gateways\Contracts\Abstract_Settings;

use Tribe__Languages__Locations;
use Tribe__Tickets__Admin__Views;
use Tribe__Tickets__Main;

/**
 * The paystack Commerce specific settings.
 *
 * @package paystack\tec\classes;
 */
class Settings extends Abstract_Settings {

	/**
	 * @inheritDoc
	 */
	public static $option_sandbox = 'tickets-commerce-paystack-sandbox';

	/**
	 * The prefix for the fields.
	 *
	 * @var string
	 */
	public static $field_prefix = 'tec-tickets-commerce-gateway-paystack-merchant-';

	/**
	 * The fields for the settings page, used to save the Data.
	 *
	 * @var array
	 */
	public static $fields = array(
		'country'          => '',
		'paystack_mode'    => 'test',
		'secret_key_test'  => '',
		'public_key_test'  => '',
		'secret_key_live'  => '',
		'public_key_live'  => '',
		'checkout_mode'    => 'popup',
		'transaction_type' => '',
		'metadata'         => array(),
	);

	/**
	 * @inheritDoc
	 */
	public function get_settings() {
		return [
			'tickets-commerce-paystack-commerce-configure' => [
				'type'            => 'wrapped_html',
				'html'            => $this->get_connection_settings_html(),
				'validation_type' => 'html',
			],
		];
	}

	/**
	 * Get the paystack Commerce Connection Settings section.
	 *
	 * @since 5.1.6
	 *
	 * @return string The paystack Commerce introduction section.
	 */
	public function get_connection_settings_html() {
		/** @var Tribe__Tickets__Admin__Views $admin_views */
		$admin_views = tribe( 'tickets.admin.views' );
		$merchant    = tribe( Merchant::class );
		$signup      = tribe( Signup::class );

		$context = array(
			'plugin_url'            => PS_TEC_URL,
			'merchant'              => $merchant,
			'is_merchant_connected' => $merchant->is_connected(),
			'is_merchant_active'    => $merchant->is_active(),
			'signup'                => $signup,
			'gateway_key'           => tribe( Gateway::class )->get_key(),
		);

		$admin_views->add_template_globals( $context );

		return $admin_views->template( 'paystack/admin-views/main', $context, false );
	}

	/**
	 * Get the formatted error HTML.
	 *
	 * @since 5.1.6
	 *
	 * @param array $errors The list of errors.
	 *
	 * @return string The formatted error HTML.
	 */
	public function get_formatted_error_html( $errors ) {
		// If there are no errors, return an empty string.
		if ( empty( $errors ) ) {
			return '';
		}

		$formatted_errors = $this->format_errors( $errors );

		// There were no errors to show.
		if ( empty( $formatted_errors ) ) {
			return '';
		}

		$is_single_error = 1 === count( $formatted_errors );

		foreach ( $formatted_errors as &$formatted_error ) {
			$formatted_error = sprintf(
				'<%1$s>%2$s</%1$s>',
				$is_single_error ? 'p' : 'li',
				$formatted_error
			);
		}

		$output = implode( "\n", $formatted_errors );

		// Wrap multiple errors in a ul.
		if ( ! $is_single_error ) {
			$output = sprintf(
				'<ul class="ul-disc">%1$s</ul>',
				$output
			);
		}

		return $output;
	}

	/**
	 * Format the list of errors.
	 *
	 * @since 5.1.6
	 *
	 * @param array $errors The list of errors to format.
	 *
	 * @return array The list of formatted errors.
	 */
	private function format_errors( $errors ) {
		$formatted_errors = [];

		foreach ( $errors as $error ) {
			if ( is_array( $error ) ) {
				switch ( $error['type'] ) {
					case 'url':
						$error = sprintf(
							'%1$s<br><code>%2$s</code>',
							$error['message'],
							urldecode_deep( $error['value'] )
						);
						break;

					case 'json':
						$error = sprintf(
							'%1$s<br><code>%2$s</code>',
							$error['message'],
							$error['value']
						);
						break;

					default:
						// This is an unrecognized error.
						$error = null;
						break;
				}
			}

			// If there is no error, just return empty.
			if ( empty( $error ) ) {
				continue;
			}

			$formatted_errors[] = $error;
		}

		return $formatted_errors;
	}

	/**
	 * Updates the country account
	 *
	 * @since 5.1.6
	 *
	 * @param string $country
	 *
	 * @return bool
	 */
	public static function update_settings() {
		$section = tribe_get_request_var( 'tc-section', false );
		if ( 'paystack' === $section ) {
			$merchant = tribe( Merchant::class );

			foreach ( self::$fields as $key => $value ) {
				$to_save = tribe_get_request_var( self::$field_prefix . $key, $value );
				$merchant->set_prop( $key, $to_save );
			}

			$merchant->save();
		}
	}
}
