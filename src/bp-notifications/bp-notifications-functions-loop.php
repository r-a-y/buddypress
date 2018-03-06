<?php

/**
 * Get the notifications returned by the template loop.
 *
 * @since 1.9.0
 *
 * @return array List of notifications.
 */
function bp_the_notifications() {
	return buddypress()->notifications->query_loop->notifications();
}

/**
 * Get the current notification object in the loop.
 *
 * @since 1.9.0
 *
 * @return object The current notification within the loop.
 */
function bp_the_notification() {
	return buddypress()->notifications->query_loop->the_notification();
}

/** Loop Output ***************************************************************/

/**
 * Output the ID of the notification currently being iterated on.
 *
 * @since 1.9.0
 */
function bp_the_notification_id() {
	echo bp_get_the_notification_id();
}
	/**
	 * Return the ID of the notification currently being iterated on.
	 *
	 * @since 1.9.0
	 *
	 * @return int ID of the current notification.
	 */
	function bp_get_the_notification_id() {

		/**
		 * Filters the ID of the notification currently being iterated on.
		 *
		 * @since 1.9.0
		 *
		 * @param int $id ID of the notification being iterated on.
		 */
		return apply_filters( 'bp_get_the_notification_id', buddypress()->notifications->query_loop->notification->id );
	}

/**
 * Output the associated item ID of the notification currently being iterated on.
 *
 * @since 1.9.0
 */
function bp_the_notification_item_id() {
	echo bp_get_the_notification_item_id();
}
	/**
	 * Return the associated item ID of the notification currently being iterated on.
	 *
	 * @since 1.9.0
	 *
	 * @return int ID of the item associated with the current notification.
	 */
	function bp_get_the_notification_item_id() {

		/**
		 * Filters the associated item ID of the notification currently being iterated on.
		 *
		 * @since 1.9.0
		 *
		 * @param int $item_id ID of the associated item.
		 */
		return apply_filters( 'bp_get_the_notification_item_id', buddypress()->notifications->query_loop->notification->item_id );
	}

/**
 * Output the secondary associated item ID of the notification currently being iterated on.
 *
 * @since 1.9.0
 */
function bp_the_notification_secondary_item_id() {
	echo bp_get_the_notification_secondary_item_id();
}
	/**
	 * Return the secondary associated item ID of the notification currently being iterated on.
	 *
	 * @since 1.9.0
	 *
	 * @return int ID of the secondary item associated with the current notification.
	 */
	function bp_get_the_notification_secondary_item_id() {

		/**
		 * Filters the secondary associated item ID of the notification currently being iterated on.
		 *
		 * @since 1.9.0
		 *
		 * @param int $secondary_item_id ID of the secondary associated item.
		 */
		return apply_filters( 'bp_get_the_notification_secondary_item_id', buddypress()->notifications->query_loop->notification->secondary_item_id );
	}

/**
 * Output the name of the component associated with the notification currently being iterated on.
 *
 * @since 1.9.0
 */
function bp_the_notification_component_name() {
	echo bp_get_the_notification_component_name();
}
	/**
	 * Return the name of the component associated with the notification currently being iterated on.
	 *
	 * @since 1.9.0
	 *
	 * @return int Name of the component associated with the current notification.
	 */
	function bp_get_the_notification_component_name() {

		/**
		 * Filters the name of the component associated with the notification currently being iterated on.
		 *
		 * @since 1.9.0
		 *
		 * @param int $component_name Name of the component associated with the current notification.
		 */
		return apply_filters( 'bp_get_the_notification_component_name', buddypress()->notifications->query_loop->notification->component_name );
	}

/**
 * Output the name of the action associated with the notification currently being iterated on.
 *
 * @since 1.9.0
 */
function bp_the_notification_component_action() {
	echo bp_get_the_notification_component_action();
}
	/**
	 * Return the name of the action associated with the notification currently being iterated on.
	 *
	 * @since 1.9.0
	 *
	 * @return int Name of the action associated with the current notification.
	 */
	function bp_get_the_notification_component_action() {

		/**
		 * Filters the name of the action associated with the notification currently being iterated on.
		 *
		 * @since 1.9.0
		 *
		 * @param int $component_action Name of the action associated with the current notification.
		 */
		return apply_filters( 'bp_get_the_notification_component_action', buddypress()->notifications->query_loop->notification->component_action );
	}

/**
 * Output the timestamp of the current notification.
 *
 * @since 1.9.0
 */
function bp_the_notification_date_notified() {
	echo bp_get_the_notification_date_notified();
}
	/**
	 * Return the timestamp of the current notification.
	 *
	 * @since 1.9.0
	 *
	 * @return string Timestamp of the current notification.
	 */
	function bp_get_the_notification_date_notified() {

		/**
		 * Filters the timestamp of the current notification.
		 *
		 * @since 1.9.0
		 *
		 * @param string $date_notified Timestamp of the current notification.
		 */
		return apply_filters( 'bp_get_the_notification_date_notified', buddypress()->notifications->query_loop->notification->date_notified );
	}

