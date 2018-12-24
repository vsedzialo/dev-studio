<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Field_Editor' )) {
    /**
     * Editor field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Editor extends \DS_AVAFields\Core\Abstracts\Field
	{
		public $type = 'editor';

		public function __construct($container_id, $section_id, $id, $params) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function build() {

			ob_start();

			wp_editor( $this->get_value($this->id),  $this->id, [
				'media_buttons' => false,
				'editor_height' => 200,
				//'quicktags'     => false
				//'quicktags' => [ 'buttons' => 'strong,em,del,ul,ol,li,close' ]
			] );

			$this->html = ob_get_clean();
		}
	}
}

