<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'AVA_Field_Select' ) ) {
    /**
     * Select field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Select extends \DS_AVAFields\Core\Abstracts\Field {
        
		public $type = 'select';


		public function __construct( $container_id, $section_id, $id, $params ) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {
			$this->html = '<select name="' . $this->id . '" id="' . $this->id . '"' . $this->get_attrs() . '>';

			if ( empty($this->params['validate']) || (! $this->params['validate']['required'] && !isset($this->params['options']['']) ) ) {
				$this->html .= '<option value=""></option>';
			}

			foreach ( $this->params['items'] as $value => $data ) {
				$selected = $this->get_value( $this->id ) == $value ? ' selected' : '';
				
				$text = '';
                $disabled = false;
                
				if (is_array($data)) {
                    if (isset($data['text'])) $text = $data['text'];
                    if (isset($data['disabled'])) $disabled = $data['disabled'];
                } else {
                    $text = $data;
                }

				$this->html .= '<option value="' . esc_attr( $value ) . '"' . $selected . ($disabled ? ' disabled':''). '>' . wp_kses_post( $text ) . '</option>';
			}
			$this->html .= '</select>';
		}
	}
}

