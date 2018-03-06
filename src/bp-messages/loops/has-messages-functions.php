<?php

/**
 * Output the 'ASC' or 'DESC' messages order string for this loop.
 */
function bp_thread_messages_order() {
	echo esc_attr( bp_get_thread_messages_order() );
}
	/**
	 * Get the 'ASC' or 'DESC' messages order string for this loop.
	 *
	 * @return string
	 */
	function bp_get_thread_messages_order() {
		global $thread_template;
		return $thread_template->thread->messages_order;
	}

/**
 * Check whether there are more messages to iterate over.
 *
 * @return bool
 */
function bp_thread_messages() {
	global $thread_template;

	return $thread_template->messages();
}

/**
 * Set up the current thread inside the loop.
 *
 * @return object
 */
function bp_thread_the_message() {
	global $thread_template;

	return $thread_template->the_message();
}

/**
 * Output the ID of the thread that the current loop belongs to.
 */
function bp_the_thread_id() {
	echo (int) bp_get_the_thread_id();
}
	/**
	 * Get the ID of the thread that the current loop belongs to.
	 *
	 * @return int
	 */
	function bp_get_the_thread_id() {
		global $thread_template;

		/**
		 * Filters the ID of the thread that the current loop belongs to.
		 *
		 * @since 1.1.0
		 *
		 * @param int $thread_id ID of the thread.
		 */
		return apply_filters( 'bp_get_the_thread_id', $thread_template->thread->thread_id );
	}

/**
 * Output the subject of the thread currently being iterated over.
 */
function bp_the_thread_subject() {
	echo bp_get_the_thread_subject();
}
	/**
	 * Get the subject of the thread currently being iterated over.
	 *
	 * @return string
	 */
	function bp_get_the_thread_subject() {
		global $thread_template;

		/**
		 * Filters the subject of the thread currently being iterated over.
		 *
		 * @since 1.1.0
		 *
		 * @return string $last_message_subject Subject of the thread currently being iterated over.
		 */
		return apply_filters( 'bp_get_the_thread_subject', $thread_template->thread->last_message_subject );
	}

/**
 * Get a list of thread recipients or a "x recipients" string.
 *
 * In BuddyPress 2.2.0, this parts of this functionality were moved into the
 * members/single/messages/single.php template. This function is no longer used
 * by BuddyPress.
 *
 * @return string
 */
function bp_get_the_thread_recipients() {
	if ( 5 <= bp_get_thread_recipients_count() ) {
		$recipients = sprintf( __( '%s recipients', 'buddypress' ), number_format_i18n( bp_get_thread_recipients_count() ) );
	} else {
		$recipients = bp_get_thread_recipients_list();
	}

	return apply_filters( 'bp_get_the_thread_recipients', $recipients );
}

/**
 * Get the number of recipients in the current thread.
 *
 * @since 2.2.0
 *
 * @return int
 */
function bp_get_thread_recipients_count() {
	global $thread_template;
	/**
	 * Filters the total number of recipients in a thread.
	 *
	 * @since 2.8.0
	 *
	 * @param int $count Total recipients number.
	 */
	return (int) apply_filters( 'bp_get_thread_recipients_count', count( $thread_template->thread->recipients ) );
}

/**
 * Get the max number of recipients to list in the 'Conversation between...' gloss.
 *
 * @since 2.3.0
 *
 * @return int
 */
function bp_get_max_thread_recipients_to_list() {
	/**
	 * Filters the max number of recipients to list in the 'Conversation between...' gloss.
	 *
	 * @since 2.3.0
	 *
	 * @param int $count Recipient count. Default: 5.
	 */
	return (int) apply_filters( 'bp_get_max_thread_recipients_to_list', 5 );
}

/**
 * Output HTML links to recipients in the current thread.
 *
 * @since 2.2.0
 */
function bp_the_thread_recipients_list() {
	echo bp_get_thread_recipients_list();
}
	/**
	 * Generate HTML links to the profiles of recipients in the current thread.
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	function bp_get_thread_recipients_list() {
		global $thread_template;

		$recipient_links = array();

		foreach( (array) $thread_template->thread->recipients as $recipient ) {
			if ( (int) $recipient->user_id !== bp_loggedin_user_id() ) {
				$recipient_link = bp_core_get_userlink( $recipient->user_id );

				if ( empty( $recipient_link ) ) {
					$recipient_link = __( 'Deleted User', 'buddypress' );
				}

				$recipient_links[] = $recipient_link;
			} else {
				$recipient_links[] = __( 'you', 'buddypress' );
			}
		}

		// Concatenate to natural language string.
		$recipient_links = wp_sprintf_l( '%l', $recipient_links );

		/**
		 * Filters the HTML links to the profiles of recipients in the current thread.
		 *
		 * @since 2.2.0
		 *
		 * @param string $value Comma-separated list of recipient HTML links for current thread.
		 */
		return apply_filters( 'bp_get_the_thread_recipients_list', $recipient_links );
	}

