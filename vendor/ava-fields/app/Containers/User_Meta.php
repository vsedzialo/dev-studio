<?php
namespace DS_AVAFields\Containers;

/**
 * User Meta container class
 *
 * Allow to add custom user options fields
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class User_Meta extends Container_Meta {

    public $WP_User;

    public function __construct( $type, $params ) {

        parent::__construct( $type, $params );

        add_action( 'show_user_profile', [ $this, 'output' ], PHP_INT_MAX );
        add_action( 'edit_user_profile', [ $this, 'output' ], PHP_INT_MAX );
        //add_action( 'edit_user_profile_update', [ $this, 'output' ], PHP_INT_MAX );
        add_action( 'personal_options_update', [ $this, 'save' ] );
        add_action( 'edit_user_profile_update', [ $this, 'save' ] );
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