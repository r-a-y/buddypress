<?php
/**
 * BuddyPress Messages Template Tags.
 *
 * @package BuddyPress
 * @subpackage MessagesTemplate
 * @since 1.5.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve private message threads for display in inbox/sentbox/notices.
 *
 * Similar to WordPress's have_posts() function, this function is responsible
 * for querying the database and retrieving private messages for display inside
 * the theme via individual template parts for a member's inbox/sentbox/notices.
 *
 * @since 1.0.0
 *
 * @global BP_Messages_Box_Template $messages_template
 *
 * @param array|string $args {
 *     Array of arguments. All are optional.
 *     @type int    $user_id      ID of the user whose threads are being loaded.
 *                                Default: ID of the logged-in user.
 *     @type string $box          Current "box" view. If not provided here, the current
 *                                view will be inferred from the URL.
 *     @type int    $per_page     Number of results to return per page. Default: 10.
 *     @type int    $max          Max results to return. Default: false.
 *     @type string $type         Type of messages to return. Values: 'all', 'read', 'unread'
 *                                Default: 'all'
 *     @type string $search_terms Terms to which to limit results. Default:
 *                                the value of $_REQUEST['s'].
 *     @type string $page_arg     URL argument used for the pagination param.
 *                                Default: 'mpage'.
 *     @type array  $meta_query   Meta query arguments. Only applicable if $box is
 *                                not 'notices'. See WP_Meta_Query more details.
 * }
 * @return bool True if there are threads to display, otherwise false.
 */
