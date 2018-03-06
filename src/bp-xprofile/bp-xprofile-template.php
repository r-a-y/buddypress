<?php
/**
 * BuddyPress XProfile Template Tags.
 *
 * @package BuddyPress
 * @subpackage XProfileTemplate
 * @since 1.5.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Query for XProfile groups and fields.
 *
 * @since 1.0.0
 *
 * @global object $profile_template
 * @see BP_XProfile_Group::get() for full description of `$args` array.
 *
 * @param array|string $args {
 *     Array of arguments. See BP_XProfile_Group::get() for full description. Those arguments whose defaults differ
 *     from that method are described here:
 *     @type string|array $member_type            Default: 'any'.
 *     @type bool         $hide_empty_groups      Default: true.
 *     @type bool         $hide_empty_fields      Defaults to true on the Dashboard, on a user's Edit Profile page,
 *                                                or during registration. Otherwise false.
 *     @type bool         $fetch_visibility_level Defaults to true when an admin is viewing a profile, or when a user is
 *                                                viewing her own profile, or during registration. Otherwise false.
 *     @type bool         $fetch_fields           Default: true.
 *     @type bool         $fetch_field_data       Default: true.
 * }
 *
 * @return bool
 */
function bp_has_profile( $args = '' ) {
	global $profile_template;

	// Only show empty fields if we're on the Dashboard, or we're on a user's
	// profile edit page, or this is a registration page.
	$hide_empty_fields_default = ( ! is_network_admin() && ! is_admin() && ! bp_is_user_profile_edit() && ! bp_is_register_page() );

	// We only need to fetch visibility levels when viewing your own profile.
	if ( bp_is_my_profile() || bp_current_user_can( 'bp_moderate' ) || bp_is_register_page() ) {
		$fetch_visibility_level_default = true;
	} else {
		$fetch_visibility_level_default = false;
	}

	// Parse arguments.
	$r = bp_parse_args( $args, array(
		'user_id'                => bp_displayed_user_id(),
		'member_type'            => 'any',
		'profile_group_id'       => false,
		'hide_empty_groups'      => true,
		'hide_empty_fields'      => $hide_empty_fields_default,
		'fetch_fields'           => true,
		'fetch_field_data'       => true,
		'fetch_visibility_level' => $fetch_visibility_level_default,
		'exclude_groups'         => false, // Comma-separated list of profile field group IDs to exclude.
		'exclude_fields'         => false, // Comma-separated list of profile field IDs to exclude.
		'update_meta_cache'      => true,
	), 'has_profile' );

	// Populate the template loop global.
	$profile_template = new BP_XProfile_Data_Template( $r );

	/**
	 * Filters whether or not a group has a profile to display.
	 *
	 * @since 1.1.0
	 * @since 2.6.0 Added the `$r` parameter.
	 *
	 * @param bool   $has_groups       Whether or not there are group profiles to display.
	 * @param string $profile_template Current profile template being used.
	 * @param array  $r                Array of arguments passed into the BP_XProfile_Data_Template class.
	 */
	return apply_filters( 'bp_has_profile', $profile_template->has_groups(), $profile_template, $r );
}

/**
 * Output the visibility level of this field.
 *
 * @since 1.6.0
 */
function bp_the_profile_field_visibility_level() {
	echo bp_get_the_profile_field_visibility_level();
}

	/**
	 * Return the visibility level of this field.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_visibility_level() {
		global $field;

		// On the registration page, values stored in POST should take
		// precedence over default visibility, so that submitted values
		// are not lost on failure.
		if ( bp_is_register_page() && ! empty( $_POST['field_' . $field->id . '_visibility'] ) ) {
			$retval = esc_attr( $_POST['field_' . $field->id . '_visibility'] );
		} else {
			$retval = ! empty( $field->visibility_level ) ? $field->visibility_level : 'public';
		}

		/**
		 * Filters the profile field visibility level.
		 *
		 * @since 1.6.0
		 *
		 * @param string $retval Field visibility level.
		 */
		return apply_filters( 'bp_get_the_profile_field_visibility_level', $retval );
	}

/**
 * Echo the visibility level label of this field.
 *
 * @since 1.6.0
 */
