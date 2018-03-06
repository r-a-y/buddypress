<?php
/**
 * BuddyPress Blogs Template Tags.
 *
 * @package BuddyPress
 * @subpackage BlogsTemplate
 * @since 1.5.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Output the blogs component slug.
 *
 * @since 1.5.0
 *
 */
function bp_blogs_slug() {
	echo bp_get_blogs_slug();
}
	/**
	 * Return the blogs component slug.
	 *
	 * @since 1.5.0
	 *
	 * @return string The 'blogs' slug.
	 */
	function bp_get_blogs_slug() {

		/**
		 * Filters the blogs component slug.
		 *
		 * @since 1.5.0
		 *
		 * @param string $slug Slug for the blogs component.
		 */
		return apply_filters( 'bp_get_blogs_slug', buddypress()->blogs->slug );
	}

/**
 * Output the blogs component root slug.
 *
 * @since 1.5.0
 *
 */
function bp_blogs_root_slug() {
	echo bp_get_blogs_root_slug();
}
	/**
	 * Return the blogs component root slug.
	 *
	 * @since 1.5.0
	 *
	 * @return string The 'blogs' root slug.
	 */
	function bp_get_blogs_root_slug() {

		/**
		 * Filters the blogs component root slug.
		 *
		 * @since 1.5.0
		 *
		 * @param string $root_slug Root slug for the blogs component.
		 */
		return apply_filters( 'bp_get_blogs_root_slug', buddypress()->blogs->root_slug );
	}

/**
 * Output blog directory permalink.
 *
 * @since 1.5.0
 *
 */
function bp_blogs_directory_permalink() {
	echo esc_url( bp_get_blogs_directory_permalink() );
}
	/**
	 * Return blog directory permalink.
	 *
	 * @since 1.5.0
	 *
	 *
	 * @return string The URL of the Blogs directory.
	 */
	function bp_get_blogs_directory_permalink() {

		/**
		 * Filters the blog directory permalink.
		 *
		 * @since 1.5.0
		 *
		 * @param string $value Permalink URL for the blog directory.
		 */
		return apply_filters( 'bp_get_blogs_directory_permalink', trailingslashit( bp_get_root_domain() . '/' . bp_get_blogs_root_slug() ) );
	}

/**
 * Rewind the blogs and reset blog index.
 */
function bp_rewind_blogs() {
	global $blogs_template;

	$blogs_template->rewind_blogs();
}

/**
 * Initialize the blogs loop.
 *
 * Based on the $args passed, bp_has_blogs() populates the $blogs_template
 * global, enabling the use of BuddyPress templates and template functions to
 * display a list of activity items.
 *
 * @global object $blogs_template {@link BP_Blogs_Template}
 *
 * @param array|string $args {
 *     Arguments for limiting the contents of the blogs loop. Most arguments
 *     are in the same format as {@link BP_Blogs_Blog::get()}. However, because
 *     the format of the arguments accepted here differs in a number of ways,
 *     and because bp_has_blogs() determines some default arguments in a
 *     dynamic fashion, we list all accepted arguments here as well.
 *
 *     Arguments can be passed as an associative array, or as a URL query
 *     string (eg, 'user_id=4&per_page=3').
 *
 *     @type int      $page             Which page of results to fetch. Using page=1 without
 *                                      per_page will result in no pagination. Default: 1.
 *     @type int|bool $per_page         Number of results per page. Default: 20.
 *     @type string   $page_arg         The string used as a query parameter in
 *                                      pagination links. Default: 'bpage'.
 *     @type int|bool $max              Maximum number of results to return.
 *                                      Default: false (unlimited).
 *     @type string   $type             The order in which results should be fetched.
 *                                      'active', 'alphabetical', 'newest', or 'random'.
 *     @type array    $include_blog_ids Array of blog IDs to limit results to.
 *     @type string   $sort             'ASC' or 'DESC'. Default: 'DESC'.
 *     @type string   $search_terms     Limit results by a search term. Default: the value of `$_REQUEST['s']` or
 *                                      `$_REQUEST['sites_search']`, if present.
 *     @type int      $user_id          The ID of the user whose blogs should be retrieved.
 *                                      When viewing a user profile page, 'user_id' defaults to the
 *                                      ID of the displayed user. Otherwise the default is false.
 * }
 * @return bool Returns true when blogs are found, otherwise false.
 */
