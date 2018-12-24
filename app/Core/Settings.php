<?php
namespace DevStudio\Core;

use DevStudio\Helpers\Utils;

/**
 * Settings class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Settings {

    public $option_name = 'dev_studio_options';
    public $avaFields;
    public $container;
    public $html;


    public function __construct() {
        // Render settings html and save to file
        DevStudio()->app_save('settings', $this->render());
    }

    public function render() {

        $this->avaFields = DevStudio()->avaFields();

        if (!$this->avaFields) {
            return Utils::message(__('Library AVA-Fields hasn\'t inslalled','dev-studio'));
        }

        $params = [
            'container' => [
                'id' => $this->option_name,
                'title' => __('Settings', 'dev-studio'),
            ],
            'appearance' => [
                'nav_style' => 'horizontal', // horizontal | vertical
            ],
            'options' => [
                'option_name' => $this->option_name
            ],
            'control' => [
                'btn-reset-section' => false,
                'btn-reset' => false
            ],
            'preloader' => [
                'image' => DevStudio()->url('assets').'images/logo-loop.svg',
                'text'  => __('Saving ...', 'dev-studio')
            ]
        ];

        $this->container = $this->avaFields->make('custom', $params);

        /**
         * Get settings data
         *
         *
         */
        $units = Data::units();
        $modules = DevStudio()->load_modules($units);

        $data_groups = [];
        $modules_items = [];
        $prev_module = '';
        foreach($modules as $module=>$module_data) {
        
            foreach($module_data->components as $component=>$component_data) {
                $items = [];
                foreach($component_data->units as $unit=>$unit_data) {
                    $items[$module.'.'.$component.'.'.$unit] = $unit_data->title;
                }
                $data_groups[$module.'.'.$component] = [
                    'parent' =>  $component_data->title,
                    'childs' => $items
                ];
                if ($prev_module != $module) {
                    $data_groups[$module . '.' . $component]['title'] = $module_data->title;
                    $prev_module = $module;
                }
            }
            
            // Modules items
            $modules_items[$module] = [
                'html' => $module_data->title
            ];
            
        }
    
        $unit_init = [];
        foreach($data_groups as $group=>$data) {
            $unit_init[$group] = ['text' => $group, 'disabled' => true];
            foreach($data['childs'] as $key=>$val) $unit_init[$key] = '- '.$val;
        }
    
        /**
         * Section :: General
         *
         */
        $this->container->add_section('general', [
            'title' => __('Application', 'dev-studio'),
            'tabs' => [
                'general' => __('General', 'dev-studio'),
                'appearance' => __('Appearance', 'dev-studio'),
                'access' => __('Access', 'dev-studio'),
            ],
            'fields' => [
                /**** Tab :: General ****/
                // Enabled
                'ds_enabled' => [
                    'tab' => 'general',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Enabled', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'general.enabled'
                    ]
                ],
                
                
                /**** Tab :: Appearance ****/
                // Unit on init
                'unit_init' => [
                    'tab' => 'appearance',
                    'type' => 'select',
                    'texts' => [
                        'label' => __('Load on Init', 'dev-studio'),
                    ],
                    'items' => $unit_init,
                    'options' => [
                        'map' => 'general.appearance.unit_init'
                    ]
                ],
    
                // Modules order
                'modules_order' => [
                    'tab' => 'appearance',
                    'type' => 'sortable',
                    'texts' => [
                        'label' => __('Modules order', 'dev-studio'),
                    ],
                    'items' => $modules_items,
                    'options' => [
                        'map' => 'general.appearance.modules_order'
                    ]
                ],
   
                
                /**** Tab :: Access ****/
                // Only for Administrators
                'only_admin' => [
                    'tab' => 'access',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Only for Administrators', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'general.access.only_admin'
                    ]
                ],
            ],
        ]);
    
        /**
         * Section :: Modules
         *
         */
        $this->container->add_section('modules', [
            'title' => __('Modules', 'dev-studio'),
            'tabs' => [
                'mysql' => __('MySQL', 'dev-studio'),
            ],
            'fields' => [
                /**** Tab :: MySQL ****/
                // Slow query time
                'slow_query' => [
                    'tab' => 'mysql',
                    'type' => 'text',
                    'texts' => [
                        'label' => __('Slow query time', 'dev-studio'),
                        'after' => __('sec', 'dev-studio')
                    ],
                    'options' => [
                        'map' => 'modules.mysql.slow_query'
                    ],
                    'attrs' => [
                        'style' => 'width:60px'
                
                    ],
                    'value' => '0.05',
                ],
            ],
        ]);
        
        /**
         * Section :: Data
         *
         */
        $fields = [
            /**** Tab :: General ****/
        
            // Exclude DevStudio data
            'exclude_ds_data' => [
                'tab' => 'general',
                'type' => 'checkbox',
                'texts' => [
                    'label' => __('Exclude plugin data', 'dev-studio'),
                ],
                'options' => [
                    'map' => 'data.general.exclude_ds_data'
                ],
                'value' => 'yes',
            ],
        
            /**** Tab :: AJAX ****/
        
            // Exclude Wordpress system queries
            'exclude_wp_ajax' => [
                'tab' => 'ajax',
                'type' => 'checkbox',
                'texts' => [
                    'label' => __('Exclude Wordpress system queries', 'dev-studio'),
                ],
                'options' => [
                    'map' => 'data.ajax.exclude_wp_ajax'
                ],
                'value' => 'no'
            ]
        ];
    
        /**** Tab :: Modules ****/
    
        
        $fields['data-units'] = [
            'tab' => 'units',
            'type' => 'checkbox-groups',
            'texts' => [],
            'groups' => $data_groups,
            'options' => [
                'map' => 'units.map'
            ]
        ];

        $this->container->add_section('data', [
            'title' => __('Data', 'dev-studio'),
            'tabs' => [
                'general' => __('General', 'dev-studio'),
                'units' => __('Modules', 'dev-studio'),
                'ajax' => __('AJAX', 'dev-studio'),
            ],
            'fields' => $fields
        ]);

        /**
         * Section :: Bar
         *
         */
        $this->container->add_section('bar', [
            'title' => __('Bar', 'dev-studio'),
            'tabs' => [
                'general' => __('General', 'dev-studio'),
                'items' => __('Items', 'dev-studio'),
            ],
            'fields' => [
                /**** Tab :: General ****/

                // Enable Bar
                'bar_enabled' => [
                    'tab' => 'general',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Enabled', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.enabled'
                    ],
                    'value' => 'yes'
                ],
                // Expand on load
                'bar_expand' => [
                    'tab' => 'general',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Expand on load', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.expand'
                    ],
                    'value' => 'yes'
                ],
                // Only for logged in users
                'bar_only_logged_in' => [
                    'tab' => 'general',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Only for Logged in users', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.only_logged_in'
                    ],
                    'value' => 'yes'
                ],

                /**** Tab :: Items ****/

                // Page Generation Time
                'bar_page_time' => [
                    'tab' => 'items',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Page Generation Time', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.items.page_time'
                    ],
                    'value' => 'yes'
                ],
                // Queries Execution Time
                'bar_db_queries_time' => [
                    'tab' => 'items',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Queries Execution Time', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.items.db_queries_time'
                    ],
                    'value' => 'yes'
                ],
                // Queries Count
                'bar_db_queries_count' => [
                    'tab' => 'items',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Queries Count', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.items.db_queries_count'
                    ],
                    'value' => 'yes'
                ],
                // Wordpress Conditionals
                'bar_conditionals' => [
                    'tab' => 'items',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('Wordpress Conditionals', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.items.conditionals'
                    ],
                    'value' => 'yes'
                ],
                // WooCommerce Conditionals
                'bar_wc_conditionals' => [
                    'tab' => 'items',
                    'type' => 'checkbox',
                    'texts' => [
                        'label' => __('WooCommerce Conditionals', 'dev-studio'),
                    ],
                    'options' => [
                        'map' => 'bar.items.wc_conditionals'
                    ],
                    'value' => 'yes'
                ]
           ]
        ]);

        $this->html = $this->avaFields->container( $this->option_name )->render();
        return $this->html;
    }

    public function output() {
        if (!empty($this->html)) {
            echo $this->html;
            return;
        }
        $this->avaFields->container( $this->option_name )->output();
    }

}