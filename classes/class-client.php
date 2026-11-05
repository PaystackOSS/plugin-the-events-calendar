<?php
/**
 * Handles registering and setup for assets on Ticket Commerce.
 *
 * @package paystack\tec\classes
 */
namespace paystack\tec\classes;

use TEC\Tickets\Commerce\Checkout;

/**
 * Client class, used to contact paystack
 *
 * @package paystack\tec\classes;
 */
class Client {

	public function get_barer_key() {
		return tribe( Gateway::class )->get_option( 'secret_key_' . $this->get_mode() );
	}

	public function get_webhook_secret() {
		return (string) tribe( Gateway::class )->get_option( 'webhook_secret_' . $this->get_mode() );
	}

	protected function get_mode() {
		$mode = tribe( Gateway::class )->get_option( 'paystack_mode' );
		return ( '' === $mode || false === $mode || 'test' === $mode ) ? 'test' : 'live';
	}

	/**
	 * Checks a webhook's signature against the raw request body.
	 *
	 * Uses the Paystack-Signature header when a webhook signing secret is set,
	 * otherwise the X-Paystack-Signature header, which only a secret key (sk_) can verify.
	 *
	 * @param string $body Raw request body.
	 *
	 * @return bool
	 */
	public function is_valid_webhook( $body ) {
		$webhook_secret = $this->get_webhook_secret();
		if ( '' !== $webhook_secret && isset( $_SERVER['HTTP_PAYSTACK_SIGNATURE'] ) ) {
			return self::verify_signature( $_SERVER['HTTP_PAYSTACK_SIGNATURE'], $body, $webhook_secret, time() );
		}

		$secret_key = (string) $this->get_barer_key();
		if ( 0 === strpos( $secret_key, 'sk_' ) && isset( $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ) ) {
			return hash_equals( hash_hmac( 'sha512', $body, $secret_key ), $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] );
		}

		return false;
	}

	/**
	 * Verifies a Paystack-Signature header: t=<unix seconds>,v1=<key id>:<hex>[,v1=...]
	 *
	 * @param string $header
	 * @param string $body   Raw request body.
	 * @param string $secret Webhook signing secret (whsec_...).
	 * @param int    $now
	 *
	 * @return bool
	 */
	public static function verify_signature( $header, $body, $secret, $now ) {
		$timestamp  = '';
		$signatures = array();
		foreach ( explode( ',', $header ) as $part ) {
			$pair = explode( '=', trim( $part ), 2 );
			if ( 2 !== count( $pair ) ) {
				continue;
			}
			if ( 't' === $pair[0] ) {
				$timestamp = $pair[1];
			} elseif ( 'v1' === $pair[0] && false !== strpos( $pair[1], ':' ) ) {
				$signatures[] = substr( $pair[1], strpos( $pair[1], ':' ) + 1 );
			}
		}

		if ( ! preg_match( '/^\d+$/', $timestamp ) || abs( $now - (int) $timestamp ) > 300 ) {
			return false;
		}

		$expected = hash_hmac( 'sha512', $timestamp . '.' . $body, $secret );
		foreach ( $signatures as $signature ) {
			if ( hash_equals( $expected, $signature ) ) {
				return true;
			}
		}

		return false;
	}

	public function initialize_transaction( $fields = array() ) {
		$defaults = array(
			'email' => '',
			'amount' => '',
		);
		//$fields     = wp_parse_args( $fields, $defaults );
		$secret_key = $this->get_barer_key();

		$return = array(
			'success' => false,
		);
		/*
		(
			[status] => 1
			[message] => Authorization URL created
			[data] => stdClass Object
				(
					[authorization_url] => https://checkout.paystack.com/ozbm3m9fedms1um
					[access_code] => ozbm3m9fedms1um
					[reference] => c5yso6fqr2
				)

		)
		*/
		if ( ! empty( $fields ) && '' !== $secret_key ) {
			$response = wp_remote_post(
				'https://api.paystack.co/transaction/initialize',
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $secret_key,
						'Cache-Control' => 'no-cache',
						'Accept'        => 'application/json',
					),
					'body' => $fields,
				)
			);
			if ( ( ! is_wp_error( $response ) ) && ( 200 === wp_remote_retrieve_response_code( $response ) ) ) {
				$data = json_decode( $response['body'] );
				if ( json_last_error() === JSON_ERROR_NONE && isset( $data->status ) ) {
					if ( 1 === $data->status || '1' === $data->status || true === $data->status ) {
						$return['authorization_url'] = $data->data->authorization_url;
						$return['access_code']       = $data->data->access_code;
						$return['reference']         = $data->data->reference;
						$return['success']           = true;
					} else {
						$return['message'] = __( 'Return status is not true.', 'paystack-for-events-calendar' );	
					}
				} else {
					$return['message'] = __( 'JSON error with the transaction response.', 'paystack-for-events-calendar' );
				}
			} else {
				$return['message'] = __( 'There was an error while initilizing the transaction.', 'paystack-for-events-calendar' );
			}
		} else {
			$return['message'] = __( 'Payment fields are empty.', 'paystack-for-events-calendar' );
		}

		return $return;
	}

	public function check_transaction( $reference = '' ) {
		$secret_key = $this->get_barer_key();

		$return = array(
			'success' => false,
		);
		if ( '' !== $reference && '' !== $secret_key ) {
			$response = wp_remote_get(
				'https://api.paystack.co/transaction/verify/' . $reference,
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $secret_key,
						'Cache-Control' => 'no-cache',
						'Accept'        => 'application/json',
					),
				)
			);
			if ( ( ! is_wp_error( $response ) ) && ( 200 === wp_remote_retrieve_response_code( $response ) ) ) {
				$data = json_decode( $response['body'] );
				if ( json_last_error() === JSON_ERROR_NONE && isset( $data->status ) ) {
					if ( 1 === $data->status || '1' === $data->status || true === $data->status ) {
						$return['access_code']       = $data->data->authorization->authorization_code;
						$return['reference']         = $reference;
						$return['success']           = true;
					} else {
						$return['message'] = __( 'Return status is not true.', 'paystack-for-events-calendar' );	
					}
				} else {
					$return['message'] = __( 'JSON error with the check transaction response.', 'paystack-for-events-calendar' );
				}
			} else {
				$return['message'] = __( 'There was an error while checking the transaction.', 'paystack-for-events-calendar' );
			}
		} else {
			$return['message'] = __( 'The reference field is empty', 'paystack-for-events-calendar' );
		}

		return $return;
	}
}
?>
