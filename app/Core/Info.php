<?php
namespace DevStudio\Core;

use DevStudio\Data\PHP;
use DevStudio\Data\MySQL;
use DevStudio\Core\Storage;
use DevStudio\Helpers\Utils;

/**
 * Info class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Info {
    
    public static function html($type) {
        switch($type) {
            case 'page':
                return self::page();
                break;
            case 'wordpress':
                return self::wordpress();
                break;
            case 'woocommerce':
                return self::woocommerce();
                break;
            case 'php':
                return self::php();
                break;
            case 'database':
                return self::database();
                break;
            case 'server':
                return self::server();
                break;
        }
    }

    /**
     * Page Info block
     *
     *
     * @return mixed
     */
    public static function page() {
        $data = DevStudio()->app_load('page');
    
        $rows = [];
        $rows[] = [
            'cols' => [
                ['val' => __('Page Generation Time', 'dev-studio')],
                ['val' => $data['page_time'].'s']
            ]
        ];
    
        $html = '<div class="page-info">';
        $html .= DevStudio()->template('data/table', [
            'title' => __('Page info', 'dev-studio'),
            'rows' => $rows
        ]);
        return $html;
    }

    /**
     * PHP info block
     *
     *
     * @return mixed
     */
    public static function php() {
        $data = PHP::data();
        $app_data = DevStudio()->app_load('php');
        
        $rows = [];
        $rows[] = [
            'cols' => [ ['val' => __('Version', 'dev-studio'), 'style' => 'width:170px'], ['val' => $data['version'], 'style' => 'width:150px'], ['val'=>''] ],
        ];
        $rows[] = [
            'cols' => [ ['val' => __('SAPI', 'dev-studio')], ['val' => $data['sapi_name']], ['val'=>''] ],
        ];
        $rows[] = [
            'cols' => [ ['val' => __('Memory Limit', 'dev-studio')], ['val' => $data['memory_limit']], ['val' => 'memory_limit'] ],
        ];
        $rows[] = [
            'cols' => [ ['val' => __('Memory Usage', 'dev-studio')], ['val' => number_format($app_data['memory']/1024).'Kb' ], ['val' => ''] ],
        ];
        $rows[] = [
            'cols' => [ ['val' => __('Upload Filesize', 'dev-studio')], ['val' => $data['upload_max_filesize']], ['val' => 'upload_max_filesize'] ],
        ];
        $rows[] = [
            'cols' => [ ['val' => __('Post Filesize', 'dev-studio')], ['val' => $data['post_max_size']], ['val' => 'post_max_size'] ],
        ];
        $rows[] = [
            'cols' => [ ['val' => __('Max Execution Time', 'dev-studio')], ['val' => $data['max_execution_time']], ['val' => 'max_execution_time'] ],
        ];

        $rows[] = [
            'cols' => [ ['val' => __('Display Errors', 'dev-studio')], ['val' => $data['display_errors']], ['val' => 'display_errors'] ],
        ];
        $rows[] = [
            'cols' => [ ['val' => __('Log Errors', 'dev-studio')], ['val' => $data['log_errors']], ['val' => 'log_errors'] ],
        ];

        if (isset($data['ini_file'])) {
            $rows[] = [
                'cols' => [ ['val' => __('INI File', 'dev-studio')], ['val' => $data['ini_file'], 'attrs' => ['colspan="2"']]  ],
            ];
        }
        
        $html = DevStudio()->template('data/table', [
            'title' => __('PHP', 'dev-studio'),
            'rows' => $rows
        ]);
        return $html;
    }

    /**
     * Server info block
     *
     *
     * @return mixed
     */
    public static function server() {
        $rows = [];

        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            $rows[] = [
                'cols' => [
                    ['val' => __('Server Software', 'dev-studio'), 'style' => 'width:150px'],
                    ['val' => $_SERVER['SERVER_SOFTWARE']],
                ],
            ];
        }
        if ( function_exists( 'php_uname' ) ) {
            $rows[] = [
                'cols' => [
                    ['val' => __('OS', 'dev-studio')],
                    ['val' => php_uname( 's' ).' '.php_uname( 'r' ).' '.php_uname( 'v' )],
                ],
            ];
            $rows[] = [
                'cols' => [
                    ['val' => __('Host', 'dev-studio')],
                    ['val' => php_uname( 'n' )],
                ],
            ];
        }
        if (isset($_SERVER['SERVER_ADDR'])) {
            $rows[] = [
                'cols' => [
                    ['val' => __('Server Address', 'dev-studio')],
                    ['val' => $_SERVER['SERVER_ADDR']],
                ],
            ];
        }
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $rows[] = [
                'cols' => [
                    ['val' => __('Remote Address', 'dev-studio')],
                    ['val' => $_SERVER['REMOTE_ADDR']],
                ],
            ];
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $rows[] = [
                'cols' => [
                    ['val' => __('User Agent', 'dev-studio')],
                    ['val' => $_SERVER['HTTP_USER_AGENT']],
                ],
            ];
        }

        $html = DevStudio()->template('data/table', [
            'title' => __('Server', 'dev-studio'),
            'rows' => $rows
        ]);
        return $html;
    }

    /**
     * Wordpress info block
     *
     *
     * @return mixed
     */
    public static function wordpress() {
        $rows = [];

        $rows[] = [
            'cols' => [
                ['val' => __('Version', 'dev-studio'), 'style' => 'width:180px'],
                ['val' => isset($GLOBALS['wp_version']) ? $GLOBALS['wp_version'] : ''],
            ],
        ];

        // Constants
        $rows[] = [
            'cols' => [
                ['val' => __('Constants', 'dev-studio'), 'class' => 'subtitle' ],
                ['val' => ''],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'WP_DEBUG'],
                ['val' => Utils::fa_true_false( Utils::get_const_value('WP_DEBUG') ), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'WP_DEBUG_DISPLAY'],
                ['val' => Utils::fa_true_false( Utils::get_const_value('WP_DEBUG_DISPLAY') ), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'WP_DEBUG_LOG'],
                ['val' => Utils::fa_true_false( Utils::get_const_value('WP_DEBUG_LOG') ), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'WP_CACHE'],
                ['val' => Utils::fa_true_false( Utils::get_const_value('WP_CACHE') ), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'SCRIPT_DEBUG'],
                ['val' => Utils::fa_true_false( Utils::get_const_value('SCRIPT_DEBUG') ), 'original' => true],
            ],
        ];


        $html = DevStudio()->template('data/table', [
            'title' => __('Wordpress', 'dev-studio'),
            'rows' => $rows
        ]);
        return $html;
    }

    /**
     * WooCommerce info block
     *
     *
     * @return mixed
     */
    public static function woocommerce() {
        $rows = [];

        $rows[] = [
            'cols' => [
                ['val' => __('Version', 'dev-studio'), 'style' => 'width:180px'],
                ['val' => WC_VERSION],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => __('API enabled', 'dev-studio')],
                ['val' => get_option('woocommerce_api_enabled')],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => __('Coupons enabled', 'dev-studio')],
                ['val' => get_option('woocommerce_enable_coupons')],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => __('Reviews enabled', 'dev-studio')],
                ['val' => get_option('woocommerce_enable_reviews')],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => __('Shipping Calc enabled', 'dev-studio')],
                ['val' => get_option('woocommerce_enable_shipping_calc')],
            ],
        ];








        $html = DevStudio()->template('data/table', [
            'title' => __('WooCommerce', 'dev-studio'),
            'rows' => $rows
        ]);
        return $html;
    }


    /**
     * Database info block
     *
     *
     * @return mixed
     */
    public static function database() {
        global $wpdb;

        $vars = MySQL::variables();

        $rows = [];

        $dbh = isset($wpdb->dbh) && is_object($wpdb->dbh) ? $wpdb->dbh : null;

        if (isset($vars['version'])) {
            $rows[] = [
                'cols' => [
                    ['val' => __('Version', 'dev-studio'), 'style' => 'width:180px'],
                    ['val' => $vars['version'] ],
                ],
            ];
        }
        if ($dbh){
            $rows[] = [
                'cols' => [['val' => __('Extension', 'dev-studio')], ['val' => get_class($dbh)]],
            ];
        }
        if (isset($vars['storage_engine'])) {
            $rows[] = [
                'cols' => [ ['val' => __('Engine', 'dev-studio')], ['val' => $vars['storage_engine']] ],
            ];
        }
        if (isset($vars['datadir'])) {
            $rows[] = [
                'cols' => [ ['val' => __('Directory', 'dev-studio')], ['val' => $vars['datadir']] ],
            ];
        }

        // Constants
        $rows[] = [
            'cols' => [
                ['val' => __('Constants', 'dev-studio'), 'class' => 'subtitle' ],
                ['val' => ''],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'SAVEQUERIES'],
                ['val' => Utils::fa_true_false( Utils::get_const_value('SAVEQUERIES') ), 'original' => true],
            ],
        ];

        // Variables
        $rows[] = [
            'cols' => [
                ['val' => __('DB variables', 'dev-studio'), 'class' => 'subtitle' ],
                ['val' => ''],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'max_connections'],
                ['val' => isset($vars['max_connections']) ? $vars['max_connections'] : Utils::not_exists(), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'key_buffer_size'],
                ['val' => isset($vars['key_buffer_size']) ? $vars['key_buffer_size'].' ('.number_format(($vars['key_buffer_size']/1024)/1024).'M'.')' : Utils::not_exists(), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'max_allowed_packet'],
                ['val' => isset($vars['max_allowed_packet']) ? $vars['max_allowed_packet'].' ('.number_format(($vars['max_allowed_packet']/1024)/1024).'M'.')' : Utils::not_exists(), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'query_cache_type'],
                ['val' => isset($vars['query_cache_type']) ? $vars['query_cache_type'] : Utils::not_exists(), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'query_cache_limit'],
                ['val' => isset($vars['query_cache_limit']) ? $vars['query_cache_limit'].' ('.number_format($vars['query_cache_limit']/1024).'Kb'.')' : Utils::not_exists(), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'query_cache_size'],
                ['val' => isset($vars['query_cache_size']) ? $vars['query_cache_size'].' ('.number_format(($vars['query_cache_size']/1024)/1024).'M'.')' : Utils::not_exists(), 'original' => true],
            ],
        ];
        $rows[] = [
            'cols' => [
                ['val' => 'slow_query_log'],
                ['val' => isset($vars['slow_query_log']) ? $vars['slow_query_log'] : Utils::not_exists(), 'original' => true],
            ],
        ];


        // Connection
        $rows[] = [
            'cols' => [
                ['val' => __('Connection', 'dev-studio'), 'class' => 'subtitle' ],
                ['val' => ''],
            ],
        ];
        $rows[] = [ 'cols' => [ ['val' => 'Name'], ['val' => $wpdb->dbname] ] ];
        $rows[] = [ 'cols' => [ ['val' => 'User'], ['val' => $wpdb->dbuser] ] ];
        $rows[] = [ 'cols' => [ ['val' => 'Host'], ['val' => $wpdb->dbhost] ] ];

        // Charset and Collate
        $rows[] = [
            'cols' => [
                ['val' => __('Charset and Collate', 'dev-studio'), 'class' => 'subtitle' ],
                ['val' => ''],
            ],
        ];
        $rows[] = [ 'cols' => [ ['val' => 'Charset'], ['val' => $wpdb->charset] ] ];
        $rows[] = [ 'cols' => [ ['val' => 'Collate'], ['val' => $wpdb->collate] ] ];

        // Stats
        if ($dbh && $wpdb->dbh->stat) {
            $rows[] = [
                'cols' => [
                    ['val' => __('Stats', 'dev-studio'), 'class' => 'subtitle'],
                    ['val' => $wpdb->dbh->stat],
                ],
            ];
        }

        $html = DevStudio()->template('data/table', [
            'title' => __('Database', 'dev-studio'),
            'rows' => $rows
        ]);
        return $html;
    }
}