<?php
/**
 * The Template for displaying the Tickets Commerce Paystack Settings.
 *
 * @version 5.3.0
 *
 * @since   5.1.10
 * @since   5.3.0 Using generic CSS classes for gateway instead of Paystack.
 *
 * @var Tribe__Tickets__Admin__Views                  $this                  [Global] Template object.
 * @var string                                        $plugin_url            [Global] The plugin URL.
 * @var paystack\tec\classes\Merchant $merchant       [Global] The merchant class.
 * @var paystack\tec\classes\Signup   $signup         [Global] The Signup class.
 * @var bool                                          $is_merchant_active    [Global] Whether the merchant is active or not.
 * @var bool                                          $is_merchant_connected [Global] Whether the merchant is connected or not.
 */

$classes = array(
	'tec-tickets__admin-settings-tickets-commerce-gateway',
	'tec-tickets__admin-settings-tickets-commerce-gateway--connected' => $is_merchant_connected,
)
?>

<div <?php tribe_classes( $classes ); ?>>
	<div id="tec-tickets__admin-settings-tickets-commerce-gateway-connect" class="tec-tickets__admin-settings-tickets-commerce-gateway-connect">
		<?php $this->template( 'paystack/admin-views/connect/inactive' ); ?>
	</div>

	<div class="tec-tickets__admin-settings-tickets-commerce-gateway-logo">
		<?php $image_src = PS_TEC_URL . 'assets/images/icon.png'; ?>

		<img
			src="<?php echo esc_url( $image_src ); ?>"
			alt="<?php esc_attr_e( 'Paystack Logo Image', 'paystack-for-events-calendar' ); ?>"
			class="tec-tickets__admin-settings-tickets-commerce-gateway-logo-image"
		>

		<ul>
			<li>
				<?php esc_html_e( 'Credit and debit card payments', 'paystack-for-events-calendar' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Alternative payment methods e.g Apple Pay, Mobile money, EFT, and more', 'paystack-for-events-calendar' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Delight customers with a seamless payments experience', 'paystack-for-events-calendar' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Enjoy phenomenal transaction success rates', 'paystack-for-events-calendar' ); ?>
			</li>
		</ul>
	</div>

</div>

<?php $this->template( 'paystack/admin-views/connect/active' ); ?>

<?php $this->template( 'paystack/admin-views/modal/signup-complete' ); ?>

<script type="text/javascript">
	(function ( $ ) {
		"use strict";

		let paystackAdmin = {
			init: function() {
				this.switchPassword( 'tec-tickets__admin-settings-tickets-commerce-gateway-merchant-secret_key_test-container' );
				this.switchPassword( 'tec-tickets__admin-settings-tickets-commerce-gateway-merchant-secret_key_live-container' );
			},
			switchPassword : function( selector ) {

				$( '.' + selector + ' span' ).on('click',function(){
					const type = $(this).parent('p').find('input').attr('type');
					console.log(type);
					$(this).removeClass( 'dashicons-visibility' ).removeClass('dashicons-hidden');

					let newType  = 'password';
					let classCSS = 'visibility';
					if ( 'password' === type ) {
						newType = 'text';
						classCSS = 'hidden';
					}
					console.log(newType);
					$(this).parent('p').find('input').attr( 'type', newType );
					$(this).addClass( 'dashicons-' + classCSS );
				});
			}
		}

		$( document ).ready(function ($) {
			paystackAdmin.init();
		});
	})( jQuery );
</script>