function bp_has_blogs( $args = '' ) {
	global $blogs_template;

	// Check for and use search terms.
	$search_terms_default = false;
	$search_query_arg = bp_core_get_component_search_query_arg( 'blogs' );
	if ( ! empty( $_REQUEST[ $search_query_arg ] ) ) {
		$search_terms_default = stripslashes( $_REQUEST[ $search_query_arg ] );
	} elseif ( ! empty( $_REQUEST['s'] ) ) {
		$search_terms_default = stripslashes( $_REQUEST['s'] );
	}

	// Parse arguments.
	$r = bp_parse_args( $args, array(
		'type'              => 'active',
		'page_arg'          => 'bpage', // See https://buddypress.trac.wordpress.org/ticket/3679.
		'page'              => 1,
		'per_page'          => 20,
		'max'               => false,
		'user_id'           => bp_displayed_user_id(), // Pass a user_id to limit to only blogs this user is a member of.
		'include_blog_ids'  => false,
		'search_terms'      => $search_terms_default,
		'update_meta_cache' => true
	), 'has_blogs' );

	// Set per_page to maximum if max is enforced.
	if ( ! empty( $r['max'] ) && ( (int) $r['per_page'] > (int) $r['max'] ) ) {
		$r['per_page'] = (int) $r['max'];
	}

	// Get the blogs.
	$blogs_template = new BP_Blogs_Template( $r['type'], $r['page'], $r['per_page'], $r['max'], $r['user_id'], $r['search_terms'], $r['page_arg'], $r['update_meta_cache'], $r['include_blog_ids'] );

	/**
	 * Filters whether or not there are blogs to list.
	 *
	 * @since 1.1.0
	 *
	 * @param bool              $value          Whether or not there are blogs to list.
	 * @param BP_Blogs_Template $blogs_template Current blogs template object.
	 * @param array             $r              Parsed arguments used in blogs template query.
	 */
	return apply_filters( 'bp_has_blogs', $blogs_template->has_blogs(), $blogs_template, $r );
}

/**
 * Output hidden fields to help with form submissions in Sites directory.
 *
 * This function detects whether 's', 'letter', or 'blogs_search' requests are
 * currently being made (as in a URL parameter), and creates corresponding
 * hidden fields.
 */
function bp_blog_hidden_fields() {
	if ( isset( $_REQUEST['s'] ) )
		echo '<input type="hidden" id="search_terms" value="' . esc_attr( $_REQUEST['s'] ). '" name="search_terms" />';

	if ( isset( $_REQUEST['letter'] ) )
		echo '<input type="hidden" id="selected_letter" value="' . esc_attr( $_REQUEST['letter'] ) . '" name="selected_letter" />';

	if ( isset( $_REQUEST['blogs_search'] ) )
		echo '<input type="hidden" id="search_terms" value="' . esc_attr( $_REQUEST['blogs_search'] ) . '" name="search_terms" />';
}

/**
 * Output the total number of blogs on the site.
 */
function bp_total_blog_count() {
	echo bp_get_total_blog_count();
}
	/**
	 * Return the total number of blogs on the site.
	 *
	 * @return int Total number of blogs.
	 */
	function bp_get_total_blog_count() {

		/**
		 * Filters the total number of blogs on the site.
		 *
		 * @since 1.2.0
		 *
		 * @param int $value Total number of blogs on the site.
		 */
		return apply_filters( 'bp_get_total_blog_count', bp_blogs_total_blogs() );
	}
	add_filter( 'bp_get_total_blog_count', 'bp_core_number_format' );

