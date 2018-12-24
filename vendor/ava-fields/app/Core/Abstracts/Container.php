<?php
namespace DS_AVAFields\Core\Abstracts;

use DS_AVAFields\Core\Options;
use DS_AVAFields\Core\Utils;

/**
 * Container abstract class
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
abstract class Container {

    public $id;
    public $type;
    public $params;

    public $schema = [

        'container' => [
            'type' => ['custom'],
            'id' => '{id}',
            'title' => '{string|empty}',
            'subtitle' => '{string|empty}',
            'section_tabs' => 'true|false'
        ],
        'menu' => [
            'parent' => '{string|empty}',
            'post_type' => '{string|empty}',
            'menu_title' => '{string|empty}',
            'menu_slug' => '{slug|empty}',
        ],

        'options' => [
            'option_name' => '{slug}'
        ],

        'access' => [
            'user_capability' => 'manage_options',

            'user_id' => [
                'value' => 1,
                'except' => true
            ],

            'user_role' => 'administrator',

            // post_meta
            'post_format' => '',
            'post_id' => '',
            'post_level' => '',
            'post_ancestor_id' => '',
            'post_template' => '',
            'post_term' => '',
            'post_type' => '',

            // term_meta
            'term' => '',
            'term_parent' => '',
            'term_level' => '',
            'term_ancestor' => '',
            'term_taxonomy' => '',

            // theme_options
            'blog_id' => '',
       ]
    ];

    // Global default parameters for all types of container
    public $global_default = [
        'container' => [
            'section_tabs' => true
        ],
        'appearance' => [
            'nav' => true,
            'nav_style' => 'vertical',
        ],
        'control' => [
            'btn-save' => [
                'class' => 'avaf-button-primary avaf-save'
            ],
            'btn-reset-section' => [
                'class' => ' avaf-reset-section'
            ],
            'btn-reset' => [
                'class' => 'avaf-reset'
            ]
        ]
    ];

    // Container default parameters
    public $default = [];

    public $sections;

    public $html;

    public $Options;




    public function __construct( $type, $params=[] ) {

        // Prepare global default parameters
        $this->prepare_global_default_params();

        // Merge global default and container default parameters
        $default = Utils::params_default( $this->default, $this->global_default );

        $params = Utils::params_default( $params, $default );

        $this->id = $params['container']['id'];
        $this->type = $type;
        $this->params = $params;

        $this->Options = new Options( $params );
    }

    public function prepare_global_default_params() {
        $gd = $this->global_default;

        $gd['control']['btn-save']['text'] = __('Save Changes', 'dev-studio');
        $gd['control']['btn-reset-section']['text'] = __('Reset Section', 'dev-studio');
        $gd['control']['btn-reset']['text'] = __('Reset All', 'dev-studio');

        $this->global_default = $gd;
    }


    abstract public function load_fields_options();

    // Return $fields_options
    public function fields_options() {
        return $this->Options->fields_options;
    }

    //abstract public function get_options();

    abstract public function add_section( $id, $params );

    abstract public function render();

    public function output( $arg=null ) {
        echo $this->render();
    }
}