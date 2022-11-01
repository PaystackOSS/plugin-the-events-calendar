/**
 * Path to this script in the global tribe Object.
 *
 * @since 5.1.9
 *
 * @type   {Object}
 */
 tribe.tickets.commerce.gateway.paystack = tribe.tickets.commerce.gateway.paystack || {};

/**
 * This script Object for public usage of the methods.
 *
 * @since 5.1.9
 *
 * @type   {Object}
 */
tribe.tickets.commerce.gateway.paystack = {};

(function ( $, paystackCheckout ) {
	"use strict";

	paystackCheckout = {
		init: function( ) {
			if ( 0 < $( "#tec-tc-gateway-stripe-checkout-button" ).length ) {
				this.setVariables();
				this.watchSubmit();
			}
		},
		setVariables: function () {
			this.errors = [];
			this.name = $( '#tec-tc-purchaser-name' );
			this.email_address = $( '#tec-tc-purchaser-email' );
			this.total = $( '#tec-paystack-total' );
			this.sub_account = $( '#tec-paystack-sub-account' );
			this.split_trans = $( '#tec-paystack-split-transaction' );
			
			this.container = $( tribe.tickets.commerce.selectors.checkoutContainer );
		},
		watchSubmit: function( ) {
			let $this   = this;
			$( "#tec-tc-gateway-stripe-checkout-button" ).on( 'click', function( event ){
				$this.validateFields(),
				$this.maybeHandover();
			});
		},
		validateFields: function () {
			let $this    = this;
			$this.errors = [];

			if ( '' === $this.name.val() ) {
				$this.errors.push(tecTicketsPaystackCheckout.errorMessages.name);
			}
			if ( '' === $this.email_address.val() ) {
				$this.errors.push(tecTicketsPaystackCheckout.errorMessages.email_address);
			}

			tribe.tickets.debug.log( 'paystackValidate', $this.errors );
		},
		maybeHandover: function () {
			if ( 0 < this.errors.length ) {
				console.log(this.errors);
			} else {
				this.createOrder();
			}
		},
		createOrder: function () {
			let $this = this;
			tribe.tickets.debug.log( 'handleCreateOrder', tribe.tickets.commerce.getPurchaserData( $this.container ) );

			console.log(tribe.tickets.commerce.getPurchaserData( $this.container ));

			return fetch(
				tecTicketsPaystackCheckout.orderEndpoint,
				{
					method: 'POST',
					body: JSON.stringify( {
						purchaser: tribe.tickets.commerce.getPurchaserData( $this.container )
					} ),
					headers: {
						//'X-WP-Nonce': $this.container.find( tribe.tickets.commerce.selectors.nonce ).val(),
						'Content-Type': 'application/json',
					}
				}
			)
			.then( response => response.json() )
			.then( data => {
				tribe.tickets.debug.log( 'handleCreateOrderResponse', data );

				if ( data.success ) {
					return $this.handoverToPopup( data );
				} else {
					alert('There was an error creating your order, please try again');
				}
			} )
			.catch( () => {
				alert('There was an error creating your order, please try again');
			} );
		},
		handoverToPopup: function( order ) {
			let $this = this;

			let settings = {
				key: tecTicketsPaystackCheckout.publicKey,
				firstname: $this.name.val(),
				lastname: $this.name.val(),
				email: $this.email_address.val(),
				amount: $this.total.val() * 100,
				currency: tecTicketsPaystackCheckout.currency_code,
				ref: order.id, // Uses the Order ID
			}

			if ( 0 < $this.sub_account.length && '' !== $this.sub_account.val() ) {
				settings.subaccount = $this.sub_account.val();
			} else if ( 0 < $this.split_trans.length && '' !== $this.split_trans.val() ) {
				settings.split_code = $this.split_trans.val();
			}

			settings.onClose = function( response ){
				response = {
					'status': 'failed',
					'transaction': order.id,
					'reference': order.id,
				}
				$this.handlePaymmentFailure( response );
			};

			settings.callback = function(response){
				tribe.tickets.debug.log( 'paystackPopUpResponse', response );
				if ( undefined === response ) {
					response = {
						'status': 'failed',
						'transaction': order.id,
						'reference': order.id,
					}
					$this.handlePaymmentFailure( response );
				} else if ( 'success' == response.status ) {
					$this.handlePaymmentSuccess( response );
				}
			};

			let handler = PaystackPop.setup( settings );
			handler.openIframe();
		},

		/**
		 * When we receive a payment complete from Paystack
		 */
		handlePaymmentSuccess: function ( response ) {
			tribe.tickets.debug.log( 'handlePaymmentSuccess', arguments );
	
			const body = {
				'reference': response.reference ?? '',
				'status': response.status ?? 'pending',
				'transaction': response.transaction ?? '',
			};
			return fetch(
				tecTicketsPaystackCheckout.orderEndpoint + '/' + response.reference,
				{
					method: 'POST',
					headers: {
						//'X-WP-Nonce': $container.find( tribe.tickets.commerce.selectors.nonce ).val(),
						'Content-Type': 'application/json',
					},
					body: JSON.stringify( body ),
				}
			)
			.then( response => response.json() )
			.then( data => {
				tribe.tickets.debug.log( 'handlePaymmentSuccessResponse', data );
				if ( data.success ) {
					console.log(data);
					if( data.redirect_url ) {
						window.location.href = data.redirect_url;
					}
				} else {
					$this.handlePaymmentFailure( response );
				}
			} )
			.catch( obj.handleApproveError );
		},
		handlePaymmentFailure: function ( response ) {
			tribe.tickets.debug.log( 'handlePaymmentFailure', response );
	
			const body = {
				'reference': response.reference ?? '',
				'status': response.status ?? 'failed',
				'transaction': response.transaction ?? '',
			};
			return fetch(
				tecTicketsPaystackCheckout.orderEndpoint + '/' + response.reference,
				{
					method: 'POST',
					headers: {
						//'X-WP-Nonce': $container.find( tribe.tickets.commerce.selectors.nonce ).val(),
						'Content-Type': 'application/json',
					},
					body: JSON.stringify( body ),
				}
			)
			.then( response => response.json() )
			.then( data => {
				tribe.tickets.debug.log( 'handlePaymmentSuccessResponse', data );
				if ( data.success ) {
					if( data.redirect_url ) {
						window.location.href = data.redirect_url;
					}
				}
			} )
			.catch( obj.handleApproveError );
		},
	}

	$( document ).ready(function ($) {
		paystackCheckout.init();
	});

})( jQuery, tribe.tickets.commerce.gateway.paystack );