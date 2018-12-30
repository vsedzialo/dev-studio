<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Field_Icons' )) {
    /**
     * Icons field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Icons extends \DS_AVAFields\Core\Abstracts\Field
	{
		public $type = 'icons';

		public function __construct($container_id, $section_id, $id, $params) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {
            $value = $this->get_value($this->id);

            $fa = !empty($value) ? 'fa fa-'.esc_attr( $value ):'';
            $this->html = '<div class="avaf-icon"><span class="'.esc_attr( $fa ).'"></span></div>';

            $this->html .= '<div class="avaf-icons-wrapper">';
            if (!empty($this->params['data']) && is_array($this->params['data'])) {
                foreach($this->params['data'] as $key=>$icons) {
                    if (isset($icons['title'])) {
                        $this->html .= '<div class="avaf-icons-title">'.wp_kses_post($icons['title']).'</div>';
                        $icons = isset($icons['icons']) && is_array($icons['icons']) ? $icons['icons']:[];
                    }
                    $this->html .= '<div class="avaf-icons-list">';
                    foreach($icons as $icon) {
                        $this->html .= '<div><span class="fa fa-'.esc_attr( $icon ).'" data-icon="'.esc_attr( $icon ).'"></span></div>';
                    }
                    $this->html .= '</div>';
                }
            }
            $this->html .= '<div>';
		}
	}
}

