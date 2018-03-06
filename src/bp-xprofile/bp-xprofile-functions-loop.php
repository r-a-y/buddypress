<?php

/**
 * Start off the profile groups.
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_profile_groups() {
	global $profile_template;
	return $profile_template->profile_groups();
}

/**
 * Set up the profile groups.
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_the_profile_group() {
	global $profile_template;
	return $profile_template->the_profile_group();
}

/**
 * Whether or not the group has fields to display.
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_profile_group_has_fields() {
	global $profile_template;
	return $profile_template->has_fields();
}

/**
 * Output the class attribute for a field.
 *
 * @since 1.0.0
 *
 * @param mixed $class Extra classes to append to class attribute.
 *                     Pass mutiple class names as an array or
 *                     space-delimited string.
 */
function bp_field_css_class( $class = false ) {
	echo bp_get_field_css_class( $class );
}

	/**
	 * Return the class attribute for a field.
	 *
	 * @since 1.1.0
	 *
	 * @param string|bool $class Extra classes to append to class attribute.
	 * @return string
	 */
	function bp_get_field_css_class( $class = false ) {
		global $profile_template;

		$css_classes = array();

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$css_classes = array_map( 'sanitize_html_class', $class );
		}

		// Set a class with the field ID.
		$css_classes[] = 'field_' . $profile_template->field->id;

		// Set a class with the field name (sanitized).
		$css_classes[] = 'field_' . sanitize_title( $profile_template->field->name );

		// Set a class indicating whether the field is required or optional.
		if ( ! empty( $profile_template->field->is_required ) ) {
			$css_classes[] = 'required-field';
		} else {
			$css_classes[] = 'optional-field';
		}

		// Add the field visibility level.
		$css_classes[] = 'visibility-' . esc_attr( bp_get_the_profile_field_visibility_level() );

		if ( $profile_template->current_field % 2 == 1 ) {
			$css_classes[] = 'alt';
		}

		$css_classes[] = 'field_type_' . sanitize_title( $profile_template->field->type );

		/**
		 * Filters the field classes to be applied to a field.
		 *
		 * @since 1.1.0
		 *
		 * @param array $css_classes Array of classes to be applied to field. Passed by reference.
		 */
		$css_classes = apply_filters_ref_array( 'bp_field_css_classes', array( &$css_classes ) );

		/**
		 * Filters the class HTML attribute to be used on a field.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value class HTML attribute with imploded classes.
		 */
		return apply_filters( 'bp_get_field_css_class', ' class="' . implode( ' ', $css_classes ) . '"' );
	}

/**
 * Whether or not the XProfile field has data to display.
 *
 * @since 1.0.0
 *
 * @global object $profile_template
 *
 * @return mixed
 */
function bp_field_has_data() {
	global $profile_template;

	/**
	 * Filters whether or not the XProfile field has data to display.
	 *
	 * @since 2.8.0
	 *
	 * @param bool   $value            Whether or not there is data to display.
	 * @param object $profile_template Profile template object.
	 * @param string $value            Profile field being displayed.
	 * @param string $value            Profile field ID being displayed.
	 */
	return apply_filters( 'bp_field_has_data', $profile_template->field_has_data, $profile_template, $profile_template->field, $profile_template->field->id );
}

/**
 * Whether or not the XProfile field has public data to display.
 *
 * @since 1.0.0
 *
 * @global object $profile_template
 *
 * @return bool
 */
function bp_field_has_public_data() {
	global $profile_template;

	/**
	 * Filters whether or not the XProfile field has public data to display.
	 *
	 * @since 2.8.0
	 *
	 * @param bool   $value            Whether or not there is public data to display.
	 * @param object $profile_template Profile template object.
	 * @param string $value            Profile field being displayed.
	 * @param string $value            Profile field ID being displayed.
	 */
	return apply_filters( 'bp_field_has_public_data', ( ! empty( $profile_template->field_has_data ) ), $profile_template, $profile_template->field, $profile_template->field->id );
}