/**
 * Output the total number of blogs for a given user.
 *
 * @param int $user_id ID of the user.
 */
function bp_total_blog_count_for_user( $user_id = 0 ) {
	echo bp_get_total_blog_count_for_user( $user_id );
}
	/**
	 * Return the total number of blogs for a given user.
	 *
	 * @param int $user_id ID of the user.
	 * @return int Total number of blogs for the user.
	 */
	function bp_get_total_blog_count_for_user( $user_id = 0 ) {

		/**
		 * Filters the total number of blogs for a given user.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$user_id` parameter.
		 *
		 * @param int $value   Total number of blogs for a given user.
		 * @param int $user_id ID of the queried user.
		 */
		return apply_filters( 'bp_get_total_blog_count_for_user', bp_blogs_total_blogs_for_user( $user_id ), $user_id );
	}
	add_filter( 'bp_get_total_blog_count_for_user', 'bp_core_number_format' );


/** Blog Registration ********************************************************/

/**
 * Checks whether blog creation is enabled.
 *
 * Returns true when blog creation is enabled for logged-in users only, or
 * when it's enabled for new registrations.
 *
 * @return bool True if blog registration is enabled.
 */
function bp_blog_signup_enabled() {
	$bp = buddypress();

	$active_signup = isset( $bp->site_options['registration'] )
		? $bp->site_options['registration']
		: 'all';

	/**
	 * Filters whether or not blog creation is enabled.
	 *
	 * Return "all", "none", "blog" or "user".
	 *
	 * @since 1.0.0
	 *
	 * @param string $active_signup Value of the registration site option creation status.
	 */
	$active_signup = apply_filters( 'wpmu_active_signup', $active_signup );

	if ( 'none' == $active_signup || 'user' == $active_signup )
		return false;

	return true;
}

/**
 * Output the wrapper markup for the blog signup form.
 *
 * @param string          $blogname   Optional. The default blog name (path or domain).
 * @param string          $blog_title Optional. The default blog title.
 * @param string|WP_Error $errors     Optional. The WP_Error object returned by a previous
 *                                    submission attempt.
 */
function bp_show_blog_signup_form($blogname = '', $blog_title = '', $errors = '') {
	global $current_user;

	if ( isset($_POST['submit']) ) {
		bp_blogs_validate_blog_signup();
	} else {
		if ( ! is_wp_error($errors) ) {
			$errors = new WP_Error();
		}

		/**
		 * Filters the default values for Blog name, title, and any current errors.
		 *
		 * @since 1.0.0
		 *
		 * @param array $value {
		 *      string   $blogname   Default blog name provided.
		 *      string   $blog_title Default blog title provided.
		 *      WP_Error $errors     WP_Error object.
		 * }
		 */
		$filtered_results = apply_filters('signup_another_blog_init', array('blogname' => $blogname, 'blog_title' => $blog_title, 'errors' => $errors ));
		$blogname = $filtered_results['blogname'];
		$blog_title = $filtered_results['blog_title'];
		$errors = $filtered_results['errors'];

		if ( $errors->get_error_code() ) {
			echo "<p>" . __('There was a problem; please correct the form below and try again.', 'buddypress') . "</p>";
		}
		?>
		<p><?php printf(__("By filling out the form below, you can <strong>add a site to your account</strong>. There is no limit to the number of sites that you can have, so create to your heart's content, but blog responsibly!", 'buddypress'), $current_user->display_name) ?></p>

		<p><?php _e("If you&#8217;re not going to use a great domain, leave it for a new user. Now have at it!", 'buddypress') ?></p>

		<form class="standard-form" id="setupform" method="post" action="">

			<input type="hidden" name="stage" value="gimmeanotherblog" />
			<?php

			/**
			 * Fires after the default hidden fields in blog signup form markup.
			 *
			 * @since 1.0.0
			 */
			do_action( 'signup_hidden_fields' ); ?>

			<?php bp_blogs_signup_blog($blogname, $blog_title, $errors); ?>
			<p>
				<input id="submit" type="submit" name="submit" class="submit" value="<?php esc_attr_e('Create Site', 'buddypress') ?>" />
			</p>

			<?php wp_nonce_field( 'bp_blog_signup_form' ) ?>
		</form>
		<?php
	}
}

