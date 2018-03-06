<?php

/**
 * Set up the current member inside the loop.
 *
 * @since 1.2.0
 *
 * @return object
 */
function bp_the_member() {
	global $members_template;
	return $members_template->the_member();
}

/**
 * Check whether there are more members to iterate over.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function bp_members() {
	global $members_template;
	return $members_template->members();
}

/**
 * Output the members pagination count.
 *
 * @since 1.2.0
 */
function bp_members_pagination_count() {
	echo bp_get_members_pagination_count();
}
	/**
	 * Generate the members pagination count.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function bp_get_members_pagination_count() {
		global $members_template;

		if ( empty( $members_template->type ) )
			$members_template->type = '';

		$start_num = intval( ( $members_template->pag_page - 1 ) * $members_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $members_template->pag_num - 1 ) > $members_template->total_member_count ) ? $members_template->total_member_count : $start_num + ( $members_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $members_template->total_member_count );

		if ( 'active' == $members_template->type ) {
			if ( 1 == $members_template->total_member_count ) {
				$pag = __( 'Viewing 1 active member', 'buddypress' );
			} else {
				$pag = sprintf( _n( 'Viewing %1$s - %2$s of %3$s active member', 'Viewing %1$s - %2$s of %3$s active members', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );
			}
		} elseif ( 'popular' == $members_template->type ) {
			if ( 1 == $members_template->total_member_count ) {
				$pag = __( 'Viewing 1 member with friends', 'buddypress' );
			} else {
				$pag = sprintf( _n( 'Viewing %1$s - %2$s of %3$s member with friends', 'Viewing %1$s - %2$s of %3$s members with friends', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );
			}
		} elseif ( 'online' == $members_template->type ) {
			if ( 1 == $members_template->total_member_count ) {
				$pag = __( 'Viewing 1 online member', 'buddypress' );
			} else {
				$pag = sprintf( _n( 'Viewing %1$s - %2$s of %3$s online member', 'Viewing %1$s - %2$s of %3$s online members', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );
			}
		} else {
			if ( 1 == $members_template->total_member_count ) {
				$pag = __( 'Viewing 1 member', 'buddypress' );
			} else {
				$pag = sprintf( _n( 'Viewing %1$s - %2$s of %3$s member', 'Viewing %1$s - %2$s of %3$s members', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );
			}
		}

		/**
		 * Filters the members pagination count.
		 *
		 * @since 1.5.0
		 *
		 * @param string $pag Pagination count string.
		 */
		return apply_filters( 'bp_members_pagination_count', $pag );
	}

/**
 * Output the members pagination links.
 *
 * @since 1.2.0
 */
function bp_members_pagination_links() {
	echo bp_get_members_pagination_links();
}
	/**
	 * Fetch the members pagination links.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	function bp_get_members_pagination_links() {
		global $members_template;

		/**
		 * Filters the members pagination link.
		 *
		 * @since 1.2.0
		 *
		 * @param string $pag_links HTML markup for pagination links.
		 */
		return apply_filters( 'bp_get_members_pagination_links', $members_template->pag_links );
	}

/**
 * Output the row class of the current member in the loop.
 *
 * @since 1.7.0
 *
 * @param array $classes Array of custom classes.
 */
