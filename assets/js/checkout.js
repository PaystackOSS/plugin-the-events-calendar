(function ($) {
	"use strict";
	$( document ).ready(function ($) {
		$( "#tec-tc-gateway-stripe-checkout-button" ).on( 'click', function( event ){
			alert('hello');

			let handler = PaystackPop.setup({
				key: 'pk_test_b6e89172925df640fee4b52251eb91cc77e3d96c', // Replace with your public key
				firstname: 'Warwick',
				lastname: 'Booth',
				email: 'warwick@lsdev.biz',
				amount: 10 * 100,
				currency: 'ZAR',
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
		} );
	});

})( jQuery );