/**
 * Output the input fields for the blog creation form.
 *
 * @param string          $blogname   Optional. The default blog name (path or domain).
 * @param string          $blog_title Optional. The default blog title.
 * @param string|WP_Error $errors     Optional. The WP_Error object returned by a previous
 *                                    submission attempt.
 */
function bp_blogs_signup_blog( $blogname = '', $blog_title = '', $errors = '' ) {
	global $current_site;

	// Blog name.
	if( !is_subdomain_install() )
		echo '<label for="blogname">' . __('Site Name:', 'buddypress') . '</label>';
	else
		echo '<label for="blogname">' . __('Site Domain:', 'buddypress') . '</label>';

	if ( $errmsg = $errors->get_error_message('blogname') ) { ?>

		<p class="error"><?php echo $errmsg ?></p>

	<?php }

	if ( !is_subdomain_install() )
		echo '<span class="prefix_address">' . $current_site->domain . $current_site->path . '</span> <input name="blogname" type="text" id="blogname" value="'.$blogname.'" maxlength="63" /><br />';
	else
		echo '<input name="blogname" type="text" id="blogname" value="'.$blogname.'" maxlength="63" ' . bp_get_form_field_attributes( 'blogname' ) . '/> <span class="suffix_address">.' . bp_signup_get_subdomain_base() . '</span><br />';

	if ( !is_user_logged_in() ) {
		print '(<strong>' . __( 'Your address will be ' , 'buddypress');

		if ( !is_subdomain_install() ) {
			print $current_site->domain . $current_site->path . __( 'blogname' , 'buddypress');
		} else {
			print __( 'domain.' , 'buddypress') . $current_site->domain . $current_site->path;
		}

		echo '.</strong> ' . __( 'Must be at least 4 characters, letters and numbers only. It cannot be changed so choose carefully!)' , 'buddypress') . '</p>';
	}

	// Blog Title.
	?>

	<label for="blog_title"><?php _e('Site Title:', 'buddypress') ?></label>

	<?php if ( $errmsg = $errors->get_error_message('blog_title') ) { ?>

		<p class="error"><?php echo $errmsg ?></p>

	<?php }
	echo '<input name="blog_title" type="text" id="blog_title" value="'.esc_html($blog_title, 1).'" /></p>';
	?>

	<fieldset class="create-site">
		<legend class="label"><?php _e('Privacy: I would like my site to appear in search engines, and in public listings around this network', 'buddypress') ?></legend>

		<label class="checkbox" for="blog_public_on">
			<input type="radio" id="blog_public_on" name="blog_public" value="1" <?php if( !isset( $_POST['blog_public'] ) || '1' == $_POST['blog_public'] ) { ?>checked="checked"<?php } ?> />
			<strong><?php _e( 'Yes' , 'buddypress'); ?></strong>
		</label>
		<label class="checkbox" for="blog_public_off">
			<input type="radio" id="blog_public_off" name="blog_public" value="0" <?php if( isset( $_POST['blog_public'] ) && '0' == $_POST['blog_public'] ) { ?>checked="checked"<?php } ?> />
			<strong><?php _e( 'No' , 'buddypress'); ?></strong>
		</label>
	</fieldset>

	<?php

	/**
	 * Fires at the end of all of the default input fields for blog creation form.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $errors WP_Error object if any present.
	 */
	do_action('signup_blogform', $errors);
}