function bp_has_message_threads( $args = array() ) {
	global $messages_template;

	// The default box the user is looking at.
	$current_action = bp_current_action();
	switch ( $current_action ) {
		case 'sentbox' :
		case 'notices' :
		case 'inbox'   :
			$default_box = $current_action;
			break;
		default :
			$default_box = 'inbox';
			break;
	}

	// User ID
	// @todo displayed user for moderators that get this far?
	$user_id = bp_displayed_user_id();

	// Search Terms.
	$search_terms = isset( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : '';

	// Parse the arguments.
	$r = bp_parse_args( $args, array(
		'user_id'      => $user_id,
		'box'          => $default_box,
		'per_page'     => 10,
		'max'          => false,
		'type'         => 'all',
		'search_terms' => $search_terms,
		'page_arg'     => 'mpage', // See https://buddypress.trac.wordpress.org/ticket/3679.
		'meta_query'   => array()
	), 'has_message_threads' );

	// Load the messages loop global up with messages.
	$messages_template = new BP_Messages_Box_Template( $r );

	/**
	 * Filters if there are any message threads to display in inbox/sentbox/notices.
	 *
	 * @since 1.1.0
	 *
	 * @param bool                     $value             Whether or not the message has threads.
	 * @param BP_Messages_Box_Template $messages_template Current message box template object.
	 * @param array                    $r                 Array of parsed arguments passed into function.
	 */
	return apply_filters( 'bp_has_message_threads', $messages_template->has_threads(), $messages_template, $r );
}

/**
 * Output the permalink for a particular thread.
 *
 * @since 2.9.0 Introduced `$user_id` parameter.
 *
 * @param int $thread_id Optional. ID of the thread. Default: current thread
 *                       being iterated on in the loop.
 * @param int $user_id   Optional. ID of the user relative to whom the link
 *                       should be generated. Default: ID of logged-in user.
 */
function bp_message_thread_view_link( $thread_id = 0, $user_id = null ) {
	echo bp_get_message_thread_view_link( $thread_id, $user_id );
}
	/**
	 * Get the permalink of a particular thread.
	 *
	 * @since 2.9.0 Introduced `$user_id` parameter.
	 *
	 * @param int $thread_id Optional. ID of the thread. Default: current
	 *                       thread being iterated on in the loop.
	 * @param int $user_id   Optional. ID of the user relative to whom the link
	 *                       should be generated. Default: ID of logged-in user.
	 * @return string
	 */
	function bp_get_message_thread_view_link( $thread_id = 0, $user_id = null ) {
		global $messages_template;

		if ( empty( $messages_template ) && (int) $thread_id > 0 ) {
			$thread_id = (int) $thread_id;
		} elseif ( ! empty( $messages_template->thread->thread_id ) ) {
			$thread_id = $messages_template->thread->thread_id;
		}

		if ( null === $user_id ) {
			$user_id = bp_loggedin_user_id();
		}

		$domain = bp_core_get_user_domain( $user_id );

		/**
		 * Filters the permalink of a particular thread.
		 *
		 * @since 1.0.0
		 * @since 2.6.0 Added the `$thread_id` parameter.
		 * @since 2.9.0 Added the `$user_id` parameter.
		 *
		 * @param string $value     Permalink of a particular thread.
		 * @param int    $thread_id ID of the thread.
		 * @param int    $user_id   ID of the user.
		 */
		return apply_filters( 'bp_get_message_thread_view_link', trailingslashit( $domain . bp_get_messages_slug() . '/view/' . $thread_id ), $thread_id, $user_id );
	}

/**
 * Output the URL for deleting the current thread.
 *
 * @since 2.9.0 Introduced `$user_id` parameter.
 *
 * @param int $user_id Optional. ID of the user relative to whom the link
 *                     should be generated. Default: ID of logged-in user.
 */
function bp_message_thread_delete_link( $user_id = null ) {
	echo esc_url( bp_get_message_thread_delete_link( $user_id ) );
}
	/**
	 * Generate the URL for deleting the current thread.
	 *
	 * @since 2.9.0 Introduced `$user_id` parameter.
	 *
	 * @param int $user_id Optional. ID of the user relative to whom the link
	 *                     should be generated. Default: ID of logged-in user.
	 * @return string
	 */
	function bp_get_message_thread_delete_link( $user_id = null ) {
		global $messages_template;

		if ( null === $user_id ) {
			$user_id = bp_loggedin_user_id();
		}

		$domain = bp_core_get_user_domain( $user_id );

		/**
		 * Filters the URL for deleting the current thread.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value   URL for deleting the current thread.
		 * @param int    $user_id ID of the user relative to whom the link should be generated.
		 */
		return apply_filters( 'bp_get_message_thread_delete_link', wp_nonce_url( trailingslashit( $domain . bp_get_messages_slug() . '/' . bp_current_action() . '/delete/' . $messages_template->thread->thread_id ), 'messages_delete_thread' ), $user_id );
	}

/**
 * Output the URL used for marking a single message thread as unread.
 *
 * Since this function directly outputs a URL, it is escaped.
 *
 * @since 2.2.0
 * @since 2.9.0 Introduced `$user_id` parameter.
 *
 * @param int $user_id Optional. ID of the user relative to whom the link
 *                     should be generated. Default: ID of logged-in user.
 */
function bp_the_message_thread_mark_unread_url( $user_id = null ) {
	echo esc_url( bp_get_the_message_thread_mark_unread_url( $user_id ) );
}
	/**
	 * Return the URL used for marking a single message thread as unread.
	 *
	 * @since 2.2.0
	 * @since 2.9.0 Introduced `$user_id` parameter.
	 *
	 * @param int $user_id Optional. ID of the user relative to whom the link
	 *                     should be generated. Default: ID of logged-in user.
	 * @return string
	 */
	function bp_get_the_message_thread_mark_unread_url( $user_id = null ) {

		// Get the message ID.
		$id = bp_get_message_thread_id();

		// Get the args to add to the URL.
		$args = array(
			'action'     => 'unread',
			'message_id' => $id
		);

		if ( null === $user_id ) {
			$user_id = bp_loggedin_user_id();
		}

		$domain = bp_core_get_user_domain( $user_id );

		// Base unread URL.
		$url = trailingslashit( $domain . bp_get_messages_slug() . '/' . bp_current_action() . '/unread' );

		// Add the args to the URL.
		$url = add_query_arg( $args, $url );

		// Add the nonce.
		$url = wp_nonce_url( $url, 'bp_message_thread_mark_unread_' . $id );

		/**
		 * Filters the URL used for marking a single message thread as unread.
		 *
		 * @since 2.2.0
		 * @since 2.9.0 Added `$user_id` parameter.
		 *
		 * @param string $url     URL used for marking a single message thread as unread.
		 * @param int    $user_id ID of the user relative to whom the link should be generated.
		 */
		return apply_filters( 'bp_get_the_message_thread_mark_unread_url', $url, $user_id );
	}

/**
 * Output the URL used for marking a single message thread as read.
 *
 * Since this function directly outputs a URL, it is escaped.
 *
 * @since 2.2.0
 * @since 2.9.0 Introduced `$user_id` parameter.
 *
 * @param int $user_id Optional. ID of the user relative to whom the link
 *                     should be generated. Default: ID of logged-in user.
 */
function bp_the_message_thread_mark_read_url( $user_id = null ) {
	echo esc_url( bp_get_the_message_thread_mark_read_url( $user_id ) );
}
	/**
	 * Return the URL used for marking a single message thread as read.
	 *
	 * @since 2.2.0
	 * @since 2.9.0 Introduced `$user_id` parameter.
	 *
	 * @param int $user_id Optional. ID of the user relative to whom the link
	 *                     should be generated. Default: ID of logged-in user.
	 * @return string
	 */
	function bp_get_the_message_thread_mark_read_url( $user_id = null ) {

		// Get the message ID.
		$id = bp_get_message_thread_id();

		// Get the args to add to the URL.
		$args = array(
			'action'     => 'read',
			'message_id' => $id
		);

		if ( null === $user_id ) {
			$user_id = bp_loggedin_user_id();
		}

		$domain = bp_core_get_user_domain( $user_id );

		// Base read URL.
		$url = trailingslashit( $domain . bp_get_messages_slug() . '/' . bp_current_action() . '/read' );

		// Add the args to the URL.
		$url = add_query_arg( $args, $url );

		// Add the nonce.
		$url = wp_nonce_url( $url, 'bp_message_thread_mark_read_' . $id );

		/**
		 * Filters the URL used for marking a single message thread as read.
		 *
		 * @since 2.2.0
		 *
		 * @param string $url     URL used for marking a single message thread as read.
		 * @param int    $user_id ID of the user relative to whom the link should be generated.
		 */
		return apply_filters( 'bp_get_the_message_thread_mark_read_url', $url );
	}

/**
 * Output a thread's total message count.
 *
 * @since 2.2.0
 *
 * @param int|bool $thread_id Optional. ID of the thread. Defaults to current thread ID.
 */
function bp_message_thread_total_count( $thread_id = false ) {
	echo bp_get_message_thread_total_count( $thread_id );
}
	/**
	 * Get the current thread's total message count.
	 *
	 * @since 2.2.0
	 *
	 * @param int|bool $thread_id Optional. ID of the thread.
	 *                            Defaults to current thread ID.
	 * @return int
	 */
	function bp_get_message_thread_total_count( $thread_id = false ) {
		if ( false === $thread_id ) {
			$thread_id = bp_get_message_thread_id();
		}

		$thread_template = new BP_Messages_Thread_Template( $thread_id, 'ASC', array(
			'update_meta_cache' => false
		) );

		$count = 0;
		if ( ! empty( $thread_template->message_count ) ) {
			$count = intval( $thread_template->message_count );
		}

		/**
		 * Filters the current thread's total message count.
		 *
		 * @since 2.2.0
		 * @since 2.6.0 Added the `$thread_id` parameter.
		 *
		 * @param int $count     Current thread total message count.
		 * @param int $thread_id ID of the queried thread.
		 */
		return apply_filters( 'bp_get_message_thread_total_count', $count, $thread_id );
	}

/**
 * Output markup for the current thread's total and unread count.
 *
 * @since 2.2.0
 *
 * @param int|bool $thread_id Optional. ID of the thread. Default: current thread ID.
 */
function bp_message_thread_total_and_unread_count( $thread_id = false ) {
	echo bp_get_message_thread_total_and_unread_count( $thread_id );
}
	/**
	 * Get markup for the current thread's total and unread count.
	 *
	 * @param int|bool $thread_id Optional. ID of the thread. Default: current thread ID.
	 * @return string Markup displaying the total and unread count for the thread.
	 */
	function bp_get_message_thread_total_and_unread_count( $thread_id = false ) {
		if ( false === $thread_id ) {
			$thread_id = bp_get_message_thread_id();
		}

		$total  = bp_get_message_thread_total_count( $thread_id );
		$unread = bp_get_message_thread_unread_count( $thread_id );

		return sprintf(
			/* translators: 1: total number, 2: accessibility text: number of unread messages */
			'<span class="thread-count">(%1$s)</span> <span class="bp-screen-reader-text">%2$s</span>',
			number_format_i18n( $total ),
			sprintf( _n( '%d unread', '%d unread', $unread, 'buddypress' ), number_format_i18n( $unread ) )
		);
	}

/**
 * Output the unread messages count for the current inbox.
 *
 * @since 2.6.x Added $user_id argument.
 *
 * @param int $user_id The user ID.
 *
 * @return int $unread_count Total inbox unread count for user.
 */
function bp_total_unread_messages_count( $user_id = 0 ) {
	echo bp_get_total_unread_messages_count( $user_id );
}
	/**
	 * Get the unread messages count for the current inbox.
	 *
	 * @since 2.6.x Added $user_id argument.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return int $unread_count Total inbox unread count for user.
	 */
	function bp_get_total_unread_messages_count( $user_id = 0 ) {

		/**
		 * Filters the unread messages count for the current inbox.
		 *
		 * @since 1.0.0
		 *
		 * @param int $value Unread messages count for the current inbox.
		 */
		return apply_filters( 'bp_get_total_unread_messages_count', BP_Messages_Thread::get_inbox_count( $user_id ) );
	}

/**
 * Output the Private Message search form.
 *
 * @todo  Move markup to template part in: /members/single/messages/search.php
 * @since 1.6.0
 */
function bp_message_search_form() {

	// Get the default search text.
	$default_search_value = bp_get_search_default_text( 'messages' );

	// Setup a few values based on what's being searched for.
	$search_submitted     = ! empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value;
	$search_placeholder   = ( $search_submitted === $default_search_value ) ? ' placeholder="' .  esc_attr( $search_submitted ) . '"' : '';
	$search_value         = ( $search_submitted !== $default_search_value ) ? ' value="'       .  esc_attr( $search_submitted ) . '"' : '';

	// Start the output buffer, so form can be filtered.
	ob_start(); ?>

	<form action="" method="get" id="search-message-form">
		<label for="messages_search" class="bp-screen-reader-text"><?php
			/* translators: accessibility text */
			esc_html_e( 'Search Messages', 'buddypress' );
		?></label>
		<input type="text" name="s" id="messages_search"<?php echo $search_placeholder . $search_value; ?> />
		<input type="submit" class="button" id="messages_search_submit" name="messages_search_submit" value="<?php esc_html_e( 'Search', 'buddypress' ); ?>" />
	</form>

	<?php

	// Get the search form from the above output buffer.
	$search_form_html = ob_get_clean();

	/**
	 * Filters the private message component search form.
	 *
	 * @since 2.2.0
	 *
	 * @param string $search_form_html HTML markup for the message search form.
	 */
	echo apply_filters( 'bp_message_search_form', $search_form_html );
}

/**
 * Echo the form action for Messages HTML forms.
 */
function bp_messages_form_action() {
	echo esc_url( bp_get_messages_form_action() );
}
	/**
	 * Return the form action for Messages HTML forms.
	 *
	 * @return string The form action.
	 */
	function bp_get_messages_form_action() {

		/**
		 * Filters the form action for Messages HTML forms.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value The form action.
		 */
		return apply_filters( 'bp_get_messages_form_action', trailingslashit( bp_displayed_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() . '/' . bp_action_variable( 0 ) ) );
	}

/**
 * Output the default username for the recipient box.
 */
function bp_messages_username_value() {
	echo esc_attr( bp_get_messages_username_value() );
}
	/**
	 * Get the default username for the recipient box.
	 *
	 * @return string
	 */
	function bp_get_messages_username_value() {
		if ( isset( $_COOKIE['bp_messages_send_to'] ) ) {

			/**
			 * Filters the default username for the recipient box.
			 *
			 * Value passed into filter is dependent on if the 'bp_messages_send_to'
			 * cookie or 'r' $_GET parameter is set.
			 *
			 * @since 1.0.0
			 *
			 * @param string $value Default user name.
			 */
			return apply_filters( 'bp_get_messages_username_value', $_COOKIE['bp_messages_send_to'] );
		} elseif ( isset( $_GET['r'] ) && !isset( $_COOKIE['bp_messages_send_to'] ) ) {
			/** This filter is documented in bp-messages-template.php */
			return apply_filters( 'bp_get_messages_username_value', $_GET['r'] );
		}
	}

/**
 * Output the default value for the Subject field.
 */
function bp_messages_subject_value() {
	echo esc_attr( bp_get_messages_subject_value() );
}
	/**
	 * Get the default value for the Subject field.
	 *
	 * Will get a value out of $_POST['subject'] if available (ie after a
	 * failed submission).
	 *
	 * @return string
	 */
	function bp_get_messages_subject_value() {

		// Sanitized in bp-messages-filters.php.
		$subject = ! empty( $_POST['subject'] )
			? $_POST['subject']
			: '';

		/**
		 * Filters the default value for the subject field.
		 *
		 * @since 1.0.0
		 *
		 * @param string $subject The default value for the subject field.
		 */
		return apply_filters( 'bp_get_messages_subject_value', $subject );
	}

/**
 * Output the default value for the Compose content field.
 */
function bp_messages_content_value() {
	echo esc_textarea( bp_get_messages_content_value() );
}
	/**
	 * Get the default value fo the Compose content field.
	 *
	 * Will get a value out of $_POST['content'] if available (ie after a
	 * failed submission).
	 *
	 * @return string
	 */
	function bp_get_messages_content_value() {

		// Sanitized in bp-messages-filters.php.
		$content = ! empty( $_POST['content'] )
			? $_POST['content']
			: '';

		/**
		 * Filters the default value for the content field.
		 *
		 * @since 1.0.0
		 *
		 * @param string $content The default value for the content field.
		 */
		return apply_filters( 'bp_get_messages_content_value', $content );
	}

/**
 * Output the markup for the message type dropdown.
 */
function bp_messages_options() {
?>

	<label for="message-type-select" class="bp-screen-reader-text"><?php
		/* translators: accessibility text */
		_e( 'Select:', 'buddypress' );
	?></label>
	<select name="message-type-select" id="message-type-select">
		<option value=""><?php _e( 'Select', 'buddypress' ); ?></option>
		<option value="read"><?php _ex('Read', 'Message dropdown filter', 'buddypress') ?></option>
		<option value="unread"><?php _ex('Unread', 'Message dropdown filter', 'buddypress') ?></option>
		<option value="all"><?php _ex('All', 'Message dropdown filter', 'buddypress') ?></option>
	</select> &nbsp;

	<?php if ( ! bp_is_current_action( 'sentbox' ) && ! bp_is_current_action( 'notices' ) ) : ?>

		<a href="#" id="mark_as_read"><?php _ex('Mark as Read', 'Message management markup', 'buddypress') ?></a> &nbsp;
		<a href="#" id="mark_as_unread"><?php _ex('Mark as Unread', 'Message management markup', 'buddypress') ?></a> &nbsp;

		<?php wp_nonce_field( 'bp_messages_mark_messages_read', 'mark-messages-read-nonce', false ); ?>
		<?php wp_nonce_field( 'bp_messages_mark_messages_unread', 'mark-messages-unread-nonce', false ); ?>

	<?php endif; ?>

	<a href="#" id="delete_<?php echo bp_current_action(); ?>_messages"><?php _e( 'Delete Selected', 'buddypress' ); ?></a> &nbsp;
	<?php wp_nonce_field( 'bp_messages_delete_selected', 'delete-selected-nonce', false ); ?>
<?php
}

/**
 * Output the dropdown for bulk management of messages.
 *
 * @since 2.2.0
 */
function bp_messages_bulk_management_dropdown() {
	?>
	<label class="bp-screen-reader-text" for="messages-select"><?php
		_e( 'Select Bulk Action', 'buddypress' );
	?></label>
	<select name="messages_bulk_action" id="messages-select">
		<option value="" selected="selected"><?php _e( 'Bulk Actions', 'buddypress' ); ?></option>
		<option value="read"><?php _e( 'Mark read', 'buddypress' ); ?></option>
		<option value="unread"><?php _e( 'Mark unread', 'buddypress' ); ?></option>
		<option value="delete"><?php _e( 'Delete', 'buddypress' ); ?></option>
		<?php
			/**
			 * Action to add additional options to the messages bulk management dropdown.
			 *
			 * @since 2.3.0
			 */
			do_action( 'bp_messages_bulk_management_dropdown' );
		?>
	</select>
	<input type="submit" id="messages-bulk-manage" class="button action" value="<?php esc_attr_e( 'Apply', 'buddypress' ); ?>">
	<?php
}

/**
 * Output the messages component slug.
 *
 * @since 1.5.0
 *
 */
function bp_messages_slug() {
	echo bp_get_messages_slug();
}
	/**
	 * Return the messages component slug.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function bp_get_messages_slug() {

		/**
		 * Filters the messages component slug.
		 *
		 * @since 1.5.0
		 *
		 * @param string $slug Messages component slug.
		 */
		return apply_filters( 'bp_get_messages_slug', buddypress()->messages->slug );
	}

