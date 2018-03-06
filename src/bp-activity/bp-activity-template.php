<?php
/**
 * BuddyPress Activity Template Functions.
 *
 * @package BuddyPress
 * @subpackage ActivityTemplate
 * @since 1.5.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Output the activity component slug.
 *
 * @since 1.5.0
 *
 */
function bp_activity_slug() {
	echo bp_get_activity_slug();
}
	/**
	 * Return the activity component slug.
	 *
	 * @since 1.5.0
	 *
	 *
	 * @return string The activity component slug.
	 */
	function bp_get_activity_slug() {

		/**
		 * Filters the activity component slug.
		 *
		 * @since 1.5.0
		 *
		 * @param string $slug Activity component slug.
		 */
		return apply_filters( 'bp_get_activity_slug', buddypress()->activity->slug );
	}

/**
 * Output the activity component root slug.
 *
 * @since 1.5.0
 *
 */
function bp_activity_root_slug() {
	echo bp_get_activity_root_slug();
}
	/**
	 * Return the activity component root slug.
	 *
	 * @since 1.5.0
	 *
	 *
	 * @return string The activity component root slug.
	 */
	function bp_get_activity_root_slug() {

		/**
		 * Filters the activity component root slug.
		 *
		 * @since 1.5.0
		 *
		 * @param string $root_slug Activity component root slug.
		 */
		return apply_filters( 'bp_get_activity_root_slug', buddypress()->activity->root_slug );
	}

/**
 * Output activity directory permalink.
 *
 * @since 1.5.0
 *
 */
function bp_activity_directory_permalink() {
	echo esc_url( bp_get_activity_directory_permalink() );
}
	/**
	 * Return activity directory permalink.
	 *
	 * @since 1.5.0
	 *
	 *
	 * @return string Activity directory permalink.
	 */
	function bp_get_activity_directory_permalink() {

		/**
		 * Filters the activity directory permalink.
		 *
		 * @since 1.5.0
		 *
		 * @param string $url Permalink url for the activity directory.
		 */
		return apply_filters( 'bp_get_activity_directory_permalink', trailingslashit( bp_get_root_domain() . '/' . bp_get_activity_root_slug() ) );
	}

/**
 * Initialize the activity loop.
 *
 * Based on the $args passed, bp_has_activities() populates the
 * $activities_template global, enabling the use of BuddyPress templates and
 * template functions to display a list of activity items.
 *
 * @since 1.0.0
 * @since 2.4.0 Introduced the `$fields` parameter.
 *
 * @global object $activities_template {@link BP_Activity_Template}
 *
 * @param array|string $args {
 *     Arguments for limiting the contents of the activity loop. Most arguments
 *     are in the same format as {@link BP_Activity_Activity::get()}. However,
 *     because the format of the arguments accepted here differs in a number of
 *     ways, and because bp_has_activities() determines some default arguments in
 *     a dynamic fashion, we list all accepted arguments here as well.
 *
 *     Arguments can be passed as an associative array, or as a URL querystring
 *     (eg, 'user_id=4&display_comments=threaded').
 *
 *     @type int               $page             Which page of results to fetch. Using page=1 without per_page will result
 *                                               in no pagination. Default: 1.
 *     @type int|bool          $per_page         Number of results per page. Default: 20.
 *     @type string            $page_arg         String used as a query parameter in pagination links. Default: 'acpage'.
 *     @type int|bool          $max              Maximum number of results to return. Default: false (unlimited).
 *     @type string            $fields           Activity fields to retrieve. 'all' to fetch entire activity objects,
 *                                               'ids' to get only the activity IDs. Default 'all'.
 *     @type string|bool       $count_total      If true, an additional DB query is run to count the total activity items
 *                                               for the query. Default: false.
 *     @type string            $sort             'ASC' or 'DESC'. Default: 'DESC'.
 *     @type array|bool        $exclude          Array of activity IDs to exclude. Default: false.
 *     @type array|bool        $in               Array of IDs to limit query by (IN). 'in' is intended to be used in
 *                                               conjunction with other filter parameters. Default: false.
 *     @type array|bool        $include          Array of exact activity IDs to query. Providing an 'include' array will
 *                                               override all other filters passed in the argument array. When viewing the
 *                                               permalink page for a single activity item, this value defaults to the ID of
 *                                               that item. Otherwise the default is false.
 *     @type array             $meta_query       Limit by activitymeta by passing an array of meta_query conditions. See
 *                                               {@link WP_Meta_Query::queries} for a description of the syntax.
 *     @type array             $date_query       Limit by date by passing an array of date_query conditions. See first
 *                                               parameter of {@link WP_Date_Query::__construct()} for syntax.
 *     @type array             $filter_query     Advanced activity filtering.  See {@link BP_Activity_Query::__construct()}.
 *     @type string            $search_terms     Limit results by a search term. Default: false.
 *     @type string            $scope            Use a BuddyPress pre-built filter.
 *                                                 - 'just-me' retrieves items belonging only to a user; this is equivalent
 *                                                   to passing a 'user_id' argument.
 *                                                 - 'friends' retrieves items belonging to the friends of a user.
 *                                                 - 'groups' retrieves items belonging to groups to which a user belongs to.
 *                                                 - 'favorites' retrieves a user's favorited activity items.
 *                                                 - 'mentions' retrieves items where a user has received an @-mention.
 *                                               The default value of 'scope' is set to one of the above if that value
 *                                               appears in the appropriate place in the URL; eg, 'scope' will be 'groups'
 *                                               when visiting http://example.com/members/joe/activity/groups/. Otherwise
 *                                               defaults to false.
 *     @type int|array|bool    $user_id          The ID(s) of user(s) whose activity should be fetched. Pass a single ID or
 *                                               an array of IDs. When viewing a user profile page (but not that user's
 *                                               activity subpages, ie My Friends, My Groups, etc), 'user_id' defaults to
 *                                               the ID of the displayed user. Otherwise the default is false.
 *     @type string|array|bool $object           Filters by the `component` column in the database, which is generally the
 *                                               component ID in the case of BuddyPress components, or the plugin slug in
 *                                               the case of plugins. For example, 'groups' will limit results to those that
 *                                               are associated with the BP Groups component. Accepts a single component
 *                                               string, or an array of multiple components. Defaults to 'groups' when
 *                                               viewing the page of a single group, the My Groups activity filter, or the
 *                                               Activity > Groups filter of a user profile. Otherwise defaults to false.
 *     @type string|array|bool $action           Filters by the `type` column in the database, which is a string
 *                                               categorizing the activity item (eg, 'new_blog_post', 'created_group').
 *                                               Accepts a comma-delimited string or an array of types. Default: false.
 *     @type int|array|bool    $primary_id       Filters by the `item_id` column in the database. The meaning of
 *                                               'primary_id' differs between components/types; for example, in the case of
 *                                               'created_group', 'primary_id' is the ID of the group. Accepts a single ID,
 *                                               or an array of multiple IDs. When viewing a single group, defaults to the
 *                                               current group ID. When viewing a user's Groups stream page, defaults to the
 *                                               IDs of the user's groups. Otherwise defaults to false.
 *     @type int|array|bool    $secondary_id     Filters by the `secondary_item_id` column in the database. The meaning of
 *                                               'secondary_id' differs between components/types. Accepts a single ID, or an
 *                                               array of multiple IDs. Defaults to false.
 *     @type int               $offset           Return only activity items with an ID greater than or equal to this one.
 *                                               Note that providing an offset will disable pagination. Default: false.
 *     @type string|bool       $display_comments How to handle activity comments. Possible values:
 *                                                 - 'threaded' - comments appear in a threaded tree, under their parent
 *                                                   items.
 *                                                 - 'stream' - the activity stream is presented in a flat manner, with
 *                                                   comments sorted in chronological order alongside other activity items.
 *                                                 - false - don't fetch activity comments at all.
 *                                               Default: 'threaded'.
 *     @type bool              $show_hidden      Whether to show items marked hide_sitewide. Defaults to false, except in
 *                                               the following cases:
 *                                                 - User is viewing his own activity stream.
 *                                                 - User is viewing the activity stream of a non-public group of which he
 *                                                   is a member.
 *     @type string|bool       $spam             Spam status. 'ham_only', 'spam_only', or false to show all activity
 *                                               regardless of spam status. Default: 'ham_only'.
 *     @type bool              $populate_extras  Whether to pre-fetch the activity metadata for the queried items.
 *                                               Default: true.
 * }
 * @return bool Returns true when activities are found, otherwise false.
 */