/**
 * Process a blog registration submission.
 *
 * Passes submitted values to {@link wpmu_create_blog()}.
 *
 * @return bool True on success, false on failure.
 */
function bp_blogs_validate_blog_signup() {
	global $wpdb, $current_user, $blogname, $blog_title, $errors, $domain, $path, $current_site;

	if ( !check_admin_referer( 'bp_blog_signup_form' ) )
		return false;

	$current_user = wp_get_current_user();

	if( !is_user_logged_in() )
		die();

	$result = bp_blogs_validate_blog_form();
	extract($result);

	if ( $errors->get_error_code() ) {
		unset($_POST['submit']);
		bp_show_blog_signup_form( $blogname, $blog_title, $errors );
		return false;
	}

	$public = (int) $_POST['blog_public'];

	// Depreciated.
	$meta = apply_filters( 'signup_create_blog_meta', array( 'lang_id' => 1, 'public' => $public ) );

	/**
	 * Filters the default values for Blog meta.
	 *
	 * @since 1.0.0
	 *
	 * @param array $meta {
	 *      string $value  Default blog language ID.
	 *      string $public Default public status.
	 * }
	 */
	$meta = apply_filters( 'add_signup_meta', $meta );

	// If this is a subdomain install, set up the site inside the root domain.
	if ( is_subdomain_install() )
		$domain = $blogname . '.' . preg_replace( '|^www\.|', '', $current_site->domain );

	$blog_id = wpmu_create_blog( $domain, $path, $blog_title, $current_user->ID, $meta, $wpdb->siteid );
	bp_blogs_confirm_blog_signup( $domain, $path, $blog_title, $current_user->user_login, $current_user->user_email, $meta, $blog_id );
	return true;
}

/**
 * Validate a blog creation submission.
 *
 * Essentially, a wrapper for {@link wpmu_validate_blog_signup()}.
 *
 * @return array Contains the new site data and error messages.
 */
function bp_blogs_validate_blog_form() {
	$user = '';
	if ( is_user_logged_in() )
		$user = wp_get_current_user();

	return wpmu_validate_blog_signup($_POST['blogname'], $_POST['blog_title'], $user);
}

/**
 * Display a message after successful blog registration.
 *
 * @since 2.6.0 Introduced `$blog_id` parameter.
 *
 * @param string       $domain     The new blog's domain.
 * @param string       $path       The new blog's path.
 * @param string       $blog_title The new blog's title.
 * @param string       $user_name  The user name of the user who created the blog. Unused.
 * @param string       $user_email The email of the user who created the blog. Unused.
 * @param string|array $meta       Meta values associated with the new blog. Unused.
 * @param int|null     $blog_id    ID of the newly created blog.
 */
function bp_blogs_confirm_blog_signup( $domain, $path, $blog_title, $user_name, $user_email = '', $meta = '', $blog_id = null ) {
	switch_to_blog( $blog_id );
	$blog_url  = set_url_scheme( home_url() );
	$login_url = set_url_scheme( wp_login_url() );
	restore_current_blog();

	?>
	<p><?php _e( 'Congratulations! You have successfully registered a new site.', 'buddypress' ) ?></p>
	<p>
		<?php printf(
			'%s %s',
			sprintf(
				__( '%s is your new site.', 'buddypress' ),
				sprintf( '<a href="%s">%s</a>', esc_url( $blog_url ), esc_url( $blog_url ) )
			),
			sprintf(
				/* translators: 1: Login URL, 2: User name */
				__( '<a href="%1$s">Log in</a> as "%2$s" using your existing password.', 'buddypress' ),
				esc_url( $login_url ),
				esc_html( $user_name )
			)
		); ?>
	</p>

<?php

	/**
	 * Fires after the default successful blog registration message markup.
	 *
	 * @since 1.0.0
	 */
	do_action('signup_finished');
}

