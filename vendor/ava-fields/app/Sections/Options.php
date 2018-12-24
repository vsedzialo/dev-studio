<?php
namespace DS_AVAFields\Sections;

/**
 * Options section class
 *
 * Allow to add sections to options container
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Options extends \DS_AVAFields\Core\Abstracts\Section {

    /**
     * Constructor.
     *
     * @param $params
     */
    public function __construct( $container_id, $id, $params ) {
        parent::__construct( $container_id, $id, $params );

        add_action( 'admin_init', [ $this, 'admin_init' ] );

    }

    public function admin_init( ) {
        // Save fields separately
        if (empty($this->option_group)) return;

        foreach($this->fields as $field) {
            register_setting( $this->option_group, $field->id );
            add_settings_field( $field->id, $field->params['texts']['label'], [$field, 'output'], $this->option_group );
        }
    }

    public function section_callback( $args ) {
    }

    public function render( $args ) {

        $this->html = '<h2>'.$this->params['title'].'</h2>';
        if (!empty($this->params['desc'])) $this->html .= '<h4>'.$this->params['desc'].'</h4>';

        $this->html	.= '<table id="" class="avaf-options form-table"><tbody>';

            foreach ( $this->fields as $field_id => $field ) {
                $this->html .= $field->render();
            }

        $this->html	.= '</tbody></table>';

        return $this->html;
    }

    public function this() {
        return $this;
    }
}