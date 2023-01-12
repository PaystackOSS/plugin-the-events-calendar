<?php
namespace paystack\tec\classes;

use TEC\Tickets\Commerce\Gateways\Contracts\Abstract_Merchant;
use Tribe__Utils__Array as Arr;
use Tribe__Date_Utils as Dates;
use TEC\Tickets\Commerce\Traits\Has_Mode;

/**
 * Class Merchant.
 *
 *
 * @package namespace paystack\tec\classes;
 */
class Merchant extends Abstract_Merchant {

	/**
	 * All account Props we use for the merchant
	 *
	 * @var string[]
	 */
	protected $account_props = array(
		'country',
		'paystack_mode',
		'secret_key_test',
		'public_key_test',
		'secret_key_live',
		'public_key_live',
		'checkout_mode',
		'transaction_type',
		'metadata',
	);

	/**
	 * Determines if the data needs to be saved to the Database
	 *
	 * @since 5.1.9
	 *
	 * @var boolean
	 */
	protected $needs_save = false;

	/**
	 * Merchant Country
	 *
	 * @since 5.1.9
	 *
	 * @var null|string
	 */
	protected $country;

	/**
	 * paystack merchant id.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	protected $paystack_mode;

	/**
	 * Client Test Secrect Key.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	protected $secret_key_test;

	/**
	 * Client Test Public Key.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	protected $public_key_test;

	/**
	 * Client Live Secrect Key.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	protected $secret_key_live;

	/**
	 * Client Live Public Key.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	protected $public_key_live;

	/**
	 * Whether or not an account is connected.
	 *
	 * @since 5.2.0
	 *
	 * @var bool
	 */
	protected $account_is_connected = false;

	/**
	 * Fetches the current Merchant ID.
	 *
	 * @since 5.1.9
	 *
	 * @return string|null
	 */
	public function get_prop( $key ) {
		return $this->$key;
	}

	/**
	 * Sets the value for Merchant ID locally, in this instance of the Merchant.
	 *
	 * @since 5.1.9
	 *
	 * @param string   $key        The id of the key
	 * @param mixed   $value      Value used for the Merchant ID.
	 * @param boolean $needs_save Determines if the proprieties saved need to save to the DB.
	 */
	public function set_prop( $key, $value, $needs_save = true ) {
		$this->set_value( $key, $value, $needs_save );
	}

	/**
	 * Determines if this instances needs to be saved to the DB.
	 *
	 * @since 5.1.9
	 *
	 * @return bool
	 */
	public function needs_save() {
		return tribe_is_truthy( $this->needs_save );
	}

	/**
	 * Return array of merchant details.
	 *
	 * @since 5.1.9
	 *
	 * @return array
	 */
	public function to_array() {
		$to_array = array();
		foreach ( $this->account_props as $key ) {
			$to_array[ $key ] = $this->get_prop( $key );
		}
		return $to_array;
	}

	/**
	 * Returns the options key for the account in the merchant mode.
	 *
	 * @since 5.1.9
	 *
	 * @return string
	 */
	public function get_account_key() {
		$gateway_key   = Gateway::get_key();
		return "tec_tickets_commerce_{$gateway_key}_account";
	}

	/**
	 * Saves a given base value into the class props.
	 *
	 * @since 5.1.9
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param bool   $needs_save
	 *
	 */
	protected function set_value( $key, $value, $needs_save = true ) {
		$this->{$key} = $value;

		// Determine if we will need to save in the DB.
		if ( $needs_save ) {
			$this->needs_save = true;
		}
	}

	/**
	 * Setup properties from array.
	 *
	 * @since 5.1.9
	 *
	 * @param array   $data       Which values need to be saved.
	 * @param boolean $needs_save Determines if the proprieties saved need to save to the DB.
	 */
	protected function setup_properties( array $data, $needs_save = true ) {

		foreach ( $this->account_props as $key ) {
			if ( array_key_exists( $key, $data ) ) {
				$this->set_prop( $key, $data[ $key ], $needs_save );
			} else {
				$this->set_prop( $key, '', $needs_save );
			}
		}
	}

	/**
	 * Validate merchant details.
	 *
	 * @since 5.1.6
	 *
	 * @param array $merchant_details
	 */
	public function validate( $merchant_details ) {
		$required = $this->account_props;

		if ( array_diff( $required, array_keys( $merchant_details ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the merchant details data.
	 *
	 * @since 5.1.9
	 *
	 * @return array
	 */
	protected function get_details_data() {
		return (array) get_option( $this->get_account_key(), [] );
	}

	/**
	 * Delete merchant account details on the Database.
	 *
	 * @since 5.1.9
	 *
	 * @return bool
	 */
	public function delete_data() {
		$status = update_option( $this->get_account_key(), null );

		if ( $status ) {
			$data = array_fill_keys( $this->account_props, null );
			// reset internal values.
			$this->setup_properties( $data, false );
		}

		return $status;
	}

	/**
	 * Disconnects the merchant completely.
	 *
	 * @since 5.1.9
	 *
	 * @return bool
	 */
	public function disconnect() {
		$statuses = array(
			$this->delete_data(),
		);

		return in_array( false, $statuses, true );
	}

	/**
	 * Determines if the Merchant is connected.
	 *
	 * @since 5.2.0
	 *
	 * @return bool
	 */
	public function is_connected( $recheck = false ) {
		$is_connected = false;
		/**
		 * TODO
		 * 
		 * If mode = test and test keys not entered, then not active.
		 * if mode = live and keys not entered, then not active.
		 */

		if ( 'test' === $this->paystack_mode ) {
			if ( '' !== $this->secret_key_test && '' !== $this->public_key_test ) {
				$is_connected = true;
			}
		} else if ( 'live' === $this->paystack_mode ) {
			if ( '' !== $this->public_key_live && '' !== $this->public_key_live ) {
				$is_connected = true;
			}
		}
		return $is_connected;
	}

	/**
	 * Determines if the Merchant is active.
	 *
	 * @since 5.1.9
	 *
	 * @return bool
	 */
	public function is_active( $recheck = false ) {

		if ( ! $this->is_connected() ) {
			return false;
		}

		return tribe( Gateway::class )->is_enabled();
	}

	/**
	 * Fetches the locale for the website, but pass it on a filter to allow changing of the locale here.
	 *
	 * @since 5.2.0
	 *
	 * @return string
	 */
	public function get_locale() {
		$locale = get_locale();

		/**
		 * Allows filtering of the locale for the Merchant.
		 *
		 * @since 5.2.0
		 *
		 * @param string $locale
		 */
		return apply_filters( 'tec_tickets_commerce_gateway_paystack_merchant_locale', $locale );
	}
}