function bp_the_profile_field_visibility_level_label() {
	echo bp_get_the_profile_field_visibility_level_label();
}

	/**
	 * Return the visibility level label of this field.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_visibility_level_label() {
		global $field;

		// On the registration page, values stored in POST should take
		// precedence over default visibility, so that submitted values
		// are not lost on failure.
		if ( bp_is_register_page() && ! empty( $_POST['field_' . $field->id . '_visibility'] ) ) {
			$level = esc_html( $_POST['field_' . $field->id . '_visibility'] );
		} else {
			$level = ! empty( $field->visibility_level ) ? $field->visibility_level : 'public';
		}

		$fields = bp_xprofile_get_visibility_levels();

		/**
		 * Filters the profile field visibility level label.
		 *
		 * @since 1.6.0
		 * @since 2.6.0 Added the `$level` parameter.
		 *
		 * @param string $retval Field visibility level label.
		 * @param string $level  Field visibility level.
		 */
		return apply_filters( 'bp_get_the_profile_field_visibility_level_label', $fields[ $level ]['label'], $level );
	}

/**
 * Return unserialized profile field data, and combine any array items into a
 * comma-separated string.
 *
 * @since 1.0.0
 *
 * @param string $value Content to maybe unserialize.
 * @return string
 */
function bp_unserialize_profile_field( $value ) {
	if ( is_serialized($value) ) {
		$field_value = @unserialize($value);
		$field_value = implode( ', ', $field_value );
		return $field_value;
	}

	return $value;
}

/**
 * Output XProfile field data.
 *
 * @since 1.2.0
 *
 * @param string|array $args Array of arguments for field data. See {@link bp_get_profile_field_data}
 */
function bp_profile_field_data( $args = '' ) {
	echo bp_get_profile_field_data( $args );
}

	/**
	 * Return XProfile field data.
	 *
	 * @since 1.2.0
	 *
	 * @param string|array $args {
	 *    Array of arguments for field data.
	 *
	 *    @type string|int|bool $field   Field identifier.
	 *    @type int             $user_id ID of the user to get field data for.
	 * }
	 * @return mixed
	 */
	function bp_get_profile_field_data( $args = '' ) {

		$r = wp_parse_args( $args, array(
			'field'   => false, // Field name or ID.
			'user_id' => bp_displayed_user_id()
		) );

		/**
		 * Filters the profile field data.
		 *
		 * @since 1.2.0
		 * @since 2.6.0 Added the `$r` parameter.
		 *
		 * @param mixed $value Profile data for a specific field for the user.
		 * @param array $r     Array of parsed arguments.
		 */
		return apply_filters( 'bp_get_profile_field_data', xprofile_get_field_data( $r['field'], $r['user_id'] ), $r );
	}

/**
 * Get all profile field groups.
 *
 * @since 2.1.0
 *
 * @return array $groups
 */
function bp_profile_get_field_groups() {

	$groups = wp_cache_get( 'all', 'bp_xprofile_groups' );
	if ( false === $groups ) {
		$groups = bp_xprofile_get_groups( array( 'fetch_fields' => true ) );
		wp_cache_set( 'all', $groups, 'bp_xprofile_groups' );
	}

	/**
	 * Filters all profile field groups.
	 *
	 * @since 2.1.0
	 *
	 * @param array $groups Array of available profile field groups.
	 */
	return apply_filters( 'bp_profile_get_field_groups', $groups );
}

/**
 * Check if there is more than one group of fields for the profile being edited.
 *
 * @since 2.1.0
 *
 * @return bool True if there is more than one profile field group.
 */
function bp_profile_has_multiple_groups() {
	$has_multiple_groups = count( (array) bp_profile_get_field_groups() ) > 1;

	/**
	 * Filters if there is more than one group of fields for the profile being edited.
	 *
	 * @since 2.1.0
	 *
	 * @param bool $has_multiple_groups Whether or not there are multiple groups.
	 */
	return (bool) apply_filters( 'bp_profile_has_multiple_groups', $has_multiple_groups );
}

/**
 * Output the tabs to switch between profile field groups.
 *
 * @since 1.0.0
 */
function bp_profile_group_tabs() {
	echo bp_get_profile_group_tabs();

	/**
	 * Fires at the end of the tab output for switching between profile field
	 * groups. This action is in a strange place for legacy reasons.
	 *
	 * @since 1.0.0
	 */
	do_action( 'xprofile_profile_group_tabs' );
}

/**
 * Return the XProfile group tabs.
 *
 * @since 2.3.0
 *
 * @return string
 */
