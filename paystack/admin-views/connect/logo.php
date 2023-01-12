<?php
/**
 * The Template for displaying the Tickets Commerce Paystack logo and features.
 *
 * @version 5.3.0
 *
 * @since   5.1.10
 * @since   5.3.0 Using generic CSS classes for gateway instead of Paystack.
 *
 * @var Tribe__Tickets__Admin__Views                  $this                  [Global] Template object.
 * @var string                                        $plugin_url            [Global] The plugin URL.
 * @var TEC\Tickets\Commerce\Gateways\Paystack\Merchant $merchant              [Global] The merchant class.
 * @var TEC\Tickets\Commerce\Gateways\Paystack\Signup   $signup                [Global] The Signup class.
 * @var bool                                          $is_merchant_active    [Global] Whether the merchant is active or not.
 * @var bool                                          $is_merchant_connected [Global] Whether the merchant is connected or not.
 */

?>

<div class="tec-tickets__admin-settings-tickets-commerce-gateway-logo">

	<?php $this->template( 'settings/tickets-commerce/paystack/connect/logo/image' ); ?>

	<?php $this->template( 'settings/tickets-commerce/paystack/connect/logo/features' ); ?>

</div>
