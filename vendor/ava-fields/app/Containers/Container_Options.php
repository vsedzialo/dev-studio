<?php
namespace DS_AVAFields\Containers;

/**
 * Options container class
 *
 * Allow to add custom options
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Container_Options extends \DS_AVAFields\Core\Abstracts\Container {

    public $option_group;

    public function __construct( $type, $params ) {
        parent::__construct( $type, $params );

        $this->option_group = str_replace('options-', '', $type);
    }

    public function load_fields_options() {

    }

    public function fields_options(  ) {
        foreach ( $this->sections as $section ) {
            foreach ( $section->fields as $field ) {

                $value = isset( $_POST[ '_' . $field->id ] ) ? sanitize_text_field($_POST[ '_' . $field->id ]) : sanitize_text_field($_POST[ $field->id ]);
                $value = $field->storage_value($value);

                update_user_meta( $user_id, sanitize_text_field($field->id), $value );
            }
        }

    }

    public function add_section( $id, $params ) {

        $section = new \DS_AVAFields\Sections\Options( $this->id, $id, $params );

        if ( $section ) {
            $this->sections[ $id ] = $section;
        }
        return $section;
    }


    public function render() {

        $this->html = '';

        foreach ( $this->sections as $section_id => $section ) {
            $this->html .= $section->render();
        }
        return $this->html;
    }

}
