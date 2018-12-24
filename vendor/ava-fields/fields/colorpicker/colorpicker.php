<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Field_Colorpicker' )) {
    /**
     * ColorPicker field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Colorpicker extends \DS_AVAFields\Core\Abstracts\Field
	{
		public $type = 'colorpicker';
        
        public $custom = [
            'custom' => [
                'layout' => 'hex', // full, rgbhex, hex
            ]
        ];

		public function __construct($container_id, $section_id, $id, $params) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function load_assets() {
            DS_AVA_Fields()->add_script( 'avaf-colorpicker', $this->field_url . 'assets/colpick/colpick.js' );
            DS_AVA_Fields()->add_style( 'avaf-colorpicker', $this->field_url . 'assets/colpick/colpick.css' );
		}

		public function build() {
			$this->html = '<div class="avaf-colorpicker" id="'.esc_attr($this->id).'" data-color="'.esc_attr($this->get_value($this->id)).'" data-layout="'.esc_attr($this->params['custom']['layout']).'"></div>';
		}

	}
}

