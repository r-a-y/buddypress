<?php

/**
 * Check whether there are more groups to iterate over.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function bp_groups() {
	global $groups_template;
	return $groups_template->groups();
}

/**
 * Set up the current group inside the loop.
 *
 * @since 1.0.0
 *
 * @return object
 */
function bp_the_group() {
	global $groups_template;
	return $groups_template->the_group();
}

/**
 * Output the row class of the current group in the loop.
 *
 * @since 1.7.0
 *
 * @param array $classes Array of custom classes.
 */
function bp_group_class( $classes = array() ) {
	echo bp_get_group_class( $classes );
}
	/**
	 * Get the row class of the current group in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @param array $classes Array of custom classes.
	 * @return string Row class of the group.
	 */
	function bp_get_group_class( $classes = array() ) {
		global $groups_template;

		// Add even/odd classes, but only if there's more than 1 group.
		if ( $groups_template->group_count > 1 ) {
			$pos_in_loop = (int) $groups_template->current_group;
			$classes[]   = ( $pos_in_loop % 2 ) ? 'even' : 'odd';

		// If we've only one group in the loop, don't bother with odd and even.
		} else {
			$classes[] = 'bp-single-group';
		}

		// Group type - public, private, hidden.
		$classes[] = sanitize_key( $groups_template->group->status );

		// Add current group types.
		if ( $group_types = bp_groups_get_group_type( bp_get_group_id(), false ) ) {
			foreach ( $group_types as $group_type ) {
				$classes[] = sprintf( 'group-type-%s', esc_attr( $group_type ) );
			}
		}

		// User's group role.
		if ( bp_is_user_active() ) {

			// Admin.
			if ( bp_group_is_admin() ) {
				$classes[] = 'is-admin';
			}

			// Moderator.
			if ( bp_group_is_mod() ) {
				$classes[] = 'is-mod';
			}

			// Member.
			if ( bp_group_is_member() ) {
				$classes[] = 'is-member';
			}
		}

		// Whether a group avatar will appear.
		if ( bp_disable_group_avatar_uploads() || ! buddypress()->avatar->show_avatars ) {
			$classes[] = 'group-no-avatar';
		} else {
			$classes[] = 'group-has-avatar';
		}

		/**
		 * Filters classes that will be applied to row class of the current group in the loop.
		 *
		 * @since 1.7.0
		 *
		 * @param array $classes Array of determined classes for the row.
		 */
		$classes = apply_filters( 'bp_get_group_class', $classes );
		$classes = array_merge( $classes, array() );
		$retval = 'class="' . join( ' ', $classes ) . '"';

		return $retval;
	}

/**
 * Output the group avatar while in the groups loop.
 *
 * @since 1.0.0
 *
 * @param array|string $args {
 *      See {@link bp_get_group_avatar()} for description of arguments.
 * }
 */
function bp_group_avatar( $args = '' ) {
	echo bp_get_group_avatar( $args );
}
	/**
	 * Get a group's avatar.
	 *
	 * @since 1.0.0
	 *
	 * @see bp_core_fetch_avatar() For a description of arguments and return values.
	 *
	 * @param array|string $args {
	 *     Arguments are listed here with an explanation of their defaults.
	 *     For more information about the arguments, see {@link bp_core_fetch_avatar()}.
	 *
	 *     @type string   $alt     Default: 'Group logo of [group name]'.
	 *     @type string   $class   Default: 'avatar'.
	 *     @type string   $type    Default: 'full'.
	 *     @type int|bool $width   Default: false.
	 *     @type int|bool $height  Default: false.
	 *     @type bool     $id      Passed to `$css_id` parameter.
	 * }
	 * @return string Group avatar string.
	 */
	function bp_get_group_avatar( $args = '' ) {
		global $groups_template;

		// Bail if avatars are turned off.
		if ( bp_disable_group_avatar_uploads() || ! buddypress()->avatar->show_avatars ) {
			return false;
		}

		// Parse the arguments.
		$r = bp_parse_args( $args, array(
			'type'   => 'full',
			'width'  => false,
			'height' => false,
			'class'  => 'avatar',
			'id'     => false,
			'alt'    => sprintf( __( 'Group logo of %s', 'buddypress' ), $groups_template->group->name )
		) );

		// Fetch the avatar from the folder.
		$avatar = bp_core_fetch_avatar( array(
			'item_id'    => $groups_template->group->id,
			'avatar_dir' => 'group-avatars',
			'object'     => 'group',
			'type'       => $r['type'],
			'alt'        => $r['alt'],
			'css_id'     => $r['id'],
			'class'      => $r['class'],
			'width'      => $r['width'],
			'height'     => $r['height'],
		) );

		// If No avatar found, provide some backwards compatibility.
		if ( empty( $avatar ) ) {
			$avatar = '<img src="' . esc_url( $groups_template->group->avatar_thumb ) . '" class="avatar" alt="' . esc_attr( $groups_template->group->name ) . '" />';
		}

		/**
		 * Filters the group avatar while in the groups loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $avatar HTML image element holding the group avatar.
		 * @param array  $r      Array of parsed arguments for the group avatar.
		 */
		return apply_filters( 'bp_get_group_avatar', $avatar, $r );
	}

