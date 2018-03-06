<?php

/**
 * Check whether there are more threads to iterate over.
 *
 * @return bool
 */
function bp_message_threads() {
	global $messages_template;
	return $messages_template->message_threads();
}

/**
 * Set up the current thread inside the loop.
 *
 * @return object
 */
function bp_message_thread() {
	global $messages_template;
	return $messages_template->the_message_thread();
}

/**
 * Output the ID of the current thread in the loop.
 */
function bp_message_thread_id() {
	echo bp_get_message_thread_id();
}
	/**
	 * Get the ID of the current thread in the loop.
	 *
	 * @return int
	 */
	function bp_get_message_thread_id() {
		global $messages_template;

		/**
		 * Filters the ID of the current thread in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param int $thread_id ID of the current thread in the loop.
		 */
		return apply_filters( 'bp_get_message_thread_id', $messages_template->thread->thread_id );
	}

/**
 * Output the subject of the current thread in the loop.
 */
function bp_message_thread_subject() {
	echo bp_get_message_thread_subject();
}
	/**
	 * Get the subject of the current thread in the loop.
	 *
	 * @return string
	 */
	function bp_get_message_thread_subject() {
		global $messages_template;

		/**
		 * Filters the subject of the current thread in the loop.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Subject of the current thread in the loop.
		 */
		return apply_filters( 'bp_get_message_thread_subject', $messages_template->thread->last_message_subject );
	}

/**
 * Output an excerpt from the current message in the loop.
 */
function bp_message_thread_excerpt() {
	echo bp_get_message_thread_excerpt();
}
	/**
	 * Generate an excerpt from the current message in the loop.
	 *
	 * @return string
	 */
	function bp_get_message_thread_excerpt() {
		global $messages_template;

		/**
		 * Filters the excerpt of the current thread in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Excerpt of the current thread in the loop.
		 */
		return apply_filters( 'bp_get_message_thread_excerpt', strip_tags( bp_create_excerpt( $messages_template->thread->last_message_content, 75 ) ) );
	}

/**
 * Output the thread's last message content.
 *
 * When viewing your Inbox, the last message is the most recent message in
 * the thread of which you are *not* the author.
 *
 * When viewing your Sentbox, last message is the most recent message in
 * the thread of which you *are* the member.
 *
 * @since 2.0.0
 */
function bp_message_thread_content() {
	echo bp_get_message_thread_content();
}
	/**
	 * Return the thread's last message content.
	 *
	 * When viewing your Inbox, the last message is the most recent message in
	 * the thread of which you are *not* the author.
	 *
	 * When viewing your Sentbox, last message is the most recent message in
	 * the thread of which you *are* the member.
	 *
	 * @since 2.0.0
	 *
	 * @return string The raw content of the last message in the thread.
	 */
	function bp_get_message_thread_content() {
		global $messages_template;

		/**
		 * Filters the content of the last message in the thread.
		 *
		 * @since 2.0.0
		 *
		 * @param string $last_message_content Content of the last message in the thread.
		 */
		return apply_filters( 'bp_get_message_thread_content', $messages_template->thread->last_message_content );
	}

/**
 * Output a link to the page of the current thread's last author.
 */
function bp_message_thread_from() {
	echo bp_get_message_thread_from();
}
	/**
	 * Get a link to the page of the current thread's last author.
	 *
	 * @return string
	 */
	function bp_get_message_thread_from() {
		global $messages_template;

		/**
		 * Filters the link to the page of the current thread's last author.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Link to the page of the current thread's last author.
		 */
		return apply_filters( 'bp_get_message_thread_from', bp_core_get_userlink( $messages_template->thread->last_sender_id ) );
	}

/**
 * Output links to the pages of the current thread's recipients.
 */
function bp_message_thread_to() {
	echo bp_get_message_thread_to();
}
	/**
	 * Generate HTML links to the pages of the current thread's recipients.
	 *
	 * @return string
	 */
	function bp_get_message_thread_to() {
		global $messages_template;

		/**
		 * Filters the HTML links to the pages of the current thread's recipients.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value HTML links to the pages of the current thread's recipients.
		 */
		return apply_filters( 'bp_message_thread_to', BP_Messages_Thread::get_recipient_links($messages_template->thread->recipients ) );
	}


