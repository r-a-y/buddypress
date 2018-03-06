<?php

/**
 * Determine if there are still blogs left in the loop.
 *
 * @global object $blogs_template {@link BP_Blogs_Template}
 *
 * @return bool Returns true when blogs are found.
 */
function bp_blogs() {
	global $blogs_template;

	return $blogs_template->blogs();
}

/**
 * Get the current blog object in the loop.
 *
 * @global object $blogs_template {@link BP_Blogs_Template}
 *
 * @return object The current blog within the loop.
 */
function bp_the_blog() {
	global $blogs_template;

	return $blogs_template->the_blog();
}

/**
 * Output the blogs pagination count.
 *
 * @since 1.0.0
 */
function bp_blogs_pagination_count() {
	echo bp_get_blogs_pagination_count();
}

/**
 * Get the blogs pagination count.
 *
 * @since 2.7.0
 *
 * @global object $blogs_template {@link BP_Blogs_Template}
 *
 * @return string
 */
function bp_get_blogs_pagination_count() {
	global $blogs_template;

	$start_num = intval( ( $blogs_template->pag_page - 1 ) * $blogs_template->pag_num ) + 1;
	$from_num  = bp_core_number_format( $start_num );
	$to_num    = bp_core_number_format( ( $start_num + ( $blogs_template->pag_num - 1 ) > $blogs_template->total_blog_count ) ? $blogs_template->total_blog_count : $start_num + ( $blogs_template->pag_num - 1 ) );
	$total     = bp_core_number_format( $blogs_template->total_blog_count );

	if ( 1 == $blogs_template->total_blog_count ) {
		$message = __( 'Viewing 1 site', 'buddypress' );
	} else {
		$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s site', 'Viewing %1$s - %2$s of %3$s sites', $blogs_template->total_blog_count, 'buddypress' ), $from_num, $to_num, $total );
	}

	/**
	 * Filters the "Viewing x-y of z blogs" pagination message.
	 *
	 * @since 2.7.0
	 *
	 * @param string $message  "Viewing x-y of z blogs" text.
	 * @param string $from_num Total amount for the low value in the range.
	 * @param string $to_num   Total amount for the high value in the range.
	 * @param string $total    Total amount of blogs found.
	 */
	return apply_filters( 'bp_get_blogs_pagination_count', $message, $from_num, $to_num, $total );
}

/**
 * Output the blogs pagination links.
 */
function bp_blogs_pagination_links() {
	echo bp_get_blogs_pagination_links();
}
	/**
	 * Return the blogs pagination links.
	 *
	 * @global object $blogs_template {@link BP_Blogs_Template}
	 *
	 * @return string HTML pagination links.
	 */
	function bp_get_blogs_pagination_links() {
		global $blogs_template;

		/**
		 * Filters the blogs pagination links.
		 *
		 * @since 1.0.0
		 *
		 * @param string $pag_links HTML pagination links.
		 */
		return apply_filters( 'bp_get_blogs_pagination_links', $blogs_template->pag_links );
	}

/**
 * Output a blog's avatar.
 *
 * @see bp_get_blog_avatar() for description of arguments.
 *
 * @param array|string $args See {@link bp_get_blog_avatar()}.
 */