/**
 * Output the timestamp of the current notification.
 *
 * @since 1.9.0
 */
function bp_the_notification_time_since() {
	echo bp_get_the_notification_time_since();
}
	/**
	 * Return the timestamp of the current notification.
	 *
	 * @since 1.9.0
	 *
	 * @return string Timestamp of the current notification.
	 */
	function bp_get_the_notification_time_since() {

		// Get the notified date.
		$date_notified = bp_get_the_notification_date_notified();

		// Notified date has legitimate data.
		if ( '0000-00-00 00:00:00' !== $date_notified ) {
			$retval = bp_core_time_since( $date_notified );

		// Notified date is empty, so return a fun string.
		} else {
			$retval = __( 'Date not found', 'buddypress' );
		}

		/**
		 * Filters the time since value of the current notification.
		 *
		 * @since 1.9.0
		 *
		 * @param string $retval Time since value for current notification.
		 */
		return apply_filters( 'bp_get_the_notification_time_since', $retval );
	}

/**
 * Output full-text description for a specific notification.
 *
 * @since 1.9.0
 */
function bp_the_notification_description() {
	echo bp_get_the_notification_description();
}

	/**
	 * Get full-text description for a specific notification.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	function bp_get_the_notification_description() {
		$bp           = buddypress();
		$notification = $bp->notifications->query_loop->notification;

		// Callback function exists.
		if ( isset( $bp->{ $notification->component_name }->notification_callback ) && is_callable( $bp->{ $notification->component_name }->notification_callback ) ) {
			$description = call_user_func( $bp->{ $notification->component_name }->notification_callback, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'string', $notification->id );

		// @deprecated format_notification_function - 1.5
		} elseif ( isset( $bp->{ $notification->component_name }->format_notification_function ) && function_exists( $bp->{ $notification->component_name }->format_notification_function ) ) {
			$description = call_user_func( $bp->{ $notification->component_name }->format_notification_function, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1 );

		// Allow non BuddyPress components to hook in.
		} else {

			/** This filter is documented in bp-notifications/bp-notifications-functions.php */
			$description = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', array( $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'string', $notification->component_action, $notification->component_name, $notification->id ) );
		}

		/**
		 * Filters the full-text description for a specific notification.
		 *
		 * @since 1.9.0
		 * @since 2.3.0 Added the `$notification` parameter.
		 *
		 * @param string $description  Full-text description for a specific notification.
		 * @param object $notification Notification object.
		 */
		return apply_filters( 'bp_get_the_notification_description', $description, $notification );
	}

/**
 * Output the pagination count for the current notification loop.
 *
 * @since 1.9.0
 */
function bp_notifications_pagination_count() {
	echo bp_get_notifications_pagination_count();
}
	/**
	 * Return the pagination count for the current notification loop.
	 *
	 * @since 1.9.0
	 *
	 * @return string HTML for the pagination count.
	 */
	function bp_get_notifications_pagination_count() {
		$query_loop = buddypress()->notifications->query_loop;
		$start_num  = intval( ( $query_loop->pag_page - 1 ) * $query_loop->pag_num ) + 1;
		$from_num   = bp_core_number_format( $start_num );
		$to_num     = bp_core_number_format( ( $start_num + ( $query_loop->pag_num - 1 ) > $query_loop->total_notification_count ) ? $query_loop->total_notification_count : $start_num + ( $query_loop->pag_num - 1 ) );
		$total      = bp_core_number_format( $query_loop->total_notification_count );

		if ( 1 == $query_loop->total_notification_count ) {
			$pag = __( 'Viewing 1 notification', 'buddypress' );
		} else {
			$pag = sprintf( _n( 'Viewing %1$s - %2$s of %3$s notification', 'Viewing %1$s - %2$s of %3$s notifications', $query_loop->total_notification_count, 'buddypress' ), $from_num, $to_num, $total );
		}

		/**
		 * Filters the pagination count for the current notification loop.
		 *
		 * @since 1.9.0
		 *
		 * @param string $pag HTML for the pagination count.
		 */
		return apply_filters( 'bp_notifications_pagination_count', $pag );
	}

/**
 * Output the pagination links for the current notification loop.
 *
 * @since 1.9.0
 */
function bp_notifications_pagination_links() {
	echo bp_get_notifications_pagination_links();
}
	/**
	 * Return the pagination links for the current notification loop.
	 *
	 * @since 1.9.0
	 *
	 * @return string HTML for the pagination links.
	 */
	function bp_get_notifications_pagination_links() {

		/**
		 * Filters the pagination links for the current notification loop.
		 *
		 * @since 1.9.0
		 *
		 * @param string $pag_links HTML for the pagination links.
		 */
		return apply_filters( 'bp_get_notifications_pagination_links', buddypress()->notifications->query_loop->pag_links );
	}