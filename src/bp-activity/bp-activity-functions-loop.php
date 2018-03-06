<?php

/**
 * Determine if there are still activities left in the loop.
 *
 * @since 1.0.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @return bool Returns true when activities are found.
 */
function bp_activities() {
	global $activities_template;
	return $activities_template->user_activities();
}

/**
 * Get the current activity object in the loop.
 *
 * @since 1.0.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @return object The current activity within the loop.
 */
function bp_the_activity() {
	global $activities_template;
	return $activities_template->the_activity();
}

/**
 * Output the URL for the Load More link.
 *
 * @since 2.1.0
 */
function bp_activity_load_more_link() {
	echo esc_url( bp_get_activity_load_more_link() );
}
	/**
	 * Get the URL for the Load More link.
	 *
	 * @since 2.1.0
	 *
	 * @return string $link
	 */
	function bp_get_activity_load_more_link() {
		global $activities_template;

		$url  = bp_get_requested_url();
		$link = add_query_arg( $activities_template->pag_arg, $activities_template->pag_page + 1, $url );

		/**
		 * Filters the Load More link URL.
		 *
		 * @since 2.1.0
		 *
		 * @param string $link                The "Load More" link URL with appropriate query args.
		 * @param string $url                 The original URL.
		 * @param object $activities_template The activity template loop global.
		 */
		return apply_filters( 'bp_get_activity_load_more_link', $link, $url, $activities_template );
	}

/**
 * Output the activity pagination count.
 *
 * @since 1.0.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 */
function bp_activity_pagination_count() {
	echo bp_get_activity_pagination_count();
}

	/**
	 * Return the activity pagination count.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The pagination text.
	 */
	function bp_get_activity_pagination_count() {
		global $activities_template;

		$start_num = intval( ( $activities_template->pag_page - 1 ) * $activities_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $activities_template->pag_num - 1 ) > $activities_template->total_activity_count ) ? $activities_template->total_activity_count : $start_num + ( $activities_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $activities_template->total_activity_count );

		if ( 1 == $activities_template->total_activity_count ) {
			$message = __( 'Viewing 1 item', 'buddypress' );
		} else {
			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s item', 'Viewing %1$s - %2$s of %3$s items', $activities_template->total_activity_count, 'buddypress' ), $from_num, $to_num, $total );
		}

		return $message;
	}

/**
 * Output the activity pagination links.
 *
 * @since 1.0.0
 *
 */
function bp_activity_pagination_links() {
	echo bp_get_activity_pagination_links();
}

	/**
	 * Return the activity pagination links.
	 *
	 * @since 1.0.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The pagination links.
	 */
	function bp_get_activity_pagination_links() {
		global $activities_template;

		/**
		 * Filters the activity pagination link output.
		 *
		 * @since 1.0.0
		 *
		 * @param string $pag_links Output for the activity pagination links.
		 */
		return apply_filters( 'bp_get_activity_pagination_links', $activities_template->pag_links );
	}

/**
 * Return true when there are more activity items to be shown than currently appear.
 *
 * @since 1.5.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @return bool $has_more_items True if more items, false if not.
 */
function bp_activity_has_more_items() {
	global $activities_template;

	if ( ! empty( $activities_template->has_more_items )  ) {
		$has_more_items = true;
	} else {
		$remaining_pages = 0;

		if ( ! empty( $activities_template->pag_page ) ) {
			$remaining_pages = floor( ( $activities_template->total_activity_count - 1 ) / ( $activities_template->pag_num * $activities_template->pag_page ) );
		}

		$has_more_items = (int) $remaining_pages > 0;
	}

	/**
	 * Filters whether there are more activity items to display.
	 *
	 * @since 1.5.0
	 *
	 * @param bool $has_more_items Whether or not there are more activity items to display.
	 */
	return apply_filters( 'bp_activity_has_more_items', $has_more_items );
}

/**
 * Output the activity count.
 *
 * @since 1.2.0
 *
 */
function bp_activity_count() {
	echo bp_get_activity_count();
}

	/**
	 * Return the activity count.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int The activity count.
	 */
	function bp_get_activity_count() {
		global $activities_template;

		/**
		 * Filters the activity count for the activity template.
		 *
		 * @since 1.2.0
		 *
		 * @param int $activity_count The count for total activity.
		 */
		return apply_filters( 'bp_get_activity_count', (int) $activities_template->activity_count );
	}

/**
 * Output the number of activities per page.
 *
 * @since 1.2.0
 *
 */
function bp_activity_per_page() {
	echo bp_get_activity_per_page();
}

	/**
	 * Return the number of activities per page.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int The activities per page.
	 */
	function bp_get_activity_per_page() {
		global $activities_template;

		/**
		 * Filters the activity posts per page value.
		 *
		 * @since 1.2.0
		 *
		 * @param int $pag_num How many post should be displayed for pagination.
		 */
		return apply_filters( 'bp_get_activity_per_page', (int) $activities_template->pag_num );
	}

/**
 * Output the activity ID.
 *
 * @since 1.2.0
 *
 */
function bp_activity_id() {
	echo bp_get_activity_id();
}

	/**
	 * Return the activity ID.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int The activity ID.
	 */
	function bp_get_activity_id() {
		global $activities_template;

		/**
		 * Filters the activity ID being displayed.
		 *
		 * @since 1.2.0
		 *
		 * @param int $id The activity ID.
		 */
		return apply_filters( 'bp_get_activity_id', $activities_template->activity->id );
	}

/**
 * Output the activity item ID.
 *
 * @since 1.2.0
 *
 */
function bp_activity_item_id() {
	echo bp_get_activity_item_id();
}

	/**
	 * Return the activity item ID.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int The activity item ID.
	 */
	function bp_get_activity_item_id() {
		global $activities_template;

		/**
		 * Filters the activity item ID being displayed.
		 *
		 * @since 1.2.0
		 *
		 * @param int $item_id The activity item ID.
		 */
		return apply_filters( 'bp_get_activity_item_id', $activities_template->activity->item_id );
	}

/**
 * Output the activity secondary item ID.
 *
 * @since 1.2.0
 *
 */
function bp_activity_secondary_item_id() {
	echo bp_get_activity_secondary_item_id();
}

	/**
	 * Return the activity secondary item ID.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int The activity secondary item ID.
	 */
	function bp_get_activity_secondary_item_id() {
		global $activities_template;

		/**
		 * Filters the activity secondary item ID being displayed.
		 *
		 * @since 1.2.0
		 *
		 * @param int $secondary_item_id The activity secondary item ID.
		 */
		return apply_filters( 'bp_get_activity_secondary_item_id', $activities_template->activity->secondary_item_id );
	}

/**
 * Output the date the activity was recorded.
 *
 * @since 1.2.0
 *
 */
function bp_activity_date_recorded() {
	echo bp_get_activity_date_recorded();
}

	/**
	 * Return the date the activity was recorded.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The date the activity was recorded.
	 */
	function bp_get_activity_date_recorded() {
		global $activities_template;

		/**
		 * Filters the date the activity was recorded.
		 *
		 * @since 1.2.0
		 *
		 * @param int $date_recorded The activity's date.
		 */
		return apply_filters( 'bp_get_activity_date_recorded', $activities_template->activity->date_recorded );
	}