function bp_member_class( $classes = array() ) {
	echo bp_get_member_class( $classes );
}
	/**
	 * Return the row class of the current member in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @param array $classes Array of custom classes.
	 * @return string Row class of the member
	 */
	function bp_get_member_class( $classes = array() ) {
		global $members_template;

		// Add even/odd classes, but only if there's more than 1 member.
		if ( $members_template->member_count > 1 ) {
			$pos_in_loop = (int) $members_template->current_member;
			$classes[]   = ( $pos_in_loop % 2 ) ? 'even' : 'odd';

			// If we've only one member in the loop, don't bother with odd and even.
		} else {
			$classes[] = 'bp-single-member';
		}

		// Maybe add 'is-online' class.
		if ( ! empty( $members_template->member->last_activity ) ) {

			// Calculate some times.
			$current_time  = bp_core_current_time( true, 'timestamp' );
			$last_activity = strtotime( $members_template->member->last_activity );
			$still_online  = strtotime( '+5 minutes', $last_activity );

			// Has the user been active recently?
			if ( $current_time <= $still_online ) {
				$classes[] = 'is-online';
			}
		}

		// Add current user class.
		if ( bp_loggedin_user_id() === (int) $members_template->member->id ) {
			$classes[] = 'is-current-user';
		}

		// Add current user member types.
		if ( $member_types = bp_get_member_type( $members_template->member->id, false ) ) {
			foreach ( $member_types as $member_type ) {
				$classes[] = sprintf( 'member-type-%s', esc_attr( $member_type ) );
			}
		}

		/**
		 * Filters the determined classes to add to the HTML element.
		 *
		 * @since 1.7.0
		 *
		 * @param string $classes Classes to be added to the HTML element.
		 */
		$classes = apply_filters( 'bp_get_member_class', $classes );
		$classes = array_merge( $classes, array() );
		$retval  = 'class="' . join( ' ', $classes ) . '"';

		return $retval;
	}

/**
 * Output nicename of current member in the loop.
 *
 * @since 1.2.5
 */
function bp_member_user_nicename() {
	echo bp_get_member_user_nicename();
}
	/**
	 * Get the nicename of the current member in the loop.
	 *
	 * @since 1.2.5
	 *
	 * @return string Members nicename.
	 */
	function bp_get_member_user_nicename() {
		global $members_template;

		/**
		 * Filters the nicename of the current member in the loop.
		 *
		 * @since 1.2.5
		 *
		 * @param string $user_nicename Nicename for the current member.
		 */
		return apply_filters( 'bp_get_member_user_nicename', $members_template->member->user_nicename );
	}

/**
 * Output login for current member in the loop.
 *
 * @since 1.2.5
 */
function bp_member_user_login() {
	echo bp_get_member_user_login();
}
	/**
	 * Get the login of the current member in the loop.
	 *
	 * @since 1.2.5
	 *
	 * @return string Member's login.
	 */
	function bp_get_member_user_login() {
		global $members_template;

		/**
		 * Filters the login of the current member in the loop.
		 *
		 * @since 1.2.5
		 *
		 * @param string $user_login Login for the current member.
		 */
		return apply_filters( 'bp_get_member_user_login', $members_template->member->user_login );
	}

/**
 * Output the email address for the current member in the loop.
 *
 * @since 1.2.5
 */
function bp_member_user_email() {
	echo bp_get_member_user_email();
}
	/**
	 * Get the email address of the current member in the loop.
	 *
	 * @since 1.2.5
	 *
	 * @return string Member's email address.
	 */
	function bp_get_member_user_email() {
		global $members_template;

		/**
		 * Filters the email address of the current member in the loop.
		 *
		 * @since 1.2.5
		 *
		 * @param string $user_email Email address for the current member.
		 */
		return apply_filters( 'bp_get_member_user_email', $members_template->member->user_email );
	}

/**
 * Check whether the current member in the loop is the logged-in user.
 *
 * @since 1.2.5
 *
 * @return bool
 */
function bp_member_is_loggedin_user() {
	global $members_template;

	/**
	 * Filters whether the current member in the loop is the logged-in user.
	 *
	 * @since 1.2.5
	 *
	 * @param bool $value Whether current member in the loop is logged in.
	 */
	return apply_filters( 'bp_member_is_loggedin_user', bp_loggedin_user_id() == $members_template->member->id ? true : false );
}

/**
 * Output a member's avatar.
 *
 * @since 1.2.0
 *
 * @see bp_get_member_avatar() for description of arguments.
 *
 * @param array|string $args See {@link bp_get_member_avatar()}.
 */