/**
 * Output the CSS class for the current thread.
 */
function bp_message_css_class() {
	echo esc_attr( bp_get_message_css_class() );
}
	/**
	 * Generate the CSS class for the current thread.
	 *
	 * @return string
	 */
	function bp_get_message_css_class() {
		global $messages_template;

		$class = false;

		if ( $messages_template->current_thread % 2 == 1 ) {
			$class .= 'alt';
		}

		/**
		 * Filters the CSS class for the current thread.
		 *
		 * @since 1.2.10
		 *
		 * @param string $class Class string to be added to the list of classes.
		 */
		return apply_filters( 'bp_get_message_css_class', trim( $class ) );
	}

/**
 * Check whether the current thread has unread items.
 *
 * @return bool True if there are unread items, otherwise false.
 */
function bp_message_thread_has_unread() {
	global $messages_template;

	$retval = ! empty( $messages_template->thread->unread_count )
		? true
		: false;

	/**
	 * Filters whether or not a message thread has unread items.
	 *
	 * @since 2.1.0
	 *
	 * @param bool $retval Whether or not a message thread has unread items.
	 */
	return apply_filters( 'bp_message_thread_has_unread', $retval );
}

/**
 * Output the current thread's unread count.
 */
function bp_message_thread_unread_count() {
	echo esc_html( bp_get_message_thread_unread_count() );
}
	/**
	 * Get the current thread's unread count.
	 *
	 * @return int
	 */
	function bp_get_message_thread_unread_count() {
		global $messages_template;

		$count = ! empty( $messages_template->thread->unread_count )
			? (int) $messages_template->thread->unread_count
			: false;

		/**
		 * Filters the current thread's unread count.
		 *
		 * @since 1.0.0
		 *
		 * @param int $count Current thread unread count.
		 */
		return apply_filters( 'bp_get_message_thread_unread_count', $count );
	}


/**
 * Output the unformatted date of the last post in the current thread.
 */
function bp_message_thread_last_post_date_raw() {
	echo bp_get_message_thread_last_post_date_raw();
}
	/**
	 * Get the unformatted date of the last post in the current thread.
	 *
	 * @return string
	 */
	function bp_get_message_thread_last_post_date_raw() {
		global $messages_template;

		/**
		 * Filters the unformatted date of the last post in the current thread.
		 *
		 * @since 2.1.0
		 *
		 * @param string $last_message_date Unformatted date of the last post in the current thread.
		 */
		return apply_filters( 'bp_get_message_thread_last_message_date', $messages_template->thread->last_message_date );
	}

/**
 * Output the nicely formatted date of the last post in the current thread.
 */
function bp_message_thread_last_post_date() {
	echo bp_get_message_thread_last_post_date();
}
	/**
	 * Get the nicely formatted date of the last post in the current thread.
	 *
	 * @return string
	 */
	function bp_get_message_thread_last_post_date() {

		/**
		 * Filters the nicely formatted date of the last post in the current thread.
		 *
		 * @since 2.1.0
		 *
		 * @param string $value Formatted date of the last post in the current thread.
		 */
		return apply_filters( 'bp_get_message_thread_last_post_date', bp_format_time( strtotime( bp_get_message_thread_last_post_date_raw() ) ) );
	}

/**
 * Output the avatar for the last sender in the current message thread.
 *
 * @see bp_get_message_thread_avatar() for a description of arguments.
 *
 * @param array|string $args See {@link bp_get_message_thread_avatar()}.
 */