/**
 * Output the group avatar thumbnail while in the groups loop.
 *
 * @since 1.0.0
 *
 * @param object|bool $group Optional. Group object.
 *                           Default: current group in loop.
 */
function bp_group_avatar_thumb( $group = false ) {
	echo bp_get_group_avatar_thumb( $group );
}
	/**
	 * Return the group avatar thumbnail while in the groups loop.
	 *
	 * @since 1.0.0
	 *
	 * @param object|bool $group Optional. Group object.
	 *                           Default: current group in loop.
	 * @return string
	 */
	function bp_get_group_avatar_thumb( $group = false ) {
		return bp_get_group_avatar( array(
			'type' => 'thumb',
			'id'   => ! empty( $group->id ) ? $group->id : false
		) );
	}

/**
 * Output the miniature group avatar thumbnail while in the groups loop.
 *
 * @since 1.0.0
 *
 * @param object|bool $group Optional. Group object.
 *                           Default: current group in loop.
 */
function bp_group_avatar_mini( $group = false ) {
	echo bp_get_group_avatar_mini( $group );
}
	/**
	 * Return the miniature group avatar thumbnail while in the groups loop.
	 *
	 * @since 1.0.0
	 *
	 * @param object|bool $group Optional. Group object.
	 *                           Default: current group in loop.
	 * @return string
	 */
	function bp_get_group_avatar_mini( $group = false ) {
		return bp_get_group_avatar( array(
			'type'   => 'thumb',
			'width'  => 30,
			'height' => 30,
			'id'     => ! empty( $group->id ) ? $group->id : false
		) );
	}

/**
 * Output the pagination HTML for a group loop.
 *
 * @since 1.2.0
 */
function bp_groups_pagination_links() {
	echo bp_get_groups_pagination_links();
}
	/**
	 * Get the pagination HTML for a group loop.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	function bp_get_groups_pagination_links() {
		global $groups_template;

		/**
		 * Filters the pagination HTML for a group loop.
		 *
		 * @since 1.2.0
		 *
		 * @param string $pag_links HTML markup for the pagination links.
		 */
		return apply_filters( 'bp_get_groups_pagination_links', $groups_template->pag_links );
	}

/**
 * Output the "Viewing x-y of z groups" pagination message.
 *
 * @since 1.2.0
 */
function bp_groups_pagination_count() {
	echo bp_get_groups_pagination_count();
}
	/**
	 * Generate the "Viewing x-y of z groups" pagination message.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function bp_get_groups_pagination_count() {
		global $groups_template;

		$start_num = intval( ( $groups_template->pag_page - 1 ) * $groups_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $groups_template->pag_num - 1 ) > $groups_template->total_group_count ) ? $groups_template->total_group_count : $start_num + ( $groups_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $groups_template->total_group_count );

		if ( 1 == $groups_template->total_group_count ) {
			$message = __( 'Viewing 1 group', 'buddypress' );
		} else {
			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s group', 'Viewing %1$s - %2$s of %3$s groups', $groups_template->total_group_count, 'buddypress' ), $from_num, $to_num, $total );
		}

		/**
		 * Filters the "Viewing x-y of z groups" pagination message.
		 *
		 * @since 1.5.0
		 *
		 * @param string $message  "Viewing x-y of z groups" text.
		 * @param string $from_num Total amount for the low value in the range.
		 * @param string $to_num   Total amount for the high value in the range.
		 * @param string $total    Total amount of groups found.
		 */
		return apply_filters( 'bp_get_groups_pagination_count', $message, $from_num, $to_num, $total );
	}

/**
 * Output the "x members" count string for a group.
 *
 * @since 1.2.0
 */
function bp_group_member_count() {
	echo bp_get_group_member_count();
}
	/**
	 * Generate the "x members" count string for a group.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	function bp_get_group_member_count() {
		global $groups_template;

		if ( isset( $groups_template->group->total_member_count ) ) {
			$count = (int) $groups_template->group->total_member_count;
		} else {
			$count = 0;
		}

		$count_string = sprintf( _n( '%s member', '%s members', $count, 'buddypress' ), bp_core_number_format( $count ) );

		/**
		 * Filters the "x members" count string for a group.
		 *
		 * @since 1.2.0
		 *
		 * @param string $count_string The "x members" count string for a group.
		 */
		return apply_filters( 'bp_get_group_member_count', $count_string );
	}