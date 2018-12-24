<?php
namespace DevStudio\Modules\Wordpress\Components\Styles;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;

/**
 * Wordpress.Styles component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Styles extends Component {

	public $name = 'Styles';
    public $title = 'Styles';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * Wordpress.Styles -> Enqueued unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Enqueued extends Unit {

    public $name = 'Enqueued';
    public $title = 'Enqueued';

    public function encode_data($data) {
        return serialize($data);
    }

    public function decode_data($data) {
        return unserialize($data);
    }

    public function data() {
        if (!empty($GLOBALS['wp_styles']) && is_object($GLOBALS['wp_styles'])) {
            $this->data = $GLOBALS['wp_styles'];
        }
    }


    public function html() {

        $rows = [];

        if (!is_object($this->data) || empty($this->data->done)) return Utils::no_data();

        // Dependents
        $dependents = [];
        foreach ($this->data->registered as $handle=>$data) {
            if (!empty($data->deps)) {
                foreach($data->deps as $dep) {
                    if (in_array($handle, $this->data->done)) {
                        $dependents[$dep][] = $handle;
                    }
                }
            }
        }

        foreach ($this->data->done as $handle) {
            if (!empty($this->data->registered[$handle])) {

                $data = Utils::style_data( $this->data->registered[$handle] );
                $dependencies = !empty($data['deps']) ? implode(', ', $data['deps']) : '';
                $deps = !empty($dependents[$handle]) ? implode(', ', $dependents[$handle]) : '';

                // Exclude me
                if (!empty($data['src']) && DevStudio()->exclude_me() && DevStudio()->me('asset', $data['src'])) continue;

                $rows[$handle] = [
                    'cols' => [
                        ['val' => $handle],
                        ['val' => $data['host']],
                        ['val' => !empty($data['src']) ? '<a class="ds-link" target="_blank" href="'.esc_attr($data['src']).'">'.$data['src'].'<a>':'', 'original' => true],
                        ['val' => $dependencies],
                        ['val' => $deps],
                        ['val' => !empty($data['extra']) ? '<span class="fa fa-check" style="color:green"></span><br/><span class="ds-sm-text">'.number_format($data['extra_size']).'</span>' : '', 'style' => 'text-align:center', 'original' => true]
                    ],
                ];

                /**
                 * Info
                 *
                 **/
                $info = '<div class="title">'.__('Handle', 'dev-studio').': '.$handle.'</div>';

                // Style data
                $style_data = [];
                if (!empty($data['host'])) {
                    $style_data[] = [
                        'cols' => [
                            ['val' => __('Host', 'dev-studio')],
                            ['val' => $data['host']],
                        ]
                    ];
                }
                if (!empty($data['src'])) {
                    $style_data[] = [
                        'cols' => [
                            ['val' => __('Source', 'dev-studio')],
                            ['val' => $data['src']],
                        ]
                    ];
                }
                if (!empty($dependencies)) {
                    $style_data[] = [
                        'cols' => [
                            ['val' => __('Dependencies', 'dev-studio')],
                            ['val' => $dependencies],
                        ]
                    ];
                }
                if (!empty($deps)) {
                    $style_data[] = [
                        'cols' => [
                            ['val' => __('Dependents', 'dev-studio')],
                            ['val' => $deps],
                        ]
                    ];
                }
                if (!empty($data['media'])) {
                    $style_data[] = [
                        'cols' => [
                            ['val' => __('Media', 'dev-studio')],
                            ['val' => $data['media']],
                        ]
                    ];
                }
                if (!empty($data['ver'])) {
                    $style_data[] = [
                        'cols' => [
                            ['val' => __('Version', 'dev-studio')],
                            ['val' => $data['ver']],
                        ]
                    ];
                }

                if (!empty($style_data)) {
                    $info .= DevStudio()->template('data/table', [
                        'h3' => __('Style data', 'dev-studio'),
                        'rows' => $style_data
                    ]);
                }

                // Extra
                if (!empty($data['extra'])) {
                    $extra_rows = [];
                    foreach ($data['extra'] as $key => $value) {
                        $extra_rows[] = [
                            'cols' => [
                                ['val' => $key],
                                ['val' => is_array($value) ? implode(', ', $value) : $value],
                            ],
                        ];
                    }
                    $extra_rows[] = [
                        'cols' => [
                            ['val' => ''],
                            ['val' => number_format($data['extra_size']).' '.__('byte(s) length', 'dev-studio'), 'class' => 'ds-gray']
                        ],
                    ];

                    $info .= DevStudio()->template('data/table', [
                        'h3' => __('Extra styles', 'dev-studio'),
                        'headers' => [
                            ['title' => __('Type', 'dev-studio')],
                            ['title' => __('Style', 'dev-studio')],
                        ],
                        'rows' => $extra_rows
                    ]);
                }

                if ($info) {
                    $rows[$handle]['class'] = 'info';
                    $rows[$handle]['info'] = $info;
                }
            }
        }

        return DevStudio()->template('data/table', [
            'class' => 'info',
            'order' => [
                ['show' => true], ['show' => true], ['show' => true], [], [], ['show' => true]
            ],
            'headers' => [
                //['title' => '#'],
                ['title' => __('Handle', 'dev-studio')],
                ['title' => __('Host', 'dev-studio')],
                ['title' => __('Source', 'dev-studio')],
                ['title' => __('Dependencies', 'dev-studio')],
                ['title' => __('Dependents', 'dev-studio')],
                ['title' => __('Extra', 'dev-studio')],
            ],
            'rows' => $rows
        ]);
    }

}

