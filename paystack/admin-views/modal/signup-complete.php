<?php
/**
 * The Template for displaying the Tickets Commerce Paystack Modal when connected.
 *
 * @version 5.2.1
 *
 * @since   5.2.1
 */

$request_vars = tribe_get_request_vars();

// Bail if we're not in the correct context, when Paystack was connected.
if ( empty( $request_vars['tc-status'] ) || 'paystack-signup-complete' !== $request_vars['tc-status'] ) {
	return;
}

$dialog_view = tribe( 'dialog.view' );
$content     = $this->template( 'settings/tickets-commerce/paystack/modal/signup-complete/content', [], false );

$args = [
	'append_target'           => '#paystack-connected-modal-target',
	'button_id'               => 'paystack-connected-modal-button',
	'content_wrapper_classes' => 'tribe-dialog__wrapper tribe-tickets__admin-container event-tickets tribe-common tribe-modal__wrapper--gateway-connected',
	'title'                   => esc_html__( "You are now connected to Paystack, here's what's next...", 'paystack-for-events-calendar' ),
	'title_classes'           => [
		'tribe-dialog__title',
		'tribe-modal__title',
		'tribe-common-h5',
		'tribe-modal__title--gateway-connected',
	],
];

ob_start();
$dialog_view->render_modal( $content, $args, 'paystack-connected-modal-id' );
$modal_content = ob_get_clean();

$modal  = '<div class="tribe-common event-tickets">';
$modal .= '<span id="' . esc_attr( 'paystack-connected-modal-target' ) . '"></span>';
$modal .= $modal_content;
$modal .= '</div>';

echo $modal; // phpcs:ignore
