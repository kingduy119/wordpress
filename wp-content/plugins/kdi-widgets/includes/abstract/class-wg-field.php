<?php
/**
 * Class Fields will help to create quickly field
 */
if( ! class_exists( 'KDI_WG_Field' ) ) :
abstract class KDI_WG_Field extends WP_Widget {
    public $wg_id;
    public $wg_name;
    public $wg_class;
    public $wg_description;
    public $settings;

    public function __construct() {
        $wg_ops = array(
            'classname'                   => $this->wg_class,
            'description'                 => $this->wg_description,
            'customize_selective_refresh' => true,
            'show_instance_in_rest'       => true,
        );

        parent::__construct( $this->wg_id, $this->wg_name, $wg_ops );
    }

    public function form( $instance ) {
        if( empty( $this->settings ) ) {
            return;
        }

        foreach( $this->settings as $key => $setting ) {
            $style = isset( $setting['style'] ) ? $setting['style'] : '';
            $class = isset( $setting['class'] ) ? $setting['class'] : '';
            $value = $instance[$key] ? $instance[$key] : $setting['std'];
            
            switch( $setting['type'] ) {
                case 'text':
                    $this->field_text( $setting, $key, $style, $class, $value );
                    break;

                case 'number':
                    $this->field_number( $setting, $key, $style, $class, $value );
                    break;

                case 'select':
                    $this->field_select( $setting, $key, $style, $class, $value );
                    break;

                case 'textarea':
                    $this->field_textarea( $setting, $key, $style, $class, $value );
                    break;

                case 'checkbox':
                    $this->field_checkbox( $setting, $key, $style, $class, $value );
                    break;

                default:
                    do_action( 'kdi_widget_field_' . $setting['type'], $key, $value, $setting, $instance );
                    break;
            }
        }
    }

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
					$instance[ $key ] = empty( $new_instance[ $key ] ) ? 0 : 1;
					break;
				default:
					$instance[ $key ] = isset( $new_instance[ $key ] ) ? sanitize_text_field( $new_instance[ $key ] ) : $setting['std'];
					break;
            }
        }

        $this->flush_widget_cache();
        return $instance;
    }

    public function flush_widget_cache() {
		foreach ( array( 'https', 'http' ) as $scheme ) {
			wp_cache_delete( $this->get_widget_id_for_cache( $this->wg_id, $scheme ), 'widget' );
		}
	}

    protected function get_widget_id_for_cache( $wg_id, $scheme = '' ) {
		if ( $scheme ) {
			$wg_id_for_cache = $wg_id . '-' . $scheme;
		} else {
			$wg_id_for_cache = $wg_id . '-' . ( is_ssl() ? 'https' : 'http' );
		}

		return $wg_id_for_cache;
	}

    /************************************
     * Fileds:
     ************************************/
    public function field_text( $setting, $key, $style, $class, $value ) {
        ?>
        <div style="<?php echo $style; ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo wp_kses_post( $setting['label'] ); ?></label><?php // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
            <input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
        </div>
        <?php
    }

    public function field_select( $setting, $key, $style, $class, $value ) {
        ?>
        <div style="<?php echo $style; ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
            <select class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>">
                <?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
                    <option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    public function field_number( $setting, $key, $style, $class, $value ) {
        $step = isset( $setting['step'] ) ? $setting['step'] : '';
        ?>
        <div style="<?php echo $style; ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
            <input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
        </div>
        <?php
    }

    public function field_textarea( $setting, $key, $style, $class, $value ) {
        ?>
        <div style="<?php echo $style; ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
            <textarea class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" cols="20" rows="3"><?php echo esc_textarea( $value ); ?></textarea>
            <?php if ( isset( $setting['desc'] ) ) : ?>
                <small><?php echo esc_html( $setting['desc'] ); ?></small>
            <?php endif; ?>
        </div>
        <?php
    }

    public function field_checkbox( $setting, $key, $style, $class, $value ) {
        ?>
        <div style="<?php echo $style; ?>">
            <input class="checkbox <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
        </div>
        <?php
    }
}
endif;


