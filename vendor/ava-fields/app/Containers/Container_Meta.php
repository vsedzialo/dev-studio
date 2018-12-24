<?php
namespace DS_AVAFields\Containers;

/**
 * Meta container class
 *
 * Allow to add custom user options fields
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Container_Meta extends \DS_AVAFields\Core\Abstracts\Container {

    public function __construct( $type, $params ) {
        parent::__construct( $type, $params );
    }

    public function load_fields_options() {}

    public function add_section( $id, $params ) {

        $section = new \DS_AVAFields\Sections\Meta( $this->id, $id, $params );

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