/**
 * Output a "Create a Site" link for users viewing their own profiles.
 *
 * This function is not used by BuddyPress as of 1.2, but is kept here for older
 * themes that may still be using it.
 */
function bp_create_blog_link() {

	// Don't show this link when not on your own profile.
	if ( ! bp_is_my_profile() ) {
		return;
	}

	/**
	 * Filters "Create a Site" links for users viewing their own profiles.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value HTML link for creating a site.
	 */
	echo apply_filters( 'bp_create_blog_link', '<a href="' . trailingslashit( bp_get_blogs_directory_permalink() . 'create' ) . '">' . __( 'Create a Site', 'buddypress' ) . '</a>' );
}

/**
 * Output navigation tabs for a user Blogs page.
 *
 * Currently unused by BuddyPress.
 */
function bp_blogs_blog_tabs() {

	// Don't show these tabs on a user's own profile.
	if ( bp_is_my_profile() ) {
		return false;
	} ?>

	<ul class="content-header-nav">
		<li<?php if ( bp_is_current_action( 'my-blogs'        ) || !bp_current_action() ) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit( bp_displayed_user_domain() . bp_get_blogs_slug() . '/my-blogs'        ); ?>"><?php printf( __( "%s's Sites", 'buddypress' ),           bp_get_displayed_user_fullname() ); ?></a></li>
		<li<?php if ( bp_is_current_action( 'recent-posts'    )                         ) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit( bp_displayed_user_domain() . bp_get_blogs_slug() . '/recent-posts'    ); ?>"><?php printf( __( "%s's Recent Posts", 'buddypress' ),    bp_get_displayed_user_fullname() ); ?></a></li>
		<li<?php if ( bp_is_current_action( 'recent-comments' )                         ) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit( bp_displayed_user_domain() . bp_get_blogs_slug() . '/recent-comments' ); ?>"><?php printf( __( "%s's Recent Comments", 'buddypress' ), bp_get_displayed_user_fullname() ); ?></a></li>
	</ul>

<?php

	/**
	 * Fires after the markup for the navigation tabs for a user Blogs page.
	 *
	 * @since 1.0.0
	 */
	do_action( 'bp_blogs_blog_tabs' );
}

/**
 * Output the blog directory search form.
 */
function bp_directory_blogs_search_form() {

	$query_arg = bp_core_get_component_search_query_arg( 'blogs' );

	if ( ! empty( $_REQUEST[ $query_arg ] ) ) {
		$search_value = stripslashes( $_REQUEST[ $query_arg ] );
	} else {
		$search_value = bp_get_search_default_text( 'blogs' );
	}

	$search_form_html = '<form action="" method="get" id="search-blogs-form">
		<label for="blogs_search"><input type="text" name="' . esc_attr( $query_arg ) . '" id="blogs_search" placeholder="'. esc_attr( $search_value ) .'" /></label>
		<input type="submit" id="blogs_search_submit" name="blogs_search_submit" value="' . __( 'Search', 'buddypress' ) . '" />
	</form>';

	/**
	 * Filters the output for the blog directory search form.
	 *
	 * @since 1.9.0
	 *
	 * @param string $search_form_html HTML markup for blog directory search form.
	 */
	echo apply_filters( 'bp_directory_blogs_search_form', $search_form_html );
}

/**
 * Output the Create a Site button.
 *
 * @since 2.0.0
 */
function bp_blog_create_button() {
	echo bp_get_blog_create_button();
}
	/**
	 * Get the Create a Site button.
	 *
	 * @since 2.0.0
	 *
	 * @return false|string
	 */
	function bp_get_blog_create_button() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		if ( ! bp_blog_signup_enabled() ) {
			return false;
		}

		$button_args = array(
			'id'         => 'create_blog',
			'component'  => 'blogs',
			'link_text'  => __( 'Create a Site', 'buddypress' ),
			'link_class' => 'blog-create no-ajax',
			'link_href'  => trailingslashit( bp_get_blogs_directory_permalink() . 'create' ),
			'wrapper'    => false,
			'block_self' => false,
		);

		/**
		 * Filters the Create a Site button.
		 *
		 * @since 2.0.0
		 *
		 * @param array $button_args Array of arguments to be used for the Create a Site button.
		 */
		return bp_get_button( apply_filters( 'bp_get_blog_create_button', $button_args ) );
	}

