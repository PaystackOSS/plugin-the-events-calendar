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
				this.checkForReturn();

				tribe.tickets.debug.log( 'paystackInit', this, tecTicketsPaystackCheckout );
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
		getSettings: function ( leaveMeta = false ) {
			let settings = {
				key: tecTicketsPaystackCheckout.publicKey,
				firstname: this.name.val(),
				lastname: this.name.val(),
				email: this.email_address.val(),
				amount: this.total.val() * 100,
				currency: tecTicketsPaystackCheckout.currency_code
			}
			if ( 0 < this.sub_account.length && '' !== this.sub_account.val() ) {
				settings.subaccount = this.sub_account.val();
			} else if ( 0 < this.split_trans.length && '' !== this.split_trans.val() ) {
				settings.split_code = this.split_trans.val();
			}

			if ( false == leaveMeta ) {
				settings.metaData = tecTicketsPaystackCheckout.metaData;
			}

			return settings;
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

			let bodyArgs = {
				purchaser: tribe.tickets.commerce.getPurchaserData( $this.container ),
				cart: $this.getSettings()
			}

			if ( 'redirect' == tecTicketsPaystackCheckout.gatewayMode ) {
				bodyArgs.redirect_url = window.location.href;
			}

			return fetch(
				tecTicketsPaystackCheckout.orderEndpoint,
				{
					method: 'POST',
					body: JSON.stringify( bodyArgs ),
					headers: {
						'Content-Type': 'application/json',
					}
				}
			)
			.then( response => response.json() )
			.then( data => {
				tribe.tickets.debug.log( 'handleCreateOrderResponse', data );
				if ( data.success ) {
					if ( 'popup' == tecTicketsPaystackCheckout.gatewayMode ) {
						$this.handoverToPopup( data );
					} else if ( 'redirect' == tecTicketsPaystackCheckout.gatewayMode && undefined != data.redirect_url ) {
						window.location.href = data.redirect_url;
					} else {
						alert( tecTicketsPaystackCheckout.errorMessages.createOrder );
					}
				} else {
					alert( tecTicketsPaystackCheckout.errorMessages.createOrder );
				}
			} )
			.catch( () => {
				alert( tecTicketsPaystackCheckout.errorMessages.createOrder );
			} );
		},
		handoverToPopup: function( order ) {
			let $this = this;

			let settings = this.getSettings( true );
			
			settings.ref = order.id; // Uses the Order ID
			if ( undefined != order.meta ) {
				settings.metadata = {
					plugin:'the-events-calendar'
				};
				settings.metadata.custom_fields = order.meta;
			} else {
				settings.metadata = {};
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

			tribe.tickets.debug.log( 'paystackPopUpSettings', settings );
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
			if ( null !== response.recheck ) {
				body.recheck = true;
			}
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
		checkForReturn: function () {
			let $this = this;

			let urlParams = new URLSearchParams( window.location.search );
			let cookie = urlParams.get( 'reference' );
			let reference = urlParams.get( 'reference' );

			tribe.tickets.debug.log( 'checkForReturn', urlParams );

			if ( undefined !== reference && null !== reference ) {
				let body = {
					'reference': reference,
					'status': 'success',
					'transaction': reference,
					'recheck': true,
				};
				$this.handlePaymmentSuccess( body );
			}
		}
	}

	$( document ).ready(function ($) {
		paystackCheckout.init();
	});

})( jQuery, tribe.tickets.commerce.gateway.paystack );