function bp_member_avatar( $args = '' ) {

	/**
	 * Filters a members avatar.
	 *
	 * @since 1.2.0
	 * @since 2.6.0 Added the `$args` parameter.
	 *
	 * @param string       $value Formatted HTML <img> element, or raw avatar URL based on $html arg.
	 * @param array|string $args  See {@link bp_get_member_avatar()}.
	 */
	echo apply_filters( 'bp_member_avatar', bp_get_member_avatar( $args ), $args );
}
	/**
	 * Get a member's avatar.
	 *
	 * @since 1.2.0
	 *
	 * @see bp_core_fetch_avatar() For a description of arguments and
	 *      return values.
	 *
	 * @param array|string $args  {
	 *     Arguments are listed here with an explanation of their defaults.
	 *     For more information about the arguments, see
	 *     {@link bp_core_fetch_avatar()}.
	 *     @type string   $alt     Default: 'Profile picture of [user name]'.
	 *     @type string   $class   Default: 'avatar'.
	 *     @type string   $type    Default: 'thumb'.
	 *     @type int|bool $width   Default: false.
	 *     @type int|bool $height  Default: false.
	 *     @type bool     $id      Currently unused.
	 *     @type bool     $no_grav Default: false.
	 * }
	 * @return string User avatar string.
	 */
	function bp_get_member_avatar( $args = '' ) {
		global $members_template;

		$fullname = !empty( $members_template->member->fullname ) ? $members_template->member->fullname : $members_template->member->display_name;

		$defaults = array(
			'type'   => 'thumb',
			'width'  => false,
			'height' => false,
			'class'  => 'avatar',
			'id'     => false,
			'alt'    => sprintf( __( 'Profile picture of %s', 'buddypress' ), $fullname )
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		/**
		 * Filters a members avatar.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $value Formatted HTML <img> element, or raw avatar URL based on $html arg.
		 * @param array  $r     Array of parsed arguments. See {@link bp_get_member_avatar()}.
		 */
		return apply_filters( 'bp_get_member_avatar', bp_core_fetch_avatar( array( 'item_id' => $members_template->member->id, 'type' => $type, 'alt' => $alt, 'css_id' => $id, 'class' => $class, 'width' => $width, 'height' => $height, 'email' => $members_template->member->user_email ) ), $r );
	}

/**
 * Output the permalink for the current member in the loop.
 *
 * @since 1.2.0
 */
function bp_member_permalink() {
	echo esc_url( bp_get_member_permalink() );
}
	/**
	 * Get the permalink for the current member in the loop.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	function bp_get_member_permalink() {
		global $members_template;

		/**
		 * Filters the permalink for the current member in the loop.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Permalink for the current member in the loop.
		 */
		return apply_filters( 'bp_get_member_permalink', bp_core_get_user_domain( $members_template->member->id, $members_template->member->user_nicename, $members_template->member->user_login ) );
	}

	/**
	 * Alias of {@link bp_member_permalink()}.
	 *
	 * @since 1.2.0
	 */
	function bp_member_link() { echo esc_url( bp_get_member_permalink() ); }

	/**
	 * Alias of {@link bp_get_member_permalink()}.
	 *
	 * @since 1.2.0
	 */
	function bp_get_member_link() { return bp_get_member_permalink(); }

/**
 * Output display name of current member in the loop.
 *
 * @since 1.2.0
 */