/**
 * Output the activity object name.
 *
 * @since 1.2.0
 *
 */
function bp_activity_object_name() {
	echo bp_get_activity_object_name();
}

	/**
	 * Return the activity object name.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity object name.
	 */
	function bp_get_activity_object_name() {
		global $activities_template;

		/**
		 * Filters the activity object name.
		 *
		 * @since 1.2.0
		 *
		 * @param string $activity_component The activity object name.
		 */
		return apply_filters( 'bp_get_activity_object_name', $activities_template->activity->component );
	}

/**
 * Output the activity type.
 *
 * @since 1.2.0
 *
 */
function bp_activity_type() {
	echo bp_get_activity_type();
}

	/**
	 * Return the activity type.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity type.
	 */
	function bp_get_activity_type() {
		global $activities_template;

		/**
		 * Filters the activity type.
		 *
		 * @since 1.2.0
		 *
		 * @param string $activity_type The activity type.
		 */
		return apply_filters( 'bp_get_activity_type', $activities_template->activity->type );
	}

	/**
	 * Output the activity action name.
	 *
	 * Just a wrapper for bp_activity_type().
	 *
	 * @since 1.2.0
	 * @deprecated 1.5.0
	 *
	 * @todo Properly deprecate in favor of bp_activity_type() and
	 *       remove redundant echo
	 *
	 */
	function bp_activity_action_name() { echo bp_activity_type(); }

	/**
	 * Return the activity type.
	 *
	 * Just a wrapper for bp_get_activity_type().
	 *
	 * @since 1.2.0
	 * @deprecated 1.5.0
	 *
	 * @todo Properly deprecate in favor of bp_get_activity_type().
	 *
	 *
	 * @return string The activity type.
	 */
	function bp_get_activity_action_name() { return bp_get_activity_type(); }

/**
 * Output the activity user ID.
 *
 * @since 1.1.0
 *
 */
function bp_activity_user_id() {
	echo bp_get_activity_user_id();
}

	/**
	 * Return the activity user ID.
	 *
	 * @since 1.1.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int The activity user ID.
	 */
	function bp_get_activity_user_id() {
		global $activities_template;

		/**
		 * Filters the activity user ID.
		 *
		 * @since 1.1.0
		 *
		 * @param int $user_id The activity user ID.
		 */
		return apply_filters( 'bp_get_activity_user_id', $activities_template->activity->user_id );
	}

/**
 * Output the activity user link.
 *
 * @since 1.2.0
 *
 */
function bp_activity_user_link() {
	echo bp_get_activity_user_link();
}

	/**
	 * Return the activity user link.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string $link The activity user link.
	 */
	function bp_get_activity_user_link() {
		global $activities_template;

		if ( empty( $activities_template->activity->user_id ) || empty( $activities_template->activity->user_nicename ) || empty( $activities_template->activity->user_login ) ) {
			$link = $activities_template->activity->primary_link;
		} else {
			$link = bp_core_get_user_domain( $activities_template->activity->user_id, $activities_template->activity->user_nicename, $activities_template->activity->user_login );
		}

		/**
		 * Filters the activity user link.
		 *
		 * @since 1.2.0
		 *
		 * @param string $link The activity user link.
		 */
		return apply_filters( 'bp_get_activity_user_link', $link );
	}

/**
 * Output the avatar of the user that performed the action.
 *
 * @since 1.1.0
 *
 * @see bp_get_activity_avatar() for description of arguments.
 *
 * @param array|string $args See {@link bp_get_activity_avatar()} for description.
 */
