<?php
namespace DS_AVAFields\Core;

/**
 * Options class
 *
 * Contain methods for work with options
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Options {

    public $options = [];

    public $fields_options;

    public $default = [
        'storage'     => 'db',
        'save_as'     => 'array', // array | row, default - array,
        'save_mode'   => 'section' // container, section, field
    ];

    public function __construct( $params ) {

        if (isset($params['options'])) {
            $this->options = Utils::params_default( $params['options'], $this->default );
        }

        // Moved to function $container->load_fields_options()
        //if ( $this->options['save_as'] == 'array' ) {
            //$this->fields_options = self::get_options( $this->options['option_name'] );
        //}
    }

    public static function save() {

        $response = [
            'result' => 'ok',
        ];

        $option_name = sanitize_text_field($_REQUEST['option_name']);
        $options = $_REQUEST['options'];

        $map = isset($_REQUEST['map']) ? $_REQUEST['map']:[];

        if (isset($_REQUEST['option_format']) && $_REQUEST['option_format']==='json') {
            $options = json_decode(sanitize_text_field($_REQUEST['options']));
        }
        self::set_options( $option_name, $options, $map );

        wp_send_json($response);
        exit;
    }

    // Get value by key
    public function get( $field, $deafult = '' ) {

        // Get from Array
        if ( $this->options['save_as'] == 'array' ) {

            $value = $this->get_value( $field, $this->field_options );

            if ( ! empty( $value ) ) {
                return $value;
            } else if ( ! empty( $deafult ) ) {
                return $deafult;
            } else {
                return '';
            }
        }
    }

    static function get_options( $option_name )
    {
        if (preg_match('#\.#', $option_name)) {
            $e = explode('.', $option_name);

            $options = get_option($e[0]);
            unset($e[0]);

            foreach ($e as $key) {
                if (isset( $options[$key] )) {
                    $options = $options[$key];
                } else {
                    return [];
                }
            }
            return $options;
        } else {
            return get_option($option_name);
        }
    }

    static function get_chain_options( &$options, $chain )
    {
        foreach ($chain as $key) {
            if (!isset($options[$key])) return '';
            $options = &$options[$key];
        }
        return $options;
    }

    static function set_chain_options( &$options, $chain, $item, $value )
    {
        foreach ($chain as $key) {
            //if (!isset($options[$key])) $options[$key] = [];
            $options = &$options[$key];
        }
        $options = $value;
    }

    static function set_options( $option_name, $options_value, $map=[] )
    {
        // Get option name
        if (preg_match('#\.#', $option_name)) {
            $opt_name_chain = explode('.', $option_name);
            $opt_name = $opt_name_chain[0];
        } else {
            $opt_name = $option_name;
        }

        // Read current options
        $options = get_option($option_name);
        if (empty($options)) $options = [];

        // Get options items array
        foreach($options_value as $item=>$value) {
            if (isset($map[$item])) {
                $item_dots = $map[$item];
            } else {
                $item_dots = $item;
            }

            $chain_item = explode('.', $option_name.'.'.$item_dots);
            unset($chain_item[0]);
            self::set_chain_options($options, $chain_item, $item, $value);
        }

        update_option($opt_name, $options);

        return $options;
    }
}