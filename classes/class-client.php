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
		$key     = '';
		$gateway = tribe( Gateway::class );

		// Get the correct Public key
		$mode = $gateway->get_option( 'paystack_mode' );
		if ( '' === $mode || false === $mode ) {
			$mode = 'test';
		}
		if ( 'test' === $mode ) {
			$key = $gateway->get_option( 'secret_key_test' );
		} else {
			$key = $gateway->get_option( 'secret_key_live' );
		}
		return $key;
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
