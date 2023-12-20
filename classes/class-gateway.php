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
	protected static $supported_currencies = array( 'NGN', 'GHS', 'USD', 'KES', 'ZAR', 'XOF', 'EGP' );

	/**
	 * @inheritDoc
	 */
	public static function get_label() {
		return __( 'Paystack', 'paystack-for-events-calendar' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_admin_notices() {
		$notices = array(
			array(
				'slug'    => 'tc-paystack-currency-not-supported',
				'content' => __( 'Currency not supported', 'paystack-for-events-calendar' ),
				'type'    => 'error',
			),
		);

		return $notices;
	}

	/**
	 * @inheritDoc
	 */
	public function get_logo_url(): string {
		return PS_TEC_URL . '/assets/images/icon.png';
	}

	/**
	 * @inheritDoc
	 */
	public function get_subtitle(): string {
		return __( 'Accept credit/debit card, Apple pay,mobile money, bank transfer, EFT, USSD, and QR payments.', 'paystack-for-events-calendar' );
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

		// If option is not explicitly set, the default will be if Paystack is connected.
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
	public function filter_admin_notices() {
		// Check for unsupported currency.
		$selected_currency = tribe_get_option( TC_Settings::$option_currency_code );
		if ( $this->is_enabled() && ! $this->is_currency_supported( $selected_currency ) ){
			?>
			<div class="notice notice-error">
				<?php echo wp_kses_post( $this->render_unsupported_currency_notice() ); ?>
			</div>
			<?php
		}
	}

	/**
	 * HTML for notice for unsupported currencies
	 *
	 * @since 5.3.2
	 *
	 * @return string
	 */
	public function render_unsupported_currency_notice() {
		$notice_header = esc_html__( 'Paystack doesn\'t support your selected currency', 'paystack-for-events-calendar' );
		$notice_text = esc_html__( 'Paystack does not support your store currency. Kindly set it to either NGN (&#8358;), GHS (&#x20b5;), USD (&#36;), KES (KSh), ZAR (R), XOF (CFA), or EGP (&#163;) ', 'paystack-for-events-calendar' );

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

	public static function get_option( $key = '' ) {
		if ( '' === $key ) {
			return false;
		}
		$options = get_option( 'tec_tickets_commerce_paystack_account' );
		if ( isset( $options[ $key ] ) && '' !== $options[ $key ] ) {
			return $options[ $key ];
		}
		// If option is not explicitly set, the default will be if Paystack is connected.
		return false;
	}
}