/**
 * Output the XProfile group ID.
 *
 * @since 1.0.0
 */
function bp_the_profile_group_id() {
	echo bp_get_the_profile_group_id();
}

	/**
	 * Return the XProfile group ID.
	 *
	 * @since 1.1.0
	 *
	 * @return int
	 */
	function bp_get_the_profile_group_id() {
		global $group;

		/**
		 * Filters the XProfile group ID.
		 *
		 * @since 1.1.0
		 *
		 * @param int $id ID for the profile group.
		 */
		return (int) apply_filters( 'bp_get_the_profile_group_id', $group->id );
	}

/**
 * Output the XProfile group name.
 *
 * @since 1.0.0
 */
function bp_the_profile_group_name() {
	echo bp_get_the_profile_group_name();
}

	/**
	 * Return the XProfile group name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_group_name() {
		global $group;

		/**
		 * Filters the XProfile group name.
		 *
		 * @since 1.0.0
		 *
		 * @param string $name Name for the profile group.
		 */
		return apply_filters( 'bp_get_the_profile_group_name', $group->name );
	}

/**
 * Output the XProfile group slug.
 *
 * @since 1.1.0
 */
function bp_the_profile_group_slug() {
	echo bp_get_the_profile_group_slug();
}

	/**
	 * Return the XProfile group slug.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_group_slug() {
		global $group;

		/**
		 * Filters the XProfile group slug.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Slug for the profile group.
		 */
		return apply_filters( 'bp_get_the_profile_group_slug', sanitize_title( $group->name ) );
	}

/**
 * Output the XProfile group description.
 *
 * @since 1.0.0
 */
function bp_the_profile_group_description() {
	echo bp_get_the_profile_group_description();
}

	/**
	 * Return the XProfile group description.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_group_description() {
		global $group;

		/**
		 * Filters the XProfile group description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $description Description for the profile group.
		 */
		return apply_filters( 'bp_get_the_profile_group_description', $group->description );
	}

/**
 * Output the XProfile group edit form action.
 *
 * @since 1.1.0
 */
function bp_the_profile_group_edit_form_action() {
	echo bp_get_the_profile_group_edit_form_action();
}

	/**
	 * Return the XProfile group edit form action.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_group_edit_form_action() {
		global $group;

		// Build the form action URL.
		$form_action = trailingslashit( bp_displayed_user_domain() . bp_get_profile_slug() . '/edit/group/' . $group->id );

		/**
		 * Filters the action for the XProfile group edit form.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value URL for the action attribute on the
		 *                      profile group edit form.
		 */
		return apply_filters( 'bp_get_the_profile_group_edit_form_action', $form_action );
	}

/**
 * Output the XProfile group field IDs.
 *
 * @since 1.1.0
 */
function bp_the_profile_group_field_ids() {
	echo bp_get_the_profile_group_field_ids();
}

	/**
	 * Return the XProfile group field IDs.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_group_field_ids() {
		global $group;

		$field_ids = '';

		if ( !empty( $group->fields ) ) {
			foreach ( (array) $group->fields as $field ) {
				$field_ids .= $field->id . ',';
			}
		}

		return substr( $field_ids, 0, -1 );
	}

/**
 * Output a comma-separated list of field IDs that are to be submitted on profile edit.
 *
 * @since 2.1.0
 */
function bp_the_profile_field_ids() {
	echo bp_get_the_profile_field_ids();
}
	/**
	 * Generate a comma-separated list of field IDs that are to be submitted on profile edit.
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_ids() {
		global $profile_template;

		$field_ids = array();
		foreach ( $profile_template->groups as $group ) {
			if ( ! empty( $group->fields ) ) {
				$field_ids = array_merge( $field_ids, wp_list_pluck( $group->fields, 'id' ) );
			}
		}

		$field_ids = implode( ',', wp_parse_id_list( $field_ids ) );

		/**
		 * Filters the comma-separated list of field IDs.
		 *
		 * @since 2.1.0
		 *
		 * @param string $field_ids Comma-separated field IDs.
		 */
		return apply_filters( 'bp_get_the_profile_field_ids', $field_ids );
	}

