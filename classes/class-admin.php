<?php
namespace paystack\tec\classes;

/**
 * Admin Class.
 *
 * @package paystack-tec-integration
 */
class Admin {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \paystack\tec\classes\Admin()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'tribe_events_cost_table', array( $this, 'display_paystack_fields' ), 15 );
		add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\member_directory\classes\Admin()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Displays the Sub account and the Split payment boxes on the event.
	 */
	public function display_paystack_fields() {
		$current_id = get_queried_object_id();
		$subaccount = get_post_meta( $current_id, 'paystack_sub_account', true );
		$splittrans = get_post_meta( $current_id, 'paystack_split_transaction', true );

		wp_nonce_field( 'edit_fields', '_paystack_nonce' );
		?>
		<table id="paystack_details" class="eventtable">
			<tr>
				<td colspan="2" class="tribe_sectionheader">
					<h4><?php echo esc_html__( 'Paystack', 'the-events-calendar' ); ?></h4></td>
			</tr>
			<tr>
				<td style="width:172px;"><?php esc_html_e( 'Sub Account:', 'the-events-calendar' ); ?></td>
				<td>
					<input tabindex="<?php tribe_events_tab_index(); ?>" type='text' id='PaytsackSubAccount' name='PaytsackSubAccount' value='<?php echo esc_attr( $subaccount ); ?>' />
				</td>
			</tr>
			<tr>
				<td style="width:172px;"><?php esc_html_e( 'Split Transaction:', 'the-events-calendar' ); ?></td>
				<td>
					<input tabindex="<?php tribe_events_tab_index(); ?>" type='text' id='PaytsackSplitTransaction' name='PaytsackSplitTransaction' value='<?php echo esc_attr( $splittrans ); ?>' />
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Saves the paystack fields
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 * @return void
	 */
	public function save_meta( $post_id, $post ) {

		// nonce check
		if ( ! isset( $_POST[ '_paystack_nonce' ] ) || ! wp_verify_nonce( $_POST[ '_paystack_nonce' ], 'edit_fields' ) ) {
			return $post_id;
		}

		// check current user permissions
		$post_type = get_post_type_object( $post->post_type );

		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		// Do not save the data if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// define your own post type here
		if ( 'tribe_events' !== $post->post_type ) {
			return $post_id;
		}

		if ( isset( $_POST['PaytsackSubAccount'] ) && ! empty( $_POST['PaytsackSubAccount'] ) ) {
			update_post_meta( $post_id, 'paystack_sub_account', sanitize_text_field( $_POST['PaytsackSubAccount'] ) );
		} else {
			delete_post_meta( $post_id, 'paystack_sub_account' );
		}
		if ( isset( $_POST['PaytsackSplitTransaction'] ) && ! empty( $_POST['PaytsackSplitTransaction'] ) ) {
			update_post_meta( $post_id, 'paystack_split_transaction', sanitize_text_field( $_POST['PaytsackSplitTransaction'] ) );
		} else {
			delete_post_meta( $post_id, 'paystack_split_transaction' );
		}

		return $post_id;

	}
}
