<?php

namespace paystack\tec\classes\REST;
use TEC\Tickets\Commerce\Cart;
use TEC\Tickets\Commerce\Gateways\Contracts\Abstract_REST_Endpoint;

use paystack\tec\classes\Gateway;
use paystack\tec\classes\Client;

use TEC\Tickets\Commerce\Order;

use TEC\Tickets\Commerce\Status\Completed;
use TEC\Tickets\Commerce\Status\Denied;
use TEC\Tickets\Commerce\Status\Pending;
use TEC\Tickets\Commerce\Status\Status_Handler;
use TEC\Tickets\Commerce\Success;
use Tribe__Utils__Array as Arr;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;


/**
 * Class Order Endpoint.
 *
 * @since   5.1.9
 *
 * @package TEC\Tickets\Commerce\Gateways\PayPal\REST
 */
class Order_Endpoint extends Abstract_REST_Endpoint {

	/**
	 * The REST API endpoint path.
	 *
	 * @since 5.1.9
	 *
	 * @var string
	 */
	protected $path = '/commerce/paystack/order';

	/**
	 * Register the actual endpoint on WP Rest API.
	 *
	 * @since 5.1.9
	 */
	public function register() {
		$namespace     = tribe( 'tickets.rest-v1.main' )->get_events_route_namespace();
		$documentation = tribe( 'tickets.rest-v1.endpoints.documentation' );

		register_rest_route(
			$namespace,
			$this->get_endpoint_path(),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'args'                => $this->create_order_args(),
				'callback'            => array( $this, 'handle_create_order' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$namespace,
			$this->get_endpoint_path() . '/(?P<order_id>[0-9a-zA-Z]+)',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'args'                => $this->update_order_args(),
				'callback'            => array( $this, 'handle_update_order' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$namespace,
			$this->get_endpoint_path() . '/webhook',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'args'                => $this->create_order_args(),
				'callback'            => array( $this, 'handle_webhook' ),
				'permission_callback' => '__return_true',
			)
		);

		$documentation->register_documentation_provider( $this->get_endpoint_path(), $this );
	}

	/**
	 * Handles the request that creates an order with Tickets Commerce and the PayPal gateway.
	 *
	 * @since 5.1.9
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_Error|WP_REST_Response An array containing the data on success or a WP_Error instance on failure.
	 */
	public function handle_create_order( WP_REST_Request $request ) {
		$response = array(
			'success' => false,
		);

		$data      = $request->get_json_params();
		$purchaser = tribe( Order::class )->get_purchaser_data( $data );

		if ( is_wp_error( $purchaser ) ) {
			return $purchaser;
		}

		$order = tribe( Order::class )->create_from_cart( tribe( \paystack\tec\classes\Gateway::class ), $purchaser );

		$unit = array(
			'reference_id' => $order->ID,
			'value'        => (string) $order->total_value->get_decimal(),
			'currency'     => $order->currency,
			'first_name'   => $order->purchaser['first_name'],
			'last_name'    => $order->purchaser['last_name'],
		);
		$update_values = array(
			'gateway_order_id' => $order->ID,
		);

		foreach ( $order->items as $item ) {
			$ticket          = \Tribe__Tickets__Tickets::load_ticket_object( $item['ticket_id'] );
			$unit['items'][] = array(
				'name'        => $ticket->name,
				'unit_amount' => [ 'value' => (string) $item['price'], 'currency_code' => $order->currency ],
				'quantity'    => $item['quantity'],
				'item_total'  => [ 'value' => (string) $item['sub_total'], 'currency_code' => $order->currency ],
				'sku'         => $ticket->sku,
			);
		}

		// If the gate is set to redirect, then initialize the transaction.
		if ( isset( $data['redirect_url'] ) ) {

			$redirect_data = array(
				'amount'       => $data['cart']['amount'],
				'currency'     => $data['cart']['currency'],
				'email'        => $data['cart']['email'],
				'callback_url' => $data['redirect_url'],
				'reference'    => $order->ID,
			);

			if ( isset( $data['cart']['subaccount'] ) ) {
				$redirect_data['subaccount'] = $data['cart']['subaccount'];
			} else if ( isset( $data['cart']['split_code'] ) ) {
				$redirect_data['split_code'] = $data['cart']['split_code'];
			}

			$client      = tribe( Client::class );
			$transaction = $client->initialize_transaction( $redirect_data );

			if ( true === $transaction['success'] ) {
				$update_values['gateway_order_id']    = $transaction['reference'];
				$update_values['gateway_access_code'] = $transaction['access_code'];
				$response['redirect_url']             = $transaction['authorization_url'];
			} else {
				$response['message'] = $transaction['message'];
				return new WP_REST_Response( $response );
			}
		}

		// Set
		$updated = tribe( Order::class )->modify_status(
			$order->ID,
			Pending::SLUG,
			$update_values
		);

		if ( is_wp_error( $updated ) ) {
			return $updated;
		}

		// Respond with the ID for Paypal Usage.
		$response['success'] = true;
		$response['id']      = $order->ID;

		return new WP_REST_Response( $response );
	}

	/**
	 * Handles the request that updates an order with Tickets Commerce and the PayPal gateway.
	 *
	 * @since 5.1.9
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_Error|WP_REST_Response An array containing the data on success or a WP_Error instance on failure.
	 */
	public function handle_update_order( WP_REST_Request $request ) {
		$response = array(
			'success' => false,
		);

		$order_id = $request->get_param( 'reference' );

		$order = tec_tc_orders()->by_args( array(
			'status'           => tribe( Pending::class )->get_wp_slug(),
			'gateway_order_id' => $order_id,
		) )->first();

		if ( ! $order ) {
			return new WP_Error( 'tec-tc-gateway-paystack-nonexistent-order-id', $messages['nonexistent-order-id'], $order );
		}

		$transaction_status = $request->get_param( 'status' );
		$transaction_id     = $request->get_param( 'transaction' );

		// If we need to check the transaction, then do so.
		$recheck = $request->get_param( 'recheck' );
		if ( ! empty( $recheck ) && null !== $recheck ) {
			$client        = tribe( Client::class );
			$transaction   = $client->check_transaction( $order_id );

			if ( true === $transaction['success'] ) {
				$update_values['gateway_order_id']    = $transaction['reference'];
				$update_values['gateway_access_code'] = $transaction['access_code'];
			} else {
				$response['message'] = $transaction['message'];
				return new WP_REST_Response( $response );
			}
		}

		if ( 'success' === $transaction_status ) {
			// Flag the order as Completed.
			tribe( Order::class )->modify_status(
				$order->ID,
				Completed::SLUG,
				array(
					'gateway_transaction_id' => $transaction_id,
				)
			);

			$response['success']  = true;
			$response['order_id'] = $order_id;

			// When we have success we clear the cart.
			tribe( Cart::class )->clear_cart();

			$response['redirect_url'] = add_query_arg( array( 'tc-order-id' => $order_id ), tribe( Success::class )->get_url() );
		} else if ( 'failed' === $transaction_status ) {

			// Flag the order as Completed.
			tribe( Order::class )->modify_status(
				$order->ID,
				Denied::SLUG,
				array(
					'gateway_transaction_id' => $transaction_id,
					'gateway_failed_reason'  => __( 'User abandoned', 'paystack-for-events-calendar' ),
				)
			);
			$response['success'] = true;

		} else {
			return new WP_Error( 'tec-tc-gateway-paystack-error-order-id', __( 'There was a problem updating your order.', 'paystack-for-events-calendar' ), $order );
		}

		return new WP_REST_Response( $response );
	}

	public function handle_webhook( WP_REST_Request $request ) {
		// only a post with paystack signature header gets our attention
		if ( ( strtoupper( $_SERVER['REQUEST_METHOD']) != 'POST' ) || ! array_key_exists( 'x-paystack-signature', $_SERVER ) ) {
			exit();
		}

		// Retrieve the request's body
		$input  = @file_get_contents( "php://input" );
		$client = tribe( Client::class );

		// validate event do all at once to avoid timing attack
		if( $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac( 'sha512', $input, $client->get_barer_key() ) ) {
			exit();
		}

		http_response_code( 200 );

		// parse event (which is json string) as object
		// Do something - that will not take long - with $event
		$event = json_decode( $input );

		if ( isset( $event->event ) && in_array( $event->event, array( 'transfer.success', 'charge.success' ) ) ) {
			if ( isset( $event->data ) && isset( $event->data->reference ) && isset( $event->data->status ) && '' !== $event->data->reference ) {

				$order = tec_tc_orders()->by_args( array(
					'status'           => tribe( Pending::class )->get_wp_slug(),
					'gateway_order_id' => $event->data->reference,
				) )->first();

				if ( ! $order ) {
					return new WP_Error( 'tec-tc-gateway-paystack-nonexistent-order-id', $messages['nonexistent-order-id'], $order );
				}

				$gateway_repsone = '';
				if ( isset( $event->data->gateway_response ) ) {
					$gateway_repsone = $event->data->gateway_response;
				}

				if ( 'success' === $event->data->status ) {
					// Flag the order as Completed.
					tribe( Order::class )->modify_status(
						$order->ID,
						Completed::SLUG,
						array(
							'gateway_transaction_id' => $event->data->reference,
							'gateway_response' => $gateway_repsone,
						)
					);
				} else if ( 'failed' === $event->data->status ) {


					// Flag the order as Completed.
					tribe( Order::class )->modify_status(
						$order->ID,
						Denied::SLUG,
						array(
							'gateway_transaction_id' => $event->data->reference,
							'gateway_response'  => $gateway_repsone,
						)
					);
				}
			}
		}
		exit();
	}

	/**
	 * Arguments used for the signup redirect.
	 *
	 * @since 5.1.9
	 *
	 * @return array
	 */
	public function create_order_args() {
		return [];
	}

	public function webhook_order_args() {
		return [];
	}

	/**
	 * Arguments used for the updating order for PayPal.
	 *
	 * @since 5.1.9
	 *
	 * @return array
	 */
	public function update_order_args() {
		return [
			'order_id' => [
				'description'       => __( 'Order ID in Paystack', 'paystack-for-events-calendar' ),
				'required'          => true,
				'type'              => 'string',
				'validate_callback' => static function ( $value ) {
					if ( ! is_string( $value ) ) {
						return new WP_Error( 'rest_invalid_param', 'The order ID argument must be a string.', [ 'status' => 400 ] );
					}

					return $value;
				},
				'sanitize_callback' => [ $this, 'sanitize_callback' ],
			],
		];
	}

	/**
	 * Sanitize a request argument based on details registered to the route.
	 *
	 * @since 5.1.9
	 *
	 * @param mixed $value Value of the 'filter' argument.
	 *
	 * @return string|array
	 */
	public function sanitize_callback( $value ) {
		if ( is_array( $value ) ) {
			return array_map( 'sanitize_text_field', $value );
		}

		return sanitize_text_field( $value );
	}
}