function bp_has_activities( $args = '' ) {
	global $activities_template;

	// Get BuddyPress.
	$bp = buddypress();

	/*
	 * Smart Defaults.
	 */

	// User filtering.
	$user_id = bp_displayed_user_id()
		? bp_displayed_user_id()
		: false;

	// Group filtering.
	if ( bp_is_group() ) {
		$object      = $bp->groups->id;
		$primary_id  = bp_get_current_group_id();
		$show_hidden = (bool) ( groups_is_user_member( bp_loggedin_user_id(), $primary_id ) || bp_current_user_can( 'bp_moderate' ) );
	} else {
		$object      = false;
		$primary_id  = false;
		$show_hidden = false;
	}

	// The default scope should recognize custom slugs.
	$scope = array_key_exists( bp_current_action(), (array) $bp->loaded_components )
		? $bp->loaded_components[ bp_current_action() ]
		: bp_current_action();

	// Support for permalinks on single item pages: /groups/my-group/activity/124/.
	$include = bp_is_current_action( bp_get_activity_slug() )
		? bp_action_variable( 0 )
		: false;

	$search_terms_default = false;
	$search_query_arg = bp_core_get_component_search_query_arg( 'activity' );
	if ( ! empty( $_REQUEST[ $search_query_arg ] ) ) {
		$search_terms_default = stripslashes( $_REQUEST[ $search_query_arg ] );
	}

	/*
	 * Parse Args.
	 */

	// Note: any params used for filtering can be a single value, or multiple
	// values comma separated.
	$r = bp_parse_args( $args, array(
		'display_comments'  => 'threaded',   // False for none, stream/threaded - show comments in the stream or threaded under items.
		'include'           => $include,     // Pass an activity_id or string of IDs comma-separated.
		'exclude'           => false,        // Pass an activity_id or string of IDs comma-separated.
		'in'                => false,        // Comma-separated list or array of activity IDs among which to search.
		'sort'              => 'DESC',       // Sort DESC or ASC.
		'page'              => 1,            // Which page to load.
		'per_page'          => 20,           // Number of items per page.
		'page_arg'          => 'acpage',     // See https://buddypress.trac.wordpress.org/ticket/3679.
		'max'               => false,        // Max number to return.
		'fields'            => 'all',
		'count_total'       => false,
		'show_hidden'       => $show_hidden, // Show activity items that are hidden site-wide?
		'spam'              => 'ham_only',   // Hide spammed items.

		// Scope - pre-built activity filters for a user (friends/groups/favorites/mentions).
		'scope'             => $scope,

		// Filtering
		'user_id'           => $user_id,     // user_id to filter on.
		'object'            => $object,      // Object to filter on e.g. groups, profile, status, friends.
		'action'            => false,        // Action to filter on e.g. activity_update, profile_updated.
		'primary_id'        => $primary_id,  // Object ID to filter on e.g. a group_id or blog_id etc.
		'secondary_id'      => false,        // Secondary object ID to filter on e.g. a post_id.
		'offset'            => false,        // Return only items >= this ID.
		'since'             => false,        // Return only items recorded since this Y-m-d H:i:s date.

		'meta_query'        => false,        // Filter on activity meta. See WP_Meta_Query for format.
		'date_query'        => false,        // Filter by date. See first parameter of WP_Date_Query for format.
		'filter_query'      => false,        // Advanced filtering.  See BP_Activity_Query for format.

		// Searching.
		'search_terms'      => $search_terms_default,
		'update_meta_cache' => true,
	), 'has_activities' );

	/*
	 * Smart Overrides.
	 */

	// Translate various values for 'display_comments'
	// This allows disabling comments via ?display_comments=0
	// or =none or =false. Final true is a strict type check. See #5029.
	if ( in_array( $r['display_comments'], array( 0, '0', 'none', 'false' ), true ) ) {
		$r['display_comments'] = false;
	}

	// Ignore pagination if an offset is passed.
	if ( ! empty( $r['offset'] ) ) {
		$r['page'] = 0;
	}

	// Search terms.
	if ( ! empty( $_REQUEST['s'] ) && empty( $r['search_terms'] ) ) {
		$r['search_terms'] = $_REQUEST['s'];
	}

	// Do not exceed the maximum per page.
	if ( ! empty( $r['max'] ) && ( (int) $r['per_page'] > (int) $r['max'] ) ) {
		$r['per_page'] = $r['max'];
	}

	/**
	 * Filters whether BuddyPress should enable afilter support.
	 *
	 * Support for basic filters in earlier BP versions is disabled by default.
	 * To enable, put add_filter( 'bp_activity_enable_afilter_support', '__return_true' );
	 * into bp-custom.php or your theme's functions.php.
	 *
	 * @since 1.6.0
	 *
	 * @param bool $value True if BuddyPress should enable afilter support.
	 */
	if ( isset( $_GET['afilter'] ) && apply_filters( 'bp_activity_enable_afilter_support', false ) ) {
		$r['filter'] = array(
			'object' => $_GET['afilter']
		);
	} elseif ( ! empty( $r['user_id'] ) || ! empty( $r['object'] ) || ! empty( $r['action'] ) || ! empty( $r['primary_id'] ) || ! empty( $r['secondary_id'] ) || ! empty( $r['offset'] ) || ! empty( $r['since'] ) ) {
		$r['filter'] = array(
			'user_id'      => $r['user_id'],
			'object'       => $r['object'],
			'action'       => $r['action'],
			'primary_id'   => $r['primary_id'],
			'secondary_id' => $r['secondary_id'],
			'offset'       => $r['offset'],
			'since'        => $r['since']
		);
	} else {
		$r['filter'] = false;
	}

	// If specific activity items have been requested, override the $hide_spam
	// argument. This prevents backpat errors with AJAX.
	if ( ! empty( $r['include'] ) && ( 'ham_only' === $r['spam'] ) ) {
		$r['spam'] = 'all';
	}

	/*
	 * Query
	 */

	$activities_template = new BP_Activity_Template( $r );

	/**
	 * Filters whether or not there are activity items to display.
	 *
	 * @since 1.1.0
	 *
	 * @param bool   $value               Whether or not there are activity items to display.
	 * @param string $activities_template Current activities template being used.
	 * @param array  $r                   Array of arguments passed into the BP_Activity_Template class.
	 */
	return apply_filters( 'bp_has_activities', $activities_template->has_activities(), $activities_template, $r );
}

