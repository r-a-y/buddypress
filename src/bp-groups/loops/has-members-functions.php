<?php

/**
 * Output a URL for promoting a user to moderator.
 *
 * @since 1.1.0
 *
 * @param array|string $args See {@link bp_get_group_member_promote_mod_link()}.
 */
function bp_group_member_promote_mod_link( $args = '' ) {
	echo bp_get_group_member_promote_mod_link( $args );
}
	/**
	 * Generate a URL for promoting a user to moderator.
	 *
	 * @since 1.1.0
	 *
	 * @param array|string $args {
	 *     @type int    $user_id ID of the member to promote. Default:
	 *                           current member in a group member loop.
	 *     @type object $group   Group object. Default: current group.
	 * }
	 * @return string
	 */
	function bp_get_group_member_promote_mod_link( $args = '' ) {
		global $members_template, $groups_template;

		$r = bp_parse_args( $args, array(
			'user_id' => $members_template->member->user_id,
			'group'   => &$groups_template->group
		), 'group_member_promote_mod_link' );
		extract( $r, EXTR_SKIP );

		/**
		 * Filters a URL for promoting a user to moderator.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value URL to use for promoting a user to moderator.
		 */
		return apply_filters( 'bp_get_group_member_promote_mod_link', wp_nonce_url( trailingslashit( bp_get_group_permalink( $group ) . 'admin/manage-members/promote/mod/' . $user_id ), 'groups_promote_member' ) );
	}

/**
 * Output a URL for promoting a user to admin.
 *
 * @since 1.1.0
 *
 * @param array|string $args See {@link bp_get_group_member_promote_admin_link()}.
 */
function bp_group_member_promote_admin_link( $args = '' ) {
	echo bp_get_group_member_promote_admin_link( $args );
}
	/**
	 * Generate a URL for promoting a user to admin.
	 *
	 * @since 1.1.0
	 *
	 * @param array|string $args {
	 *     @type int    $user_id ID of the member to promote. Default:
	 *                           current member in a group member loop.
	 *     @type object $group   Group object. Default: current group.
	 * }
	 * @return string
	 */
	function bp_get_group_member_promote_admin_link( $args = '' ) {
		global $members_template, $groups_template;

		$r = bp_parse_args( $args, array(
			'user_id' => !empty( $members_template->member->user_id ) ? $members_template->member->user_id : false,
			'group'   => &$groups_template->group
		), 'group_member_promote_admin_link' );
		extract( $r, EXTR_SKIP );

		/**
		 * Filters a URL for promoting a user to admin.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value URL to use for promoting a user to admin.
		 */
		return apply_filters( 'bp_get_group_member_promote_admin_link', wp_nonce_url( trailingslashit( bp_get_group_permalink( $group ) . 'admin/manage-members/promote/admin/' . $user_id ), 'groups_promote_member' ) );
	}

/**
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_group_members() {
	global $members_template;

	return $members_template->members();
}

/**
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_group_the_member() {
	global $members_template;

	return $members_template->the_member();
}

/**
 * Output the group member avatar while in the groups members loop.
 *
 * @since 1.0.0
 *
 * @param array|string $args {@see bp_core_fetch_avatar()}.
 */
function bp_group_member_avatar( $args = '' ) {
	echo bp_get_group_member_avatar( $args );
}
	/**
	 * Return the group member avatar while in the groups members loop.
	 *
	 * @since 1.0.0
	 *
	 * @param array|string $args {@see bp_core_fetch_avatar()}.
	 * @return string
	 */
	function bp_get_group_member_avatar( $args = '' ) {
		global $members_template;

		$r = bp_parse_args( $args, array(
			'item_id' => $members_template->member->user_id,
			'type'    => 'full',
			'email'   => $members_template->member->user_email,
			'alt'     => sprintf( __( 'Profile picture of %s', 'buddypress' ), $members_template->member->display_name )
		) );

		/**
		 * Filters the group member avatar while in the groups members loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value HTML markup for group member avatar.
		 * @param array  $r     Parsed args used for the avatar query.
		 */
		return apply_filters( 'bp_get_group_member_avatar', bp_core_fetch_avatar( $r ), $r );
	}

/**
 * Output the group member avatar while in the groups members loop.
 *
 * @since 1.0.0
 *
 * @param array|string $args {@see bp_core_fetch_avatar()}.
 */
