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

	protected function get_barer_key() {
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
			'email' => 'customer@email.com',
			'amount' => '20000',
		);
		$fields     = wp_parse_args( $fields, $defaults );
		$secret_key = $this->get_barer_key();

		print_r('<pre>');
		print_r($fields);
		print_r($secret_key);
		print_r('</pre>');

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
				$responseBody = json_decode( $response['body'] );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					print_r('<pre>');
					print_r($responseBody);
					print_r('</pre>');
				}
			}
		}
	}
}
?>