function bp_member_name() {

	/**
	 * Filters the display name of current member in the loop.
	 *
	 * @since 1.2.0
	 *
	 * @param string $value Display name for current member.
	 */
	echo apply_filters( 'bp_member_name', bp_get_member_name() );
}
	/**
	 * Get the display name of the current member in the loop.
	 *
	 * Full name is, by default, pulled from xprofile's Full Name field.
	 * When this field is empty, we try to get an alternative name from the
	 * WP users table, in the following order of preference: display_name,
	 * user_nicename, user_login.
	 *
	 * @since 1.2.0
	 *
	 * @return string The user's fullname for display.
	 */
	function bp_get_member_name() {
		global $members_template;

		// Generally, this only fires when xprofile is disabled.
		if ( empty( $members_template->member->fullname ) ) {
			// Our order of preference for alternative fullnames.
			$name_stack = array(
				'display_name',
				'user_nicename',
				'user_login'
			);

			foreach ( $name_stack as $source ) {
				if ( !empty( $members_template->member->{$source} ) ) {
					// When a value is found, set it as fullname and be done with it.
					$members_template->member->fullname = $members_template->member->{$source};
					break;
				}
			}
		}

		/**
		 * Filters the display name of current member in the loop.
		 *
		 * @since 1.2.0
		 *
		 * @param string $fullname Display name for current member.
		 */
		return apply_filters( 'bp_get_member_name', $members_template->member->fullname );
	}
	add_filter( 'bp_get_member_name', 'wp_filter_kses' );
	add_filter( 'bp_get_member_name', 'stripslashes'   );
	add_filter( 'bp_get_member_name', 'strip_tags'     );
	add_filter( 'bp_get_member_name', 'esc_html'       );

/**
 * Output the current member's last active time.
 *
 * @since 1.2.0
 *
 * @param array $args {@see bp_get_member_last_active()}.
 */
function bp_member_last_active( $args = array() ) {
	echo bp_get_member_last_active( $args );
}
	/**
	 * Return the current member's last active time.
	 *
	 * @since 1.2.0
	 * @since 2.7.0 Added 'relative' as a parameter to $args.
	 *
	 * @param array $args {
	 *     Array of optional arguments.
	 *     @type mixed $active_format If true, formatted "active 5 minutes ago". If false, formatted "5 minutes
	 *                                ago". If string, should be sprintf'able like 'last seen %s ago'.
	 *     @type bool  $relative      If true, will return relative time "5 minutes ago". If false, will return
	 *                                date from database. Default: true.
	 * }
	 * @return string
	 */
	function bp_get_member_last_active( $args = array() ) {
		global $members_template;

		// Parse the activity format.
		$r = bp_parse_args( $args, array(
			'active_format' => true,
			'relative'      => true,
		) );

		// Backwards compatibility for anyone forcing a 'true' active_format.
		if ( true === $r['active_format'] ) {
			$r['active_format'] = __( 'active %s', 'buddypress' );
		}

		// Member has logged in at least one time.
		if ( isset( $members_template->member->last_activity ) ) {
			// We do not want relative time, so return now.
			// @todo Should the 'bp_member_last_active' filter be applied here?
			if ( ! $r['relative'] ) {
				return esc_attr( $members_template->member->last_activity );
			}

			// Backwards compatibility for pre 1.5 'ago' strings.
			$last_activity = ! empty( $r['active_format'] )
				? bp_core_get_last_activity( $members_template->member->last_activity, $r['active_format'] )
				: bp_core_time_since( $members_template->member->last_activity );

		// Member has never logged in or been active.
		} else {
			$last_activity = __( 'Never active', 'buddypress' );
		}

		/**
		 * Filters the current members last active time.
		 *
		 * @since 1.2.0
		 *
		 * @param string $last_activity Formatted time since last activity.
		 * @param array  $r             Array of parsed arguments for query.
		 */
		return apply_filters( 'bp_member_last_active', $last_activity, $r );
	}

/**
 * Output the latest update of the current member in the loop.
 *
 * @since 1.2.0
 *
 * @param array|string $args {@see bp_get_member_latest_update()}.
 */
