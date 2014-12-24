<?php
/**
 * Deprecated functions
 *
 * @package BuddyPress
 * @subpackage Core
 * @deprecated 2.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Detect a change in post status, and initiate an activity update if necessary.
 *
 * Posts get new activity updates when (a) they are being published, and (b)
 * they have not already been published. This enables proper posting for
 * regular posts as well as scheduled posts, while preventing post bumping.
 *
 * See #4090, #3746, #2546 for background.
 *
 * @since BuddyPress (2.0.0)
 * @deprecated BuddyPress (2.2.0)
 *
 * @todo Support untrashing better
 *
 * @param string $new_status New status for the post.
 * @param string $old_status Old status for the post.
 * @param object $post Post data.
 */
function bp_blogs_catch_transition_post_status( $new_status, $old_status, $post ) {
	_deprecated_function( __FUNCTION__, '2.2', 'bp_activity_catch_transition_post_type_status()' );
	bp_activity_catch_transition_post_type_status( $new_status, $old_status, $post );
}

/**
 * Record a new blog post in the BuddyPress activity stream.
 *
 * @deprecated BuddyPress (2.2.0)
 *
 * @param int $post_id ID of the post being recorded.
 * @param object $post The WP post object passed to the 'save_post' action.
 * @param int $user_id Optional. The user to whom the activity item will be
 *        associated. Defaults to the post_author.
 * @return bool|null Returns false on failure.
 */
function bp_blogs_record_post( $post_id, $post, $user_id = 0 ) {
	_deprecated_function( __FUNCTION__, '2.2', 'bp_activity_post_type_publish()' );
	bp_activity_post_type_publish( $post_id, $post, $user_id );
}

/**
 * Updates a blog post's corresponding activity entry during a post edit.
 *
 * @since BuddyPress (2.0.0)
 * @deprecated BuddyPress (2.2.0)
 *
 * @see bp_blogs_catch_transition_post_status()
 *
 * @param WP_Post $post
 */
function bp_blogs_update_post( $post ) {
	_deprecated_function( __FUNCTION__, '2.2', 'bp_activity_post_type_update()' );
	bp_activity_post_type_update( $post );
}

/**
 * Clear cache when a new blog is created.
 *
 * @since BuddyPress (1.0.0)
 * @deprecated BuddyPress (2.2.0)
 *
 * @param BP_Blogs_Blog $recorded_blog_obj The recorded blog, passed by
 *        'bp_blogs_new_blog'.
 */
function bp_blogs_format_clear_blog_cache( $recorded_blog_obj ) {
	_deprecated_function( __FUNCTION__, '2.2', 'bp_blogs_clear_blog_object_cache()' );
	bp_blogs_clear_blog_object_cache( false, $recorded_blog_obj->user_id );
}

/**
 * Format 'new_member' activity actions.
 *
 * @since BuddyPress (2.0.0)
 * @deprecated BuddyPress (2.2.0)
 *
 * @param string $action Static activity action.
 * @param object $activity Activity object.
 * @return string
 */
function bp_xprofile_format_activity_action_new_member( $action, $activity ) {
	_deprecated_function( __FUNCTION__, '2.2', 'bp_members_format_activity_action_new_member()' );

	$action = apply_filters( 'bp_xprofile_format_activity_action_new_member', $action, $activity );
	return bp_members_format_activity_action_new_member( $action, $activity );
}
