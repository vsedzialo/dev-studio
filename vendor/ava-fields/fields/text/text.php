<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Field_Text' )) {
    /**
     * Text field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Text extends \DS_AVAFields\Core\Abstracts\Field
	{
		public $type = 'text';

		public function __construct($container_id, $section_id, $id, $params) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {
            $value = $this->get_value($this->id);

            $this->html = '<input type="text" '.
                'name="' . $this->id . '" '.
                'id="' . $this->id . '" '.
                'value="' . esc_attr( $value ) . '" ' .
                $this->get_attrs() .
                '>';
		}
	}
}