function bp_member_latest_update( $args = '' ) {
	echo bp_get_member_latest_update( $args );
}
	/**
	 * Get the latest update from the current member in the loop.
	 *
	 * @since 1.2.0
	 *
	 * @param array|string $args {
	 *     Array of optional arguments.
	 *     @type int  $length    Truncation length. Default: 225.
	 *     @type bool $view_link Whether to provide a 'View' link for
	 *                           truncated entries. Default: false.
	 * }
	 * @return string
	 */
	function bp_get_member_latest_update( $args = '' ) {
		global $members_template;

		$defaults = array(
			'length'    => 225,
			'view_link' => true
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r );

		if ( !bp_is_active( 'activity' ) || empty( $members_template->member->latest_update ) || !$update = maybe_unserialize( $members_template->member->latest_update ) )
			return false;

		/**
		 * Filters the excerpt of the latest update for current member in the loop.
		 *
		 * @since 1.2.5
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $value Excerpt of the latest update for current member in the loop.
		 * @param array  $r     Array of parsed arguments.
		 */
		$update_content = apply_filters( 'bp_get_activity_latest_update_excerpt', trim( strip_tags( bp_create_excerpt( $update['content'], $length ) ) ), $r );

		$update_content = sprintf( _x( '- &quot;%s&quot;', 'member latest update in member directory', 'buddypress' ), $update_content );

		// If $view_link is true and the text returned by bp_create_excerpt() is different from the original text (ie it's
		// been truncated), add the "View" link.
		if ( $view_link && ( $update_content != $update['content'] ) ) {
			$view = __( 'View', 'buddypress' );

			$update_content .= '<span class="activity-read-more"><a href="' . bp_activity_get_permalink( $update['id'] ) . '" rel="nofollow">' . $view . '</a></span>';
		}

		/**
		 * Filters the latest update from the current member in the loop.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $update_content Formatted latest update for current member.
		 * @param array  $r              Array of parsed arguments.
		 */
		return apply_filters( 'bp_get_member_latest_update', $update_content, $r );
	}


/**
 * Output the 'registered [x days ago]' string for the current member.
 *
 * @since 1.2.0
 * @since 2.7.0 Added $args as a parameter.
 *
 * @param array $args Optional. {@see bp_get_member_registered()}
 */
function bp_member_registered( $args = array() ) {
	echo bp_get_member_registered( $args );
}
	/**
	 * Get the 'registered [x days ago]' string for the current member.
	 *
	 * @since 1.2.0
	 * @since 2.7.0 Added $args as a parameter.
	 *
	 * @param array $args {
	 *     Array of optional parameters.
	 *
	 *     @type bool $relative Optional. If true, returns relative registered date. eg. registered 5 months ago.
	 *                          If false, returns registered date value from database.
	 * }
	 *
	 * @return string
	 */
	function bp_get_member_registered( $args = array() ) {
		global $members_template;

		$r = wp_parse_args( $args, array(
			'relative' => true,
		) );

		// We do not want relative time, so return now.
		// @todo Should the 'bp_member_registered' filter be applied here?
		if ( ! $r['relative'] ) {
			return esc_attr( $members_template->member->user_registered );
		}

		$registered = esc_attr( bp_core_get_last_activity( $members_template->member->user_registered, _x( 'registered %s', 'Records the timestamp that the user registered into the activity stream', 'buddypress' ) ) );

		/**
		 * Filters the 'registered [x days ago]' string for the current member.
		 *
		 * @since 2.1.0
		 *
		 * @param string $registered The 'registered [x days ago]' string.
		 */
		return apply_filters( 'bp_member_registered', $registered );
	}

/**
 * Output a random piece of profile data for the current member in the loop.
 *
 * @since 1.2.0
 */
function bp_member_random_profile_data() {
	global $members_template;

	if ( bp_is_active( 'xprofile' ) ) { ?>
		<?php $random_data = xprofile_get_random_profile_data( $members_template->member->id, true ); ?>
			<strong><?php echo wp_filter_kses( $random_data[0]->name ) ?></strong>
			<?php echo wp_filter_kses( $random_data[0]->value ) ?>
	<?php }
}