/**
 * Echo the ID of the current message in the thread.
 *
 * @since 1.9.0
 */
function bp_the_thread_message_id() {
	echo (int) bp_get_the_thread_message_id();
}
	/**
	 * Get the ID of the current message in the thread.
	 *
	 * @since 1.9.0
	 *
	 * @return int
	 */
	function bp_get_the_thread_message_id() {
		global $thread_template;

		$thread_message_id = isset( $thread_template->message->id )
			? (int) $thread_template->message->id
			: null;

		/**
		 * Filters the ID of the current message in the thread.
		 *
		 * @since 1.9.0
		 *
		 * @param int $thread_message_id ID of the current message in the thread.
		 */
		return apply_filters( 'bp_get_the_thread_message_id', $thread_message_id );
	}

/**
 * Output the CSS classes for messages within a single thread.
 *
 * @since 2.1.0
 */
function bp_the_thread_message_css_class() {
	echo esc_attr( bp_get_the_thread_message_css_class() );
}
	/**
	 * Generate the CSS classes for messages within a single thread.
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	function bp_get_the_thread_message_css_class() {
		global $thread_template;

		$classes = array();

		// Zebra-striping.
		$classes[] = bp_get_the_thread_message_alt_class();

		// ID of the sender.
		$classes[] = 'sent-by-' . intval( $thread_template->message->sender_id );

		// Whether the sender is the same as the logged-in user.
		if ( bp_loggedin_user_id() == $thread_template->message->sender_id ) {
			$classes[] = 'sent-by-me';
		}

		/**
		 * Filters the CSS classes for messages within a single thread.
		 *
		 * @since 2.1.0
		 *
		 * @param array $classes Array of classes to add to the HTML class attribute.
		 */
		$classes = apply_filters( 'bp_get_the_thread_message_css_class', $classes );

		return implode( ' ', $classes );
	}

/**
 * Output the CSS class used for message zebra striping.
 */
function bp_the_thread_message_alt_class() {
	echo esc_attr( bp_get_the_thread_message_alt_class() );
}
	/**
	 * Get the CSS class used for message zebra striping.
	 *
	 * @return string
	 */
	function bp_get_the_thread_message_alt_class() {
		global $thread_template;

		if ( $thread_template->current_message % 2 == 1 ) {
			$class = 'even alt';
		} else {
			$class = 'odd';
		}

		/**
		 * Filters the CSS class used for message zebra striping.
		 *
		 * @since 1.1.0
		 *
		 * @param string $class Class determined to be next for zebra striping effect.
		 */
		return apply_filters( 'bp_get_the_thread_message_alt_class', $class );
	}

/**
 * Output the ID for message sender within a single thread.
 *
 * @since 2.1.0
 */
function bp_the_thread_message_sender_id() {
	echo (int) bp_get_the_thread_message_sender_id();
}
	/**
	 * Return the ID for message sender within a single thread.
	 *
	 * @since 2.1.0
	 *
	 * @return int
	 */
	function bp_get_the_thread_message_sender_id() {
		global $thread_template;

		$user_id = ! empty( $thread_template->message->sender_id )
			? $thread_template->message->sender_id
			: 0;

		/**
		 * Filters the ID for message sender within a single thread.
		 *
		 * @since 2.1.0
		 *
		 * @param int $user_id ID of the message sender.
		 */
		return (int) apply_filters( 'bp_get_the_thread_message_sender_id', (int) $user_id );
	}

/**
 * Output the avatar for the current message sender.
 *
 * @param array|string $args See {@link bp_get_the_thread_message_sender_avatar_thumb()}
 *                           for a description.
 */
function bp_the_thread_message_sender_avatar( $args = '' ) {
	echo bp_get_the_thread_message_sender_avatar_thumb( $args );
}
	/**
	 * Get the avatar for the current message sender.
	 *
	 * @param array|string $args {
	 *     Array of arguments. See {@link bp_core_fetch_avatar()} for more
	 *     complete details. All arguments are optional.
	 *     @type string $type   Avatar type. Default: 'thumb'.
	 *     @type int    $width  Avatar width. Default: default for your $type.
	 *     @type int    $height Avatar height. Default: default for your $type.
	 * }
	 * @return string <img> tag containing the avatar.
	 */
	function bp_get_the_thread_message_sender_avatar_thumb( $args = '' ) {
		global $thread_template;

		$r = bp_parse_args( $args, array(
			'type'   => 'thumb',
			'width'  => false,
			'height' => false,
		) );

		/**
		 * Filters the avatar for the current message sender.
		 *
		 * @since 1.1.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $value <img> tag containing the avatar value.
		 * @param array  $r     Array of parsed arguments.
		 */
		return apply_filters( 'bp_get_the_thread_message_sender_avatar_thumb', bp_core_fetch_avatar( array(
			'item_id' => $thread_template->message->sender_id,
			'type'    => $r['type'],
			'width'   => $r['width'],
			'height'  => $r['height'],
			'alt'     => bp_core_get_user_displayname( $thread_template->message->sender_id )
		) ), $r );
	}