/**
 * Output the activities title.
 *
 * @since 1.0.0
 *
 * @todo Deprecate.
 */
function bp_activities_title() {
	echo bp_get_activities_title();
}

	/**
	 * Return the activities title.
	 *
	 * @since 1.0.0
	 *
	 * @global string $bp_activity_title
	 * @todo Deprecate.
	 *
	 * @return string The activities title.
	 */
	function bp_get_activities_title() {
		global $bp_activity_title;

		/**
		 * Filters the activities title for the activity template.
		 *
		 * @since 1.0.0
		 *
		 * @param string $bp_activity_title The title to be displayed.
		 */
		return apply_filters( 'bp_get_activities_title', $bp_activity_title );
	}

/**
 * {@internal Missing Description}
 *
 * @since 1.0.0
 *
 * @todo Deprecate.
 */
function bp_activities_no_activity() {
	echo bp_get_activities_no_activity();
}

	/**
	 * {@internal Missing Description}
	 *
	 * @since 1.0.0
	 *
	 * @global string $bp_activity_no_activity
	 * @todo Deprecate.
	 *
	 * @return string
	 */
	function bp_get_activities_no_activity() {
		global $bp_activity_no_activity;

		/**
		 * Filters the text used when there is no activity to display.
		 *
		 * @since 1.0.0
		 *
		 * @param string $bp_activity_no_activity Text to display for no activity.
		 */
		return apply_filters( 'bp_get_activities_no_activity', $bp_activity_no_activity );
	}

/**
 * Output the display name of the member who posted the activity.
 *
 * @since 2.1.0
 *
 */
function bp_activity_member_display_name() {
	echo bp_get_activity_member_display_name();
}

	/**
	 * Return the display name of the member who posted the activity.
	 *
	 * @since 2.1.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The date the activity was recorded.
	 */
	function bp_get_activity_member_display_name() {
		global $activities_template;

		$retval = isset( $activities_template->activity->display_name )
			? $activities_template->activity->display_name
			: '';

		/**
		 * Filters the display name of the member who posted the activity.
		 *
		 * @since 2.1.0
		 *
		 * @param int $retval Display name for the member who posted.
		 */
		return apply_filters( 'bp_get_activity_member_display_name', $retval );
	}

/**
 * Output the activity comment form action.
 *
 * @since 1.2.0
 *
 */
function bp_activity_comment_form_action() {
	echo bp_get_activity_comment_form_action();
}

	/**
	 * Return the activity comment form action.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @return string The activity comment form action.
	 */
	function bp_get_activity_comment_form_action() {

		/**
		 * Filters the activity comment form action URL.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value URL to use in the comment form's action attribute.
		 */
		return apply_filters( 'bp_get_activity_comment_form_action', home_url( bp_get_activity_root_slug() . '/reply/' ) );
	}

/**
 * Output the activity latest update link.
 *
 * @since 1.2.0
 *
 * @see bp_get_activity_latest_update() for description of parameters.
 *
 * @param int $user_id See {@link bp_get_activity_latest_update()} for description.
 */
