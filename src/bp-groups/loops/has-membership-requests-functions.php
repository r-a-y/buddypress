<?php

/**
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_group_membership_requests() {
	global $requests_template;

	return $requests_template->requests();
}

/**
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_group_the_membership_request() {
	global $requests_template;

	return $requests_template->the_request();
}

/**
 * @since 1.0.0
 */
function bp_group_request_user_avatar_thumb() {
	global $requests_template;

	/**
	 * Filters the requesting user's avatar thumbnail.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value HTML markup for the user's avatar thumbnail.
	 */
	echo apply_filters( 'bp_group_request_user_avatar_thumb', bp_core_fetch_avatar( array( 'item_id' => $requests_template->request->user_id, 'type' => 'thumb', 'alt' => sprintf( __( 'Profile picture of %s', 'buddypress' ), bp_core_get_user_displayname( $requests_template->request->user_id ) ) ) ) );
}

/**
 * @since 1.0.0
 */
function bp_group_request_reject_link() {
	echo bp_get_group_request_reject_link();
}

	/**
	 * @since 1.2.6
	 *
	 * @return mixed|void
	 */
	function bp_get_group_request_reject_link() {
		global $requests_template;

		/**
		 * Filters the URL to use to reject a membership request.
		 *
		 * @since 1.2.6
		 *
		 * @param string $value URL to use to reject a membership request.
		 */
		return apply_filters( 'bp_get_group_request_reject_link', wp_nonce_url( trailingslashit( bp_get_group_permalink( groups_get_current_group() ) . 'admin/membership-requests/reject/' . $requests_template->request->membership_id ), 'groups_reject_membership_request' ) );
	}

/**
 * @since 1.0.0
 */
function bp_group_request_accept_link() {
	echo bp_get_group_request_accept_link();
}

	/**
	 * @since 1.2.6
	 * @return mixed|void
	 */
	function bp_get_group_request_accept_link() {
		global $requests_template;

		/**
		 * Filters the URL to use to accept a membership request.
		 *
		 * @since 1.2.6
		 *
		 * @param string $value URL to use to accept a membership request.
		 */
		return apply_filters( 'bp_get_group_request_accept_link', wp_nonce_url( trailingslashit( bp_get_group_permalink( groups_get_current_group() ) . 'admin/membership-requests/accept/' . $requests_template->request->membership_id ), 'groups_accept_membership_request' ) );
	}

/**
 * @since 1.0.0
 */
function bp_group_request_user_link() {
	echo bp_get_group_request_user_link();
}

	/**
	 * @since 1.2.6
	 *
	 * @return mixed|void
	 */
	function bp_get_group_request_user_link() {
		global $requests_template;

		/**
		 * Filters the URL for the user requesting membership.
		 *
		 * @since 1.2.6
		 *
		 * @param string $value URL for the user requestion membership.
		 */
		return apply_filters( 'bp_get_group_request_user_link', bp_core_get_userlink( $requests_template->request->user_id ) );
	}

/**
 * @since 1.0.0
 */
function bp_group_request_time_since_requested() {
	global $requests_template;

	/**
	 * Filters the formatted time since membership was requested.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Formatted time since membership was requested.
	 */
	echo apply_filters( 'bp_group_request_time_since_requested', sprintf( __( 'requested %s', 'buddypress' ), bp_core_time_since( $requests_template->request->date_modified ) ) );
}

/**
 * @since 1.0.0
 */
function bp_group_request_comment() {
	global $requests_template;

	/**
	 * Filters the membership request comment left by user.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Membership request comment left by user.
	 */
	echo apply_filters( 'bp_group_request_comment', strip_tags( stripslashes( $requests_template->request->comments ) ) );
}

/**
 * Output pagination links for group membership requests.
 *
 * @since 2.0.0
 */
function bp_group_requests_pagination_links() {
	echo bp_get_group_requests_pagination_links();
}
	/**
	 * Get pagination links for group membership requests.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	function bp_get_group_requests_pagination_links() {
		global $requests_template;

		/**
		 * Filters pagination links for group membership requests.
		 *
		 * @since 2.0.0
		 *
		 * @param string $value Pagination links for group membership requests.
		 */
		return apply_filters( 'bp_get_group_requests_pagination_links', $requests_template->pag_links );
	}

/**
 * Output pagination count text for group membership requests.
 *
 * @since 2.0.0
 */
function bp_group_requests_pagination_count() {
	echo bp_get_group_requests_pagination_count();
}
	/**
	 * Get pagination count text for group membership requests.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	function bp_get_group_requests_pagination_count() {
		global $requests_template;

		$start_num = intval( ( $requests_template->pag_page - 1 ) * $requests_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $requests_template->pag_num - 1 ) > $requests_template->total_request_count ) ? $requests_template->total_request_count : $start_num + ( $requests_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $requests_template->total_request_count );

		if ( 1 == $requests_template->total_request_count ) {
			$message = __( 'Viewing 1 request', 'buddypress' );
		} else {
			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s request', 'Viewing %1$s - %2$s of %3$s requests', $requests_template->total_request_count, 'buddypress' ), $from_num, $to_num, $total );
		}

		/**
		 * Filters pagination count text for group membership requests.
		 *
		 * @since 2.0.0
		 *
		 * @param string $message  Pagination count text for group membership requests.
		 * @param string $from_num Total amount for the low value in the range.
		 * @param string $to_num   Total amount for the high value in the range.
		 * @param string $total    Total amount of members found.
		 */
		return apply_filters( 'bp_get_group_requests_pagination_count', $message, $from_num, $to_num, $total );
	}