/**
 * Generate markup for currently active notices.
 */
function bp_message_get_notices() {
	$notice = BP_Messages_Notice::get_active();

	if ( empty( $notice ) ) {
		return false;
	}

	$closed_notices = bp_get_user_meta( bp_loggedin_user_id(), 'closed_notices', true );

	if ( empty( $closed_notices ) ) {
		$closed_notices = array();
	}

	if ( is_array( $closed_notices ) ) {
		if ( !in_array( $notice->id, $closed_notices ) && $notice->id ) {
			?>
			<div id="message" class="info notice" rel="n-<?php echo esc_attr( $notice->id ); ?>">
				<p>
					<strong><?php echo stripslashes( wp_filter_kses( $notice->subject ) ) ?></strong><br />
					<?php echo stripslashes( wp_filter_kses( $notice->message) ) ?>
					<button type="button" id="close-notice" class="bp-tooltip" data-bp-tooltip="<?php esc_attr_e( 'Dismiss this notice', 'buddypress' ) ?>"><span class="bp-screen-reader-text"><?php _e( 'Dismiss this notice', 'buddypress' ) ?></span> <span aria-hidden="true">&Chi;</span></button>
					<?php wp_nonce_field( 'bp_messages_close_notice', 'close-notice-nonce' ); ?>
				</p>
			</div>
			<?php
		}
	}
}