function bp_group_member_avatar_thumb( $args = '' ) {
	echo bp_get_group_member_avatar_thumb( $args );
}
	/**
	 * Return the group member avatar while in the groups members loop.
	 *
	 * @since 1.0.0
	 *
	 * @param array|string $args {@see bp_core_fetch_avatar()}.
	 * @return string
	 */
	function bp_get_group_member_avatar_thumb( $args = '' ) {
		global $members_template;

		$r = bp_parse_args( $args, array(
			'item_id' => $members_template->member->user_id,
			'type'    => 'thumb',
			'email'   => $members_template->member->user_email,
			'alt'     => sprintf( __( 'Profile picture of %s', 'buddypress' ), $members_template->member->display_name )
		) );

		/**
		 * Filters the group member avatar thumb while in the groups members loop.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value HTML markup for group member avatar thumb.
		 * @param array  $r     Parsed args used for the avatar query.
		 */
		return apply_filters( 'bp_get_group_member_avatar_thumb', bp_core_fetch_avatar( $r ), $r );
	}

/**
 * Output the group member avatar while in the groups members loop.
 *
 * @since 1.0.0
 *
 * @param int $width  Width of avatar to fetch.
 * @param int $height Height of avatar to fetch.
 */
function bp_group_member_avatar_mini( $width = 30, $height = 30 ) {
	echo bp_get_group_member_avatar_mini( $width, $height );
}
	/**
	 * Output the group member avatar while in the groups members loop.
	 *
	 * @since 1.0.0
	 *
	 * @param int $width  Width of avatar to fetch.
	 * @param int $height Height of avatar to fetch.
	 * @return string
	 */
	function bp_get_group_member_avatar_mini( $width = 30, $height = 30 ) {
		global $members_template;

		$r = bp_parse_args( array(), array(
			'item_id' => $members_template->member->user_id,
			'type'    => 'thumb',
			'email'   => $members_template->member->user_email,
			'alt'     => sprintf( __( 'Profile picture of %s', 'buddypress' ), $members_template->member->display_name ),
			'width'   => absint( $width ),
			'height'  => absint( $height )
		) );

		/**
		 * Filters the group member avatar mini while in the groups members loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value HTML markup for group member avatar mini.
		 * @param array  $r     Parsed args used for the avatar query.
		 */
		return apply_filters( 'bp_get_group_member_avatar_mini', bp_core_fetch_avatar( $r ), $r );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_name() {
	echo bp_get_group_member_name();
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_name() {
		global $members_template;

		/**
		 * Filters the group member display name of the current user in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $display_name Display name of the current user.
		 */
		return apply_filters( 'bp_get_group_member_name', $members_template->member->display_name );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_url() {
	echo bp_get_group_member_url();
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_url() {
		global $members_template;

		/**
		 * Filters the group member url for the current user in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value URL for the current user.
		 */
		return apply_filters( 'bp_get_group_member_url', bp_core_get_user_domain( $members_template->member->user_id, $members_template->member->user_nicename, $members_template->member->user_login ) );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_link() {
	echo bp_get_group_member_link();
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_link() {
		global $members_template;

		/**
		 * Filters the group member HTML link for the current user in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value HTML link for the current user.
		 */
		return apply_filters( 'bp_get_group_member_link', '<a href="' . bp_core_get_user_domain( $members_template->member->user_id, $members_template->member->user_nicename, $members_template->member->user_login ) . '">' . $members_template->member->display_name . '</a>' );
	}

/**
 * @since 1.2.0
 */
function bp_group_member_domain() {
	echo bp_get_group_member_domain();
}

	/**
	 * @since 1.2.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_domain() {
		global $members_template;

		/**
		 * Filters the group member domain for the current user in the loop.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Domain for the current user.
		 */
		return apply_filters( 'bp_get_group_member_domain', bp_core_get_user_domain( $members_template->member->user_id, $members_template->member->user_nicename, $members_template->member->user_login ) );
	}

/**
 * @since 1.2.0
 */
function bp_group_member_is_friend() {
	echo bp_get_group_member_is_friend();
}

	/**
	 * @since 1.2.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_is_friend() {
		global $members_template;

		if ( !isset( $members_template->member->is_friend ) ) {
			$friend_status = 'not_friends';
		} else {
			$friend_status = ( 0 == $members_template->member->is_friend )
				? 'pending'
				: 'is_friend';
		}

		/**
		 * Filters the friendship status between current user and displayed user in group member loop.
		 *
		 * @since 1.2.0
		 *
		 * @param string $friend_status Current status of the friendship.
		 */
		return apply_filters( 'bp_get_group_member_is_friend', $friend_status );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_is_banned() {
	echo bp_get_group_member_is_banned();
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_is_banned() {
		global $members_template;

		/**
		 * Filters whether the member is banned from the current group.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $is_banned Whether or not the member is banned.
		 */
		return apply_filters( 'bp_get_group_member_is_banned', $members_template->member->is_banned );
	}

/**
 * @since 1.2.6
 */
function bp_group_member_css_class() {
	global $members_template;

	if ( $members_template->member->is_banned ) {

		/**
		 * Filters the class to add to the HTML if member is banned.
		 *
		 * @since 1.2.6
		 *
		 * @param string $value HTML class to add.
		 */
		echo apply_filters( 'bp_group_member_css_class', 'banned-user' );
	}
}

/**
 * Output the joined date for the current member in the group member loop.
 *
 * @since 1.0.0
 * @since 2.7.0 Added $args as a parameter.
 *
 * @param array|string $args {@see bp_get_group_member_joined_since()}
 * @return string|null
 */
function bp_group_member_joined_since( $args = array() ) {
	echo bp_get_group_member_joined_since( $args );
}
	/**
	 * Return the joined date for the current member in the group member loop.
	 *
	 * @since 1.0.0
	 * @since 2.7.0 Added $args as a parameter.
	 *
	 * @param array|string $args {
	 *     Array of optional parameters.
	 *
	 *     @type bool $relative Optional. If true, returns relative joined date. eg. joined 5 months ago.
	 *                          If false, returns joined date value from database. Default: true.
	 * }
	 * @return string
	 */
	function bp_get_group_member_joined_since( $args = array() ) {
		global $members_template;

		$r = bp_parse_args( $args, array(
			'relative' => true,
		), 'group_member_joined_since' );

		// We do not want relative time, so return now.
		// @todo Should the 'bp_get_group_member_joined_since' filter be applied here?
		if ( ! $r['relative'] ) {
			return esc_attr( $members_template->member->date_modified );
		}

		/**
		 * Filters the joined since time for the current member in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Joined since time.
		 */
		return apply_filters( 'bp_get_group_member_joined_since', bp_core_get_last_activity( $members_template->member->date_modified, __( 'joined %s', 'buddypress') ) );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_id() {
	echo bp_get_group_member_id();
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_id() {
		global $members_template;

		/**
		 * Filters the member's user ID for group members loop.
		 *
		 * @since 1.0.0
		 *
		 * @param int $user_id User ID of the member.
		 */
		return apply_filters( 'bp_get_group_member_id', $members_template->member->user_id );
	}

/**
 * @since 1.0.0
 *
 * @return bool
 */
function bp_group_member_needs_pagination() {
	global $members_template;

	if ( $members_template->total_member_count > $members_template->pag_num ) {
		return true;
	}

	return false;
}

/**
 * @since 1.0.0
 */
function bp_group_pag_id() {
	echo bp_get_group_pag_id();
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_pag_id() {

		/**
		 * Filters the string to be used as the group pag id.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Value to use for the pag id.
		 */
		return apply_filters( 'bp_get_group_pag_id', 'pag' );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_pagination() {
	echo bp_get_group_member_pagination();
	wp_nonce_field( 'bp_groups_member_list', '_member_pag_nonce' );
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_pagination() {
		global $members_template;

		/**
		 * Filters the HTML markup to be used for group member listing pagination.
		 *
		 * @since 1.0.0
		 *
		 * @param string $pag_links HTML markup for the pagination.
		 */
		return apply_filters( 'bp_get_group_member_pagination', $members_template->pag_links );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_pagination_count() {
	echo bp_get_group_member_pagination_count();
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed|void
	 */
	function bp_get_group_member_pagination_count() {
		global $members_template;

		$start_num = intval( ( $members_template->pag_page - 1 ) * $members_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $members_template->pag_num - 1 ) > $members_template->total_member_count ) ? $members_template->total_member_count : $start_num + ( $members_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $members_template->total_member_count );

		if ( 1 == $members_template->total_member_count ) {
			$message = __( 'Viewing 1 member', 'buddypress' );
		} else {
			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s member', 'Viewing %1$s - %2$s of %3$s members', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );
		}

		/**
		 * Filters the "Viewing x-y of z members" pagination message.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value    "Viewing x-y of z members" text.
		 * @param string $from_num Total amount for the low value in the range.
		 * @param string $to_num   Total amount for the high value in the range.
		 * @param string $total    Total amount of members found.
		 */
		return apply_filters( 'bp_get_group_member_pagination_count', $message, $from_num, $to_num, $total );
	}

/**
 * @since 1.0.0
 */
function bp_group_member_admin_pagination() {
	echo bp_get_group_member_admin_pagination();
	wp_nonce_field( 'bp_groups_member_admin_list', '_member_admin_pag_nonce' );
}

	/**
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	function bp_get_group_member_admin_pagination() {
		global $members_template;

		return $members_template->pag_links;
	}