function bp_message_thread_avatar( $args = '' ) {
	echo bp_get_message_thread_avatar( $args );
}
	/**
	 * Return the avatar for the last sender in the current message thread.
	 *
	 * @see bp_core_fetch_avatar() For a description of arguments and
	 *      return values.
	 *
	 * @param array|string $args {
	 *     Arguments are listed here with an explanation of their defaults.
	 *     For more information about the arguments, see
	 *     {@link bp_core_fetch_avatar()}.
	 *     @type string      $type   Default: 'thumb'.
	 *     @type int|bool    $width  Default: false.
	 *     @type int|bool    $height Default: false.
	 *     @type string      $class  Default: 'avatar'.
	 *     @type string|bool $id     Default: false.
	 *     @type string      $alt    Default: 'Profile picture of [display name]'.
	 * }
	 * @return string User avatar string.
	 */
	function bp_get_message_thread_avatar( $args = '' ) {
		global $messages_template;

		$fullname = bp_core_get_user_displayname( $messages_template->thread->last_sender_id );
		$alt      = sprintf( __( 'Profile picture of %s', 'buddypress' ), $fullname );

		$r = bp_parse_args( $args, array(
			'type'   => 'thumb',
			'width'  => false,
			'height' => false,
			'class'  => 'avatar',
			'id'     => false,
			'alt'    => $alt
		) );

		/**
		 * Filters the avatar for the last sender in the current message thread.
		 *
		 * @since 1.0.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $value User avatar string.
		 * @param array  $r     Array of parsed arguments.
		 */
		return apply_filters( 'bp_get_message_thread_avatar', bp_core_fetch_avatar( array(
			'item_id' => $messages_template->thread->last_sender_id,
			'type'    => $r['type'],
			'alt'     => $r['alt'],
			'css_id'  => $r['id'],
			'class'   => $r['class'],
			'width'   => $r['width'],
			'height'  => $r['height'],
		) ), $r );
	}

/**
 * Generate the "Viewing message x to y (of z messages)" string for a loop.
 */
function bp_messages_pagination_count() {
	global $messages_template;

	$start_num = intval( ( $messages_template->pag_page - 1 ) * $messages_template->pag_num ) + 1;
	$from_num  = bp_core_number_format( $start_num );
	$to_num    = bp_core_number_format( ( $start_num + ( $messages_template->pag_num - 1 ) > $messages_template->total_thread_count ) ? $messages_template->total_thread_count : $start_num + ( $messages_template->pag_num - 1 ) );
	$total     = bp_core_number_format( $messages_template->total_thread_count );

	if ( 1 == $messages_template->total_thread_count ) {
		$message = __( 'Viewing 1 message', 'buddypress' );
	} else {
		$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s message', 'Viewing %1$s - %2$s of %3$s messages', $messages_template->total_thread_count, 'buddypress' ), $from_num, $to_num, $total );
	}

	echo esc_html( $message );
}

/**
 * Output the pagination HTML for the current thread loop.
 */
function bp_messages_pagination() {
	echo bp_get_messages_pagination();
}
	/**
	 * Get the pagination HTML for the current thread loop.
	 *
	 * @return string
	 */
	function bp_get_messages_pagination() {
		global $messages_template;

		/**
		 * Filters the pagination HTML for the current thread loop.
		 *
		 * @since 1.0.0
		 *
		 * @param int $pag_links Pagination HTML for the current thread loop.
		 */
		return apply_filters( 'bp_get_messages_pagination', $messages_template->pag_links );
	}

/**
 * Return whether or not the notice is currently active.
 *
 * @since 1.6.0
 *
 * @return bool
 */
function bp_messages_is_active_notice() {
	global $messages_template;

	$retval = ! empty( $messages_template->thread->is_active )
		? true
		: false;

	/**
	 * Filters whether or not the notice is currently active.
	 *
	 * @since 2.1.0
	 *
	 * @param bool $retval Whether or not the notice is currently active.
	 */
	return apply_filters( 'bp_messages_is_active_notice', $retval );
}

/**
 * Output a string for the active notice.
 *
 * Since 1.6 this function has been deprecated in favor of text in the theme.
 *
 * @since 1.0.0
 * @deprecated 1.6.0
 * @return bool
 */
function bp_message_is_active_notice() {
	echo bp_get_message_is_active_notice();
}
	/**
	 * Returns a string for the active notice.
	 *
	 * Since 1.6 this function has been deprecated in favor of text in the
	 * theme.
	 *
	 * @since 1.0.0
	 * @deprecated 1.6.0
	 * @return string
	 */
	function bp_get_message_is_active_notice() {

		$string = bp_messages_is_active_notice()
			? __( 'Currently Active', 'buddypress' )
			: '';

		return apply_filters( 'bp_get_message_is_active_notice', $string );
	}

/**
 * Output the ID of the current notice in the loop.
 */
function bp_message_notice_id() {
	echo (int) bp_get_message_notice_id();
}
	/**
	 * Get the ID of the current notice in the loop.
	 *
	 * @return int
	 */
	function bp_get_message_notice_id() {
		global $messages_template;

		/**
		 * Filters the ID of the current notice in the loop.
		 *
		 * @since 1.5.0
		 *
		 * @param int $id ID of the current notice in the loop.
		 */
		return apply_filters( 'bp_get_message_notice_id', $messages_template->thread->id );
	}