/**
 * Return the XProfile fields.
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_profile_fields() {
	global $profile_template;
	return $profile_template->profile_fields();
}

/**
 * Sets up the XProfile field.
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function bp_the_profile_field() {
	global $profile_template;
	return $profile_template->the_profile_field();
}

/**
 * Output the XProfile field ID.
 *
 * @since 1.1.0
 */
function bp_the_profile_field_id() {
	echo bp_get_the_profile_field_id();
}

	/**
	 * Return the XProfile field ID.
	 *
	 * @since 1.1.0
	 *
	 * @return int
	 */
	function bp_get_the_profile_field_id() {
		global $field;

		/**
		 * Filters the XProfile field ID.
		 *
		 * @since 1.1.0
		 *
		 * @param int $id ID for the profile field.
		 */
		return (int) apply_filters( 'bp_get_the_profile_field_id', $field->id );
	}

/**
 * Outputs the XProfile field name.
 *
 * @since 1.0.0
 */
function bp_the_profile_field_name() {
	echo bp_get_the_profile_field_name();
}

	/**
	 * Returns the XProfile field name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_name() {
		global $field;

		/**
		 * Filters the XProfile field name.
		 *
		 * @since 1.0.0
		 *
		 * @param string $name Name for the profile field.
		 */
		return apply_filters( 'bp_get_the_profile_field_name', $field->name );
	}

/**
 * Outputs the XProfile field value.
 *
 * @since 1.0.0
 */
function bp_the_profile_field_value() {
	echo bp_get_the_profile_field_value();
}

	/**
	 * Returns the XProfile field value.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_value() {
		global $field;

		$field->data->value = bp_unserialize_profile_field( $field->data->value );

		/**
		 * Filters the XProfile field value.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value Value for the profile field.
		 * @param string $type  Type for the profile field.
		 * @param int    $id    ID for the profile field.
		 */
		return apply_filters( 'bp_get_the_profile_field_value', $field->data->value, $field->type, $field->id );
	}

/**
 * Outputs the XProfile field edit value.
 *
 * @since 1.1.0
 */
function bp_the_profile_field_edit_value() {
	echo bp_get_the_profile_field_edit_value();
}

	/**
	 * Returns the XProfile field edit value.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_edit_value() {
		global $field;

		// Make sure field data object exists
		if ( ! isset( $field->data ) ) {
			$field->data = new stdClass;
		}

		// Default to empty value
		if ( ! isset( $field->data->value ) ) {
			$field->data->value = '';
		}

		// Was a new value posted? If so, use it instead.
		if ( isset( $_POST['field_' . $field->id] ) ) {

			// This is sanitized via the filter below (based on the field type)
			$field->data->value = $_POST['field_' . $field->id];
		}

		/**
		 * Filters the XProfile field edit value.
		 *
		 * @since 1.1.0
		 *
		 * @param string $field_value Current field edit value.
		 * @param string $type        Type for the profile field.
		 * @param int    $id          ID for the profile field.
		 */
		return apply_filters( 'bp_get_the_profile_field_edit_value', $field->data->value, $field->type, $field->id );
	}

/**
 * Outputs the XProfile field type.
 *
 * @since 1.1.0
 */
function bp_the_profile_field_type() {
	echo bp_get_the_profile_field_type();
}

	/**
	 * Returns the XProfile field type.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_type() {
		global $field;

		/**
		 * Filters the XProfile field type.
		 *
		 * @since 1.1.0
		 *
		 * @param string $type Type for the profile field.
		 */
		return apply_filters( 'bp_the_profile_field_type', $field->type );
	}

/**
 * Outputs the XProfile field description.
 *
 * @since 1.1.0
 */
