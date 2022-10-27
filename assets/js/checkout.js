(function ($) {
	"use strict";

	var paystackCheckout = {
		init: function( ) {
			if ( 0 < $( "#tec-tc-gateway-stripe-checkout-button" ).length ) {
				this.errors = [];
				this.first_name = $( '#tec-paystack-first-name' );
				this.last_name = $( '#tec-paystack-last-name' );
				this.email_address = $( '#tec-paystack-email-address' );
				this.watchSubmit();

				console.log(tecTicketsPaystackCheckout);
			}
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
		},
		maybeHandover: function () {
			if ( 0 < this.errors.length ) {
				console.log(this.errors);
			} else {
				this.handoverToPopup();
			}
		},
		handoverToPopup: function() {
			let $this   = this;
			let handler = PaystackPop.setup({
				key: tecTicketsPaystackCheckout.publicKey,
				firstname: $this.first_name.val(),
				lastname: $this.last_name.val(),
				email: $this.email_address.val(),

				amount: 10 * 100,
				currency: tecTicketsPaystackCheckout.currency_code,

				ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
				// label: "Optional string that replaces customer email"
				onClose: function(){
				  alert('Window closed.');
				},
				callback: function(response){
				  let message = 'Payment complete! Reference: ' + response.reference;
				  alert(message);
				}
			  });
			  handler.openIframe();
		}
	}


	$( document ).ready(function ($) {
		paystackCheckout.init();
	});

})( jQuery );