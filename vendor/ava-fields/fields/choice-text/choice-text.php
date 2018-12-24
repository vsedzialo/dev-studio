<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'AVA_Field_Choice_Text' ) ) {
    /**
     * Choice Text field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Choice_Text extends \DS_AVAFields\Core\Abstracts\Field {
        
		public $type = 'choice-text';

		public function __construct( $container_id, $section_id, $id, $params ) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {

		    $value = $this->get_value($this->id);

			$this->html = '<select name="' . $this->id . '-choice" id="' . $this->id . '-choice"' . $this->get_attrs('choice-attrs') . '>';

			if ( empty($this->params['validate']) || (! $this->params['validate']['required'] && !isset($this->params['options']['']) ) ) {
				$this->html .= '<option value=""></option>';
			}

			foreach ( $this->params['choice'] as $val => $text ) {
				$selected = $value['choice'] == $val ? ' selected' : '';

				$this->html .= '<option value="' . esc_attr( $val ) . '"' . $selected . '>' . wp_kses_post( $text ) . '</option>';
			}
			$this->html .= '</select>';

            $this->html .= '<input type="text" '.
                'name="' . $this->id . '-text" '.
                'id="' . $this->id . '-text" '.
                'value="' . esc_attr( $value['text'] ) . '" ' .
                $this->get_attrs('text-attrs') .
                '>';
		}
	}
}