function bp_the_profile_field_description() {
	echo bp_get_the_profile_field_description();
}

	/**
	 * Returns the XProfile field description.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_description() {
		global $field;

		/**
		 * Filters the XProfile field description.
		 *
		 * @since 1.1.0
		 *
		 * @param string $description Description for the profile field.
		 */
		return apply_filters( 'bp_get_the_profile_field_description', $field->description );
	}

/**
 * Outputs the XProfile field input name.
 *
 * @since 1.1.0
 */
function bp_the_profile_field_input_name() {
	echo bp_get_the_profile_field_input_name();
}

	/**
	 * Retursn the XProfile field input name.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	function bp_get_the_profile_field_input_name() {
		global $field;

		/**
		 * Filters the profile field input name.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value Value used for the name attribute on an input.
		 */
		return apply_filters( 'bp_get_the_profile_field_input_name', 'field_' . $field->id );
	}

/**
 * Returns the action name for any signup errors related to this profile field.
 *
 * In the registration templates, signup errors are pulled from the global
 * object and rendered at actions that look like 'bp_field_12_errors'. This
 * function allows the action name to be easily concatenated and called in the
 * following fashion:
 *   do_action( bp_get_the_profile_field_errors_action() );
 *
 * @since 1.8.0
 *
 * @return string The _errors action name corresponding to this profile field.
 */
function bp_get_the_profile_field_errors_action() {
	global $field;
	return 'bp_field_' . $field->id . '_errors';
}

/**
 * Displays field options HTML for field types of 'selectbox', 'multiselectbox',
 * 'radio', 'checkbox', and 'datebox'.
 *
 * @since 1.1.0
 *
 * @param array $args Specify type for datebox. Allowed 'day', 'month', 'year'.
 */
function bp_the_profile_field_options( $args = array() ) {
	echo bp_get_the_profile_field_options( $args );
}
	/**
	 * Retrieves field options HTML for field types of 'selectbox', 'multiselectbox', 'radio', 'checkbox', and 'datebox'.
	 *
	 * @since 1.1.0
	 *
	 *
	 * @param array $args {
	 *     Array of optional arguments.
	 *     @type string|bool $type    Type of datebox. False if it's not a
	 *                                datebox, otherwise 'day, 'month', or 'year'. Default: false.
	 *     @type int         $user_id ID of the user whose profile values should be
	 *                                used when rendering options. Default: displayed user.
	 * }
	 *
	 * @return string $vaue Field options markup.
	 */
	function bp_get_the_profile_field_options( $args = array() ) {
		global $field;

		$args = bp_parse_args( $args, array(
			'type'    => false,
			'user_id' => bp_displayed_user_id(),
		), 'get_the_profile_field_options' );

		/**
		 * In some cases, the $field global is not an instantiation of the BP_XProfile_Field class.
		 * However, we have to make sure that all data originally in $field gets merged back in, after reinstantiation.
		 */
		if ( ! method_exists( $field, 'get_children' ) ) {
			$field_obj = xprofile_get_field( $field->id );

			foreach ( $field as $field_prop => $field_prop_value ) {
				if ( ! isset( $field_obj->{$field_prop} ) ) {
					$field_obj->{$field_prop} = $field_prop_value;
				}
			}

			$field = $field_obj;
		}

		ob_start();
		$field->type_obj->edit_field_options_html( $args );
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

/**
 * Render whether or not a profile field is required.
 *
 * @since 1.1.0
 */
function bp_the_profile_field_is_required() {
	echo bp_get_the_profile_field_is_required();
}

	/**
	 * Return whether or not a profile field is required.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	function bp_get_the_profile_field_is_required() {
		global $field;

		$retval = false;

		if ( isset( $field->is_required ) ) {
			$retval = $field->is_required;
		}

		/**
		 * Filters whether or not a profile field is required.
		 *
		 * @since 1.1.0
		 * @since 2.8.0 Added field ID.
		 *
		 * @param bool   $retval Whether or not the field is required.
		 * @param string $value  Field ID that may be required.
		 */
		return (bool) apply_filters( 'bp_get_the_profile_field_is_required', $retval, $field->id );
	}