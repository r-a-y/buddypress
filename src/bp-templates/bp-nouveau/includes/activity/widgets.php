<?php
/**
 * BP Nouveau Activity widgets
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * A widget to display the latest activities of your community!
 *
 * @since 1.0.0
 */
class BP_Latest_Activities extends WP_Widget {
	/**
	 * Construct the widget.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$widget_ops = apply_filters(
			'bp_latest_activities', array(
				'classname'                   => 'bp-latest-activities buddypress',
				'description'                 => __( 'Display the latest updates of your community having the type(s) of your choice.', 'buddypress' ),
				'customize_selective_refresh' => true,
			)
		);

		parent::__construct( false, __( '(BuddyPress) Latest Activities', 'buddypress' ), $widget_ops );
	}

	/**
	 * Register the widget.
	 *
	 * @since 1.0.0
	 */
	public static function register_widget() {
		register_widget( 'BP_Latest_Activities' );
	}

	/**
	 * Display the widget content.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args     Widget arguments.
	 * @param  array $instance Widget settings, as saved by the user.
	 */
	public function widget( $args, $instance ) {
		// Default values
		$title      = __( 'Latest updates', 'buddypress' );
		$type       = array( 'activity_update' );
		$max        = 5;
		$bp_nouveau = bp_nouveau();

		// Check instance for a custom title
		if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];
		}

		// Allow title to be filtered
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		// Check instance for custom max number of activities to display
		if ( ! empty( $instance['max'] ) ) {
			$max = (int) $instance['max'];
		}

		// Check instance for custom activity types
		if ( ! empty( $instance['type'] ) ) {
			$type    = maybe_unserialize( $instance['type'] );
			$classes = array_map( 'sanitize_html_class', array_merge( $type, array( 'bp-latest-activities' ) ) );

			// Add classes to the container
			$args['before_widget'] = str_replace( 'bp-latest-activities', join( ' ', $classes ), $args['before_widget'] );
		}

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$reset_activities_template = null;
		if ( ! empty( $GLOBALS['activities_template'] ) ) {
			$reset_activities_template = $GLOBALS['activities_template'];
		}

		/**
		 * Globalize the activity widget arguments.
		 * @see bp_nouveau_activity_widget_query() to override
		 */
		$bp_nouveau->activity->widget_args = array(
			'max'          => $max,
			'scope'        => 'all',
			'user_id'      => 0,
			'object'       => false,
			'action'       => join( ',', $type ),
			'primary_id'   => 0,
			'secondary_id' => 0,
		);

		bp_get_template_part( 'activity/widget' );

		// Reset the globals
		$GLOBALS['activities_template']    = $reset_activities_template;
		$bp_nouveau->activity->widget_args = array();

		echo $args['after_widget'];
	}

	/**
	 * Update the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance The new instance settings.
	 * @param array $old_instance The old instance settings.
	 *
	 * @return array The widget settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['max']   = 5;
		if ( ! empty( $new_instance['max'] ) ) {
			$instance['max'] = $new_instance['max'];
		}

		$instance['type'] = maybe_serialize( array( 'activity_update' ) );
		if ( ! empty( $new_instance['type'] ) ) {
			$instance['type'] = maybe_serialize( $new_instance['type'] );
		}

		return $instance;
	}

	/**
	 * Display the form to set the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Settings for this widget.
	 *
	 * @return string HTML output.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => __( 'Latest updates', 'buddypress' ),
			'max'   => 5,
			'type'  => '',
		) );

		$title = esc_attr( $instance['title'] );
		$max   = (int) $instance['max'];

		$type = array( 'activity_update' );
		if ( ! empty( $instance['type'] ) ) {
			$type = maybe_unserialize( $instance['type'] );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'buddypress' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'max' ); ?>"><?php _e( 'Max amount to display:', 'buddypress' ); ?></label>
			<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" value="<?php echo intval( $max ); ?>" step="1" min="1" max="20" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php esc_html_e( 'Type:', 'buddypress' ); ?></label>
			<select class="widefat" multiple="multiple" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>[]">
				<?php foreach ( bp_nouveau_get_activity_filters() as $key => $name ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( in_array( $key, $type ) ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}
}