function bp_get_profile_group_tabs() {

	// Get field group data.
	$groups     = bp_profile_get_field_groups();
	$group_name = bp_get_profile_group_name();
	$tabs       = array();

	// Loop through field groups and put a tab-lst together.
	for ( $i = 0, $count = count( $groups ); $i < $count; ++$i ) {

		// Setup the selected class.
		$selected = '';
		if ( $group_name === $groups[ $i ]->name ) {
			$selected = ' class="current"';
		}

		// Skip if group has no fields.
		if ( empty( $groups[ $i ]->fields ) ) {
			continue;
		}

		// Build the profile field group link.
		$link   = trailingslashit( bp_displayed_user_domain() . bp_get_profile_slug() . '/edit/group/' . $groups[ $i ]->id );

		// Add tab to end of tabs array.
		$tabs[] = sprintf(
			'<li %1$s><a href="%2$s">%3$s</a></li>',
			$selected,
			esc_url( $link ),
			esc_html( apply_filters( 'bp_get_the_profile_group_name', $groups[ $i ]->name ) )
		);
	}

	/**
	 * Filters the tabs to display for profile field groups.
	 *
	 * @since 1.5.0
	 *
	 * @param array  $tabs       Array of tabs to display.
	 * @param array  $groups     Array of profile groups.
	 * @param string $group_name Name of the current group displayed.
	 */
	$tabs = apply_filters( 'xprofile_filter_profile_group_tabs', $tabs, $groups, $group_name );

	return join( '', $tabs );
}

/**
 * Output the XProfile group name.
 *
 * @since 1.0.0
 *
 * @param bool $deprecated Deprecated boolean parameter.
 *
 * @return string|null
 */
function bp_profile_group_name( $deprecated = true ) {
	if ( ! $deprecated ) {
		return bp_get_profile_group_name();
	} else {
		echo bp_get_profile_group_name();
	}
}

	/**
	 * Return the XProfile group name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function bp_get_profile_group_name() {

		// Check action variable.
		$group_id = bp_action_variable( 1 );
		if ( empty( $group_id ) || ! is_numeric( $group_id ) ) {
			$group_id = 1;
		}

		// Check for cached group.
		$group = new BP_XProfile_Group( $group_id );

		/**
		 * Filters the profile group name.
		 *
		 * @since 1.0.0
		 * @since 2.6.0 Added the `$group_id` parameter
		 *
		 * @param string $name     Name of the profile group.
		 * @param int    $group_id ID of the profile group.
		 */
		return apply_filters( 'bp_get_profile_group_name', $group->name, $group_id );
	}

/**
 * Render a formatted string displaying when a profile was last updated.
 *
 * @since 1.0.0
 */
function bp_profile_last_updated() {

	$last_updated = bp_get_profile_last_updated();

	if ( empty( $last_updated ) ) {
		_e( 'Profile not recently updated.', 'buddypress' );
	} else {
		echo $last_updated;
	}
}

	/**
	 * Return a formatted string displaying when a profile was last updated.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|string
	 */
	function bp_get_profile_last_updated() {

		$last_updated = bp_get_user_meta( bp_displayed_user_id(), 'profile_last_updated', true );

		if ( ! empty( $last_updated ) ) {

			/**
			 * Filters the formatted string used to display when a profile was last updated.
			 *
			 * @since 1.0.0
			 *
			 * @param string $value Formatted last updated indicator string.
			 */
			return apply_filters( 'bp_get_profile_last_updated', sprintf( __( 'Profile updated %s', 'buddypress' ), bp_core_time_since( strtotime( $last_updated ) ) ) );
		}

		return false;
	}

/**
 * Display the current profile group ID.
 *
 * @since 1.1.0
 */
function bp_current_profile_group_id() {
	echo bp_get_current_profile_group_id();
}

	/**
	 * Return the current profile group ID.
	 *
	 * @since 1.1.0
	 *
	 * @return int
	 */
	function bp_get_current_profile_group_id() {
		$profile_group_id = bp_action_variable( 1 );
		if ( empty( $profile_group_id ) ) {
			$profile_group_id = 1;
		}

		/**
		 * Filters the current profile group ID.
		 *
		 * Possible values are admin/profile/edit/[group-id].
		 *
		 * @since 1.1.0
		 *
		 * @param int $profile_group_id Current profile group ID.
		 */
		return (int) apply_filters( 'bp_get_current_profile_group_id', $profile_group_id );
	}

/**
 * Render an avatar delete link.
 *
 * @since 1.1.0
 */
