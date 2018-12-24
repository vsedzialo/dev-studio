<?php
namespace DevStudio\Modules\MySQL\Components\Queries;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Data\MySQL;

/**
 * MySQL.Queries component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Queries extends Component {

	public $name = 'Queries';
    public $title = 'Queries';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * MySQL.Queries -> Callers unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Callers extends Unit {

    public $name = 'Callers';
    public $title = 'Callers';
    public $headers = [];

    public function data() {

        $data = MySQL::queries();

        if (!empty($data)) {

            // Define queries types
            $headers = [];
            foreach ($data['callers'] as $caller => $_data) {
                foreach ($_data['types'] as $type => $__data) $headers[$type] = true;
            }

            // Get table headers
            $this->headers = [];
            $this->headers[] = [
                'title' => __('Caller', 'dev-studio')
            ];
            foreach ($headers as $header => $_) {
                $this->headers[] = [
                    'title' => $header
                ];
                $this->headers[] = [
                    'title' => __('Time,s', 'dev-studio')
                ];
            }
            $this->headers[] = [
                'title' => __('Total', 'dev-studio')
            ];
            $this->headers[] = [
                'title' => __('Total Time,s', 'dev-studio')
            ];
            $this->data = [
                'headers' => $this->headers
            ];

            foreach ($data['callers'] as $caller => $_data) {
                $cols = [
                    ['val' => $caller]
                ];
                //$total_queries
                foreach ($headers as $type => $_) {
                    $cols[] = [
                        'val' => isset($_data['types'][$type]) ? $_data['types'][$type]['queries'] : ''
                    ];
                    $cols[] = [
                        'val' => isset($_data['types'][$type]) ? sprintf('%3.5f', $_data['types'][$type]['time']) : ''
                    ];
                }
                $cols[] = ['val' => $_data['queries']];
                $cols[] = ['val' => sprintf('%3.5f', $_data['time'])];

                $this->data['rows'][] = [
                    'cols' => $cols,
                ];
            }
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'headers' => $this->data['headers'],
            'rows' => $this->data['rows']
        ]);

    }
}

/**
 * MySQL.Queries -> Queries unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Queries extends Unit {

    public $name = 'Queries';
    public $title = 'Queries';

    public function data() {

        $data = MySQL::queries();

        if (!empty($data) && isset($data['queries']) ) {
            $index = 0;
            $this->data = [];
            foreach ($data['queries'] as $key => $_data) {

                // Info: backtrace
                $info_backtrace = [];
                foreach ($_data['backtrace'] as $num=>$backtrace) {
                    $info_backtrace[] = [
                        ['val' => ($num+1).'. '.$backtrace]
                    ];
                }

                $time_class = $_data['slow_query'] ? 'ds-attention' : '';
                $this->data['queries'][] = [
                    'index' => $index,
                    'class' => 'info',
                    'atts' => [
                        'data-qtype = "'.esc_attr($_data['qtype']).'"',
                        'data-slow_query = "'.($_data['slow_query'] ? 1:0).'"',
                    ],
                    'cols' => [
                        ['class' => 'ds-pos'],
                        [
                            'val' => $_data['query'],
                            'style' => 'width:60%;word-break:break-all;font-size:12px;',
                            'original' => true
                        ],
                        [
                            'val' => $_data['time'],
                            'style' => 'width:15%;word-break:break-all;font-size:12px;',
                            'class' => $time_class
                        ],
                        [
                            'val' => $_data['caller'],
                            'style' => 'width:25%;word-break:break-all',
                        ]
                    ],
                    'info' =>
                        DevStudio()->template('data/simple-array', [
                            'title' => __('Backtrace', 'dev-studio'),
                            'rows' => $info_backtrace
                        ])
                ];
            }
            $this->data['slow_queries'] = $data['slow_queries'];
            $this->data['types'] = $data['types'];
        }
    }

    public function html() {

        // Filters
        $filters = [];
        if (!empty($this->data['types'])) {
            $options = ['' => ''];
            foreach($this->data['types'] as $type) $options[$type] = $type;

            $filters[] = [
                'label'     => __('Query type', 'dev-studio'),
                'type'      => 'select',
                'options'   => $options,
                'src'       => 'data', // data-, column,
                'src_data'  => 'qtype',
                'src_value' => 0, // column number, if src = column
            ];
        }

        if (!empty($this->data['slow_queries'])) {
            $filters[] = [
                'label'     => __('Slow queries', 'dev-studio') . ' ('.count($this->data['slow_queries']).')',
                'type'      => 'checkbox',
                'src'       => 'data',
                'src_data'  => 'slow_query',
                'src_value' => 1,
            ];
        }


        return DevStudio()->template('data/table', [
            'panel' => [
                'filters' => $filters
            ],
            'order' => [
                [],
                ['show' => true],
                ['show' => true],
                ['show' => true]
            ],
            'headers' => [
                ['title' => '#'],
                ['title' => __('Query', 'dev-studio')],
                ['title' => __('Time,s', 'dev-studio')],
                ['title' => __('Caller', 'dev-studio')]
            ],
            'rows' => $this->data['queries']
        ]);

    }

}