<?php
if (!defined( 'ABSPATH' )) {
	die( '-1' );
}

if (!class_exists( 'AVA_Field_Code_Editor' )) {
    /**
     * Code Editor field
     *
     * @category   Wordpress
     * @package    ava-fields
     * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
     * @version    Release: 1.0.0
     * @since      Class available since Release 1.0.0
     */
	class AVA_Field_Code_Editor extends \DS_AVAFields\Core\Abstracts\Field
	{
		public $type = 'code-editor';


		public function __construct($container_id, $section_id, $id, $params) {
			parent::__construct( $container_id, $section_id, $id, $params );

			$this->add_handler( $this->field_dir . 'assets/handler.js' );
		}

		public function load_assets() {
			//wp_enqueue_script( 'ava-ace-editor', $this->field_url . 'assets/ace/ace.js', [] );
			//wp_enqueue_script( 'ava-ace-mode-javascript', $this->field_url . 'assets/ace/mode-javascript.js', [] );
            
            DS_AVA_Fields()->add_style( 'avaf-codemirror', $this->field_url . 'assets/codemirror/codemirror.css' );
            DS_AVA_Fields()->add_script( 'avaf-codemirror', $this->field_url . 'assets/codemirror/codemirror.js' );
            DS_AVA_Fields()->add_script( 'avaf-codemirror-mode-javascript', $this->field_url . 'assets/codemirror/javascript.js' );
		}

		public function build() {
			$this->html = '<textarea class="avaf-code-editor" id="'.esc_attr($this->id).'">'.$this->get_value($this->id).'</textarea>';
		}

	}
}