function bp_blog_avatar( $args = '' ) {
	echo bp_get_blog_avatar( $args );
}
	/**
	 * Get a blog's avatar.
	 *
	 * At the moment, blog avatars are simply the user avatars of the blog
	 * admin. Filter 'bp_get_blog_avatar_' . $blog_id to customize.
	 *
	 * @since 2.4.0 Introduced `$title` argument.
	 *
	 * @see bp_core_fetch_avatar() For a description of arguments and
	 *      return values.
	 *
	 * @param array|string $args  {
	 *     Arguments are listed here with an explanation of their defaults.
	 *     For more information about the arguments, see
	 *     {@link bp_core_fetch_avatar()}.
	 *     @type string   $alt     Default: 'Profile picture of site author [user name]'.
	 *     @type string   $class   Default: 'avatar'.
	 *     @type string   $type    Default: 'full'.
	 *     @type int|bool $width   Default: false.
	 *     @type int|bool $height  Default: false.
	 *     @type bool     $id      Currently unused.
	 *     @type bool     $no_grav Default: true.
	 * }
	 * @return string User avatar string.
	 */
	function bp_get_blog_avatar( $args = '' ) {
		global $blogs_template;

		// Bail if avatars are turned off
		// @todo Should we maybe still filter this?
		if ( ! buddypress()->avatar->show_avatars ) {
			return false;
		}

		$author_displayname = bp_core_get_user_displayname( $blogs_template->blog->admin_user_id );

		// Parse the arguments.
		$r = bp_parse_args( $args, array(
			'type'    => 'full',
			'width'   => false,
			'height'  => false,
			'class'   => 'avatar',
			'id'      => false,
			'alt'     => sprintf( __( 'Profile picture of site author %s', 'buddypress' ), esc_attr( $author_displayname ) ),
			'no_grav' => true,
		) );

		// Use site icon if available.
		$avatar = '';
		if ( bp_is_active( 'blogs', 'site-icon' ) && function_exists( 'has_site_icon' ) ) {
			$site_icon = bp_blogs_get_blogmeta( bp_get_blog_id(), "site_icon_url_{$r['type']}" );

			// Never attempted to fetch site icon before; do it now!
			if ( '' === $site_icon ) {
				switch_to_blog( bp_get_blog_id() );

				// Fetch the other size first.
				if ( 'full' === $r['type'] ) {
					$size      = bp_core_avatar_thumb_width();
					$save_size = 'thumb';
				} else {
					$size      = bp_core_avatar_full_width();
					$save_size = 'full';
				}

				$site_icon = get_site_icon_url( $size );
				// Empty site icons get saved as integer 0.
				if ( empty( $site_icon ) ) {
					$site_icon = 0;
				}

				// Sync site icon for other size to blogmeta.
				bp_blogs_update_blogmeta( bp_get_blog_id(), "site_icon_url_{$save_size}", $site_icon );

				// Now, fetch the size we want.
				if ( 0 !== $site_icon ) {
					$size      = 'full' === $r['type'] ? bp_core_avatar_full_width() : bp_core_avatar_thumb_width();
					$site_icon = get_site_icon_url( $size );
				}

				// Sync site icon to blogmeta.
				bp_blogs_update_blogmeta( bp_get_blog_id(), "site_icon_url_{$r['type']}", $site_icon );

				restore_current_blog();
			}

			// We have a site icon.
			if ( ! is_numeric( $site_icon ) ) {
				if ( empty( $r['width'] ) && ! isset( $size ) ) {
					$size = 'full' === $r['type'] ? bp_core_avatar_full_width() : bp_core_avatar_thumb_width();
				} else {
					$size = (int) $r['width'];
				}

				$avatar = sprintf( '<img src="%1$s" class="%2$s" width="%3$s" height="%3$s" alt="%4$s" />',
					esc_url( $site_icon ),
					esc_attr( "{$r['class']} avatar-{$size}" ),
					esc_attr( $size ),
					sprintf( esc_attr__( 'Site icon for %s', 'buddypress' ), bp_get_blog_name() )
				);
			}
		}

		// Fallback to user ID avatar.
		if ( '' === $avatar ) {
			$avatar = bp_core_fetch_avatar( array(
				'item_id'    => $blogs_template->blog->admin_user_id,
				// 'avatar_dir' => 'blog-avatars',
				// 'object'     => 'blog',
				'type'       => $r['type'],
				'alt'        => $r['alt'],
				'css_id'     => $r['id'],
				'class'      => $r['class'],
				'width'      => $r['width'],
				'height'     => $r['height']
			) );
		}

		/**
		 * In future BuddyPress versions you will be able to set the avatar for a blog.
		 * Right now you can use a filter with the ID of the blog to change it if you wish.
		 * By default it will return the avatar for the primary blog admin.
		 *
		 * This filter is deprecated as of BuddyPress 1.5 and may be removed in a future version.
		 * Use the 'bp_get_blog_avatar' filter instead.
		 */
		$avatar = apply_filters( 'bp_get_blog_avatar_' . $blogs_template->blog->blog_id, $avatar );

		/**
		 * Filters a blog's avatar.
		 *
		 * @since 1.5.0
		 *
		 * @param string $avatar  Formatted HTML <img> element, or raw avatar
		 *                        URL based on $html arg.
		 * @param int    $blog_id ID of the blog whose avatar is being displayed.
		 * @param array  $r       Array of arguments used when fetching avatar.
		 */
		return apply_filters( 'bp_get_blog_avatar', $avatar, $blogs_template->blog->blog_id, $r );
	}