function bp_avatar_delete_link() {
	echo bp_get_avatar_delete_link();
}

	/**
	 * Return an avatar delete link.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_avatar_delete_link() {

		/**
		 * Filters the link used for deleting an avatar.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Nonced URL used for deleting an avatar.
		 */
		return apply_filters( 'bp_get_avatar_delete_link', wp_nonce_url( bp_displayed_user_domain() . bp_get_profile_slug() . '/change-avatar/delete-avatar/', 'bp_delete_avatar_link' ) );
	}

/**
 * Render an edit profile button.
 *
 * @since 1.0.0
 */
function bp_edit_profile_button() {
	bp_button( array(
		'id'                => 'edit_profile',
		'component'         => 'xprofile',
		'must_be_logged_in' => true,
		'block_self'        => true,
		'link_href'         => trailingslashit( bp_displayed_user_domain() . bp_get_profile_slug() . '/edit' ),
		'link_class'        => 'edit',
		'link_text'         => __( 'Edit Profile', 'buddypress' ),
	) );
}

/** Visibility ****************************************************************/

/**
 * Echo the field visibility radio buttons.
 *
 * @since 1.6.0
 *
 * @param array|string $args Args for the radio buttons. See {@link bp_profile_get_visibility_radio_buttons}
 */
function bp_profile_visibility_radio_buttons( $args = '' ) {
	echo bp_profile_get_visibility_radio_buttons( $args );
}
	/**
	 * Return the field visibility radio buttons.
	 *
	 * @since 1.6.0
	 *
	 * @param array|string $args {
	 *    Args for the radio buttons.
	 *
	 *    @type int    $field_id     ID of the field to render.
	 *    @type string $before       Markup to render before the field.
	 *    @type string $after        Markup to render after the field.
	 *    @type string $before_radio Markup to render before the radio button.
	 *    @type string $after_radio  Markup to render after the radio button.
	 *    @type string $class        Class to apply to the field markup.
	 * }
	 * @return string $retval
	 */
	function bp_profile_get_visibility_radio_buttons( $args = '' ) {

		// Parse optional arguments.
		$r = bp_parse_args( $args, array(
			'field_id'     => bp_get_the_profile_field_id(),
			'before'       => '<div class="radio">',
			'after'        => '</div>',
			'before_radio' => '',
			'after_radio'  => '',
			'class'        => 'bp-xprofile-visibility'
		), 'xprofile_visibility_radio_buttons' );

		// Empty return value, filled in below if a valid field ID is found.
		$retval = '';

		// Only do-the-do if there's a valid field ID.
		if ( ! empty( $r['field_id'] ) ) :

			// Start the output buffer.
			ob_start();

			// Output anything before.
			echo $r['before']; ?>

			<?php if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>

				<?php foreach( bp_xprofile_get_visibility_levels() as $level ) : ?>

					<?php printf( $r['before_radio'], esc_attr( $level['id'] ) ); ?>

					<label for="<?php echo esc_attr( 'see-field_' . $r['field_id'] . '_' . $level['id'] ); ?>">
						<input type="radio" id="<?php echo esc_attr( 'see-field_' . $r['field_id'] . '_' . $level['id'] ); ?>" name="<?php echo esc_attr( 'field_' . $r['field_id'] . '_visibility' ); ?>" value="<?php echo esc_attr( $level['id'] ); ?>" <?php checked( $level['id'], bp_get_the_profile_field_visibility_level() ); ?> />
						<span class="field-visibility-text"><?php echo esc_html( $level['label'] ); ?></span>
					</label>

					<?php echo $r['after_radio']; ?>

				<?php endforeach; ?>

			<?php endif;

			// Output anything after.
			echo $r['after'];

			// Get the output buffer and empty it.
			$retval = ob_get_clean();
		endif;

		/**
		 * Filters the radio buttons for setting visibility.
		 *
		 * @since 1.6.0
		 *
		 * @param string $retval HTML output for the visibility radio buttons.
		 * @param array  $r      Parsed arguments to be used with display.
		 * @param array  $args   Original passed in arguments to be used with display.
		 */
		return apply_filters( 'bp_profile_get_visibility_radio_buttons', $retval, $r, $args );
	}

/**
 * Output the XProfile field visibility select list for settings.
 *
 * @since 2.0.0
 *
 * @param array|string $args Args for the select list. See {@link bp_profile_get_settings_visibility_select}
 */
