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
class Options extends Container_Options {

    public function __construct( $type, $params ) {
        parent::__construct( $type, $params );
    }

    public function load_fields_options() {
        $user_meta = get_user_meta($this->WP_User->data->ID);

        foreach($user_meta as $key=>$arr) {
            $this->Options->fields_options[ $key ] = $arr[0];
        }
    }

    function save( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }

        foreach ( $this->sections as $section ) {
            foreach ( $section->fields as $field ) {

                $value = isset( $_POST[ '_' . $field->id ] ) ? sanitize_text_field($_POST[ '_' . $field->id ]) : sanitize_text_field($_POST[ $field->id ]);
                $value = $field->storage_value($value);

                update_user_meta( $user_id, sanitize_text_field($field->id), $value );
            }
        }
    }

    public function output( $WP_User=null ) {

        $this->WP_User = $WP_User;
        $this->load_fields_options();

        echo $this->render();
    }
}