/**
 * Output the URL for the Private Message link in member profile headers.
 */
function bp_send_private_message_link() {
	echo esc_url( bp_get_send_private_message_link() );
}
	/**
	 * Generate the URL for the Private Message link in member profile headers.
	 *
	 * @return bool|string False on failure, otherwise the URL.
	 */
	function bp_get_send_private_message_link() {

		if ( bp_is_my_profile() || ! is_user_logged_in() ) {
			return false;
		}

		/**
		 * Filters the URL for the Private Message link in member profile headers.
		 *
		 * @since 1.2.10
		 *
		 * @param string $value URL for the Private Message link in member profile headers.
		 */
		return apply_filters( 'bp_get_send_private_message_link', wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( bp_displayed_user_id() ) ) );
	}

/**
 * Output the 'Private Message' button for member profile headers.
 *
 * Explicitly named function to avoid confusion with public messages.
 *
 * @since 1.2.6
 *
 */
function bp_send_private_message_button() {
	echo bp_get_send_message_button();
}

/**
 * Output the 'Private Message' button for member profile headers.
 *
 * @since 1.2.0
 * @since 3.0.0 Added `$args` parameter.
 *
 * @param array|string $args See {@link bp_get_send_message_button()}.
 */
function bp_send_message_button( $args = '' ) {
	echo bp_get_send_message_button( $args );
}
	/**
	 * Generate the 'Private Message' button for member profile headers.
	 *
	 * @since 1.2.0
	 * @since 3.0.0 Added `$args` parameter.
	 *
	 * @param array|string $args {
	 *     All arguments are optional. See {@link BP_Button} for complete
	 *     descriptions.
	 *     @type string $id                Default: 'private_message'.
	 *     @type string $component         Default: 'messages'.
	 *     @type bool   $must_be_logged_in Default: true.
	 *     @type bool   $block_self        Default: true.
	 *     @type string $wrapper_id        Default: 'send-private-message'.
	 *     @type string $link_href         Default: the private message link for
	 *                                     the current member in the loop.
	 *     @type string $link_text         Default: 'Private Message'.
	 *     @type string $link_class        Default: 'send-message'.
	 * }
	 * @return string
	 */
	function bp_get_send_message_button( $args = '' ) {

		$r = bp_parse_args( $args, array(
			'id'                => 'private_message',
			'component'         => 'messages',
			'must_be_logged_in' => true,
			'block_self'        => true,
			'wrapper_id'        => 'send-private-message',
			'link_href'         => bp_get_send_private_message_link(),
			'link_text'         => __( 'Private Message', 'buddypress' ),
			'link_class'        => 'send-message',
		) );


		// Note: 'bp_get_send_message_button' is a legacy filter. Use
		// 'bp_get_send_message_button_args' instead. See #4536.
		return apply_filters( 'bp_get_send_message_button',

			/**
			 * Filters the "Private Message" button for member profile headers.
			 *
			 * @since 1.8.0
			 *
			 * @param array $value See {@link BP_Button}.
			 */
			bp_get_button( apply_filters( 'bp_get_send_message_button_args', $r ) )
		);
	}

