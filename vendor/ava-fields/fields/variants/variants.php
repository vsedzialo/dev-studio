<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'AVA_Field_Variants' ) ) {
    /**
     * Variants field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Variants extends \DS_AVAFields\Core\Abstracts\Field {
        
		public $type = 'variants';
        
        public $custom = [];

		public function __construct( $container_id, $section_id, $id, $params ) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

        
		public function build() {

            $this->html = '<div class="avaf-variants">';
            
            foreach ( $this->params['items'] as $value => $variant ) {
                
                $active = $this->get_value( $this->id ) == $value ? ' active' : '';
                
                $this->html .= '<div class="avaf-variant'. $active.'" data-value="'.esc_attr($value).'" data-group="'.esc_attr($this->id).'">';
                    $this->html .= $variant;
                $this->html .= '</div>';
            }
            
            $this->html .= '<input type="hidden" value="'.esc_attr($value).'">';
            
            $this->html .= '</div>';
		}
	}
}

