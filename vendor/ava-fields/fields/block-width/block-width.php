<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'AVA_Field_Block_Width' ) ) {
    /**
     * Block Width field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Block_Width extends \DS_AVAFields\Core\Abstracts\Field {

		public $type = 'block-width';
        
        public $custom = [
            'custom' => [
            ],
            'value' => '{"type":"full","pixels":99,"percents":53}'
        ];

		public function __construct( $container_id, $section_id, $id, $params ) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {
            $field_value = $this->get_value( $this->id );
            $field_value = @json_decode($field_value, true);
            $field_value = array_replace_recursive( ['type' => '', 'pixels' => '', 'percents' => ''], (array)$field_value );

            // Select
			$this->html = '<select name="' . $this->id . '-type" id="' . $this->id . '-type">';

			if ( empty($this->params['validate']) || !$this->params['validate']['required'] ) {
				//$this->html .= '<option value=""></option>';
			}

			$this->params['options'] = [
                'full' => __('Full width', 'dev-studio'),
                'fixed' => __('Fixed width', 'dev-studio'),
            ];

			foreach ( $this->params['options'] as $value => $text ) {
				$selected = $field_value['type'] == $value ? ' selected' : '';

				$this->html .= '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . wp_kses_post( $text ) . '</option>';
			}
			$this->html .= '</select>';
            
            // Width (px)
            $this->html .= '&nbsp;<input type="text" class="block-width-pixels" name="' . $this->id . '-pixels" id="' . $this->id . '-pixels" value="' . esc_attr( $field_value['pixels'] ) . '" ><span class="avaf-after">'.__('px', 'dev-studio').'</span>';
            $this->html .= '<input type="text" class="block-width-percents" name="' . $this->id . '-percents" id="' . $this->id . '-percents" value="' . esc_attr( $field_value['percents'] ) . '" ><span class="avaf-after">%</span>';
		}
	}
}

