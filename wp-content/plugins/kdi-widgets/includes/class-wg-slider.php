<?php

if( ! class_exists( 'KDI_WG_Slider' ) ) {
    class KDI_WG_Slider extends KDI_WG_Field {

        public function __construct() {
            $this->wg_id            = 'kdi_wg_slider';
            $this->wg_class         = 'kdi-wg-slider';
            $this->wg_name          = __( 'KDI Slider', 'kdi' );
            $this->wg_description   = __( 'Slider widget', 'kdi' );

            parent::__construct();
        }

        public function widget( $args, $instance ) {
            extract( $args );
    
            echo $before_widget;
            
            // $temp_1 = 'includes/templates/slider.php';
            // $temp_2 = 'includes/templates/carousel.php';
            // $temp_3 = 'includes/templates/gallery.php';

            // $template_args = array();
            // kdi_get_template_part( $temp_1, $template_args );
            // kdi_get_template_part( $temp_2, $template_args );
            // kdi_get_template_part( $temp_3, $template_args );
            
            echo $after_widget;
        }
    }
}
