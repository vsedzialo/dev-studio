<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Field_Checkbox' )) {
    /**
     * Checkbox field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Checkbox extends \DS_AVAFields\Core\Abstracts\Field
	{
		public $type = 'checkbox';


		public function __construct($container_id, $section_id, $id, $params) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {
			$checked = $this->get_value($this->id) === 'yes' ? ' checked':'';

            $this->html = '<input type="checkbox" name="' . $this->id . '" id="' . $this->id . '" ' . $this->get_attrs() . $checked . '>';
		}

		public function storage_value( $value ) {
			return \DS_AVAFields\Core\Utils::yes_no($value);
		}
	}
}

