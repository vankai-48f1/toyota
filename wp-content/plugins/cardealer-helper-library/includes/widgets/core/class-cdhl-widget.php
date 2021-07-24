<?php
/**
 * CDHL Widget class.
 *
 * @class CDHL_Widget
 * @package CarDealer Helper\Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CDHL_Widget
 *
 * @package CarDealer Helper\Widgets
 * @version  1.9.0
 * @extends  WP_Widget
 */
class CDHL_Widget extends WP_Widget {

	/**
	 * CSS class.
	 *
	 * @var string
	 */
	public $widget_cssclass;

	/**
	 * Widget description.
	 *
	 * @var string
	 */
	public $widget_description;

	/**
	 * Widget ID.
	 *
	 * @var string
	 */
	public $widget_id;

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $widget_name;

	/**
	 * Settings.
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => $this->widget_cssclass,
			'description'                 => $this->widget_description,
			'customize_selective_refresh' => true,
		);

		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	/**
	 * Get cached widget.
	 *
	 * @param  array $args Arguments.
	 * @return bool true if the widget is cached otherwise false
	 */
	public function get_cached_widget( $args ) {
		// Don't get cache if widget_id doesn't exists.
		if ( empty( $args['widget_id'] ) ) {
			return false;
		}

		$cache = wp_cache_get( $this->get_widget_id_for_cache( $this->widget_id ), 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( isset( $cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ] ) ) {
			echo $cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ]; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			return true;
		}