function bp_blog_permalink() {
	echo bp_get_blog_permalink();
}
	function bp_get_blog_permalink() {
		global $blogs_template;

		if ( empty( $blogs_template->blog->domain ) )
			$permalink = bp_get_root_domain() . $blogs_template->blog->path;
		else {
			$protocol = 'http://';
			if ( is_ssl() )
				$protocol = 'https://';

			$permalink = $protocol . $blogs_template->blog->domain . $blogs_template->blog->path;
		}

		/**
		 * Filters the blog permalink.
		 *
		 * @since 1.0.0
		 *
		 * @param string $permalink Permalink URL for the blog.
		 */
		return apply_filters( 'bp_get_blog_permalink', $permalink );
	}

/**
 * Output the name of the current blog in the loop.
 */
function bp_blog_name() {
	echo bp_get_blog_name();
}
	/**
	 * Return the name of the current blog in the loop.
	 *
	 * @return string The name of the current blog in the loop.
	 */
	function bp_get_blog_name() {
		global $blogs_template;

		/**
		 * Filters the name of the current blog in the loop.
		 *
		 * @since 1.2.0
		 *
		 * @param string $name Name of the current blog in the loop.
		 */
		return apply_filters( 'bp_get_blog_name', $blogs_template->blog->name );
	}

/**
 * Output the ID of the current blog in the loop.
 *
 * @since 1.7.0
 */
function bp_blog_id() {
	echo bp_get_blog_id();
}
	/**
	 * Return the ID of the current blog in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @return int ID of the current blog in the loop.
	 */
	function bp_get_blog_id() {
		global $blogs_template;

		/**
		 * Filters the ID of the current blog in the loop.
		 *
		 * @since 1.7.0
		 *
		 * @param int $blog_id ID of the current blog in the loop.
		 */
		return apply_filters( 'bp_get_blog_id', $blogs_template->blog->blog_id );
	}

/**
 * Output the description of the current blog in the loop.
 */
function bp_blog_description() {

	/**
	 * Filters the description of the current blog in the loop.
	 *
	 * @since 1.2.0
	 *
	 * @param string $value Description of the current blog in the loop.
	 */
	echo apply_filters( 'bp_blog_description', bp_get_blog_description() );
}
	/**
	 * Return the description of the current blog in the loop.
	 *
	 * @return string Description of the current blog in the loop.
	 */
	function bp_get_blog_description() {
		global $blogs_template;

		/**
		 * Filters the description of the current blog in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Description of the current blog in the loop.
		 */
		return apply_filters( 'bp_get_blog_description', $blogs_template->blog->description );
	}

/**
 * Output the row class of the current blog in the loop.
 *
 * @since 1.7.0
 *
 * @param array $classes Array of custom classes.
 */
