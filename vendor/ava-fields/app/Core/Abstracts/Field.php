<?php
namespace DS_AVAFields\Core\Abstracts;

use DS_AVAFields\Core\Options;

/**
 * Field abstract class
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
abstract class Field {
    public $type;
    public $id;
    public $value;
    public $container_id;
    public $section_id;
    public $params;
    public $html;
    public $fields_options;

    public $field_dir;
    public $field_url;

    // Default parameters for all field types
    public $default = [
        'direction' => 'ltr'
    ];

    // Default parameters for particular field
    public $custom = [];


    public function __construct( $container_id, $section_id, $id, $params ) {

        $this->id           = $id;
        $this->container_id = $container_id;
        $this->section_id   = $section_id;
        $this->params       = array_replace_recursive( $this->default, $this->custom );
        $this->params       = array_replace_recursive( $this->params, $params );
        $this->value        = isset($this->params['value']) ? $this->params['value'] : ( isset($this->params['default']) ? $this->params['default']:'' );

        $this->field_dir = DS_AVA_Fields()->dir( 'fields' ) . $this->type . '/';
        $this->field_url = DS_AVA_Fields()->url( 'fields' ) . $this->type . '/';

        $this->load_styles();
        $this->load_assets();
    }

    public function load_styles() {
        if (file_exists($this->field_dir . 'assets/styles.css')) {
            DS_AVA_Fields()->add_style( 'avaf-field-'.$this->type, $this->field_dir . 'assets/styles.css' );
        }
    }

    public function load_assets() {

    }

    /**
     * Get value for output
     *
     * @param $key
     * @param null $key2
     *
     * @return mixed
     */
    public function get_value( $key, $key2 = null ) {

        if ($this->container_id) {
            $fields_options = DS_AVA_Fields()->container( $this->container_id )->Options->fields_options;

            if (!isset($this->params['options']['map'])) {
                if (empty($key2)) {
                    return isset($fields_options[$key]) ? $fields_options[$key] : '';
                } else {
                    return isset($fields_options[$key]) && isset($fields_options[$key][$key2]) ? $fields_options[$key][$key2] : '';
                }
            } else {
                $value = Options::get_chain_options(
                    $fields_options,
                    explode('.', $this->params['options']['map'])
                );
                return $value;
            }
        } else
            return $this->value;
    }

    /**
     * Prepare value for storage
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function storage_value( $value ) {
        return $value ? $value:'';
    }



    /*
    public function get_name_attr() {
        return esc_attr( $this->section->id . '['.$this->id.']');
    }

    public function get_id_attr() {
        return esc_attr( 'avaf-'.$this->section->id . '-'.$this->id);
    }
    */

    /**
     * Render group html
     *
     * @return string
     */
    public function render() {

        $this->html = '';
        $this->build();

        if (!$this->container_id || !$this->section_id) {
            return $this->renderPageField();
        }

        if ( DS_AVA_Fields()->section( $this->container_id, $this->section_id ) instanceof \DS_AVAFields\Sections\Page ) {
            $html = $this->renderPageField();
        } else if ( DS_AVA_Fields()->section( $this->container_id, $this->section_id ) instanceof \DS_AVAFields\Sections\Meta ) {
            $html = $this->renderMetaField();
        } else if ( DS_AVA_Fields()->section( $this->container_id, $this->section_id ) instanceof \DS_AVAFields\Sections\Options ) {
            $html = $this->renderOptionsField();
        }

        return $html;
    }

    public function output() {
        echo $this->render();
    }

    public function renderPageField() {

        // Define group extra classes
        $classes = [];
        if ($this->params['direction'] == 'rtl') $classes[] = 'avaf-rtl';
        //if (!empty($this->params['atts']['class'])) $classes[] = $this->params['atts']['class'];
        $classes = !empty($classes) ? ' '.implode(' ', $classes):'';

        $group_atts = [
            'class = "avaf-group avaf-group-' . esc_attr( $this->type ) . $classes . '"',
            'data-group = "' . esc_attr( $this->id ) . '"',
            'data-type = "' . esc_attr( $this->type ) . '"'
        ];
        if (!empty($this->params['options']['map'])) {
            $group_atts[] = 'data-map = "' . esc_attr( $this->params['options']['map'] ) . '"';
        }

        $html = '<div '.implode(' ', $group_atts).'>';

        // label
        $html_label = '';
        if ( ! empty( $this->params['texts']['label'] ) ) {
            $html_label = '<label class="avaf-label" for="' . esc_attr( $this->id ) . '">';
            if ( ! empty( $this->params['texts']['label'] ) ) {
                $html_label .= $this->params['texts']['label'];

                // Tip
                if (!empty($this->params['texts']['tip'])) {
                    $html_label .= '&nbsp;<span class="avaf-tip" data-tippy-content = "' . esc_attr( $this->params['texts']['tip'] ) . '">';
                    if (isset($this->params['texts']['tip_icon'])) $html_label .= $this->params['texts']['tip_icon'];
                    $html_label .= '</span>';
                }
            }
            if ( ! empty( $this->params['texts']['subtitle'] ) ) {
                $html_label .= ' <small>' . $this->params['texts']['subtitle'] . '</small>';
            }
            $html_label .= '</label>';
        }

        // field
        $html_field = '<div class="avaf-field avaf-field-' . esc_attr( $this->type ) . '">';
        $html_field .= $this->get_before();
        $html_field .= $this->html;
        $html_field .= $this->get_after();
        $html_field .= $this->get_desc();
        $html_field .= '</div>';

        $html .= $this->params['direction'] == 'ltr' ? $html_label.$html_field : $html_field.$html_label;

        $html .= '</div>';

        return $html;
    }

    public function renderMetaField() {

        $html = '<tr class="">';

        // label
        $html .= '<th>';
        if ( ! empty( $this->params['texts']['label'] ) || ! empty( $this->params['texts']['subtitle'] ) ) {
            $html .= '<label for="email">';
            if ( ! empty( $this->params['texts']['label'] ) ) {
                $html .= $this->params['texts']['label'];
            }
            if ( ! empty( $this->params['texts']['subtitle'] ) ) {
                $html .= ' <span class="description">(' . $this->params['texts']['subtitle'] . ')</span>';
            }
            $html .= '</label>';
        }
        $html .= '</th>';

        // field
        $html .= '<td class="avaf-field avaf-field-' . esc_attr( $this->type ) . '">';
        $html .= $this->get_before();
        $html .= $this->html;
        $html .= $this->get_after();
        $html .= $this->get_desc();
        $html .= '</td>';

        $html .= '</tr>';

        return $html;
    }

    public function renderOptionsField() {

        $html = '';
        /*
        $html = '<tr class="">';

        // label
        $html .= '<th>';
        if ( ! empty( $this->params['texts']['label'] ) || ! empty( $this->params['texts']['subtitle'] ) ) {
            $html .= '<label for="email">';
            if ( ! empty( $this->params['texts']['label'] ) ) {
                $html .= $this->params['texts']['label'];
            }
            if ( ! empty( $this->params['texts']['subtitle'] ) ) {
                $html .= ' <span class="description">(' . $this->params['texts']['subtitle'] . ')</span>';
            }
            $html .= '</label>';
        }
        $html .= '</th>';

        // field
        $html .= '<td class="avaf-field avaf-field-' . esc_attr( $this->type ) . '">';
        */

        $html .= $this->get_before();
        $html .= $this->html;
        $html .= $this->get_after();
        $html .= $this->get_desc();

        /*
        $html .= '</td>';

        $html .= '</tr>';
        */

        return $html;
    }

    /**
     * Get field attributes
     *
     */
    public function get_attrs( $key='attrs') {
        if ( !isset($this->params[$key]) || empty( $this->params[$key] ) || ! is_array( $this->params[$key] ) ) {
            return '';
        }

        $attrs = [];
        foreach ( $this->params[$key] as $_key => $value ) {
            $attrs[] = esc_attr( $_key ) . ' = "' . esc_attr( $value ) . '"';
        }

        return implode( ' ', $attrs );
    }

    /**
     * Get field description
     *
     */
    public function get_desc() {
        if ( empty( $this->params['texts']['desc'] ) ) {
            return '';
        }

        return '<div class="avaf-desc">' . $this->params['texts']['desc'] . '</div>';
    }

    /**
     * Get text before
     *
     */
    public function get_before() {
        if ( empty( $this->params['texts']['before'] ) ) {
            return '';
        }

        return '<span class="avaf-before">' . $this->params['texts']['before'] . '</span>';
    }

    /**
     * Get text after
     *
     */
    public function get_after() {
        if ( empty( $this->params['texts']['after'] ) ) {
            return '';
        }

        return '<span class="avaf-after">' . $this->params['texts']['after'] . '</span>';
    }

    public function add_handler( $dir ) {
        DS_AVA_Fields()->handlers[] = $dir;
    }

    public function get_unique_id( $suffix ) {
        return DS_AVA_Fields()->container( $this->container_id )->id . '-' . DS_AVA_Fields()->section( $this->container_id, $this->section_id )->id . '-' . preg_replace( '/[^a-z0-9_-]/i', '_', $suffix );
    }

    /**
     * Render field html
     *
     * @return mixed
     */
    abstract public function build();
}