function bp_profile_settings_visibility_select( $args = '' ) {
	echo bp_profile_get_settings_visibility_select( $args );
}
	/**
	 * Return the XProfile field visibility select list for settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array|string $args {
	 *    Args for the select list.
	 *
	 *    @type int    $field_id ID of the field to render.
	 *    @type string $before   Markup to render before the field.
	 *    @type string $before_controls  markup before form controls.
	 *    @type string $after    Markup to render after the field.
	 *    @type string $after_controls Markup after the form controls.
	 *    @type string $class    Class to apply to the field markup.
	 *    @type string $label_class Class to apply for the label element.
	 *    @type string $notoggle_tag Markup element to use for notoggle tag.
	 *    @type string $notoggle_class Class to apply to the notoggle element.
	 * }
	 * @return string $retval
	 */
	function bp_profile_get_settings_visibility_select( $args = '' ) {

		// Parse optional arguments.
		$r = bp_parse_args( $args, array(
			'field_id'         => bp_get_the_profile_field_id(),
			'before'           => '',
			'before_controls'  => '',
			'after'            => '',
			'after_controls'   => '',
			'class'            => 'bp-xprofile-visibility',
			'label_class'      => 'bp-screen-reader-text',
			'notoggle_tag'     => 'span',
			'notoggle_class'   => 'field-visibility-settings-notoggle',
		), 'xprofile_settings_visibility_select' );

		// Empty return value, filled in below if a valid field ID is found.
		$retval = '';

		// Only do-the-do if there's a valid field ID.
		if ( ! empty( $r['field_id'] ) ) :

			// Start the output buffer.
			ob_start();

			// Output anything before.
			echo $r['before']; ?>

			<?php if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>

			<?php echo $r['before_controls']; ?>

				<label for="<?php echo esc_attr( 'field_' . $r['field_id'] ) ; ?>_visibility" class="<?php echo esc_attr( $r['label_class'] ); ?>"><?php
					/* translators: accessibility text */
					_e( 'Select visibility', 'buddypress' );
				?></label>
				<select class="<?php echo esc_attr( $r['class'] ); ?>" name="<?php echo esc_attr( 'field_' . $r['field_id'] ) ; ?>_visibility" id="<?php echo esc_attr( 'field_' . $r['field_id'] ) ; ?>_visibility">

					<?php foreach ( bp_xprofile_get_visibility_levels() as $level ) : ?>

						<option value="<?php echo esc_attr( $level['id'] ); ?>" <?php selected( $level['id'], bp_get_the_profile_field_visibility_level() ); ?>><?php echo esc_html( $level['label'] ); ?></option>

					<?php endforeach; ?>

				</select>

			<?php echo $r['after_controls']; ?>

			<?php else : ?>

				<<?php echo esc_html( $r['notoggle_tag'] ); ?> class="<?php echo esc_attr( $r['notoggle_class'] ); ?>"><?php bp_the_profile_field_visibility_level_label(); ?></<?php echo esc_html( $r['notoggle_tag'] ); ?>>

			<?php endif;

			// Output anything after.
			echo $r['after'];

			// Get the output buffer and empty it.
			$retval = ob_get_clean();
		endif;

		/**
		 * Filters the dropdown list for setting visibility.
		 *
		 * @since 2.0.0
		 *
		 * @param string $retval HTML output for the visibility dropdown list.
		 * @param array  $r      Parsed arguments to be used with display.
		 * @param array  $args   Original passed in arguments to be used with display.
		 */
		return apply_filters( 'bp_profile_settings_visibility_select', $retval, $r, $args );
	}

/**
 * Output the 'required' markup in extended profile field labels.
 *
 * @since 2.4.0
 */
function bp_the_profile_field_required_label() {
	echo bp_get_the_profile_field_required_label();
}

	/**
	 * Return the 'required' markup in extended profile field labels.
	 *
	 * @since 2.4.0
	 *
	 * @return string HTML for the required label.
	 */
	function bp_get_the_profile_field_required_label() {
		$retval = '';

		if ( bp_get_the_profile_field_is_required() ) {
			$translated_string = __( '(required)', 'buddypress' );

			$retval = ' <span class="bp-required-field-label">';
			$retval .= apply_filters( 'bp_get_the_profile_field_required_label', $translated_string, bp_get_the_profile_field_id() );
			$retval .= '</span>';

		}

		return $retval;
	}