function bp_blog_class( $classes = array() ) {
	echo bp_get_blog_class( $classes );
}
	/**
	 * Return the row class of the current blog in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @global BP_Blogs_Template $blogs_template
	 *
	 * @param array $classes Array of custom classes.
	 * @return string Row class of the site.
	 */
	function bp_get_blog_class( $classes = array() ) {
		global $blogs_template;

		// Add even/odd classes, but only if there's more than 1 group.
		if ( $blogs_template->blog_count > 1 ) {
			$pos_in_loop = (int) $blogs_template->current_blog;
			$classes[]   = ( $pos_in_loop % 2 ) ? 'even' : 'odd';

		// If we've only one site in the loop, don't bother with odd and even.
		} else {
			$classes[] = 'bp-single-blog';
		}

		/**
		 * Filters the row class of the current blog in the loop.
		 *
		 * @since 1.7.0
		 *
		 * @param array $classes Array of classes to be applied to row.
		 */
		$classes = apply_filters( 'bp_get_blog_class', $classes );
		$classes = array_merge( $classes, array() );
		$retval  = 'class="' . join( ' ', $classes ) . '"';

		return $retval;
	}

/**
 * Output the last active date of the current blog in the loop.
 *
 * @param array $args See {@link bp_get_blog_last_active()}.
 */
function bp_blog_last_active( $args = array() ) {
	echo bp_get_blog_last_active( $args );
}
	/**
	 * Return the last active date of the current blog in the loop.
	 *
	 * @param array $args {
	 *     Array of optional arguments.
	 *     @type bool $active_format If true, formatted "Active 5 minutes ago".
	 *                               If false, formatted "5 minutes ago".
	 *                               Default: true.
	 * }
	 * @return string Last active date.
	 */
	function bp_get_blog_last_active( $args = array() ) {
		global $blogs_template;

		// Parse the activity format.
		$r = bp_parse_args( $args, array(
			'active_format' => true
		) );

		// Backwards compatibility for anyone forcing a 'true' active_format.
		if ( true === $r['active_format'] ) {
			$r['active_format'] = __( 'active %s', 'buddypress' );
		}

		// Blog has been posted to at least once.
		if ( isset( $blogs_template->blog->last_activity ) ) {

			// Backwards compatibility for pre 1.5 'ago' strings.
			$last_activity = ! empty( $r['active_format'] )
				? bp_core_get_last_activity( $blogs_template->blog->last_activity, $r['active_format'] )
				: bp_core_time_since( $blogs_template->blog->last_activity );

		// Blog has never been posted to.
		} else {
			$last_activity = __( 'Never active', 'buddypress' );
		}

		/**
		 * Filters the last active date of the current blog in the loop.
		 *
		 * @since 1.2.0
		 *
		 * @param string $last_activity Last active date.
		 * @param array  $r             Array of parsed args used to determine formatting.
		 */
		return apply_filters( 'bp_blog_last_active', $last_activity, $r );
	}

/**
 * Output the latest post from the current blog in the loop.
 *
 * @param array $args See {@link bp_get_blog_latest_post()}.
 */
function bp_blog_latest_post( $args = array() ) {
	echo bp_get_blog_latest_post( $args );
}
	/**
	 * Return the latest post from the current blog in the loop.
	 *
	 * @param array $args {
	 *     Array of optional arguments.
	 *     @type bool $latest_format If true, formatted "Latest post: [link to post]".
	 *                               If false, formatted "[link to post]".
	 *                               Default: true.
	 * }
	 * @return string $retval String of the form 'Latest Post: [link to post]'.
	 */
	function bp_get_blog_latest_post( $args = array() ) {
		global $blogs_template;

		$r = wp_parse_args( $args, array(
			'latest_format' => true,
		) );

		$retval = bp_get_blog_latest_post_title();

		if ( ! empty( $retval ) ) {
			if ( ! empty( $r['latest_format'] ) ) {

				/**
				 * Filters the title text of the latest post for the current blog in loop.
				 *
				 * @since 1.0.0
				 *
				 * @param string $retval Title of the latest post.
				 */
				$retval = sprintf( __( 'Latest Post: %s', 'buddypress' ), '<a href="' . $blogs_template->blog->latest_post->guid . '">' . apply_filters( 'the_title', $retval ) . '</a>' );
			} else {

				/** This filter is documented in bp-blogs/bp-blogs-template.php */
				$retval = '<a href="' . $blogs_template->blog->latest_post->guid . '">' . apply_filters( 'the_title', $retval ) . '</a>';
			}
		}

		/**
		 * Filters the HTML markup result for the latest blog post in loop.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param string $retval HTML markup for the latest post.
		 * @param array  $r      Array of parsed arguments.
		 */
		return apply_filters( 'bp_get_blog_latest_post', $retval, $r );
	}