/**
 * Output the post date of the current notice in the loop.
 */
function bp_message_notice_post_date() {
	echo bp_get_message_notice_post_date();
}
	/**
	 * Get the post date of the current notice in the loop.
	 *
	 * @return string
	 */
	function bp_get_message_notice_post_date() {
		global $messages_template;

		/**
		 * Filters the post date of the current notice in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Formatted post date of the current notice in the loop.
		 */
		return apply_filters( 'bp_get_message_notice_post_date', bp_format_time( strtotime( $messages_template->thread->date_sent ) ) );
	}

/**
 * Output the subject of the current notice in the loop.
 */
function bp_message_notice_subject() {
	echo bp_get_message_notice_subject();
}
	/**
	 * Get the subject of the current notice in the loop.
	 *
	 * @return string
	 */
	function bp_get_message_notice_subject() {
		global $messages_template;

		/**
		 * Filters the subject of the current notice in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $subject Subject of the current notice in the loop.
		 */
		return apply_filters( 'bp_get_message_notice_subject', $messages_template->thread->subject );
	}

/**
 * Output the text of the current notice in the loop.
 */
function bp_message_notice_text() {
	echo bp_get_message_notice_text();
}
	/**
	 * Get the text of the current notice in the loop.
	 *
	 * @return string
	 */
	function bp_get_message_notice_text() {
		global $messages_template;

		/**
		 * Filters the text of the current notice in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message Text for the current notice in the loop.
		 */
		return apply_filters( 'bp_get_message_notice_text', $messages_template->thread->message );
	}

/**
 * Output the URL for deleting the current notice.
 */
function bp_message_notice_delete_link() {
	echo esc_url( bp_get_message_notice_delete_link() );
}
	/**
	 * Get the URL for deleting the current notice.
	 *
	 * @return string Delete URL.
	 */
	function bp_get_message_notice_delete_link() {
		global $messages_template;

		/**
		 * Filters the URL for deleting the current notice.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value URL for deleting the current notice.
		 * @param string $value Text indicating action being executed.
		 */
		return apply_filters( 'bp_get_message_notice_delete_link', wp_nonce_url( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() . '/notices/delete/' . $messages_template->thread->id ), 'messages_delete_notice' ) );
	}

/**
 * Output the URL for deactivating the current notice.
 */
function bp_message_activate_deactivate_link() {
	echo esc_url( bp_get_message_activate_deactivate_link() );
}
	/**
	 * Get the URL for deactivating the current notice.
	 *
	 * @return string
	 */
	function bp_get_message_activate_deactivate_link() {
		global $messages_template;

		if ( 1 === (int) $messages_template->thread->is_active ) {
			$link = wp_nonce_url( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() . '/notices/deactivate/' . $messages_template->thread->id ), 'messages_deactivate_notice' );
		} else {
			$link = wp_nonce_url( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() . '/notices/activate/' . $messages_template->thread->id ), 'messages_activate_notice' );
		}

		/**
		 * Filters the URL for deactivating the current notice.
		 *
		 * @since 1.0.0
		 *
		 * @param string $link URL for deactivating the current notice.
		 */
		return apply_filters( 'bp_get_message_activate_deactivate_link', $link );
	}

/**
 * Output the Deactivate/Activate text for the notice action link.
 */
function bp_message_activate_deactivate_text() {
	echo esc_html( bp_get_message_activate_deactivate_text() );
}
	/**
	 * Generate the text ('Deactivate' or 'Activate') for the notice action link.
	 *
	 * @return string
	 */
	function bp_get_message_activate_deactivate_text() {
		global $messages_template;

		if ( 1 === (int) $messages_template->thread->is_active  ) {
			$text = __('Deactivate', 'buddypress');
		} else {
			$text = __('Activate', 'buddypress');
		}

		/**
		 * Filters the "Deactivate" or "Activate" text for notice action links.
		 *
		 * @since 1.0.0
		 *
		 * @param string $text Text used for notice action links.
		 */
		return apply_filters( 'bp_message_activate_deactivate_text', $text );
	}