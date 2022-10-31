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
			let $this   = this;
			let handler = PaystackPop.setup({
				key: tecTicketsPaystackCheckout.publicKey,
				firstname: $this.name.val(),
				lastname: $this.name.val(),
				email: $this.email_address.val(),
				amount: $this.total.val() * 100,
				currency: tecTicketsPaystackCheckout.currency_code,
				ref: order.id, // Uses the Order ID
				onClose: function(){

				  alert('Window closed.');

				},
				callback: function(response){
					tribe.tickets.debug.log( 'paystackPopUpResponse', response );

					if ( undefined === response ) {
 
					} else if ( 'success' == response.status ) {
						//$this.handlePaymmentSuccess( response );
					}

				  	let message = 'Payment complete! Reference: ' + response.reference;
				  	alert(message);
				}
			  });
			  handler.openIframe();
		},

		/**
		 * When we receive a payment complete from Paystack
		 */
		handlePaymmentSuccess: function ( response ) {
			tribe.tickets.debug.log( 'handlePaymmentSuccess', arguments );
	
			const body = {
				'payer_id': data.payerID ?? '',
			};
	
			return fetch(
				obj.orderEndpointUrl + '/' + data.orderID,
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
					if ( data.success ) {
						//return obj.handleCheckSuccess( data, actions, $container );
					} else {
						//return obj.handleApproveFail( data, actions, $container );
					}
				} )
				.catch( obj.handleApproveError );
		},
		handlePaymmentFailure: function () {

		},
	}


	$( document ).ready(function ($) {
		paystackCheckout.init();
	});

})( jQuery, tribe.tickets.commerce.gateway.paystack );