/**
 * Output the title of the latest post on the current blog in the loop.
 *
 * @since 1.7.0
 *
 * @see bp_get_blog_latest_post_title()
 */
function bp_blog_latest_post_title() {
	echo bp_get_blog_latest_post_title();
}
	/**
	 * Return the title of the latest post on the current blog in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @global BP_Blogs_Template
	 *
	 * @return string Post title.
	 */
	function bp_get_blog_latest_post_title() {
		global $blogs_template;

		$retval = '';

		if ( ! empty( $blogs_template->blog->latest_post ) && ! empty( $blogs_template->blog->latest_post->post_title ) )
			$retval = $blogs_template->blog->latest_post->post_title;

		/**
		 * Filters the title text of the latest post on the current blog in the loop.
		 *
		 * @since 1.7.0
		 *
		 * @param string $retval Title text for the latest post.
		 */
		return apply_filters( 'bp_get_blog_latest_post_title', $retval );
	}

/**
 * Output the permalink of the latest post on the current blog in the loop.
 *
 * @since 1.7.0
 *
 * @see bp_get_blog_latest_post_title()
 */
function bp_blog_latest_post_permalink() {
	echo esc_url( bp_get_blog_latest_post_permalink() );
}
	/**
	 * Return the permalink of the latest post on the current blog in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @global BP_Blogs_Template
	 *
	 * @return string URL of the blog's latest post.
	 */
	function bp_get_blog_latest_post_permalink() {
		global $blogs_template;

		$retval = '';

		if ( ! empty( $blogs_template->blog->latest_post ) && ! empty( $blogs_template->blog->latest_post->ID ) )
			$retval = add_query_arg( 'p', $blogs_template->blog->latest_post->ID, bp_get_blog_permalink() );

		/**
		 * Filters the permalink of the latest post on the current blog in the loop.
		 *
		 * @since 1.7.0
		 *
		 * @param string $retval Permalink URL of the latest post.
		 */
		return apply_filters( 'bp_get_blog_latest_post_permalink', $retval );
	}

/**
 * Output the content of the latest post on the current blog in the loop.
 *
 * @since 1.7.0
 *
 */
function bp_blog_latest_post_content() {
	echo bp_get_blog_latest_post_content();
}
	/**
	 * Return the content of the latest post on the current blog in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @global BP_Blogs_Template
	 *
	 * @return string Content of the blog's latest post.
	 */
	function bp_get_blog_latest_post_content() {
		global $blogs_template;

		$retval = '';

		if ( ! empty( $blogs_template->blog->latest_post ) && ! empty( $blogs_template->blog->latest_post->post_content ) )
			$retval = $blogs_template->blog->latest_post->post_content;

		/**
		 * Filters the content of the latest post on the current blog in the loop.
		 *
		 * @since 1.7.0
		 *
		 * @param string $retval Content of the latest post on the current blog in the loop.
		 */
		return apply_filters( 'bp_get_blog_latest_post_content', $retval );
	}

/**
 * Output the featured image of the latest post on the current blog in the loop.
 *
 * @since 1.7.0
 *
 * @see bp_get_blog_latest_post_content() For description of parameters.
 *
 * @param string $size See {@link bp_get_blog_latest_post_featured_image()}.
 */