function bp_activity_latest_update( $user_id = 0 ) {
	echo bp_get_activity_latest_update( $user_id );
}

	/**
	 * Return the activity latest update link.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @param int $user_id If empty, will fall back on displayed user.
	 * @return string|bool $latest_update The activity latest update link.
	 *                                    False on failure.
	 */
	function bp_get_activity_latest_update( $user_id = 0 ) {

		if ( empty( $user_id ) ) {
			$user_id = bp_displayed_user_id();
		}

		if ( bp_is_user_inactive( $user_id ) ) {
			return false;
		}

		if ( !$update = bp_get_user_meta( $user_id, 'bp_latest_update', true ) ) {
			return false;
		}

		/**
		 * Filters the latest update excerpt.
		 *
		 * @since 1.2.10
		 * @since 2.6.0  Added the `$user_id` parameter.
		 *
		 * @param string $value   The excerpt for the latest update.
		 * @param int    $user_id ID of the queried user.
		 */
		$latest_update = apply_filters( 'bp_get_activity_latest_update_excerpt', trim( strip_tags( bp_create_excerpt( $update['content'], bp_activity_get_excerpt_length() ) ) ), $user_id );

		$latest_update = sprintf(
			'%s <a href="%s">%s</a>',
			$latest_update,
			esc_url_raw( bp_activity_get_permalink( $update['id'] ) ),
			esc_attr__( 'View', 'buddypress' )
		);

		/**
		 * Filters the latest update excerpt with view link appended to the end.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$user_id` parameter.
		 *
		 * @param string $latest_update The latest update with "view" link appended to it.
		 * @param int    $user_id       ID of the queried user.
		 */
		return apply_filters( 'bp_get_activity_latest_update', $latest_update, $user_id );
	}

/**
 * Output the activity filter links.
 *
 * @since 1.1.0
 *
 * @see bp_get_activity_filter_links() for description of parameters.
 *
 * @param array|bool $args See {@link bp_get_activity_filter_links()} for description.
 */
function bp_activity_filter_links( $args = false ) {
	echo bp_get_activity_filter_links( $args );
}

	/**
	 * Return the activity filter links.
	 *
	 * @since 1.1.0
	 *
	 *
	 * @param array|bool $args {
	 *     @type string $style The type of markup to use for the links.
	 *                         'list', 'paragraph', or 'span'. Default: 'list'.
	 * }
	 * @return string|bool $component_links The activity filter links.
	 *         False on failure.
	 */
	function bp_get_activity_filter_links( $args = false ) {

		$r = wp_parse_args( $args, array(
			'style' => 'list'
		) );

		// Define local variable.
		$component_links = array();

		// Fetch the names of components that have activity recorded in the DB.
		$components = BP_Activity_Activity::get_recorded_components();

		if ( empty( $components ) ) {
			return false;
		}

		foreach ( (array) $components as $component ) {

			// Skip the activity comment filter.
			if ( 'activity' == $component ) {
				continue;
			}

			if ( isset( $_GET['afilter'] ) && $component == $_GET['afilter'] ) {
				$selected = ' class="selected"';
			} else {
				$selected = '';
			}

			$component = esc_attr( $component );

			switch ( $r['style'] ) {
				case 'list':
					$tag = 'li';
					$before = '<li id="afilter-' . $component . '"' . $selected . '>';
					$after = '</li>';
				break;
				case 'paragraph':
					$tag = 'p';
					$before = '<p id="afilter-' . $component . '"' . $selected . '>';
					$after = '</p>';
				break;
				case 'span':
					$tag = 'span';
					$before = '<span id="afilter-' . $component . '"' . $selected . '>';
					$after = '</span>';
				break;
			}

			$link = add_query_arg( 'afilter', $component );
			$link = remove_query_arg( 'acpage' , $link );

			/**
			 * Filters the activity filter link URL for the current activity component.
			 *
			 * @since 1.1.0
			 *
			 * @param string $link      The URL for the current component.
			 * @param string $component The current component getting links constructed for.
			 */
			$link = apply_filters( 'bp_get_activity_filter_link_href', $link, $component );

			$component_links[] = $before . '<a href="' . esc_url( $link ) . '">' . ucwords( $component ) . '</a>' . $after;
		}

		$link = remove_query_arg( 'afilter' , $link );

		if ( isset( $_GET['afilter'] ) ) {
			$component_links[] = '<' . $tag . ' id="afilter-clear"><a href="' . esc_url( $link ) . '">' . __( 'Clear Filter', 'buddypress' ) . '</a></' . $tag . '>';
		}

		/**
		 * Filters all of the constructed filter links.
		 *
		 * @since 1.1.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $value All of the links to be displayed to the user.
		 * @param array  $r     Array of parsed arguments.
		 */
		return apply_filters( 'bp_get_activity_filter_links', implode( "\n", $component_links ), $r );
	}

/**
 * Determine whether a comment can be made on an activity reply item.
 *
 * @since 1.5.0
 *
 * @param  bool|object $comment     Activity comment.
 * @return bool        $can_comment True if comment can receive comments,
 *                                  otherwise false.
 */
function bp_activity_can_comment_reply( $comment = false ) {

	// Assume activity can be commented on.
	$can_comment = true;

	// Check that comment exists.
	if ( empty( $comment ) ) {
		$comment = bp_activity_current_comment();
	}

	if ( ! empty( $comment ) ) {

		// Fall back on current comment in activity loop.
		$comment_depth = isset( $comment->depth )
			? intval( $comment->depth )
			: bp_activity_get_comment_depth( $comment );

		// Threading is turned on, so check the depth.
		if ( get_option( 'thread_comments' ) ) {
			$can_comment = (bool) ( $comment_depth < get_option( 'thread_comments_depth' ) );

		// No threading for comment replies if no threading for comments.
		} else {
			$can_comment = false;
		}
	}

	/**
	 * Filters whether a comment can be made on an activity reply item.
	 *
	 * @since 1.5.0
	 *
	 * @param bool   $can_comment Status on if activity reply can be commented on.
	 * @param string $comment     Current comment being checked on.
	 */
	return (bool) apply_filters( 'bp_activity_can_comment_reply', $can_comment, $comment );
}

/**
 * Determine whether favorites are allowed.
 *
 * Defaults to true, but can be modified by plugins.
 *
 * @since 1.5.0
 *
 * @return bool True if comment can receive comments.
 */
function bp_activity_can_favorite() {

	/**
	 * Filters whether or not users can favorite activity items.
	 *
	 * @since 1.5.0
	 *
	 * @param bool $value Whether or not favoriting is enabled.
	 */
	return apply_filters( 'bp_activity_can_favorite', true );
}

/**
 * Output the total favorite count for a specified user.
 *
 * @since 1.2.0
 *
 * @see bp_get_total_favorite_count_for_user() for description of parameters.
 *
 * @param int $user_id See {@link bp_get_total_favorite_count_for_user()}.
 */
