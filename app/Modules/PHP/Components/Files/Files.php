<?php
namespace DevStudio\Modules\PHP\Components\Files;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * PHP.Files component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */

class Files extends Component {

    public $name = 'Files';
    public $title = 'Files';

    public function __construct() {

        parent::__construct();

    }
}

/**
 * PHP.Files -> Components unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Components extends Unit {

    public $name = 'Components';
    public $title = 'Components';
    public $type = 'static';

    public function data() {
    }

    public function html() {

        /******** Data ********/
        $files = get_included_files();

        $data = [];
        foreach($files as $key=>$file) {
            $component = Utils::get_file_component($file);
            $file = explode(str_replace('\\', '/', ABSPATH), str_replace('\\', '/', $file))[1];
            $cmp = !empty($component['name']) ? $component['name'] : '';

            // Exclude me
            if (DevStudio()->exclude_me() && DevStudio()->me('component', $cmp)) continue;

            $data[$cmp][] = $file;
        }

        //return print_r($data, true);

        $rows = [];
        foreach($data as $cmp=>$files) {
            $rows[] = [
                'atts' => [
                    'data-cmp = "' . esc_attr($cmp) . '"',
                ],
                'cols' => [
                    ['val' => $cmp],
                    ['val' => count($files)]
                ]
            ];
        }

        return DevStudio()->template('data/table', [
            'order' => [ ['show' => true], ['show' => true, 'type' => 'number'] ],
            'headers' => [
                ['title' => __('Component', 'dev-studio')],
                ['title' => __('Files count', 'dev-studio')],
            ],
            'rows' => $rows
        ]);

    }

}

/**
 * PHP.Files -> Included_Files unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Included_Files extends Unit {

    public $name = 'Included_Files';
    public $title = 'Included Files';
    public $type = 'static';

    public function html() {

        //return print_r(DevStudio()->exclude_me(), true);

        /******** Data ********/
        $files = get_included_files();

        foreach($files as $key=>$file) {
            $component = Utils::get_file_component($file);
            $file = explode(str_replace('\\', '/', ABSPATH), str_replace('\\', '/', $file))[1];
            $cmp = !empty($component['name']) ? $component['name'] : '';

            // Exclude me
            if (DevStudio()->exclude_me() && DevStudio()->me('component', $cmp)) continue;

            $this->data[] = [
                'atts' => [
                    'data-cmp = "' . esc_attr($cmp) . '"',
                ],
                'cols' => [
                    ['class' => 'ds-pos'],
                    ['val' => $file],
                    ['val' => $cmp]
                ]
            ];
        }


        /******** Filters ********/
        $filters = [];

        // Components
        $options = ['' => ''];
        foreach($this->data as $data) {
            $cmp = $data['cols'][2]['val'];
            $options[$cmp] = $cmp;
        }
        $filters[] = [
            'label'     => __('Component', 'dev-studio'),
            'type'      => 'select',
            'options'   => $options,
            'src'       => 'data',
            'src_data'  => 'cmp'
        ];

        return DevStudio()->template('data/table', [
            'panel' => [
                'filters' => $filters
            ],
            'order' => [ [], [], ['show' => true] ],
            'headers' => [
                ['title' => '#'],
                ['title' => __('File', 'dev-studio')],
                ['title' => __('Component', 'dev-studio')],
            ],
            'rows' => $this->data
        ]);

    }

}
