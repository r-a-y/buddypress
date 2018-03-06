<?php

/**
 * @since 1.1.0
 *
 * @return mixed
 */
function bp_group_invites() {
	global $invites_template;

	return $invites_template->invites();
}

/**
 * @since 1.1.0
 *
 * @return mixed
 */
function bp_group_the_invite() {
	global $invites_template;

	return $invites_template->the_invite();
}

/**
 * @since 1.1.0
 */
function bp_group_invite_item_id() {
	echo bp_get_group_invite_item_id();
}

	/**
	 * @since 1.1.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_invite_item_id() {
		global $invites_template;

		/**
		 * Filters the group invite item ID.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Group invite item ID.
		 */
		return apply_filters( 'bp_get_group_invite_item_id', 'uid-' . $invites_template->invite->user->id );
	}

/**
 * @since 1.1.0
 */
function bp_group_invite_user_avatar() {
	echo bp_get_group_invite_user_avatar();
}

	/**
	 * @since 1.1.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_invite_user_avatar() {
		global $invites_template;

		/**
		 * Filters the group invite user avatar.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Group invite user avatar.
		 */
		return apply_filters( 'bp_get_group_invite_user_avatar', $invites_template->invite->user->avatar_thumb );
	}

/**
 * @since 1.1.0
 */
function bp_group_invite_user_link() {
	echo bp_get_group_invite_user_link();
}

	/**
	 * @since 1.1.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_invite_user_link() {
		global $invites_template;

		/**
		 * Filters the group invite user link.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Group invite user link.
		 */
		return apply_filters( 'bp_get_group_invite_user_link', bp_core_get_userlink( $invites_template->invite->user->id ) );
	}

/**
 * @since 1.1.0
 */
function bp_group_invite_user_last_active() {
	echo bp_get_group_invite_user_last_active();
}

	/**
	 * @since 1.1.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_invite_user_last_active() {
		global $invites_template;

		/**
		 * Filters the group invite user's last active time.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Group invite user's last active time.
		 */
		return apply_filters( 'bp_get_group_invite_user_last_active', $invites_template->invite->user->last_active );
	}

/**
 * @since 1.1.0
 */
function bp_group_invite_user_remove_invite_url() {
	echo bp_get_group_invite_user_remove_invite_url();
}

	/**
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_group_invite_user_remove_invite_url() {
		global $invites_template;

		$user_id = intval( $invites_template->invite->user->id );

		if ( bp_is_current_action( 'create' ) ) {
			$uninvite_url = bp_get_groups_directory_permalink() . 'create/step/group-invites/?user_id=' . $user_id;
		} else {
			$uninvite_url = trailingslashit( bp_get_group_permalink( groups_get_current_group() ) . 'send-invites/remove/' . $user_id );
		}

		return wp_nonce_url( $uninvite_url, 'groups_invite_uninvite_user' );
	}

/**
 * Output pagination links for group invitations.
 *
 * @since 2.0.0
 */
function bp_group_invite_pagination_links() {
	echo bp_get_group_invite_pagination_links();
}

	/**
	 * Get pagination links for group invitations.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	function bp_get_group_invite_pagination_links() {
		global $invites_template;

		/**
		 * Filters the pagination links for group invitations.
		 *
		 * @since 2.0.0
		 *
		 * @param string $value Pagination links for group invitations.
		 */
		return apply_filters( 'bp_get_group_invite_pagination_links', $invites_template->pag_links );
	}

/**
 * Output pagination count text for group invitations.
 *
 * @since 2.0.0
 */
function bp_group_invite_pagination_count() {
	echo bp_get_group_invite_pagination_count();
}
	/**
	 * Get pagination count text for group invitations.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	function bp_get_group_invite_pagination_count() {
		global $invites_template;

		$start_num = intval( ( $invites_template->pag_page - 1 ) * $invites_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $invites_template->pag_num - 1 ) > $invites_template->total_invite_count ) ? $invites_template->total_invite_count : $start_num + ( $invites_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $invites_template->total_invite_count );

		if ( 1 == $invites_template->total_invite_count ) {
			$message = __( 'Viewing 1 invitation', 'buddypress' );
		} else {
			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s invitation', 'Viewing %1$s - %2$s of %3$s invitations', $invites_template->total_invite_count, 'buddypress' ), $from_num, $to_num, $total );
		}

		/** This filter is documented in bp-groups/bp-groups-template.php */
		return apply_filters( 'bp_get_groups_pagination_count', $message, $from_num, $to_num, $total );
	}