function bp_total_favorite_count_for_user( $user_id = 0 ) {
	echo bp_get_total_favorite_count_for_user( $user_id );
}

	/**
	 * Return the total favorite count for a specified user.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @param int $user_id ID of user being queried. Default: displayed user ID.
	 * @return int The total favorite count for the specified user.
	 */
	function bp_get_total_favorite_count_for_user( $user_id = 0 ) {
		$retval = false;

		if ( bp_activity_can_favorite() ) {
			// Default to displayed user if none is passed.
			$user_id = empty( $user_id )
				? bp_displayed_user_id()
				: $user_id;

			// Get user meta if user ID exists.
			if ( ! empty( $user_id ) ) {
				$retval = bp_activity_total_favorites_for_user( $user_id );
			}
		}

		/**
		 * Filters the total favorite count for a user.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$user_id` parameter.
		 *
		 * @param int|bool $retval  Total favorite count for a user. False on no favorites.
		 * @param int      $user_id ID of the queried user.
		 */
		return apply_filters( 'bp_get_total_favorite_count_for_user', $retval, $user_id );
	}


/**
 * Output the total mention count for a specified user.
 *
 * @since 1.2.0
 *
 * @see bp_get_total_mention_count_for_user() for description of parameters.
 *
 * @param int $user_id See {@link bp_get_total_mention_count_for_user()}.
 */
function bp_total_mention_count_for_user( $user_id = 0 ) {
	echo bp_get_total_mention_count_for_user( $user_id );
}

	/**
	 * Return the total mention count for a specified user.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @param int $user_id ID of user being queried. Default: displayed user ID.
	 * @return int The total mention count for the specified user.
	 */
	function bp_get_total_mention_count_for_user( $user_id = 0 ) {

		// Default to displayed user if none is passed.
		$user_id = empty( $user_id )
			? bp_displayed_user_id()
			: $user_id;

		// Get user meta if user ID exists.
		$retval = ! empty( $user_id )
			? bp_get_user_meta( $user_id, 'bp_new_mention_count', true )
			: false;

		/**
		 * Filters the total mention count for a user.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$user_id` parameter.
		 *
		 * @param int|bool $retval  Total mention count for a user. False on no mentions.
		 * @param int      $user_id ID of the queried user.
		 */
		return apply_filters( 'bp_get_total_mention_count_for_user', $retval, $user_id );
	}

/**
 * Output the public message link for displayed user.
 *
 * @since 1.2.0
 *
 */
function bp_send_public_message_link() {
	echo esc_url( bp_get_send_public_message_link() );
}

	/**
	 * Return the public message link for the displayed user.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @return string The public message link for the displayed user.
	 */
	function bp_get_send_public_message_link() {

		// No link if not logged in, not looking at someone else's profile.
		if ( ! is_user_logged_in() || ! bp_is_user() || bp_is_my_profile() ) {
			$retval = '';
		} else {
			$args   = array( 'r' => bp_get_displayed_user_mentionname() );
			$url    = add_query_arg( $args, bp_get_activity_directory_permalink() );
			$retval = wp_nonce_url( $url );
		}

		/**
		 * Filters the public message link for the displayed user.
		 *
		 * @since 1.2.0
		 *
		 * @param string $retval The URL for the public message link.
		 */
		return apply_filters( 'bp_get_send_public_message_link', $retval );
	}

/**
 * Recurse through all activity comments and return the activity comment IDs.
 *
 * @since 2.0.0
 *
 * @param array $activity Array of activities generated from {@link bp_activity_get()}.
 * @param array $activity_ids Used for recursion purposes in this function.
 * @return array
 */
function bp_activity_recurse_comments_activity_ids( $activity = array(), $activity_ids = array() ) {
	if ( is_array( $activity ) && ! empty( $activity['activities'] ) ) {
		$activity = $activity['activities'][0];
	}

	if ( ! empty( $activity->children ) ) {
		foreach ($activity->children as $child ) {
			$activity_ids[] = $child->id;

			if( ! empty( $child->children ) ) {
				$activity_ids = bp_activity_recurse_comments_activity_ids( $child, $activity_ids );
			}
		}
	}

	return $activity_ids;
}

/**
 * Output the mentioned user display name.
 *
 * @since 1.2.0
 *
 * @see bp_get_mentioned_user_display_name() for description of parameters.
 *
 * @param int|string|bool $user_id_or_username See {@link bp_get_mentioned_user_display_name()}.
 */
function bp_mentioned_user_display_name( $user_id_or_username = false ) {
	echo bp_get_mentioned_user_display_name( $user_id_or_username );
}

	/**
	 * Returns the mentioned user display name.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @param int|string|bool $user_id_or_username User ID or username.
	 * @return string The mentioned user's display name.
	 */
	function bp_get_mentioned_user_display_name( $user_id_or_username = false ) {

		// Get user display name.
		$name = bp_core_get_user_displayname( $user_id_or_username );

		// If user somehow has no name, return this really lame string.
		if ( empty( $name ) ) {
			$name = __( 'a user', 'buddypress' );
		}

		/**
		 * Filters the mentioned user display name.
		 *
		 * @since 1.2.0
		 *
		 * @param string     $name                Display name for the mentioned user.
		 * @param int|string $user_id_or_username User ID or username use for query.
		 */
		return apply_filters( 'bp_get_mentioned_user_display_name', $name, $user_id_or_username );
	}

/**
 * Output button for sending a public message (an @-mention).
 *
 * @since 1.2.0
 *
 * @see bp_get_send_public_message_button() for description of parameters.
 *
 * @param array|string $args See {@link bp_get_send_public_message_button()}.
 */
