<?php
namespace paystack\tec\classes;

use TEC\Tickets\Commerce\Gateways\Contracts\Abstract_Gateway;
use TEC\Tickets\Commerce\Notice_Handler;
use TEC\Tickets\Commerce\Settings as TC_Settings;
use TEC\Tickets\Commerce\Utils\Currency;
use \Tribe__Tickets__Main;

/**
 * Class Gateway
 *
 * @since   5.1.6
 * @package paystack\tec\classes
 */
class Gateway extends Abstract_Gateway {
	/**
	 * @inheritDoc
	 */
	protected static $key = 'paystack';

	/**
	 * @inheritDoc
	 */
	protected static $settings = Settings::class;

	/**
	 * @inheritDoc
	 */
	protected static $merchant = Merchant::class;

	/**
	 * @inheritDoc
	 */
	protected static $supported_currencies = [
		'AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF',
		'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN',
		'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD',
	];

	/**
	 * @inheritDoc
	 */
	public static function get_label() {
		return __( 'Paystack', 'ps-tec-gateway' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_admin_notices() {
		$notices = [
			[
				'slug'    => 'tc-paypal-signup-complete',
				'content' => __( 'PayPal is now connected.', 'event-tickets' ),
				'type'    => 'info',
			],
			[
				'slug'    => 'tc-paypal-disconnect-failed',
				'content' => __( 'Failed to disconnect PayPal account.', 'event-tickets' ),
				'type'    => 'error',
			],
			[
				'slug'    => 'tc-paypal-disconnected',
				'content' => __( 'Disconnected PayPal account.', 'event-tickets' ),
				'type'    => 'info',
			],
			[
				'slug'    => 'tc-paypal-refresh-token-failed',
				'content' => __( 'Failed to refresh PayPal access token.', 'event-tickets' ),
				'type'    => 'error',
			],
			[
				'slug'    => 'tc-paypal-refresh-token',
				'content' => __( 'PayPal access token was refreshed successfully.', 'event-tickets' ),
				'type'    => 'info',
			],
			[
				'slug'    => 'tc-paypal-refresh-user-info-failed',
				'content' => __( 'Failed to refresh PayPal user info.', 'event-tickets' ),
				'type'    => 'error',
			],
			[
				'slug'    => 'tc-paypal-refresh-user-info',
				'content' => __( 'PayPal user info was refreshed successfully.', 'event-tickets' ),
				'type'    => 'info',
			],
			[
				'slug'    => 'tc-paypal-refresh-webhook-failed',
				'content' => __( 'Failed to refresh PayPal webhooks.', 'event-tickets' ),
				'type'    => 'error',
			],
			[
				'slug'    => 'tc-paypal-refresh-webhook-success',
				'content' => __( 'PayPal webhooks refreshed successfully.', 'event-tickets' ),
				'type'    => 'info',
			],
			[
				'slug'    => 'tc-paypal-ssl-not-available',
				'content' => __( 'A valid SSL certificate is required to set up your PayPal account and accept payments', 'event-tickets' ),
				'type'    => 'error',
			],
		];

		return $notices;
	}

	/**
	 * @inheritDoc
	 */
	public function get_logo_url(): string {
		return PS_TEC_URL . '/icon.png';
	}

	/**
	 * @inheritDoc
	 */
	public function get_subtitle(): string {
		return __( 'Enable payments through paystack.', 'ps-tec-gateway' );
	}

	/**
	 * @inheritDoc
	 */
	public static function is_enabled(): bool {
		if ( ! static::should_show() ) {
			return false;
		}
		$option_value = tribe_get_option( static::get_enabled_option_key() );
		if ( '' !== $option_value ) {
			return (bool) $option_value;
		}

		// If option is not explicitly set, the default will be if PayPal is connected.
		return static::is_connected();
	}

	/**
	 * Filter to add any admin notices that might be needed.
	 *
	 * @since 5.3.2
	 *
	 * @param array Array of admin notices.
	 *
	 * @return array
	 */
	public function filter_admin_notices( $notices ) {

		// Check for unsupported currency.
		$selected_currency = tribe_get_option( TC_Settings::$option_currency_code );
		if ( $this->is_enabled() && ! $this->is_currency_supported( $selected_currency ) ){
			$notices[] = [
				'tc-paypal-currency-not-supported',
				[ $this, 'render_unsupported_currency_notice' ],
				[ 'dismiss' => false, 'type' => 'error' ],
			];
		}

		return $notices;
	}

	/**
	 * HTML for notice for unsupported currencies
	 *
	 * @since 5.3.2
	 *
	 * @return string
	 */
	public function render_unsupported_currency_notice() {
		$selected_currency = tribe_get_option( TC_Settings::$option_currency_code );
		$currency_name = tribe( Currency::class )->get_currency_name( $selected_currency );
		// If we don't have the currency name configured, use the currency code instead.
		if ( empty( $currency_name ) ) {
			$currency_name = $selected_currency;
		}
		$notice_link = sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( 'https://developer.paypal.com/docs/reports/reference/paypal-supported-currencies/' ),
			esc_html__( 'here', 'event-tickets' )
		);
		$notice_header = esc_html__( 'PayPal doesn\'t support your selected currency', 'event-tickets' );
		$notice_text = sprintf(
			// Translators: %1$s: Currency Name. %2$s: Link to gateway provider's currency documentation.
			esc_html__( 'Unfortunately PayPal doesn\'t support payments in %1$s. Please try using a different gateway or adjusting your Tickets Commerce currency setting. You can see a list of supported currencies %2$s.', 'event-tickets' ),
			$currency_name,
			$notice_link
		);

		return sprintf(
			'<p><strong>%1$s</strong></p><p>%2$s</p>',
			$notice_header,
			$notice_text
		);
	}

	/**
	 * Renders the paystack checkout template.
	 *
	 * @param \Tribe__Template $template
	 * @return string
	 */
	public function render_checkout_template( \Tribe__Template $template ): string {
		$gateway_key   = static::get_key();
		$template_path = "{$gateway_key}/checkout/container";

		//$args = tribe( Buttons::class )->get_checkout_template_vars();
		$args = array();

		return $template->template( $template_path, $args );
	}
}