function bp_blog_latest_post_featured_image( $size = 'thumbnail' ) {
	echo bp_get_blog_latest_post_featured_image( $size );
}
	/**
	 * Return the featured image of the latest post on the current blog in the loop.
	 *
	 * @since 1.7.0
	 *
	 * @global BP_Blogs_Template
	 *
	 * @param string $size Image version to return. 'thumbnail', 'medium',
	 *                     'large', or 'post-thumbnail'. Default: 'thumbnail'.
	 * @return string URL of the image.
	 */
	function bp_get_blog_latest_post_featured_image( $size = 'thumbnail' ) {
		global $blogs_template;

		$retval = '';

		if ( ! empty( $blogs_template->blog->latest_post ) && ! empty( $blogs_template->blog->latest_post->images[$size] ) )
			$retval = $blogs_template->blog->latest_post->images[$size];

		/**
		 * Filters the featured image of the latest post on the current blog in the loop.
		 *
		 * @since 1.7.0
		 *
		 * @param string $retval The featured image of the latest post on the current blog in the loop.
		 */
		return apply_filters( 'bp_get_blog_latest_post_featured_image', $retval );
	}

/**
 * Does the latest blog post have a featured image?
 *
 * @since 1.7.0
 *
 * @param string $thumbnail Image version to return. 'thumbnail', 'medium', 'large',
 *                          or 'post-thumbnail'. Default: 'thumbnail'.
 * @return bool True if the latest blog post from the current blog has a
 *              featured image of the given size.
 */
function bp_blog_latest_post_has_featured_image( $thumbnail = 'thumbnail' ) {
	$image  = bp_get_blog_latest_post_featured_image( $thumbnail );

	/**
	 * Filters whether or not the latest blog post has a featured image.
	 *
	 * @since 1.7.0
	 *
	 * @param bool   $value     Whether or not the latest blog post has a featured image.
	 * @param string $thumbnail Image version to return.
	 * @param string $image     Returned value from bp_get_blog_latest_post_featured_image.
	 */
	return apply_filters( 'bp_blog_latest_post_has_featured_image', ! empty( $image ), $thumbnail, $image );
}

/**
 * Output button for visiting a blog in a loop.
 *
 * @see bp_get_blogs_visit_blog_button() for description of arguments.
 *
 * @param array|string $args See {@link bp_get_blogs_visit_blog_button()}.
 */
function bp_blogs_visit_blog_button( $args = '' ) {
	echo bp_get_blogs_visit_blog_button( $args );
}
	/**
	 * Return button for visiting a blog in a loop.
	 *
	 * @see BP_Button for a complete description of arguments and return
	 *      value.
	 *
	 * @param array|string $args {
	 *     Arguments are listed below, with their default values. For a
	 *     complete description of arguments, see {@link BP_Button}.
	 *     @type string $id                Default: 'visit_blog'.
	 *     @type string $component         Default: 'blogs'.
	 *     @type bool   $must_be_logged_in Default: false.
	 *     @type bool   $block_self        Default: false.
	 *     @type string $wrapper_class     Default: 'blog-button visit'.
	 *     @type string $link_href         Permalink of the current blog in the loop.
	 *     @type string $link_class        Default: 'blog-button visit'.
	 *     @type string $link_text         Default: 'Visit Site'.
	 * }
	 * @return string The HTML for the Visit button.
	 */
	function bp_get_blogs_visit_blog_button( $args = '' ) {
		$defaults = array(
			'id'                => 'visit_blog',
			'component'         => 'blogs',
			'must_be_logged_in' => false,
			'block_self'        => false,
			'wrapper_class'     => 'blog-button visit',
			'link_href'         => bp_get_blog_permalink(),
			'link_class'        => 'blog-button visit',
			'link_text'         => __( 'Visit Site', 'buddypress' ),
		);

		$button = wp_parse_args( $args, $defaults );

		/**
		 * Filters the button for visiting a blog in a loop.
		 *
		 * @since 1.2.10
		 *
		 * @param array $button Array of arguments to be used for the button to visit a blog.
		 */
		return bp_get_button( apply_filters( 'bp_get_blogs_visit_blog_button', $button ) );
	}