/**
 * Output the URL of the Messages AJAX loader gif.
 */
function bp_message_loading_image_src() {
	echo esc_url( bp_get_message_loading_image_src() );
}
	/**
	 * Get the URL of the Messages AJAX loader gif.
	 *
	 * @return string
	 */
	function bp_get_message_loading_image_src() {

		/**
		 * Filters the URL of the Messages AJAX loader gif.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value URL of the Messages AJAX loader gif.
		 */
		return apply_filters( 'bp_get_message_loading_image_src', buddypress()->messages->image_base . '/ajax-loader.gif' );
	}

/**
 * Output the markup for the message recipient tabs.
 */
function bp_message_get_recipient_tabs() {
	$recipients = explode( ' ', bp_get_message_get_recipient_usernames() );

	foreach ( $recipients as $recipient ) {

		$user_id = bp_is_username_compatibility_mode()
			? bp_core_get_userid( $recipient )
			: bp_core_get_userid_from_nicename( $recipient );

		if ( ! empty( $user_id ) ) : ?>

			<li id="un-<?php echo esc_attr( $recipient ); ?>" class="friend-tab">
				<span><?php
					echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 15, 'height' => 15 ) );
					echo bp_core_get_userlink( $user_id );
				?></span>
			</li>

		<?php endif;
	}
}