/**
 * Output the Create a Site nav item.
 *
 * @since 2.2.0
 */
function bp_blog_create_nav_item() {
	echo bp_get_blog_create_nav_item();
}

	/**
	 * Get the Create a Site nav item.
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	function bp_get_blog_create_nav_item() {
		// Get the create a site button.
		$create_blog_button = bp_get_blog_create_button();

		// Make sure the button is available.
		if ( empty( $create_blog_button ) ) {
			return;
		}

		$output = '<li id="blog-create-nav">' . $create_blog_button . '</li>';

		/**
		 * Filters the Create A Site nav item output.
		 *
		 * @since 2.2.0
		 *
		 * @param string $output Nav item output.
		 */
		return apply_filters( 'bp_get_blog_create_nav_item', $output );
	}

/**
 * Checks if a specific theme is still filtering the Blogs directory title
 * if so, transform the title button into a Blogs directory nav item.
 *
 * @since 2.2.0
 *
 * @return string|null HTML Output
 */
function bp_blog_backcompat_create_nav_item() {
	// Bail if Blogs nav item is already used by bp-legacy.
	if ( has_action( 'bp_blogs_directory_blog_types', 'bp_legacy_theme_blog_create_nav', 999 ) ) {
		return;
	}

	// Bail if the theme is not filtering the Blogs directory title.
	if ( ! has_filter( 'bp_blogs_directory_header' ) ) {
		return;
	}

	bp_blog_create_nav_item();
}
add_action( 'bp_blogs_directory_blog_types', 'bp_blog_backcompat_create_nav_item', 1000 );

/** Stats **********************************************************************/

/**
 * Display the number of blogs in user's profile.
 *
 * @since 2.0.0
 *
 * @param array|string $args Before|after|user_id.
 */
function bp_blogs_profile_stats( $args = '' ) {
	echo bp_blogs_get_profile_stats( $args );
}
add_action( 'bp_members_admin_user_stats', 'bp_blogs_profile_stats', 9, 1 );

/**
 * Return the number of blogs in user's profile.
 *
 * @since 2.0.0
 *
 * @param array|string $args Before|after|user_id.
 * @return string HTML for stats output.
 */
function bp_blogs_get_profile_stats( $args = '' ) {

	// Parse the args.
	$r = bp_parse_args( $args, array(
		'before'  => '<li class="bp-blogs-profile-stats">',
		'after'   => '</li>',
		'user_id' => bp_displayed_user_id(),
		'blogs'   => 0,
		'output'  => ''
	), 'blogs_get_profile_stats' );

	// Allow completely overloaded output.
	if ( is_multisite() && empty( $r['output'] ) ) {

		// Only proceed if a user ID was passed.
		if ( ! empty( $r['user_id'] ) ) {

			// Get the user's blogs.
			if ( empty( $r['blogs'] ) ) {
				$r['blogs'] = absint( bp_blogs_total_blogs_for_user( $r['user_id'] ) );
			}

			// If blogs exist, show some formatted output.
			$r['output'] = $r['before'] . sprintf( _n( '%s site', '%s sites', $r['blogs'], 'buddypress' ), '<strong>' . $r['blogs'] . '</strong>' ) . $r['after'];
		}
	}

	/**
	 * Filters the number of blogs in user's profile.
	 *
	 * @since 2.0.0
	 *
	 * @param string $value Output determined for the profile stats.
	 * @param array  $r     Array of arguments used for default output if none provided.
	 */
	return apply_filters( 'bp_blogs_get_profile_stats', $r['output'], $r );
}