function bp_send_public_message_button( $args = '' ) {
	echo bp_get_send_public_message_button( $args );
}

	/**
	 * Return button for sending a public message (an @-mention).
	 *
	 * @since 1.2.0
	 *
	 *
	 * @param array|string $args {
	 *     All arguments are optional. See {@link BP_Button} for complete
	 *     descriptions.
	 *     @type string $id                Default: 'public_message'.
	 *     @type string $component         Default: 'activity'.
	 *     @type bool   $must_be_logged_in Default: true.
	 *     @type bool   $block_self        Default: true.
	 *     @type string $wrapper_id        Default: 'post-mention'.
	 *     @type string $link_href         Default: the public message link for
	 *                                     the current member in the loop.
	 *     @type string $link_text         Default: 'Public Message'.
	 *     @type string $link_class        Default: 'activity-button mention'.
	 * }
	 * @return string The button for sending a public message.
	 */
	function bp_get_send_public_message_button( $args = '' ) {

		$r = bp_parse_args( $args, array(
			'id'                => 'public_message',
			'component'         => 'activity',
			'must_be_logged_in' => true,
			'block_self'        => true,
			'wrapper_id'        => 'post-mention',
			'link_href'         => bp_get_send_public_message_link(),
			'link_text'         => __( 'Public Message', 'buddypress' ),
			'link_class'        => 'activity-button mention'
		) );

		/**
		 * Filters the public message button HTML.
		 *
		 * @since 1.2.10
		 *
		 * @param array $r Array of arguments for the public message button HTML.
		 */
		return bp_get_button( apply_filters( 'bp_get_send_public_message_button', $r ) );
	}

/**
 * Output the activity post form action.
 *
 * @since 1.2.0
 *
 */
function bp_activity_post_form_action() {
	echo bp_get_activity_post_form_action();
}

	/**
	 * Return the activity post form action.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @return string The activity post form action.
	 */
	function bp_get_activity_post_form_action() {

		/**
		 * Filters the action url used for the activity post form.
		 *
		 * @since 1.2.0
		 *
		 * @param string $value URL to be used for the activity post form.
		 */
		return apply_filters( 'bp_get_activity_post_form_action', home_url( bp_get_activity_root_slug() . '/post/' ) );
	}

/**
 * Echo a list of linked avatars of users who have commented on the current activity item.
 *
 * Use this function to easily output activity comment authors' avatars.
 *
 * Avatars are wrapped in <li> elements, but you've got to provide your own
 * <ul> or <ol> wrapper markup.
 *
 * @since 1.7.0
 *
 * @see bp_core_fetch_avatar() for a description of arguments.
 *
 * @param array $args See {@link bp_core_fetch_avatar()}.
 */
function bp_activity_comments_user_avatars( $args = array() ) {

	$r = bp_parse_args( $args, array(
		'height' => false,
		'html'   => true,
		'type'   => 'thumb',
		'width'  => false,
	) );

	// Get the user IDs of everyone who has left a comment to the current activity item.
	$user_ids = bp_activity_get_comments_user_ids();
	$output   = array();
	$retval   = '';

	if ( ! empty( $user_ids ) ) {
		foreach ( (array) $user_ids as $user_id ) {

			// Skip an empty user ID.
			if ( empty( $user_id ) ) {
				continue;
			}

			// Get profile link for this user.
			$profile_link = bp_core_get_user_domain( $user_id );

			// Get avatar for this user.
			$image_html   = bp_core_fetch_avatar( array(
				'item_id' => $user_id,
				'height'  => $r['height'],
				'html'    => $r['html'],
				'type'    => $r['type'],
				'width'   => $r['width']
			) );

			// If user has link & avatar, add them to the output array.
			if ( ! empty( $profile_link ) && ! empty( $image_html ) ) {
				$output[] = sprintf( '<a href="%1$s">%2$s</a>', esc_url( $profile_link ), $image_html );
			}
		}

		// If output array is not empty, wrap everything in some list items.
		if ( ! empty( $output ) ) {
			$retval = '<li>' . implode( '</li><li>', $output ) . '</li>';
		}
	}

	/**
	 * Filters the list of linked avatars for users who have commented on the current activity item.
	 *
	 * @since 1.7.0
	 *
	 * @param string $retval HTML markup for the list of avatars.
	 * @param array  $r      Array of arguments used for each avatar.
	 * @param array  $output Array of each avatar found, before imploded into single string.
	 */
	echo apply_filters( 'bp_activity_comments_user_avatars', $retval, $r, $output );
}

/**
 * Return the IDs of every user who's left a comment on the current activity item.
 *
 * @since 1.7.0
 *
 * @return bool|array An array of IDs, or false if none are found.
 */
function bp_activity_get_comments_user_ids() {
	global $activities_template;

	$user_ids = ! empty( $activities_template->activity->children )
		? (array) bp_activity_recurse_comments_user_ids( $activities_template->activity->children )
		: array();

	/**
	 * Filters the list of user IDs for the current activity item.
	 *
	 * @since 1.7.0
	 *
	 * @param array $value Array of unique user IDs for the current activity item.
	 */
	return apply_filters( 'bp_activity_get_comments_user_ids', array_unique( $user_ids ) );
}

	/**
	 * Recurse through all activity comments and collect the IDs of the users who wrote them.
	 *
	 * @since 1.7.0
	 *
	 * @param array $comments Array of {@link BP_Activity_Activity} items.
	 * @return array Array of user IDs.
	 */
	function bp_activity_recurse_comments_user_ids( array $comments = array() ) {

		// Default user ID's array.
		$user_ids = array();

		// Loop through comments and try to get user ID's.
		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {

				// If a user is a spammer, their activity items will have been
				// automatically marked as spam. Skip these.
				if ( ! empty( $comment->is_spam ) ) {
					continue;
				}

				// Add user ID to array.
				$user_ids[] = $comment->user_id;

				// Check for commentception.
				if ( ! empty( $comment->children ) ) {
					$user_ids = array_merge( $user_ids, bp_activity_recurse_comments_user_ids( $comment->children ) );
				}
			}
		}

		/**
		 * Filters the list of user IDs for the current activity comment item.
		 *
		 * @since 2.1.0
		 *
		 * @param array $user_ids Array of user IDs for the current activity comment item.
		 * @param array $comments Array of comments being checked for user IDs.
		 */
		return apply_filters( 'bp_activity_recurse_comments_user_ids', $user_ids, $comments );
	}

/**
 * Output the mentionname for the displayed user.
 *
 * @since 1.9.0
 */
function bp_displayed_user_mentionname() {
	echo bp_get_displayed_user_mentionname();
}
	/**
	 * Get the mentionname for the displayed user.
	 *
	 * @since 1.9.0
	 *
	 * @return string Mentionname for the displayed user, if available.
	 */
	function bp_get_displayed_user_mentionname() {

		/**
		 * Filters the mentionname for the displayed user.
		 *
		 * @since 1.9.0
		 *
		 * @param string $value The mentionanme for the displayed user.
		 */
		return apply_filters( 'bp_get_displayed_user_mentionname', bp_activity_get_user_mentionname( bp_displayed_user_id() ) );
	}