function bp_activity_avatar( $args = '' ) {
	echo bp_get_activity_avatar( $args );
}
	/**
	 * Return the avatar of the user that performed the action.
	 *
	 * @since 1.1.0
	 *
	 * @see bp_core_fetch_avatar() For a description of the arguments.
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @param array|string $args  {
	 *     Arguments are listed here with an explanation of their defaults.
	 *     For more information about the arguments, see
	 *     {@link bp_core_fetch_avatar()}.
	 *     @type string      $alt     Default: 'Profile picture of [user name]' if
	 *                                activity user name is available, otherwise 'Profile picture'.
	 *     @type string      $class   Default: 'avatar'.
	 *     @type string|bool $email   Default: Email of the activity's
	 *                                associated user, if available. Otherwise false.
	 *     @type string      $type    Default: 'full' when viewing a single activity
	 *                                permalink page, otherwise 'thumb'.
	 *     @type int|bool    $user_id Default: ID of the activity's user.
	 * }
	 * @return string User avatar string.
	 */
	function bp_get_activity_avatar( $args = '' ) {
		global $activities_template;

		$bp = buddypress();

		// On activity permalink pages, default to the full-size avatar.
		$type_default = bp_is_single_activity() ? 'full' : 'thumb';

		// Within the activity comment loop, the current activity should be set
		// to current_comment. Otherwise, just use activity.
		$current_activity_item = isset( $activities_template->activity->current_comment ) ? $activities_template->activity->current_comment : $activities_template->activity;

		// Activity user display name.
		$dn_default  = isset( $current_activity_item->display_name ) ? $current_activity_item->display_name : '';

		// Prepend some descriptive text to alt.
		$alt_default = !empty( $dn_default ) ? sprintf( __( 'Profile picture of %s', 'buddypress' ), $dn_default ) : __( 'Profile picture', 'buddypress' );

		$defaults = array(
			'alt'     => $alt_default,
			'class'   => 'avatar',
			'email'   => false,
			'type'    => $type_default,
			'user_id' => false
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		if ( !isset( $height ) && !isset( $width ) ) {

			// Backpat.
			if ( isset( $bp->avatar->full->height ) || isset( $bp->avatar->thumb->height ) ) {
				$height = ( 'full' == $type ) ? $bp->avatar->full->height : $bp->avatar->thumb->height;
			} else {
				$height = 20;
			}

			// Backpat.
			if ( isset( $bp->avatar->full->width ) || isset( $bp->avatar->thumb->width ) ) {
				$width = ( 'full' == $type ) ? $bp->avatar->full->width : $bp->avatar->thumb->width;
			} else {
				$width = 20;
			}
		}

		/**
		 * Filters the activity avatar object based on current activity item component.
		 *
		 * This is a variable filter dependent on the component used.
		 * Possible hooks are bp_get_activity_avatar_object_blog,
		 * bp_get_activity_avatar_object_group, and bp_get_activity_avatar_object_user.
		 *
		 * @since 1.1.0
		 *
		 * @param string $component Component being displayed.
		 */
		$object  = apply_filters( 'bp_get_activity_avatar_object_' . $current_activity_item->component, 'user' );
		$item_id = !empty( $user_id ) ? $user_id : $current_activity_item->user_id;

		/**
		 * Filters the activity avatar item ID.
		 *
		 * @since 1.2.10
		 *
		 * @param int $item_id Item ID for the activity avatar.
		 */
		$item_id = apply_filters( 'bp_get_activity_avatar_item_id', $item_id );

		// If this is a user object pass the users' email address for Gravatar so we don't have to prefetch it.
		if ( 'user' == $object && empty( $user_id ) && empty( $email ) && isset( $current_activity_item->user_email ) ) {
			$email = $current_activity_item->user_email;
		}

		/**
		 * Filters the value returned by bp_core_fetch_avatar.
		 *
		 * @since 1.1.3
		 *
		 * @param array $value HTML image element containing the activity avatar.
		 */
		return apply_filters( 'bp_get_activity_avatar', bp_core_fetch_avatar( array(
			'item_id' => $item_id,
			'object'  => $object,
			'type'    => $type,
			'alt'     => $alt,
			'class'   => $class,
			'width'   => $width,
			'height'  => $height,
			'email'   => $email
		) ) );
	}

/**
 * Output the avatar of the object that action was performed on.
 *
 * @since 1.2.0
 *
 * @see bp_get_activity_secondary_avatar() for description of arguments.
 *
 * @param array|string $args See {@link bp_get_activity_secondary_avatar} for description.
 */
function bp_activity_secondary_avatar( $args = '' ) {
	echo bp_get_activity_secondary_avatar( $args );
}

	/**
	 * Return the avatar of the object that action was performed on.
	 *
	 * @since 1.2.0
	 *
	 * @see bp_core_fetch_avatar() for description of arguments.
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @param array|string $args  {
	 *     For a complete description of arguments, see {@link bp_core_fetch_avatar()}.
	 *     @type string      $alt     Default value varies based on current activity
	 *                                item component.
	 *     @type string      $type    Default: 'full' when viewing a single activity
	 *                                permalink page, otherwise 'thumb'.
	 *     @type string      $class   Default: 'avatar'.
	 *     @type string|bool $email   Default: email of the activity's user.
	 *     @type int|bool    $user_id Default: ID of the activity's user.
	 * }
	 * @return string The secondary avatar.
	 */
	function bp_get_activity_secondary_avatar( $args = '' ) {
		global $activities_template;

		$r = wp_parse_args( $args, array(
			'alt'        => '',
			'type'       => 'thumb',
			'width'      => 20,
			'height'     => 20,
			'class'      => 'avatar',
			'link_class' => '',
			'linked'     => true,
			'email'      => false
		) );
		extract( $r, EXTR_SKIP );

		// Set item_id and object (default to user).
		switch ( $activities_template->activity->component ) {
			case 'groups' :
				if ( bp_disable_group_avatar_uploads() ) {
					return false;
				}

				$object  = 'group';
				$item_id = $activities_template->activity->item_id;
				$link    = '';
				$name    = '';

				// Only if groups is active.
				if ( bp_is_active( 'groups' ) ) {
					$group = groups_get_group( $item_id );
					$link  = bp_get_group_permalink( $group );
					$name  = $group->name;
				}

				if ( empty( $alt ) ) {
					$alt = __( 'Group logo', 'buddypress' );

					if ( ! empty( $name ) ) {
						$alt = sprintf( __( 'Group logo of %s', 'buddypress' ), $name );
					}
				}

				break;
			case 'blogs' :
				$object  = 'blog';
				$item_id = $activities_template->activity->item_id;
				$link    = home_url();

				if ( empty( $alt ) ) {
					$alt = sprintf( __( 'Profile picture of the author of the site %s', 'buddypress' ), get_blog_option( $item_id, 'blogname' ) );
				}

				break;
			case 'friends' :
				$object  = 'user';
				$item_id = $activities_template->activity->secondary_item_id;
				$link    = bp_core_get_userlink( $item_id, false, true );

				if ( empty( $alt ) ) {
					$alt = sprintf( __( 'Profile picture of %s', 'buddypress' ), bp_core_get_user_displayname( $activities_template->activity->secondary_item_id ) );
				}

				break;
			default :
				$object  = 'user';
				$item_id = $activities_template->activity->user_id;
				$email   = $activities_template->activity->user_email;
				$link    = bp_core_get_userlink( $item_id, false, true );

				if ( empty( $alt ) ) {
					$alt = sprintf( __( 'Profile picture of %s', 'buddypress' ), $activities_template->activity->display_name );
				}

				break;
		}

		/**
		 * Filters the activity secondary avatar object based on current activity item component.
		 *
		 * This is a variable filter dependent on the component used. Possible hooks are
		 * bp_get_activity_secondary_avatar_object_blog, bp_get_activity_secondary_avatar_object_group,
		 * and bp_get_activity_secondary_avatar_object_user.
		 *
		 * @since 1.2.10
		 *
		 * @param string $object Component being displayed.
		 */
		$object  = apply_filters( 'bp_get_activity_secondary_avatar_object_' . $activities_template->activity->component, $object );

		/**
		 * Filters the activity secondary avatar item ID.
		 *
		 * @since 1.2.10
		 *
		 * @param int $item_id ID for the secondary avatar item.
		 */
		$item_id = apply_filters( 'bp_get_activity_secondary_avatar_item_id', $item_id );

		// If we have no item_id or object, there is no avatar to display.
		if ( empty( $item_id ) || empty( $object ) ) {
			return false;
		}

		// Get the avatar.
		$avatar = bp_core_fetch_avatar( array(
			'item_id' => $item_id,
			'object'  => $object,
			'type'    => $type,
			'alt'     => $alt,
			'class'   => $class,
			'width'   => $width,
			'height'  => $height,
			'email'   => $email
		) );

		if ( !empty( $linked ) ) {

			/**
			 * Filters the secondary avatar link for current activity.
			 *
			 * @since 1.7.0
			 *
			 * @param string $link      Link to wrap the avatar image in.
			 * @param string $component Activity component being acted on.
			 */
			$link = apply_filters( 'bp_get_activity_secondary_avatar_link', $link, $activities_template->activity->component );

			/**
			 * Filters the determined avatar for the secondary activity item.
			 *
			 * @since 1.2.10
			 *
			 * @param string $avatar Formatted HTML <img> element, or raw avatar URL.
			 */
			$avatar = apply_filters( 'bp_get_activity_secondary_avatar', $avatar );

			return sprintf( '<a href="%s" class="%s">%s</a>',
				$link,
				$link_class,
				$avatar
			);
		}

		/** This filter is documented in bp-activity/bp-activity-template.php */
		return apply_filters( 'bp_get_activity_secondary_avatar', $avatar );
	}

/**
 * Output the activity action.
 *
 * @since 1.2.0
 *
 * @param array $args See bp_get_activity_action().
 */
function bp_activity_action( $args = array() ) {
	echo bp_get_activity_action( $args );
}

	/**
	 * Return the activity action.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @param array $args {
	 *     @type bool $no_timestamp Whether to exclude the timestamp.
	 * }
	 *
	 * @return string The activity action.
	 */
	function bp_get_activity_action( $args = array() ) {
		global $activities_template;

		$r = wp_parse_args( $args, array(
			'no_timestamp' => false,
		) );

		/**
		 * Filters the activity action before the action is inserted as meta.
		 *
		 * @since 1.2.10
		 *
		 * @param array $value Array containing the current action, the current activity, and the $args array passed into the function.
		 */
		$action = apply_filters_ref_array( 'bp_get_activity_action_pre_meta', array(
			$activities_template->activity->action,
			&$activities_template->activity,
			$r
		) );

		// Prepend the activity action meta (link, time since, etc...).
		if ( ! empty( $action ) && empty( $r['no_timestamp'] ) ) {
			$action = bp_insert_activity_meta( $action );
		}

		/**
		 * Filters the activity action after the action has been inserted as meta.
		 *
		 * @since 1.2.0
		 *
		 * @param array $value Array containing the current action, the current activity, and the $args array passed into the function.
		 */
		return apply_filters_ref_array( 'bp_get_activity_action', array(
			$action,
			&$activities_template->activity,
			$r
		) );
	}

/**
 * Output the activity content body.
 *
 * @since 1.2.0
 *
 */
function bp_activity_content_body() {
	echo bp_get_activity_content_body();
}

	/**
	 * Return the activity content body.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity content body.
	 */
	function bp_get_activity_content_body() {
		global $activities_template;

		// Backwards compatibility if action is not being used.
		if ( empty( $activities_template->activity->action ) && ! empty( $activities_template->activity->content ) ) {
			$activities_template->activity->content = bp_insert_activity_meta( $activities_template->activity->content );
		}

		/**
		 * Filters the activity content body.
		 *
		 * @since 1.2.0
		 *
		 * @param string $content  Content body.
		 * @param object $activity Activity object. Passed by reference.
		 */
		return apply_filters_ref_array( 'bp_get_activity_content_body', array( $activities_template->activity->content, &$activities_template->activity ) );
	}

/**
 * Does the activity have content?
 *
 * @since 1.2.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @return bool True if activity has content, false otherwise.
 */
function bp_activity_has_content() {
	global $activities_template;

	if ( ! empty( $activities_template->activity->content ) ) {
		return true;
	}

	return false;
}

/**
 * Output the activity content.
 *
 * @since 1.0.0
 * @deprecated 1.5.0
 *
 * @todo properly deprecate this function.
 *
 */
function bp_activity_content() {
	echo bp_get_activity_content();
}

	/**
	 * Return the activity content.
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.0
	 *
	 * @todo properly deprecate this function.
	 *
	 *
	 * @return string The activity content.
	 */
	function bp_get_activity_content() {

		/**
		 * If you want to filter activity update content, please use
		 * the filter 'bp_get_activity_content_body'.
		 *
		 * This function is mainly for backwards compatibility.
		 */
		$content = bp_get_activity_action() . ' ' . bp_get_activity_content_body();
		return apply_filters( 'bp_get_activity_content', $content );
	}

/**
 * Attach metadata about an activity item to the activity content.
 *
 * This metadata includes the time since the item was posted (which will appear
 * as a link to the item's permalink).
 *
 * @since 1.2.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @param string $content The activity content.
 * @return string The activity content with the metadata string attached.
 */
function bp_insert_activity_meta( $content = '' ) {
	global $activities_template;

	// Strip any legacy time since placeholders from BP 1.0-1.1.
	$new_content = str_replace( '<span class="time-since">%s</span>', '', $content );

	// Get the time since this activity was recorded.
	$date_recorded  = bp_core_time_since( $activities_template->activity->date_recorded );

	// Set up 'time-since' <span>.
	$time_since = sprintf(
		'<span class="time-since" data-livestamp="%1$s">%2$s</span>',
		bp_core_get_iso8601_date( $activities_template->activity->date_recorded ),
		$date_recorded
	);

	/**
	 * Filters the activity item time since markup.
	 *
	 * @since 1.2.0
	 *
	 * @param array $value Array containing the time since markup and the current activity component.
	 */
	$time_since = apply_filters_ref_array( 'bp_activity_time_since', array(
		$time_since,
		&$activities_template->activity
	) );

	// Insert the permalink.
	if ( ! bp_is_single_activity() ) {

		// Setup variables for activity meta.
		$activity_permalink = bp_activity_get_permalink( $activities_template->activity->id, $activities_template->activity );
		$activity_meta      = sprintf( '%1$s <a href="%2$s" class="view activity-time-since bp-tooltip" data-bp-tooltip="%3$s">%4$s</a>',
			$new_content,
			$activity_permalink,
			esc_attr__( 'View Discussion', 'buddypress' ),
			$time_since
		);

		/**
		 * Filters the activity permalink to be added to the activity content.
		 *
		 * @since 1.2.0
		 *
		 * @param array $value Array containing the html markup for the activity permalink, after being parsed by
		 *                     sprintf and current activity component.
		 */
		$new_content = apply_filters_ref_array( 'bp_activity_permalink', array(
			$activity_meta,
			&$activities_template->activity
		) );
	} else {
		$new_content .= str_pad( $time_since, strlen( $time_since ) + 2, ' ', STR_PAD_BOTH );
	}

	/**
	 * Filters the activity content after activity metadata has been attached.
	 *
	 * @since 1.2.0
	 *
	 * @param string $content Activity content with the activity metadata added.
	 */
	return apply_filters( 'bp_insert_activity_meta', $new_content, $content );
}

/**
 * Determine if the current user can delete an activity item.
 *
 * @since 1.2.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @param false|BP_Activity_Activity $activity Optional. Falls back on the current item in the loop.
 * @return bool True if can delete, false otherwise.
 */
function bp_activity_user_can_delete( $activity = false ) {
	global $activities_template;

	// Try to use current activity if none was passed.
	if ( empty( $activity ) && ! empty( $activities_template->activity ) ) {
		$activity = $activities_template->activity;
	}

	// If current_comment is set, we'll use that in place of the main activity.
	if ( isset( $activity->current_comment ) ) {
		$activity = $activity->current_comment;
	}

	// Assume the user cannot delete the activity item.
	$can_delete = false;

	// Only logged in users can delete activity.
	if ( is_user_logged_in() ) {

		// Community moderators can always delete activity (at least for now).
		if ( bp_current_user_can( 'bp_moderate' ) ) {
			$can_delete = true;
		}

		// Users are allowed to delete their own activity. This is actually
		// quite powerful, because doing so also deletes all comments to that
		// activity item. We should revisit this eventually.
		if ( isset( $activity->user_id ) && ( $activity->user_id === bp_loggedin_user_id() ) ) {
			$can_delete = true;
		}

		// Viewing a single item, and this user is an admin of that item.
		if ( bp_is_single_item() && bp_is_item_admin() ) {
			$can_delete = true;
		}
	}

	/**
	 * Filters whether the current user can delete an activity item.
	 *
	 * @since 1.5.0
	 *
	 * @param bool   $can_delete Whether the user can delete the item.
	 * @param object $activity   Current activity item object.
	 */
	return (bool) apply_filters( 'bp_activity_user_can_delete', $can_delete, $activity );
}

/**
 * Output the activity parent content.
 *
 * @since 1.2.0
 *
 * @see bp_get_activity_parent_content() for a description of arguments.
 *
 * @param array|string $args See {@link bp_get_activity_parent_content} for description.
 */
function bp_activity_parent_content( $args = '' ) {
	echo bp_get_activity_parent_content($args);
}

	/**
	 * Return the activity content.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @param string $args Unused. Left over from an earlier implementation.
	 * @return mixed False on failure, otherwise the activity parent content.
	 */
	function bp_get_activity_parent_content( $args = '' ) {
		global $activities_template;

		// Bail if no activity on no item ID.
		if ( empty( $activities_template->activity ) || empty( $activities_template->activity->item_id ) ) {
			return false;
		}

		// Get the ID of the parent activity content.
		$parent_id = $activities_template->activity->item_id;

		// Bail if no parent content.
		if ( empty( $activities_template->activity_parents[ $parent_id ] ) ) {
			return false;
		}

		// Bail if no action.
		if ( empty( $activities_template->activity_parents[ $parent_id ]->action ) ) {
			return false;
		}

		// Content always includes action.
		$content = $activities_template->activity_parents[ $parent_id ]->action;

		// Maybe append activity content, if it exists.
		if ( ! empty( $activities_template->activity_parents[ $parent_id ]->content ) ) {
			$content .= ' ' . $activities_template->activity_parents[ $parent_id ]->content;
		}

		// Remove the time since content for backwards compatibility.
		$content = str_replace( '<span class="time-since">%s</span>', '', $content );

		// Remove images.
		$content = preg_replace( '/<img[^>]*>/Ui', '', $content );

		/**
		 * Filters the activity parent content.
		 *
		 * @since 1.2.0
		 *
		 * @param string $content Content set to be displayed as parent content.
		 */
		return apply_filters( 'bp_get_activity_parent_content', $content );
	}

/**
 * Output the parent activity's user ID.
 *
 * @since 1.7.0
 */
function bp_activity_parent_user_id() {
	echo bp_get_activity_parent_user_id();
}

	/**
	 * Return the parent activity's user ID.
	 *
	 * @since 1.7.0
	 *
	 * @global BP_Activity_Template $activities_template
	 *
	 * @return bool|int False if parent activity can't be found, otherwise
	 *                  the parent activity's user ID.
	 */
	function bp_get_activity_parent_user_id() {
		global $activities_template;

		// Bail if no activity on no item ID.
		if ( empty( $activities_template->activity ) || empty( $activities_template->activity->item_id ) ) {
			return false;
		}

		// Get the ID of the parent activity content.
		$parent_id = $activities_template->activity->item_id;

		// Bail if no parent item.
		if ( empty( $activities_template->activity_parents[ $parent_id ] ) ) {
			return false;
		}

		// Bail if no parent user ID.
		if ( empty( $activities_template->activity_parents[ $parent_id ]->user_id ) ) {
			return false;
		}

		$retval = $activities_template->activity_parents[ $parent_id ]->user_id;

		/**
		 * Filters the activity parent item's user ID.
		 *
		 * @since 1.7.0
		 *
		 * @param int $retval ID for the activity parent's user.
		 */
		return (int) apply_filters( 'bp_get_activity_parent_user_id', $retval );
	}

/**
 * Output whether or not the current activity is in a current user's favorites.
 *
 * @since 1.2.0
 *
 */
function bp_activity_is_favorite() {
	echo bp_get_activity_is_favorite();
}

	/**
	 * Return whether the current activity is in a current user's favorites.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return bool True if user favorite, false otherwise.
	 */
	function bp_get_activity_is_favorite() {
		global $activities_template;

		/**
		 * Filters whether the current activity item is in the current user's favorites.
		 *
		 * @since 1.2.0
		 *
		 * @param bool $value Whether or not the current activity item is in the current user's favorites.
		 */
		return (bool) apply_filters( 'bp_get_activity_is_favorite', in_array( $activities_template->activity->id, (array) $activities_template->my_favs ) );
	}

/**
 * Output the comment markup for an activity item.
 *
 * @since 1.2.0
 *
 * @todo deprecate $args param
 *
 * @param array|string $args See {@link bp_activity_get_comments} for description.
 */
function bp_activity_comments( $args = '' ) {
	echo bp_activity_get_comments( $args );
}

	/**
	 * Get the comment markup for an activity item.
	 *
	 * @since 1.2.0
	 *
	 * @todo deprecate $args param
	 * @todo Given that checks for children already happen in bp_activity_recurse_comments(),
	 *       this function can probably be streamlined or removed.
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @param string $args Unused. Left over from an earlier implementation.
	 * @return bool
	 */
	function bp_activity_get_comments( $args = '' ) {
		global $activities_template;

		if ( empty( $activities_template->activity->children ) ) {
			return false;
		}

		bp_activity_recurse_comments( $activities_template->activity );
	}

		/**
		 * Loops through a level of activity comments and loads the template for each.
		 *
		 * Note: The recursion itself used to happen entirely in this function. Now it is
		 * split between here and the comment.php template.
		 *
		 * @since 1.2.0
		 *
		 * @global object $activities_template {@link BP_Activity_Template}
		 *
		 * @param object $comment The activity object currently being recursed.
		 * @return bool|string
		 */
		function bp_activity_recurse_comments( $comment ) {
			global $activities_template;

			if ( empty( $comment ) ) {
				return false;
			}

			if ( empty( $comment->children ) ) {
				return false;
			}

			/**
			 * Filters the opening tag for the template that lists activity comments.
			 *
			 * @since 1.6.0
			 *
			 * @param string $value Opening tag for the HTML markup to use.
			 */
			echo apply_filters( 'bp_activity_recurse_comments_start_ul', '<ul>' );
			foreach ( (array) $comment->children as $comment_child ) {

				// Put the comment into the global so it's available to filters.
				$activities_template->activity->current_comment = $comment_child;

				$template = bp_locate_template( 'activity/comment.php', false, false );

				// Backward compatibility. In older versions of BP, the markup was
				// generated in the PHP instead of a template. This ensures that
				// older themes (which are not children of bp-default and won't
				// have the new template) will still work.
				if ( !$template ) {
					$template = buddypress()->plugin_dir . '/bp-themes/bp-default/activity/comment.php';
				}

				load_template( $template, false );

				unset( $activities_template->activity->current_comment );
			}

			/**
			 * Filters the closing tag for the template that list activity comments.
			 *
			 * @since  1.6.0
			 *
			 * @param string $value Closing tag for the HTML markup to use.
			 */
			echo apply_filters( 'bp_activity_recurse_comments_end_ul', '</ul>' );
		}

/**
 * Utility function that returns the comment currently being recursed.
 *
 * @since 1.5.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @return object|bool $current_comment The activity comment currently being
 *                                      displayed. False on failure.
 */
function bp_activity_current_comment() {
	global $activities_template;

	$current_comment = !empty( $activities_template->activity->current_comment )
		? $activities_template->activity->current_comment
		: false;

	/**
	 * Filters the current comment being recursed.
	 *
	 * @since 1.5.0
	 *
	 * @param object|bool $current_comment The activity comment currently being displayed. False on failure.
	 */
	return apply_filters( 'bp_activity_current_comment', $current_comment );
}


/**
 * Output the ID of the activity comment currently being displayed.
 *
 * @since 1.5.0
 *
 */
function bp_activity_comment_id() {
	echo bp_get_activity_comment_id();
}

	/**
	 * Return the ID of the activity comment currently being displayed.
	 *
	 * @since 1.5.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int|bool $comment_id The ID of the activity comment currently
	 *                              being displayed, false if none is found.
	 */
	function bp_get_activity_comment_id() {
		global $activities_template;

		$comment_id = isset( $activities_template->activity->current_comment->id ) ? $activities_template->activity->current_comment->id : false;

		/**
		 * Filters the ID of the activity comment currently being displayed.
		 *
		 * @since 1.5.0
		 *
		 * @param int|bool $comment_id ID for the comment currently being displayed.
		 */
		return apply_filters( 'bp_activity_comment_id', $comment_id );
	}

/**
 * Output the ID of the author of the activity comment currently being displayed.
 *
 * @since 1.5.0
 *
 */
function bp_activity_comment_user_id() {
	echo bp_get_activity_comment_user_id();
}

	/**
	 * Return the ID of the author of the activity comment currently being displayed.
	 *
	 * @since 1.5.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return int|bool $user_id The user_id of the author of the displayed
	 *                           activity comment. False on failure.
	 */
	function bp_get_activity_comment_user_id() {
		global $activities_template;

		$user_id = isset( $activities_template->activity->current_comment->user_id ) ? $activities_template->activity->current_comment->user_id : false;

		/**
		 * Filters the ID of the author of the activity comment currently being displayed.
		 *
		 * @since 1.5.0
		 *
		 * @param int|bool $user_id ID for the author of the comment currently being displayed.
		 */
		return apply_filters( 'bp_activity_comment_user_id', $user_id );
	}

/**
 * Output the author link for the activity comment currently being displayed.
 *
 * @since 1.5.0
 *
 */
function bp_activity_comment_user_link() {
	echo bp_get_activity_comment_user_link();
}

	/**
	 * Return the author link for the activity comment currently being displayed.
	 *
	 * @since 1.5.0
	 *
	 *
	 * @return string $user_link The URL of the activity comment author's profile.
	 */
	function bp_get_activity_comment_user_link() {
		$user_link = bp_core_get_user_domain( bp_get_activity_comment_user_id() );

		/**
		 * Filters the author link for the activity comment currently being displayed.
		 *
		 * @since 1.5.0
		 *
		 * @param string $user_link Link for the author of the activity comment currently being displayed.
		 */
		return apply_filters( 'bp_activity_comment_user_link', $user_link );
	}

/**
 * Output the author name for the activity comment currently being displayed.
 *
 * @since 1.5.0
 *
 */
function bp_activity_comment_name() {
	echo bp_get_activity_comment_name();
}

	/**
	 * Return the author name for the activity comment currently being displayed.
	 *
	 * The use of the 'bp_acomment_name' filter is deprecated. Please use
	 * 'bp_activity_comment_name'.
	 *
	 * @since 1.5.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string $name The full name of the activity comment author.
	 */
	function bp_get_activity_comment_name() {
		global $activities_template;

		if ( isset( $activities_template->activity->current_comment->user_fullname ) ) {

			$name = apply_filters( 'bp_acomment_name', $activities_template->activity->current_comment->user_fullname, $activities_template->activity->current_comment );  // Backward compatibility.
		} else {
			$name = $activities_template->activity->current_comment->display_name;
		}

		/**
		 * Filters the name of the author for the activity comment.
		 *
		 * @since 1.5.0
		 *
		 * @param string $name Name to be displayed with the activity comment.
		 */
		return apply_filters( 'bp_activity_comment_name', $name );
	}

/**
 * Output the formatted date_recorded of the activity comment currently being displayed.
 *
 * @since 1.5.0
 *
 */
function bp_activity_comment_date_recorded() {
	echo bp_get_activity_comment_date_recorded();
}

	/**
	 * Return the formatted date_recorded for the activity comment currently being displayed.
	 *
	 * @since 1.5.0
	 *
	 *
	 * @return string|bool $date_recorded Time since the activity was recorded,
	 *                                    in the form "%s ago". False on failure.
	 */
	function bp_get_activity_comment_date_recorded() {

		/**
		 * Filters the recorded date of the activity comment currently being displayed.
		 *
		 * @since 1.5.0
		 *
		 * @param string|bool Date for the activity comment currently being displayed.
		 */
		return apply_filters( 'bp_activity_comment_date_recorded', bp_core_time_since( bp_get_activity_comment_date_recorded_raw() ) );
	}

/**
 * Output the date_recorded of the activity comment currently being displayed.
 *
 * @since 2.3.0
 *
 */
function bp_activity_comment_date_recorded_raw() {
	echo bp_get_activity_comment_date_recorded_raw();
}

	/**
	 * Return the date_recorded for the activity comment currently being displayed.
	 *
	 * @since 2.3.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string|bool $date_recorded Time since the activity was recorded,
	 *                                    in the form "%s ago". False on failure.
	 */
	function bp_get_activity_comment_date_recorded_raw() {
		global $activities_template;

		/**
		 * Filters the raw recorded date of the activity comment currently being displayed.
		 *
		 * @since 2.3.0
		 *
		 * @param string|bool Raw date for the activity comment currently being displayed.
		 */
		return apply_filters( 'bp_activity_comment_date_recorded', $activities_template->activity->current_comment->date_recorded );
	}

/**
 * Output the 'delete' URL for the activity comment currently being displayed.
 *
 * @since 1.5.0
 *
 */
function bp_activity_comment_delete_link() {
	echo bp_get_activity_comment_delete_link();
}

	/**
	 * Gets the 'delete' URL for the activity comment currently being displayed.
	 *
	 * @since 1.5.0
	 *
	 *
	 * @return string $link The nonced URL for deleting the current
	 *                      activity comment.
	 */
	function bp_get_activity_comment_delete_link() {
		$link = wp_nonce_url( trailingslashit( bp_get_activity_directory_permalink() . 'delete/' . bp_get_activity_comment_id() ) . '?cid=' . bp_get_activity_comment_id(), 'bp_activity_delete_link' );

		/**
		 * Filters the link used for deleting the activity comment currently being displayed.
		 *
		 * @since 1.5.0
		 *
		 * @param string $link Link to use for deleting the currently displayed activity comment.
		 */
		return apply_filters( 'bp_activity_comment_delete_link', $link );
	}

/**
 * Output the content of the activity comment currently being displayed.
 *
 * @since 1.5.0
 *
 */
function bp_activity_comment_content() {
	echo bp_get_activity_comment_content();
}

	/**
	 * Return the content of the activity comment currently being displayed.
	 *
	 * The content is run through two filters. 'bp_get_activity_content'
	 * will apply all filters applied to activity items in general. Use
	 * 'bp_activity_comment_content' to modify the content of activity
	 * comments only.
	 *
	 * @since 1.5.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string $content The content of the current activity comment.
	 */
	function bp_get_activity_comment_content() {
		global $activities_template;

		/** This filter is documented in bp-activity/bp-activity-template.php */
		$content = apply_filters( 'bp_get_activity_content', $activities_template->activity->current_comment->content );

		/**
		 * Filters the content of the current activity comment.
		 *
		 * @since 1.2.0
		 * @since 3.0.0 Added $context parameter to disambiguate from bp_get_activity_comment_content().
		 *
		 * @param string $content The content of the current activity comment.
		 * @param string $context This filter's context ("get").
		 */
		return apply_filters( 'bp_activity_comment_content', $content, 'get' );
	}

/**
 * Output the activity comment count.
 *
 * @since 1.2.0
 *
 */
function bp_activity_comment_count() {
	echo bp_activity_get_comment_count();
}

	/**
	 * Return the comment count of an activity item.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @param array|null $deprecated Deprecated.
	 * @return int $count The activity comment count.
	 */
	function bp_activity_get_comment_count( $deprecated = null ) {
		global $activities_template;

		// Deprecated notice about $args.
		if ( ! empty( $deprecated ) ) {
			_deprecated_argument( __FUNCTION__, '1.2', sprintf( __( '%1$s no longer accepts arguments. See the inline documentation at %2$s for more details.', 'buddypress' ), __FUNCTION__, __FILE__ ) );
		}

		// Get the count using the purpose-built recursive function.
		$count = ! empty( $activities_template->activity->children )
			? bp_activity_recurse_comment_count( $activities_template->activity )
			: 0;

		/**
		 * Filters the activity comment count.
		 *
		 * @since 1.2.0
		 *
		 * @param int $count The activity comment count.
		 */
		return apply_filters( 'bp_activity_get_comment_count', (int) $count );
	}

		/**
		 * Return the total number of comments to the current comment.
		 *
		 * This function recursively adds the total number of comments each
		 * activity child has, and returns them.
		 *
		 * @since 1.2.0
		 *
		 *
		 * @param object $comment Activity comment object.
		 * @param int    $count The current iteration count.
		 * @return int $count The activity comment count.
		 */
		function bp_activity_recurse_comment_count( $comment, $count = 0 ) {

			// Copy the count.
			$new_count = $count;

			// Loop through children and recursively count comments.
			if ( ! empty( $comment->children ) ) {
				foreach ( (array) $comment->children as $comment ) {
					$new_count++;
					$new_count = bp_activity_recurse_comment_count( $comment, $new_count );
				}
			}

			/**
			 * Filters the total number of comments for the current comment.
			 *
			 * @since 2.1.0
			 *
			 * @param int    $new_count New total count for the current comment.
			 * @param object $comment   Activity comment object.
			 * @param int    $count     Current iteration count for the current comment.
			 */
			return apply_filters( 'bp_activity_recurse_comment_count', $new_count, $comment, $count );
		}

/**
 * Output the depth of the current activity comment.
 *
 * @since 2.0.0
 * @since 2.8.0 Added $comment as a parameter.
 *
 * @param object|int $comment Object of the activity comment or activity comment ID. Usually unnecessary
 *                            when used in activity comment loop.
 */
function bp_activity_comment_depth( $comment = 0 ) {
	echo bp_activity_get_comment_depth( $comment );
}

	/**
	 * Return the current activity comment depth.
	 *
	 * @since 2.0.0
	 * @since 2.8.0 Added $comment as a parameter.
	 *
	 * @param  object|int $comment Object of the activity comment or activity comment ID. Usually unnecessary
	 *                             when used in activity comment loop.
	 * @return int
	 */
	function bp_activity_get_comment_depth( $comment = 0 ) {
		$depth = 0;

		// Activity comment loop takes precedence.
		if ( isset( $GLOBALS['activities_template']->activity->current_comment->depth ) ) {
			$depth = $GLOBALS['activities_template']->activity->current_comment->depth;

		// Get depth for activity comment manually.
		} elseif ( ! empty( $comment ) ) {
			// We passed an activity ID, so fetch the activity object.
			if ( is_int( $comment ) ) {
				$comment = new BP_Activity_Activity( $comment );
			}

			// Recurse through activity tree to find the depth.
			if ( is_object( $comment ) && isset( $comment->type ) && 'activity_comment' === $comment->type ) {
				// Fetch the entire root comment tree... ugh.
				$comments = BP_Activity_Activity::get_activity_comments( $comment->item_id, 1, constant( 'PHP_INT_MAX' ) );

				// Recursively find our comment object from the comment tree.
				$iterator  = new RecursiveArrayIterator( $comments );
				$recursive = new RecursiveIteratorIterator( $iterator, RecursiveIteratorIterator::SELF_FIRST );
				foreach ( $recursive as $cid => $cobj ) {
					// Skip items that are not a comment object.
					if ( ! is_numeric( $cid ) || ! is_object( $cobj ) ) {
						continue;
					}

					// We found the activity comment! Set the depth.
					if ( $cid === $comment->id && isset( $cobj->depth ) ) {
						$depth = $cobj->depth;
						break;
					}
				}
			}
		}

		/**
		 * Filters the comment depth of the current activity comment.
		 *
		 * @since 2.0.0
		 *
		 * @param int $depth Depth for the current activity comment.
		 */
		return apply_filters( 'bp_activity_get_comment_depth', $depth );
	}

/**
 * Output the activity comment link.
 *
 * @since 1.2.0
 *
 */
function bp_activity_comment_link() {
	echo bp_get_activity_comment_link();
}

	/**
	 * Return the activity comment link.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity comment link.
	 */
	function bp_get_activity_comment_link() {
		global $activities_template;

		/**
		 * Filters the comment link for the current activity comment.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Constructed URL parameters with activity IDs.
		 */
		return apply_filters( 'bp_get_activity_comment_link', '?ac=' . $activities_template->activity->id . '/#ac-form-' . $activities_template->activity->id );
	}

/**
 * Output the activity comment form no JavaScript display CSS.
 *
 * @since 1.2.0
 *
 */
function bp_activity_comment_form_nojs_display() {
	echo bp_get_activity_comment_form_nojs_display();
}

	/**
	 * Return the activity comment form no JavaScript display CSS.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string|false The activity comment form no JavaScript
	 *                      display CSS. False on failure.
	 */
	function bp_get_activity_comment_form_nojs_display() {
		global $activities_template;

		if ( isset( $_GET['ac'] ) && ( $_GET['ac'] === ( $activities_template->activity->id . '/' ) ) ) {
			return 'style="display: block"';
		}

		return false;
	}

/**
 * Output the activity thread permalink.
 *
 * @since 1.2.0
 *
 */
function bp_activity_thread_permalink() {
	echo esc_url( bp_get_activity_thread_permalink() );
}

	/**
	 * Return the activity thread permalink.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @return string $link The activity thread permalink.
	 */
	function bp_get_activity_thread_permalink() {
		global $activities_template;

		$link = bp_activity_get_permalink( $activities_template->activity->id, $activities_template->activity );

		/**
		 * Filters the activity thread permalink.
		 *
		 * @since 1.2.0
		 *
		 * @param string $link The activity thread permalink.
		 */
		return apply_filters( 'bp_get_activity_thread_permalink', $link );
	}

/**
 * Output the activity comment permalink.
 *
 * @since 1.8.0
 *
 */
function bp_activity_comment_permalink() {
	echo esc_url( bp_get_activity_comment_permalink() );
}
	/**
	 * Return the activity comment permalink.
	 *
	 * @since 1.8.0
	 *
	 * @return string $link The activity comment permalink.
	 */
	function bp_get_activity_comment_permalink() {
		global $activities_template;

		$link = bp_activity_get_permalink( $activities_template->activity->id, $activities_template->activity );

		// Used for filter below.
		$comment_id = isset( $activities_template->activity->current_comment->id )
			? $activities_template->activity->current_comment->id
			: 0;

		/**
		 * Filters the activity comment permalink.
		 *
		 * @since 1.8.0
		 *
		 * @param string $link       Activity comment permalink.
		 * @param int    $comment_id ID for the current activity comment.
		 */
		return apply_filters( 'bp_get_activity_comment_permalink', $link, $comment_id );
	}

/**
 * Output the activity permalink ID.
 *
 * @since 1.2.0
 *
 */
function bp_activity_permalink_id() {
	echo bp_get_activity_permalink_id();
}

	/**
	 * Return the activity permalink ID.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @return string The activity permalink ID.
	 */
	function bp_get_activity_permalink_id() {

		/**
		 * Filters the activity action permalink ID.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Current action for the activity item.
		 */
		return apply_filters( 'bp_get_activity_permalink_id', bp_current_action() );
	}

/**
 * Output the activity favorite link.
 *
 * @since 1.2.0
 *
 */
function bp_activity_favorite_link() {
	echo bp_get_activity_favorite_link();
}

	/**
	 * Return the activity favorite link.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity favorite link.
	 */
	function bp_get_activity_favorite_link() {
		global $activities_template;

		/**
		 * Filters the activity favorite link.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Constructed link for favoriting the activity comment.
		 */
		return apply_filters( 'bp_get_activity_favorite_link', wp_nonce_url( home_url( bp_get_activity_root_slug() . '/favorite/' . $activities_template->activity->id . '/' ), 'mark_favorite' ) );
	}

/**
 * Output the activity unfavorite link.
 *
 * @since 1.2.0
 *
 */
function bp_activity_unfavorite_link() {
	echo bp_get_activity_unfavorite_link();
}

	/**
	 * Return the activity unfavorite link.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity unfavorite link.
	 */
	function bp_get_activity_unfavorite_link() {
		global $activities_template;

		/**
		 * Filters the activity unfavorite link.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value Constructed link for unfavoriting the activity comment.
		 */
		return apply_filters( 'bp_get_activity_unfavorite_link', wp_nonce_url( home_url( bp_get_activity_root_slug() . '/unfavorite/' . $activities_template->activity->id . '/' ), 'unmark_favorite' ) );
	}

/**
 * Output the activity CSS class.
 *
 * @since 1.0.0
 *
 */
function bp_activity_css_class() {
	echo bp_get_activity_css_class();
}

	/**
	 * Return the current activity item's CSS class.
	 *
	 * @since 1.0.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity item's CSS class.
	 */
	function bp_get_activity_css_class() {
		global $activities_template;

		/**
		 * Filters the available mini activity actions available as CSS classes.
		 *
		 * @since 1.2.0
		 *
		 * @param array $value Array of classes used to determine classes applied to HTML element.
		 */
		$mini_activity_actions = apply_filters( 'bp_activity_mini_activity_types', array(
			'friendship_accepted',
			'friendship_created',
			'new_blog',
			'joined_group',
			'created_group',
			'new_member'
		) );

		$class = ' activity-item';

		if ( in_array( $activities_template->activity->type, (array) $mini_activity_actions ) || empty( $activities_template->activity->content ) ) {
			$class .= ' mini';
		}

		if ( bp_activity_get_comment_count() && bp_activity_can_comment() ) {
			$class .= ' has-comments';
		}

		/**
		 * Filters the determined classes to add to the HTML element.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Classes to be added to the HTML element.
		 */
		return apply_filters( 'bp_get_activity_css_class', $activities_template->activity->component . ' ' . $activities_template->activity->type . $class );
	}

/**
 * Output the activity delete link.
 *
 * @since 1.1.0
 *
 */
function bp_activity_delete_link() {
	echo bp_get_activity_delete_link();
}

	/**
	 * Return the activity delete link.
	 *
	 * @since 1.1.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string $link Activity delete link. Contains $redirect_to arg
	 *                      if on single activity page.
	 */
	function bp_get_activity_delete_link() {

		$url   = bp_get_activity_delete_url();
		$class = 'delete-activity';

		// Determine if we're on a single activity page, and customize accordingly.
		if ( bp_is_activity_component() && is_numeric( bp_current_action() ) ) {
			$class = 'delete-activity-single';
		}

		$link = '<a href="' . esc_url( $url ) . '" class="button item-button bp-secondary-action ' . $class . ' confirm" rel="nofollow">' . __( 'Delete', 'buddypress' ) . '</a>';

		/**
		 * Filters the activity delete link.
		 *
		 * @since 1.1.0
		 *
		 * @param string $link Activity delete HTML link.
		 */
		return apply_filters( 'bp_get_activity_delete_link', $link );
	}

/**
 * Output the URL to delete a single activity stream item.
 *
 * @since 2.1.0
 *
 */
function bp_activity_delete_url() {
	echo esc_url( bp_get_activity_delete_url() );
}
	/**
	 * Return the URL to delete a single activity item.
	 *
	 * @since 2.1.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string $link Activity delete link. Contains $redirect_to arg
	 *                      if on single activity page.
	 */
	function bp_get_activity_delete_url() {
		global $activities_template;

		$url = trailingslashit( bp_get_root_domain() . '/' . bp_get_activity_root_slug() . '/delete/' . $activities_template->activity->id );

		// Determine if we're on a single activity page, and customize accordingly.
		if ( bp_is_activity_component() && is_numeric( bp_current_action() ) ) {
			$url = add_query_arg( array( 'redirect_to' => wp_get_referer() ), $url );
		}

		$url = wp_nonce_url( $url, 'bp_activity_delete_link' );

		/**
		 * Filters the activity delete URL.
		 *
		 * @since 2.1.0
		 *
		 * @param string $url Activity delete URL.
		 */
		return apply_filters( 'bp_get_activity_delete_url', $url );
	}

/**
 * Determine if a comment can be made on an activity item.
 *
 * @since 1.2.0
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @return bool $can_comment True if item can receive comments.
 */
function bp_activity_can_comment() {
	global $activities_template;
	$bp = buddypress();

	// Determine ability to comment based on activity type name.
	$activity_type = bp_get_activity_type();

	// Get the 'comment-reply' support for the current activity type.
	$can_comment = bp_activity_type_supports( $activity_type, 'comment-reply' );

	// Neutralize activity_comment.
	if ( 'activity_comment' === $activity_type ) {
		$can_comment = false;
	}

	/**
	 * Filters whether a comment can be made on an activity item.
	 *
	 * @since 1.5.0
	 * @since 2.5.0 Use $activity_type instead of $activity_name for the second parameter.
	 *
	 * @param bool   $can_comment     Status on if activity can be commented on.
	 * @param string $activity_type   Current activity type being checked on.
	 */
	return apply_filters( 'bp_activity_can_comment', $can_comment, $activity_type );
}