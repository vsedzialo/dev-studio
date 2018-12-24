<?php
namespace DevStudio\Modules\Wordpress\Components\Filters;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * Wordpress.Filters component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Filters extends Component {

	public $name = 'Filters';
    public $title = 'Filters';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Filters -> Filters unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Filters extends Unit {

    public $name = 'Filters';
    public $title = 'Filters';

    public static function process_action($filter_name, array $wp_filter, $hide_qm = false, $hide_core = false) {

        $callbacks = $components = [];

        if (isset($wp_filter[$filter_name])) {

            # http://core.trac.wordpress.org/ticket/17817
            $action = $wp_filter[$filter_name];

            foreach ($action as $priority => $_callbacks) {

                foreach ($_callbacks as $callback) {

                    $callback = Utils::callback_data($callback);

                    if (isset($callback['component'])) {

                        // Exclude me
                        if (DevStudio()->exclude_me() && DevStudio()->me('component', $callback['component']['name'])) continue;

                        $components[$callback['component']['name']] = $callback['component']['name'];
                    }

                    // This isn't used and takes up a ton of memory:
                    unset($callback['function']);

                    $callbacks[] = [
                        'priority' => $priority,
                        'callback' => $callback,
                    ];

                }
            }
        }

        if (empty($callbacks)) return null;

        // Form info data
        $info = [];
        foreach ($callbacks as $callback) {
            $info[] = [
                ['val' => $callback['priority']],
                ['val' =>
                    [
                        $callback['callback']['name'],
                        !empty($callback['callback']['file']) ? '[[ds-gray]]' . $callback['callback']['file'] . ', line ' . $callback['callback']['line'] . '[[/ds-gray]]' : ''
                    ],
                ],
                ['val' => $callback['callback']['accepted_args']],
                ['val' => isset($callback['callback']['component']) ? $callback['callback']['component']['name'] : '']
            ];
        }

        $return = [
            'filter_name' => $filter_name,
            'callbacks' => $callbacks,
            'components' => $components,
            'info' => $info
        ];

        return $return;
    }

    public function data() {
        global $wp_filter;

        if (isset($wp_filter) && !empty($wp_filter)) {
            $this->data = [];
            foreach ($wp_filter as $filter => $wp_hook) {

                // Exclude me
                if (DevStudio()->exclude_me() && DevStudio()->me('filter', $filter)) continue;

                $data = self::process_action($filter, $wp_filter);
                if ($data) $this->data[] = $data;
            }
        }

    }


    public function html() {

        $rows = [];
        foreach ($this->data as $filter) {

            $rows[] = [
                'class' => 'info',
                'cols' => [
                    ['class' => 'ds-pos'],
                    ['val' => strlen($filter['filter_name']) > 60 ? substr($filter['filter_name'], 0, 60) . '...' : $filter['filter_name']],
                    [
                        'val' => implode('<br/>', $filter['components']),
                        'attrs' => ['noWrap'],
                        'original' => true
                    ],
                    [
                        'val' => count($filter['callbacks'])
                    ]
                ],
                'info' => $filter['info']
            ];
        }

        /******** Info ********/
        foreach($rows as $key=>$data) {
            if (!empty($data['info'])) {
                $rows[$key]['info'] = DevStudio()->template('data/simple-array', [
                    'title' => isset($rows[$key]['cols'][1]['val']) ? $rows[$key]['cols'][1]['val']:'',

                    'headers' => [
                        ['title' => __('Priority', 'dev-studio')],
                        ['title' => __('Function', 'dev-studio')],
                        ['title' => __('Arguments', 'dev-studio')],
                        ['title' => __('Component', 'dev-studio')],
                    ],
                    'rows' => $data['info']
                ]);
            }
        }


        /******** Filters ********/
        /*
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
        */

        return DevStudio()->template('data/table', [
            'order' => [
                [], ['show' => true], [], ['show' => true, 'type' => 'number']
            ],
            'headers' => [
                ['title' => '#'],
                ['title' => __('Filter', 'dev-studio')],
                ['title' => __('Component', 'dev-studio')],
                ['title' => __('Callbacks', 'dev-studio')],
            ],
            'rows' => $rows
        ]);
    }
}