/**
 * Echo a list of all registered activity types for use in dropdowns or checkbox lists.
 *
 * @since 1.7.0
 *
 * @param string       $output Optional. Either 'select' or 'checkbox'. Default: 'select'.
 * @param array|string $args {
 *     Optional extra arguments.
 *     @type string       $checkbox_name When returning checkboxes, sets the 'name'
 *                                       attribute.
 *     @type array|string $selected      A list of types that should be checked/
 *                                       selected.
 * }
 */
function bp_activity_types_list( $output = 'select', $args = '' ) {

	$args = bp_parse_args( $args, array(
		'checkbox_name' => 'bp_activity_types',
		'selected'      => array(),
	) );

	$activities = bp_activity_get_types();
	natsort( $activities );

	// Loop through the activity types and output markup.
	foreach ( $activities as $type => $description ) {

		// See if we need to preselect the current type.
		$checked  = checked(  true, in_array( $type, (array) $args['selected'] ), false );
		$selected = selected( true, in_array( $type, (array) $args['selected'] ), false );

		// Switch output based on the element.
		switch ( $output ) {
			case 'select' :
				printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $type ), $selected, esc_html( $description ) );
				break;
			case 'checkbox' :
				printf( '<label style="" for="%1$s[]">%2$s<input type="checkbox" id="%1$s[]" name="%1$s[]" value="%3$s" %4$s/></label>', esc_attr( $args['checkbox_name'] ), esc_html( $description ), esc_attr( $args['checkbox_name'] ), esc_attr( $args['checkbox_name'] ), esc_attr( $type ), $checked );
				break;
		}

		/**
		 * Fires at the end of the listing of activity types.
		 *
		 * This is a variable action hook. The actual hook to use will depend on the output type specified.
		 * Two default hooks are bp_activity_types_list_select and bp_activity_types_list_checkbox.
		 *
		 * @since 1.7.0
		 *
		 * @param array  $args        Array of arguments passed into function.
		 * @param string $type        Activity type being rendered in the output.
		 * @param string $description Description of the activity type being rendered.
		 */
		do_action( 'bp_activity_types_list_' . $output, $args, $type, $description );
	}

	// Backpat with BP-Default for dropdown boxes only.
	if ( 'select' === $output ) {
		do_action( 'bp_activity_filter_options' );
	}
}


/* RSS Feed Template Tags ****************************************************/

/**
 * Output the sitewide activity feed link.
 *
 * @since 1.0.0
 *
 */
function bp_sitewide_activity_feed_link() {
	echo bp_get_sitewide_activity_feed_link();
}

	/**
	 * Returns the sitewide activity feed link.
	 *
	 * @since 1.0.0
	 *
	 *
	 * @return string The sitewide activity feed link.
	 */
	function bp_get_sitewide_activity_feed_link() {

		/**
		 * Filters the sidewide activity feed link.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value The feed link for sitewide activity.
		 */
		return apply_filters( 'bp_get_sitewide_activity_feed_link', bp_get_root_domain() . '/' . bp_get_activity_root_slug() . '/feed/' );
	}

/**
 * Output the member activity feed link.
 *
 * @since 1.2.0
 *
 */
function bp_member_activity_feed_link() {
	echo bp_get_member_activity_feed_link();
}

/**
 * Output the member activity feed link.
 *
 * @since 1.0.0
 * @deprecated 1.2.0
 *
 * @todo properly deprecate in favor of bp_member_activity_feed_link().
 *
 */
function bp_activities_member_rss_link() { echo bp_get_member_activity_feed_link(); }

	/**
	 * Return the member activity feed link.
	 *
	 * @since 1.2.0
	 *
	 *
	 * @return string $link The member activity feed link.
	 */
	function bp_get_member_activity_feed_link() {

		// Single member activity feed link.
		if ( bp_is_profile_component() || bp_is_current_action( 'just-me' ) ) {
			$link = bp_displayed_user_domain() . bp_get_activity_slug() . '/feed/';

		// Friend feed link.
		} elseif ( bp_is_active( 'friends' ) && bp_is_current_action( bp_get_friends_slug() ) ) {
			$link = bp_displayed_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/feed/';

		// Group feed link.
		} elseif ( bp_is_active( 'groups'  ) && bp_is_current_action( bp_get_groups_slug()  ) ) {
			$link = bp_displayed_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/feed/';

		// Favorites activity feed link.
		} elseif ( 'favorites' === bp_current_action() ) {
			$link = bp_displayed_user_domain() . bp_get_activity_slug() . '/favorites/feed/';

		// Mentions activity feed link.
		} elseif ( ( 'mentions' === bp_current_action() ) && bp_activity_do_mentions() ) {
			$link = bp_displayed_user_domain() . bp_get_activity_slug() . '/mentions/feed/';

		// No feed link.
		} else {
			$link = '';
		}

		/**
		 * Filters the member activity feed link.
		 *
		 * @since 1.0.0
		 *
		 * @param string $link URL for the member activity feed.
		 */
		return apply_filters( 'bp_get_activities_member_rss_link', $link );
	}

	/**
	 * Return the member activity feed link.
	 *
	 * @since 1.0.0
	 * @deprecated 1.2.0
	 *
	 * @todo properly deprecate in favor of bp_get_member_activity_feed_link().
	 *
	 *
	 * @return string The member activity feed link.
	 */
	function bp_get_activities_member_rss_link() { return bp_get_member_activity_feed_link(); }


/** Template tags for RSS feed output ****************************************/

/**
 * Outputs the activity feed item guid.
 *
 * @since 1.0.0
 *
 */
function bp_activity_feed_item_guid() {
	echo bp_get_activity_feed_item_guid();
}

	/**
	 * Returns the activity feed item guid.
	 *
	 * @since 1.2.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity feed item guid.
	 */
	function bp_get_activity_feed_item_guid() {
		global $activities_template;

		/**
		 * Filters the activity feed item guid.
		 *
		 * @since 1.1.3
		 *
		 * @param string $value Calculated md5 value for the activity feed item.
		 */
		return apply_filters( 'bp_get_activity_feed_item_guid', md5( $activities_template->activity->date_recorded . '-' . $activities_template->activity->content ) );
	}

