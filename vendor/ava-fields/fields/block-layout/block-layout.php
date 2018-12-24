<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'AVA_Field_Block_Layout' ) ) {
    /**
     * Block Layout field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Block_Layout extends \DS_AVAFields\Core\Abstracts\Field {

		public $type = 'block-layout';
        
        public $custom = [
            'custom' => [
            ],
            //'value' => '{"margin-top": 1,"margin-right":2,"margin-bottom":3,"margin-left":4,"border-top":5,"border-right":6,"border-bottom":7,"border-left":8,"padding-top":9,"padding-right":10,"padding-bottom":11,"padding-left":12}'
        ];

		public function __construct( $container_id, $section_id, $id, $params ) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {

            $field_value = $this->get_value( $this->id );
            $field_value = @json_decode($field_value, true);
            $field_value = array_replace_recursive( [
                'margin-top' => '', 'margin-right' => '', 'margin-bottom' => '', 'margin-left' => '',
                'border-top' => '', 'border-right' => '', 'border-bottom' => '', 'border-left' => '',
                'padding-top' => '', 'padding-right' => '', 'padding-bottom' => '', 'padding-left' => ''
            ], (array)$field_value );

            $this->html = '<div class="avaf-block-layout">';
                //---- margin ----->
                $this->html .= '<div class="bl-margin">';
                    $this->html .= '<div class="legend">'.__('margin', 'dev-studio').'</div>';
                    $this->html .= '<div class="bl-row bl-row1"><input type="text" name="bl-margin-top" value="' . esc_attr( $field_value['margin-top'] ) . '"></div>';
                    $this->html .= '<div class="bl-row bl-row2">';
                        $this->html .= '<div><input type="text" name="bl-margin-left" value="' . esc_attr( $field_value['margin-left'] ) . '"></div>';
                        //---- border ----->
                        $this->html .= '<div class="bl-border">';
                            $this->html .= '<div class="legend">'.__('border', 'dev-studio').'</div>';
                            $this->html .= '<div class="bl-row bl-row1"><input type="text" name="bl-border-top" value="' . esc_attr( $field_value['border-top'] ) . '"></div>';
                            $this->html .= '<div class="bl-row bl-row2">';
                                $this->html .= '<div><input type="text" name="bl-border-left" value="' . esc_attr( $field_value['border-left'] ) . '"></div>';
                                //---- padding ----->
                                $this->html .= '<div class="bl-padding">';
                                    $this->html .= '<div class="legend">'.__('padding', 'dev-studio').'</div>';
                                    $this->html .= '<div class="bl-row bl-row1"><input type="text" name="bl-padding-top" value="' . esc_attr( $field_value['padding-top'] ) . '"></div>';
                                    $this->html .= '<div class="bl-row bl-row2">';
                                        $this->html .= '<div><input type="text" name="bl-padding-left" value="' . esc_attr( $field_value['padding-left'] ) . '"></div>';
                                        $this->html .= '<div class="bl-center"></div>';
                                        $this->html .= '<div><input type="text" name="bl-padding-right" value="' . esc_attr( $field_value['padding-right'] ) . '"></div>';
                                    $this->html .= '</div>';
                                    $this->html .= '<div class="bl-row bl-row3"><input type="text" name="bl-padding-bottom" value="' . esc_attr( $field_value['padding-bottom'] ) . '"></div>';
                                $this->html .= '</div>';
                                //---- /padding ----->
                                $this->html .= '<div><input type="text" name="bl-border-right" value="' . esc_attr( $field_value['border-right'] ) . '"></div>';
                            $this->html .= '</div>';
                            $this->html .= '<div class="bl-row bl-row3"><input type="text" name="bl-border-bottom" value="' . esc_attr( $field_value['border-bottom'] ) . '"></div>';
                        $this->html .= '</div>';
                        //---- /border ----->
                        $this->html .= '<div><input type="text" name="bl-margin-right" value="' . esc_attr( $field_value['margin-right'] ) . '"></div>';
                    $this->html .= '</div>';
                    $this->html .= '<div class="bl-row bl-row3"><input type="text" name="bl-margin-bottom" value="' . esc_attr( $field_value['margin-bottom'] ) . '"></div>';
                $this->html .= '</div>';
                //---- /margin ----->
                    
            $this->html .= '</div>';

            /*
            // Select
			$this->html = '<select name="' . $this->id . '-type" id="' . $this->id . '-type">';

			if ( ! $this->params['validate']['required'] ) {
				$this->html .= '<option value=""></option>';
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
            $this->html .= '<br><input type="text" class="block-width-pixel" name="' . $this->id . '-width" id="' . $this->id . '-pixel" value="' . esc_attr( $field_value['pixel'] ) . '" ><span class="avaf-after">'.__('px', 'dev-studio').'</span>';
            $this->html .= '&nbsp;<input type="text" class="block-width-percent" name="' . $this->id . '-width" id="' . $this->id . '-percent" value="' . esc_attr( $field_value['percent'] ) . '" ><span class="avaf-after">%</span>';
            */
		}

	}
}

