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
			this.first_name = $( '#tec-paystack-first-name' );
			this.last_name = $( '#tec-paystack-last-name' );
			this.email_address = $( '#tec-paystack-email-address' );
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

			if ( '' === $this.first_name.val() ) {
				$this.errors.push(tecTicketsPaystackCheckout.errorMessages.first_name);
			}
			if ( '' === $this.last_name.val() ) {
				$this.errors.push(tecTicketsPaystackCheckout.errorMessages.last_name);
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
			tribe.tickets.debug.log( 'handleCreateOrder', arguments );

			return fetch(
				tecTicketsPaystackCheckout.orderEndpoint,
				{
					method: 'POST',
					body: JSON.stringify( {
						purchaser: tribe.tickets.commerce.getPurchaserData( $this.container )
					} ),
					headers: {
						'X-WP-Nonce': $this.container.find( tribe.tickets.commerce.selectors.nonce ).val(),
						'Content-Type': 'application/json',
					}
				}
			)
			.then( response => response.json() )
			.then( data => {
				tribe.tickets.debug.log( data );
				if ( data.success ) {
					return $this.handoverToPopup();
				} else {
					alert('There was an error creating your order, please try again');
				}
			} )
			.catch( () => {
				alert('There was an error creating your order, please try again');
			} );
		},
		handoverToPopup: function() {
			let $this   = this;
			let handler = PaystackPop.setup({
				key: tecTicketsPaystackCheckout.publicKey,
				firstname: $this.first_name.val(),
				lastname: $this.last_name.val(),
				email: $this.email_address.val(),
				amount: $this.total.val() * 100,
				currency: tecTicketsPaystackCheckout.currency_code,
				ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
				// label: "Optional string that replaces customer email"
				onClose: function(){

				  alert('Window closed.');

				},
				callback: function(response){
					
					if ( undefined === response ) {
 
					} else if ( 'success' == response.status ) {
						$this.handlePaymmentSuccess();
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
		handlePaymmentSuccess: function () {

		},
		handlePaymmentFailure: function () {

		},
	}


	$( document ).ready(function ($) {
		paystackCheckout.init();
	});

})( jQuery, tribe.tickets.commerce.gateway.paystack );