/**
 * Output a link to the sender of the current message.
 *
 * @since 1.1.0
 */
function bp_the_thread_message_sender_link() {
	echo esc_url( bp_get_the_thread_message_sender_link() );
}
	/**
	 * Get a link to the sender of the current message.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_thread_message_sender_link() {
		global $thread_template;

		/**
		 * Filters the link to the sender of the current message.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Link to the sender of the current message.
		 */
		return apply_filters( 'bp_get_the_thread_message_sender_link', bp_core_get_userlink( $thread_template->message->sender_id, false, true ) );
	}

/**
 * Output the display name of the sender of the current message.
 *
 * @since 1.1.0
 */
function bp_the_thread_message_sender_name() {
	echo esc_html( bp_get_the_thread_message_sender_name() );
}
	/**
	 * Get the display name of the sender of the current message.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_thread_message_sender_name() {
		global $thread_template;

		$display_name = bp_core_get_user_displayname( $thread_template->message->sender_id );

		if ( empty( $display_name ) ) {
			$display_name = __( 'Deleted User', 'buddypress' );
		}

		/**
		 * Filters the display name of the sender of the current message.
		 *
		 * @since 1.1.0
		 *
		 * @param string $display_name Display name of the sender of the current message.
		 */
		return apply_filters( 'bp_get_the_thread_message_sender_name', $display_name );
	}

/**
 * Output the URL for deleting the current thread.
 *
 * @since 1.5.0
 */
function bp_the_thread_delete_link() {
	echo esc_url( bp_get_the_thread_delete_link() );
}
	/**
	 * Get the URL for deleting the current thread.
	 *
	 * @since 1.5.0
	 *
	 * @return string URL
	 */
	function bp_get_the_thread_delete_link() {

		/**
		 * Filters the URL for deleting the current thread.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value URL for deleting the current thread.
		 * @param string $value Text indicating action being executed.
		 */
		return apply_filters( 'bp_get_message_thread_delete_link', wp_nonce_url( bp_displayed_user_domain() . bp_get_messages_slug() . '/inbox/delete/' . bp_get_the_thread_id(), 'messages_delete_thread' ) );
	}

/**
 * Output the 'Sent x hours ago' string for the current message.
 *
 * @since 1.1.0
 */
function bp_the_thread_message_time_since() {
	echo bp_get_the_thread_message_time_since();
}
	/**
	 * Generate the 'Sent x hours ago' string for the current message.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_thread_message_time_since() {

		/**
		 * Filters the 'Sent x hours ago' string for the current message.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Default text of 'Sent x hours ago'.
		 */
		return apply_filters( 'bp_get_the_thread_message_time_since', sprintf( __( 'Sent %s', 'buddypress' ), bp_core_time_since( bp_get_the_thread_message_date_sent() ) ) );
	}

/**
 * Output the timestamp for the current message.
 *
 * @since 2.1.0
 */
function bp_the_thread_message_date_sent() {
	echo bp_get_the_thread_message_date_sent();
}
	/**
	 * Generate the 'Sent x hours ago' string for the current message.
	 *
	 * @since 2.1.0
	 *
	 *
	 * @return int
	 */
	function bp_get_the_thread_message_date_sent() {
		global $thread_template;

		/**
		 * Filters the date sent value for the current message as a timestamp.
		 *
		 * @since 2.1.0
		 *
		 * @param string $value Timestamp of the date sent value for the current message.
		 */
		return apply_filters( 'bp_get_the_thread_message_date_sent', strtotime( $thread_template->message->date_sent ) );
	}

/**
 * Output the content of the current message in the loop.
 *
 * @since 1.1.0
 */
function bp_the_thread_message_content() {
	echo bp_get_the_thread_message_content();
}
	/**
	 * Get the content of the current message in the loop.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_thread_message_content() {
		global $thread_template;

		$content = $thread_template->message->message;

		// If user was deleted, mark content as deleted.
		if ( false === bp_core_get_core_userdata( bp_get_the_thread_message_sender_id() ) ) {
			$content = esc_html__( '[deleted]', 'buddypress' );
		}

		/**
		 * Filters the content of the current message in the loop.
		 *
		 * @since 1.1.0
		 *
		 * @param string $message The content of the current message in the loop.
		 */
		return apply_filters( 'bp_get_the_thread_message_content', $content );
	}