		return false;
	}

	/**
	 * Cache the widget.
	 *
	 * @param  array  $args Arguments.
	 * @param  string $content Content.
	 * @return string the content that was cached
	 */
	public function cache_widget( $args, $content ) {
		// Don't set any cache if widget_id doesn't exist.
		if ( empty( $args['widget_id'] ) ) {
			return $content;
		}

		$cache = wp_cache_get( $this->get_widget_id_for_cache( $this->widget_id ), 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		$cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ] = $content;

		wp_cache_set( $this->get_widget_id_for_cache( $this->widget_id ), $cache, 'widget' );

		return $content;
	}

	/**
	 * Flush the cache.
	 */
	public function flush_widget_cache() {
		foreach ( array( 'https', 'http' ) as $scheme ) {
			wp_cache_delete( $this->get_widget_id_for_cache( $this->widget_id, $scheme ), 'widget' );
		}
	}

	/**
	 * Get this widgets title.
	 *
	 * @param array $instance Array of instance options.
	 * @return string
	 */
	protected function get_instance_title( $instance ) {
		if ( isset( $instance['title'] ) ) {
			return $instance['title'];
		}

		if ( isset( $this->settings, $this->settings['title'], $this->settings['title']['std'] ) ) {
			return $this->settings['title']['std'];
		}

		return '';
	}

	/**
	 * Output the html at the start of a widget.
	 *
	 * @param array $args Arguments.
	 * @param array $instance Instance.
	 */
	public function widget_start( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

		$title = apply_filters( 'widget_title', $this->get_instance_title( $instance ), $instance, $this->id_base );

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Output the html at the end of a widget.
	 *
	 * @param  array $args Arguments.
	 */
	public function widget_end( $args ) {
		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see    WP_Widget->update
	 * @param  array $new_instance New instance.
	 * @param  array $old_instance Old instance.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		if ( empty( $this->settings ) ) {
			return $instance;
		}

		// Loop settings and get values to save.
		foreach ( $this->settings as $key => $setting ) {
			if ( ! isset( $setting['type'] ) ) {
				continue;
			}

			// Format the value based on settings type.
			switch ( $setting['type'] ) {
				case 'number':
					$instance[ $key ] = absint( $new_instance[ $key ] );

					if ( isset( $setting['min'] ) && '' !== $setting['min'] ) {
						$instance[ $key ] = max( $instance[ $key ], $setting['min'] );
					}

					if ( isset( $setting['max'] ) && '' !== $setting['max'] ) {
						$instance[ $key ] = min( $instance[ $key ], $setting['max'] );
					}
					break;
				case 'textarea':
					$instance[ $key ] = wp_kses( trim( wp_unslash( $new_instance[ $key ] ) ), wp_kses_allowed_html( 'post' ) );
					break;
				case 'checkbox':
					$instance[ $key ] = empty( $new_instance[ $key ] ) ? false : true;
					break;
				default:
					$instance[ $key ] = isset( $new_instance[ $key ] ) ? sanitize_text_field( $new_instance[ $key ] ) : $setting['std'];
					break;
			}

			/**
			 * Sanitize the value of a setting.
			 */
			$instance[ $key ] = apply_filters( 'cdhl_widget_settings_sanitize_option', $instance[ $key ], $new_instance, $key, $setting );
		}

		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see   WP_Widget->form
	 *
	 * @param array $instance Instance.
	 */
	public function form( $instance ) {

		if ( empty( $this->settings ) ) {
			return;
		}

		foreach ( $this->settings as $key => $setting ) {

			if ( isset( $setting['is_disabled'] ) && true === (bool) $setting['is_disabled'] ) {
				continue;
			}

			$class = isset( $setting['class'] ) ? $setting['class'] : '';
			$value = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];

			?>
			<div class="pgs-widget-field pgs-widget-field-type-<?php echo esc_attr( $setting['type'] ); ?>">
				<?php
				$value = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];
				$class = isset( $setting['class'] ) ? $setting['class'] : '';

				$field_function = 'generate_field_' . $setting['type'];
				$callback       = array( $this, $field_function );

				if ( is_callable( $callback ) ) {
					call_user_func( $callback, $key, $value, $class, $setting, $instance );
				} else {
					do_action( 'cdhl_widget_field_' . $setting['type'], $key, $value, $class, $setting, $instance, $this );
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Get widget id plus scheme/protocol to prevent serving mixed content from (persistently) cached widgets.
	 *
	 * @since  3.4.0
	 * @param  string $widget_id Id of the cached widget.
	 * @param  string $scheme    Scheme for the widget id.
	 * @return string            Widget id including scheme/protocol.
	 */
	protected function get_widget_id_for_cache( $widget_id, $scheme = '' ) {
		if ( $scheme ) {
			$widget_id_for_cache = $widget_id . '-' . $scheme;
		} else {
			$widget_id_for_cache = $widget_id . '-' . ( is_ssl() ? 'https' : 'http' );
		}

		return apply_filters( 'cdhl_cached_widget_id', $widget_id_for_cache );
	}

	/**
	 * Vehicle Dropdown categories.
	 *
	 * @param array $args Args to control display of dropdown.
	 */
	protected function vehicle_dropdown_categories( $args = array() ) {
		global $wp_query;
		global $vehicle_cat_id;

		$args = wp_parse_args(
			$args,
			array(
				'pad_counts'         => 1,
				'show_count'         => 1,
				'hierarchical'       => 1,
				'hide_empty'         => 1,
				'show_uncategorized' => 1,
				'orderby'            => 'name',
				'selected'           => isset( $wp_query->query_vars['vehicle_cat'] ) ? $wp_query->query_vars['vehicle_cat'] : '',
				'show_option_none'   => esc_html__( 'Select a category', 'cardealer-helper' ),
				'option_none_value'  => '',
				'value_field'        => 'slug',
				'taxonomy'           => 'vehicle_cat',
				'name'               => 'vehicle_cat',
				'class'              => 'vehicle-categories-dropdown',
			)
		);

		if ( 'order' === $args['orderby'] ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'order'; // phpcs:ignore
		}

		wp_dropdown_categories( $args );
	}

	/**
	 * Field Label
	 *
	 * @access protected
	 * @param string $key      Field key.
	 * @param array  $setting  Field settings.
	 * @param array  $instance Widget instance.
	 * @return void
	 */
	protected function field_label( $key, $setting, $instance ) {
		?>
		<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
		<?php
	}

	/**
	 * Field Description
	 *
	 * @access protected
	 * @param string $key      Field key.
	 * @param array  $setting  Field settings.
	 * @param array  $instance Widget instance.
	 * @return void
	 */
	protected function field_desc( $key, $setting, $instance ) {
		if ( isset( $setting['desc'] ) && ! empty( $setting['desc'] ) ) {
			?>
			<em class="widget-field-desc"><?php echo esc_html( $setting['desc'] ); ?></em>
			<?php
		}
	}

	/**
	 * Generate text field.
	 *
	 * @access protected
	 * @param string $key      Field key.
	 * @param mixed  $value    Field value.
	 * @param mixed  $class    Field class.
	 * @param array  $setting  Field settings.
	 * @param array  $instance Widget instance.
	 * @return void
	 */
	protected function generate_field_text( $key, $value, $class, $setting, $instance ) {
		$this->field_label( $key, $setting, $instance );
		?>
		<input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
		<?php
		$this->field_desc( $key, $setting, $instance );
	}

	/**
	 * Generate number field.
	 *
	 * @access protected
	 * @param string $key      Field key.
	 * @param mixed  $value    Field value.
	 * @param mixed  $class    Field class.
	 * @param array  $setting  Field settings.
	 * @param array  $instance Widget instance.
	 * @return void
	 */
	protected function generate_field_number( $key, $value, $class, $setting, $instance ) {
		$this->field_label( $key, $setting, $instance );
		?>
		<input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number" step="<?php echo esc_attr( $setting['step'] ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
		<?php
		$this->field_desc( $key, $setting, $instance );
	}

	/**
	 * Generate textarea field.
	 *
	 * @access protected
	 * @param string $key      Field key.
	 * @param mixed  $value    Field value.
	 * @param mixed  $class    Field class.
	 * @param array  $setting  Field settings.
	 * @param array  $instance Widget instance.
	 * @return void
	 */
	protected function generate_field_textarea( $key, $value, $class, $setting, $instance ) {
		$this->field_label( $key, $setting, $instance );
		?>
		<textarea class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" cols="20" rows="3"><?php echo esc_textarea( $value ); ?></textarea>
		<?php
		$this->field_desc( $key, $setting, $instance );
	}

	/**
	 * Generate select field.
	 *
	 * @access protected
	 * @param string $key      Field key.
	 * @param mixed  $value    Field value.
	 * @param mixed  $class    Field class.
	 * @param array  $setting  Field settings.
	 * @param array  $instance Widget instance.
	 * @return void
	 */
	protected function generate_field_select( $key, $value, $class, $setting, $instance ) {
		$this->field_label( $key, $setting, $instance );
		?>
		<select class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>">
			<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
				<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		$this->field_desc( $key, $setting, $instance );
	}

	/**
	 * Generate checkbox field.
	 *
	 * @access protected
	 * @param string $key      Field key.
	 * @param mixed  $value    Field value.
	 * @param mixed  $class    Field class.
	 * @param array  $setting  Field settings.
	 * @param array  $instance Widget instance.
	 * @return void
	 */
	protected function generate_field_checkbox( $key, $value, $class, $setting, $instance ) {
		?>
		<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>">
		<input class="checkbox <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
		<?php echo esc_html( $setting['label'] ); ?></label>
		<?php
		$this->field_desc( $key, $setting, $instance );
	}
}