/**
 * Wordpress.Styles -> Registered unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Registered extends Unit {

    public $name = 'Registered';
    public $title = 'Registered';

    public function encode_data($data) {
        return serialize($data);
    }

    public function decode_data($data) {
        return unserialize($data);
    }

    public function data() {
        if (!empty($GLOBALS['wp_styles']) && is_object($GLOBALS['wp_styles'])) {
            $this->data = $GLOBALS['wp_styles'];
        }
    }


    public function html() {

        if (!is_object($this->data) || !isset($this->data->registered) || empty($this->data->registered)) return Utils::no_data();

        // Dependents
        $dependents = [];
        foreach ($this->data->registered as $handle=>$data) {
            if (!empty($data->deps)) {
                foreach($data->deps as $dep) {
                    //if (in_array($handle, $this->data->done)) {
                    $dependents[$dep][] = $handle;
                    //}
                }
            }
        }

        $rows = [];
        foreach ($this->data->registered as $handle=>$data) {

            $data = Utils::style_data( $data );
            $dependencies = !empty($data['deps']) ? implode(', ', $data['deps']) : '';
            $deps = !empty($dependents[$handle]) ? implode(', ', $dependents[$handle]) : '';

            // Exclude me
            if (!empty($data['src']) && DevStudio()->exclude_me() && DevStudio()->me('asset', $data['src'])) continue;

            $rows[$handle] = [
                'cols' => [
                    ['class' => 'ds-pos'],
                    ['val' => $handle],
                    ['val' => $data['host']],
                    ['val' => !empty($data['src']) ? '<a class="ds-link" target="_blank" href="'.esc_attr($data['src']).'">'.$data['src'].'<a>':'', 'original' => true],
                    ['val' => $dependencies],
                    ['val' => $deps],
                    ['val' => !empty($data['extra']) ? '<span class="fa fa-check" style="color:green"></span><br/><span class="ds-sm-text">'.number_format($data['extra_size']).'</span>' : '', 'style' => 'text-align:center', 'original' => true]
                ],
            ];

            /**
             * Info
             *
             **/
            $info = '<div class="title">'.__('Handle', 'dev-studio').': '.$handle.'</div>';

            // Style data
            $style_data = [];
            if (!empty($data['host'])) {
                $style_data[] = [
                    'cols' => [
                        ['val' => __('Host', 'dev-studio')],
                        ['val' => $data['host']],
                    ]
                ];
            }
            if (!empty($data['src'])) {
                $style_data[] = [
                    'cols' => [
                        ['val' => __('Source', 'dev-studio')],
                        ['val' => $data['src']],
                    ]
                ];
            }
            if (!empty($dependencies)) {
                $style_data[] = [
                    'cols' => [
                        ['val' => __('Dependencies', 'dev-studio')],
                        ['val' => $dependencies],
                    ]
                ];
            }
            if (!empty($deps)) {
                $style_data[] = [
                    'cols' => [
                        ['val' => __('Dependents', 'dev-studio')],
                        ['val' => $deps],
                    ]
                ];
            }
            if (!empty($data['media'])) {
                $style_data[] = [
                    'cols' => [
                        ['val' => __('Media', 'dev-studio')],
                        ['val' => $data['media']],
                    ]
                ];
            }
            if (!empty($data['ver'])) {
                $style_data[] = [
                    'cols' => [
                        ['val' => __('Version', 'dev-studio')],
                        ['val' => $data['ver']],
                    ]
                ];
            }

            if (!empty($style_data)) {
                $info .= DevStudio()->template('data/table', [
                    'h3' => __('Style data', 'dev-studio'),
                    'rows' => $style_data
                ]);
            }

            // Extra
            if (!empty($data['extra'])) {
                $extra_rows = [];
                foreach ($data['extra'] as $key => $value) {
                    $extra_rows[] = [
                        'cols' => [
                            ['val' => $key],
                            ['val' => is_array($value) ? implode(', ', $value) : $value],
                        ],
                    ];
                }
                $extra_rows[] = [
                    'cols' => [
                        ['val' => ''],
                        ['val' => number_format($data['extra_size']).' '.__('byte(s) length', 'dev-studio'), 'class' => 'ds-gray']
                    ],
                ];

                $info .= DevStudio()->template('data/table', [
                    'h3' => __('Extra styles', 'dev-studio'),
                    'headers' => [
                        ['title' => __('Type', 'dev-studio')],
                        ['title' => __('Style', 'dev-studio')],
                    ],
                    'rows' => $extra_rows
                ]);
            }

            if ($info) {
                $rows[$handle]['class'] = 'info';
                $rows[$handle]['info'] = $info;
            }
        }

        return DevStudio()->template('data/table', [
            'class' => 'info',
            'order' => [
                [], ['show' => true], ['show' => true], ['show' => true], [], ['show' => true]
            ],
            'headers' => [
                ['title' => '#'],
                ['title' => __('Handle', 'dev-studio')],
                ['title' => __('Host', 'dev-studio')],
                ['title' => __('Source', 'dev-studio')],
                ['title' => __('Dependencies', 'dev-studio')],
                ['title' => __('Dependents', 'dev-studio')],
                ['title' => __('Extra', 'dev-studio')],
            ],
            'rows' => $rows
        ]);
    }

}

/**
 * Wordpress.Styles -> WP_Styles unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_WP_Styles extends Unit {

    public $name = 'WP_Styles';
    public $title = 'WP_Styles';

    public function encode_data($data) {
        return serialize($data);
    }

    public function decode_data($data) {
        return unserialize($data);
    }

    public function data() {

        if (!empty($GLOBALS['wp_styles']) && is_object($GLOBALS['wp_styles'])) {
            $this->data = $GLOBALS['wp_styles'];
        }

    }

    public function html() {

        return DevStudio()->template('data/object', $this->data);

    }

}