/**
 * Output recipient usernames for prefilling the 'To' field on the Compose screen.
 */
function bp_message_get_recipient_usernames() {
	echo esc_attr( bp_get_message_get_recipient_usernames() );
}
	/**
	 * Get the recipient usernames for prefilling the 'To' field on the Compose screen.
	 *
	 * @return string
	 */
	function bp_get_message_get_recipient_usernames() {

		// Sanitized in bp-messages-filters.php.
		$recipients = isset( $_GET['r'] )
			? $_GET['r']
			: '';

		/**
		 * Filters the recipients usernames for prefilling the 'To' field on the Compose screen.
		 *
		 * @since 1.0.0
		 *
		 * @param string $recipients Recipients usernames for 'To' field prefilling.
		 */
		return apply_filters( 'bp_get_message_get_recipient_usernames', $recipients );
	}

/**
 * Initialize the messages template loop for a specific thread.
 *
 * @param array|string $args {
 *     Array of arguments. All are optional.
 *     @type int    $thread_id         ID of the thread whose messages you are displaying.
 *                                     Default: if viewing a thread, the thread ID will be parsed from
 *                                     the URL (bp_action_variable( 0 )).
 *     @type string $order             'ASC' or 'DESC'. Default: 'ASC'.
 *     @type bool   $update_meta_cache Whether to pre-fetch metadata for
 *                                     queried message items. Default: true.
 * }
 * @return bool True if there are messages to display, otherwise false.
 */