/**
 * Output the activity feed item title.
 *
 * @since 1.0.0
 *
 */
function bp_activity_feed_item_title() {
	echo bp_get_activity_feed_item_title();
}

	/**
	 * Return the activity feed item title.
	 *
	 * @since 1.0.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string $title The activity feed item title.
	 */
	function bp_get_activity_feed_item_title() {
		global $activities_template;

		if ( !empty( $activities_template->activity->action ) ) {
			$content = $activities_template->activity->action;
		} else {
			$content = $activities_template->activity->content;
		}

		$content = explode( '<span', $content );
		$title   = strip_tags( ent2ncr( trim( convert_chars( $content[0] ) ) ) );

		if ( ':' === substr( $title, -1 ) ) {
			$title = substr( $title, 0, -1 );
		}

		if ( 'activity_update' === $activities_template->activity->type ) {
			$title .= ': ' . strip_tags( ent2ncr( trim( convert_chars( bp_create_excerpt( $activities_template->activity->content, 70, array( 'ending' => " [&#133;]" ) ) ) ) ) );
		}

		/**
		 * Filters the activity feed item title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title The title for the activity feed item.
		 */
		return apply_filters( 'bp_get_activity_feed_item_title', $title );
	}

/**
 * Output the activity feed item link.
 *
 * @since 1.0.0
 *
 */
function bp_activity_feed_item_link() {
	echo bp_get_activity_feed_item_link();
}

	/**
	 * Return the activity feed item link.
	 *
	 * @since 1.0.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity feed item link.
	 */
	function bp_get_activity_feed_item_link() {
		global $activities_template;

		$retval = ! empty( $activities_template->activity->primary_link )
			? $activities_template->activity->primary_link
			: '';

		/**
		 * Filters the activity feed item link.
		 *
		 * @since 1.0.0
		 *
		 * @param string $retval The URL for the activity feed item.
		 */
		return apply_filters( 'bp_get_activity_feed_item_link', $retval );
	}

/**
 * Output the activity feed item date.
 *
 * @since 1.0.0
 *
 */
function bp_activity_feed_item_date() {
	echo bp_get_activity_feed_item_date();
}

	/**
	 * Return the activity feed item date.
	 *
	 * @since 1.0.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity feed item date.
	 */
	function bp_get_activity_feed_item_date() {
		global $activities_template;

		$retval = ! empty( $activities_template->activity->date_recorded )
			? $activities_template->activity->date_recorded
			: '';

		/**
		 * Filters the activity feed item date.
		 *
		 * @since 1.0.0
		 *
		 * @param string $retval The date for the activity feed item.
		 */
		return apply_filters( 'bp_get_activity_feed_item_date', $retval );
	}

/**
 * Output the activity feed item description.
 *
 * @since 1.0.0
 *
 */
function bp_activity_feed_item_description() {
	echo bp_get_activity_feed_item_description();
}

	/**
	 * Return the activity feed item description.
	 *
	 * @since 1.0.0
	 *
	 * @global object $activities_template {@link BP_Activity_Template}
	 *
	 * @return string The activity feed item description.
	 */
	function bp_get_activity_feed_item_description() {
		global $activities_template;

		// Get the content, if exists.
		$content = ! empty( $activities_template->activity->content )
			? $activities_template->activity->content
			: '';

		// Perform a few string conversions on the content, if it's not empty.
		if ( ! empty( $content ) ) {
			$content = ent2ncr( convert_chars( str_replace( '%s', '', $content ) ) );
		}

		/**
		 * Filters the activity feed item description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $content The description for the activity feed item.
		 */
		return apply_filters( 'bp_get_activity_feed_item_description', $content );
	}

/**
 * Template tag so we can hook activity feed to <head>.
 *
 * @since 1.5.0
 *
 */
function bp_activity_sitewide_feed() {
?>

	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ) ?> | <?php _e( 'Site Wide Activity RSS Feed', 'buddypress' ) ?>" href="<?php bp_sitewide_activity_feed_link() ?>" />

<?php
}
add_action( 'bp_head', 'bp_activity_sitewide_feed' );

/**
 * Display available filters depending on the scope.
 *
 * @since 2.1.0
 *
 * @param string $context The current context. 'activity', 'member',
 *                        'member_groups', 'group'.
 */
function bp_activity_show_filters( $context = '' ) {
	echo bp_get_activity_show_filters( $context );
}
	/**
	 * Get available filters depending on the scope.
	 *
	 * @since 2.1.0
	 *
	 * @param string $context The current context. 'activity', 'member',
	 *                        'member_groups', 'group'.
	 *
	 * @return string HTML for <option> values.
	 */
	function bp_get_activity_show_filters( $context = '' ) {
		$filters = array();
		$actions = bp_activity_get_actions_for_context( $context );
		foreach ( $actions as $action ) {
			// Friends activity collapses two filters into one.
			if ( in_array( $action['key'], array( 'friendship_accepted', 'friendship_created' ) ) ) {
				$action['key'] = 'friendship_accepted,friendship_created';
			}

			$filters[ $action['key'] ] = $action['label'];
		}

		/**
		 * Filters the options available in the activity filter dropdown.
		 *
		 * @since 2.2.0
		 *
		 * @param array  $filters Array of filter options for the given context, in the following format: $option_value => $option_name.
		 * @param string $context Context for the filter. 'activity', 'member', 'member_groups', 'group'.
		 */
		$filters = apply_filters( 'bp_get_activity_show_filters_options', $filters, $context );

		// Build the options output.
		$output = '';

		if ( ! empty( $filters ) ) {
			foreach ( $filters as $value => $filter ) {
				$output .= '<option value="' . esc_attr( $value ) . '">' . esc_html( $filter ) . '</option>' . "\n";
			}
		}

		/**
		 * Filters the HTML markup result for the activity filter dropdown.
		 *
		 * @since 2.1.0
		 *
		 * @param string $output  HTML output for the activity filter dropdown.
		 * @param array  $filters Array of filter options for the given context, in the following format: $option_value => $option_name.
		 * @param string $context Context for the filter. 'activity', 'member', 'member_groups', 'group'.
		 */
		return apply_filters( 'bp_get_activity_show_filters', $output, $filters, $context );
	}
