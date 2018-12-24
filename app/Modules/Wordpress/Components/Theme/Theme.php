<?php
namespace DevStudio\Modules\Wordpress\Components\Theme;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * Wordpress.Theme component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Theme extends Component {

	public $name = 'Theme';
    public $title = 'Theme';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Theme -> Menu_Locations unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Menu_Locations extends Unit {

    public $name = 'Menu_Locations';
    public $title = 'Menu Locations';

    public function data() {
        $this->data = get_nav_menu_locations();
    }

    public function html() {

        $rows = [];
        foreach ($this->data as $key => $value) {
            $rows[] = [
                'cols' => [
                    ['val' => $key, 'style' => 'width:200px'],
                    ['val' => $value]
                ]
            ];
        }

        return DevStudio()->template('data/table', [
            'headers' => [
                ['title' => __('Location', 'dev-studio')],
                ['title' => __('ID', 'dev-studio')],
            ],
            'rows' => $rows
        ]);
    }
}

/**
 * Wordpress.Theme -> Menus unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Menus extends Unit {

    public $name = 'Menus';
    public $title = 'Menus';

    public function data() {
        $this->data = wp_get_nav_menus();
    }

    public function html() {

        $html = '';
        foreach ($this->data as $key=>$arr) {
            $arr = (array)$arr;

            $rows = [];
            foreach ($arr as $_key => $_value) {
                $col1 = ['val' => $_key, 'style' => 'width:200px'];
                $col2 = ['val' => $_value];

                if ($_key === 'name') $name = $_value;
                if ($_key === 'slug') $slug = $_value;

                if ($_key === 'count' && $_value) {
                    $items = wp_get_nav_menu_items($slug);
                    $info_rows = [];
                    foreach($items as $obj) {
                        $info_rows[] = [
                            'cols' => [
                                ['val' => $obj->ID],
                                ['val' => $obj->menu_item_parent],
                                ['val' => $obj->type],
                                ['val' => $obj->type_label],
                                ['val' => $obj->title],
                                ['val' => $obj->url],
                                ['val' => $obj->target],
                                ['val' => $obj->attr_title]
                            ]
                        ];
                    }

                    $col2['info'] = DevStudio()->template('data/table', [
                        'title' => __('Menu:', 'dev-studio').' '.$name,
                        'headers' => [
                            ['title' => __('ID', 'dev-studio')],
                            ['title' => __('Parent Item', 'dev-studio')],
                            ['title' => __('Type', 'dev-studio')],
                            ['title' => __('Label', 'dev-studio')],
                            ['title' => __('Title', 'dev-studio')],
                            ['title' => __('URL', 'dev-studio')],
                            ['title' => __('Target', 'dev-studio')],
                            ['title' => __('Attr title', 'dev-studio')],
                        ],
                        'rows' => $info_rows
                    ]);;
                }

                $rows[] = [
                    'cols' => [ $col1, $col2 ]
                ];
            }

            $html .= DevStudio()->template('data/table', [
                'title' => $arr['name'],
                'id'    => $key,
                'rows'  => $rows
            ]);
        }
        return $html;
    }

}

/**
 * Wordpress.Theme -> Sidebars unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Sidebars extends Unit {

    public $name = 'Sidebars';
    public $title = 'Sidebars';

    public function data() {
        global $wp_registered_sidebars;

        $this->data = $wp_registered_sidebars;
    }

    public function html() {

        //return print_r($this->data, true);

        $widgets = wp_get_sidebars_widgets();

        $html = '';
        foreach ($this->data as $key=>$sidebar) {
            $rows = [];
            foreach ($sidebar as $_key => $_value) {
                if ($_key === 'name') $name = $_value;
                if ($_key === 'id') $id = $_value;
                $rows[] = [
                    'cols' => [
                        ['val' => $_key, 'style' => 'width:200px'],
                        ['val' => $_value],
                    ]
                ];
            }

            // Widgets
            $col1 = ['val' => __('Widgets', 'dev-studio'), 'style' => 'font-weight:bold'];
            if (!empty($widgets[$id])) {
                $col2 = [
                    'val' => count($widgets[$id]),
                    'info' => DevStudio()->template('data/simple-array', [
                        'title' => __('Widgets', 'dev-studio'),
                        'rows' => Utils::get_simple_array($widgets[$id])
                    ])
                ];
            } else
                $col2 = ['val' => '-'];

            $rows[] = [
                'cols' => [ $col1, $col2 ]
            ];

            $html .= DevStudio()->template('data/table', [
                'title' => $name,
                'headers' => [
                    [ 'title' => __('Attribute', 'dev-studio')],
                    [ 'title' => __('Value', 'dev-studio')],
                ],
                'rows' => $rows
            ]);
        }
        return $html;
    }

}

/**
 * Wordpress.Theme -> Widgets unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Widgets extends Unit {

    public $name = 'Widgets';
    public $title = 'Widgets';

    public function encode_data($data) {
        return serialize($data);
    }

    public function decode_data($data) {
        return unserialize($data);
    }

    public function data() {
        global $wp_widget_factory;

        if (!empty($wp_widget_factory) && is_object($wp_widget_factory)) {
            $this->data = $wp_widget_factory;
        }
    }

    public function html() {

        if (is_object($this->data) && isset($this->data->widgets)){
            $rows = [];
            foreach ($this->data->widgets as $widget => $object) {
                $rows[] = [
                    'class' => 'info',
                    'cols' => [
                        ['val' => $widget],
                        ['val' => $object->name],
                        ['val' => $object->id_base],
                    ],
                    'info' => DevStudio()->template('data/object', $object)
                ];
            }

            $html = DevStudio()->template('data/table', [
                'headers' => [
                    [ 'title' => __('Object', 'dev-studio') ],
                    [ 'title' => __('Name', 'dev-studio') ],
                    [ 'title' => __('ID_Base', 'dev-studio') ]
                ],
                'rows' => $rows
            ]);

            return $html;
        }
    }
}