function bp_thread_has_messages( $args = '' ) {
	global $thread_template;

	$r = bp_parse_args( $args, array(
		'thread_id'         => false,
		'order'             => 'ASC',
		'update_meta_cache' => true,
	), 'thread_has_messages' );

	if ( empty( $r['thread_id'] ) && bp_is_messages_component() && bp_is_current_action( 'view' ) ) {
		$r['thread_id'] = (int) bp_action_variable( 0 );
	}

	// Set up extra args.
	$extra_args = $r;
	unset( $extra_args['thread_id'], $extra_args['order'] );

	$thread_template = new BP_Messages_Thread_Template( $r['thread_id'], $r['order'], $extra_args );

	return $thread_template->has_messages();
}

/** Embeds *******************************************************************/

/**
 * Enable oEmbed support for Messages.
 *
 * @since 1.5.0
 *
 * @see BP_Embed
 */
function bp_messages_embed() {
	add_filter( 'embed_post_id',         'bp_get_the_thread_message_id' );
	add_filter( 'bp_embed_get_cache',    'bp_embed_message_cache',      10, 3 );
	add_action( 'bp_embed_update_cache', 'bp_embed_message_save_cache', 10, 3 );
}
add_action( 'thread_loop_start', 'bp_messages_embed' );

/**
 * Fetch a private message item's cached embeds.
 *
 * Used during {@link BP_Embed::parse_oembed()} via {@link bp_messages_embed()}.
 *
 * @since 2.2.0
 *
 * @param string $cache    An empty string passed by BP_Embed::parse_oembed() for
 *                         functions like this one to filter.
 * @param int    $id       The ID of the message item.
 * @param string $cachekey The cache key generated in BP_Embed::parse_oembed().
 * @return mixed The cached embeds for this message item.
 */
function bp_embed_message_cache( $cache, $id, $cachekey ) {
	return bp_messages_get_meta( $id, $cachekey );
}

/**
 * Set a private message item's embed cache.
 *
 * Used during {@link BP_Embed::parse_oembed()} via {@link bp_messages_embed()}.
 *
 * @since 2.2.0
 *
 * @param string $cache    An empty string passed by BP_Embed::parse_oembed() for
 *                         functions like this one to filter.
 * @param string $cachekey The cache key generated in BP_Embed::parse_oembed().
 * @param int    $id       The ID of the message item.
 */
function bp_embed_message_save_cache( $cache, $cachekey, $id ) {
	bp_messages_update_meta( $id, $cachekey, $cache );
}
