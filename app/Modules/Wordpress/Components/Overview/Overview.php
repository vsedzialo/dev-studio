<?php
namespace DevStudio\Modules\Wordpress\Components\Overview;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Data\Wordpress;
use DevStudio\Helpers\Utils;

/**
 * Wordpress.Overview component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Overview extends Component {

	public $name = 'Overview';
    public $title = 'Overview';

	public function __construct(  ) {

		parent::__construct( );

	}
}

/**
 * Wordpress.Overview -> Overview unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Overview extends Unit {

    public $name = 'Overview';
    public $title = 'Overview';
    public $template;

    public function __construct(Component $component) {

        parent::__construct($component);

        add_filter( 'template_include', [ $this, 'template_include' ], PHP_INT_MAX );
    }

    public function template_include($template) {
        $this->template = $template;
        return $template;
    }

    public function data() {
        $this->data = [
            __('Wordpress Version', 'dev-studio') => isset($GLOBALS['wp_version']) ? $GLOBALS['wp_version'] : '',
            __('Required PHP version', 'dev-studio') => isset($GLOBALS['required_php_version']) ? $GLOBALS['required_php_version'] : '',
            __('Required MySQL version', 'dev-studio') => isset($GLOBALS['required_mysql_version']) ? $GLOBALS['required_mysql_version'] : '',
            __('TinyMCE version', 'dev-studio') => isset($GLOBALS['tinymce_version']) ? $GLOBALS['tinymce_version'] : '',
            __('Theme', 'dev-studio') => function_exists('get_stylesheet') ? get_stylesheet():'',
            __('Template', 'dev-studio') => !empty($this->template) ? $this->template:'',
        ];
    }

    public function html() {

        $data = [];
        foreach ($this->data as $key => $value) {
            if (is_array($value) && isset($value['type'])) {
                $value['val'] = $key;
                $data[] = $value;
            } else {
                $data[] = [
                    'cols' => [
                        ['val' => $key],
                        ['val' => $value]
                    ]
                ];

            }
        }

        return DevStudio()->template('data/table', [
            'rows' => $data
        ]);

    }
}

/**
 * Wordpress.Overview -> Constants unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Constants extends Unit {

    public $name = 'Constants';
    public $title = 'Constants';

    public $options = [
        'data-info' => false
    ];

    public $constants = [
        // Base
        'WP_AUTO_UPDATE_CORE' => ['type' => 'base'],
        'AUTOMATIC_UPDATER_DISABLED' => ['type' => 'base'],
        'AUTOSAVE_INTERVAL' => ['type' => 'base'],
        'CORE_UPGRADE_SKIP_NEW_BUNDLED' => ['type' => 'base'],
        'DISABLE_WP_CRON' => ['type' => 'base'],
        'EMPTY_TRASH_DAYS' => ['type' => 'base'],
        'IMAGE_EDIT_OVERWRITE' => ['type' => 'base'],
        'MEDIA_TRASH' => ['type' => 'base'],
        'WPLANG' => ['type' => 'base'],
        'WP_DEFAULT_THEME' => ['type' => 'base'],
        'WP_CRON_LOCK_TIMEOUT' => ['type' => 'base'],
        'WP_MAIL_INTERVAL' => ['type' => 'base'],
        'WP_POST_REVISIONS' => ['type' => 'base'],
        'WP_MAX_MEMORY_LIMIT' => ['type' => 'base'],
        'WP_MEMORY_LIMIT' => ['type' => 'base'],
        // Statuses
        'APP_REQUEST' => ['type' => 'statuses'],
        'COMMENTS_TEMPLATE' => ['type' => 'statuses'],
        'DOING_AJAX' => ['type' => 'statuses'],
        'DOING_AUTOSAVE' => ['type' => 'statuses'],
        'DOING_CRON' => ['type' => 'statuses'],
        'IFRAME_REQUEST' => ['type' => 'statuses'],
        'IS_PROFILE_PAGE' => ['type' => 'statuses'],
        'SHORTINIT' => ['type' => 'statuses'],
        'WP_ADMIN' => ['type' => 'statuses'],
        'WP_BLOG_ADMIN' => ['type' => 'statuses'],
        'WP_IMPORTING' => ['type' => 'statuses'],
        'WP_INSTALLING' => ['type' => 'statuses'],
        'WP_INSTALLING_NETWORK' => ['type' => 'statuses'],
        'WP_LOAD_IMPORTERS' => ['type' => 'statuses'],
        'WP_NETWORK_ADMIN' => ['type' => 'statuses'],
        'WP_REPAIRING' => ['type' => 'statuses'],
        'WP_SETUP_CONFIG' => ['type' => 'statuses'],
        'WP_UNINSTALL_PLUGIN' => ['type' => 'statuses'],
        'WP_USER_ADMIN' => ['type' => 'statuses'],
        'XMLRPC_REQUEST' => ['type' => 'statuses'],
        // Directories and URLs
        'ABSPATH' => ['type' => 'paths'],
        'WPINC' => ['type' => 'paths'],
        'WP_LANG_DIR' => ['type' => 'paths'],
        'WP_PLUGIN_DIR' => ['type' => 'paths'],
        'WP_PLUGIN_URL' => ['type' => 'paths'],
        'WP_CONTENT_DIR' => ['type' => 'paths'],
        'WP_CONTENT_URL' => ['type' => 'paths'],
        'WP_HOME' => ['type' => 'paths'],
        'WP_SITEURL' => ['type' => 'paths'],
        'WP_TEMP_DIR' => ['type' => 'paths'],
        'WPMU_PLUGIN_DIR' => ['type' => 'paths'],
        'WPMU_PLUGIN_URL' => ['type' => 'paths'],
        // Database
        'DB_CHARSET' => ['type' => 'db'],
        'DB_COLLATE' => ['type' => 'db'],
        'DB_HOST' => ['type' => 'db'],
        'DB_NAME' => ['type' => 'db'],
        'DB_PASSWORD' => ['type' => 'db'],
        'DB_USER' => ['type' => 'db'],
        'WP_ALLOW_REPAIR' => ['type' => 'db'],
        'CUSTOM_USER_TABLE' => ['type' => 'db'],
        'CUSTOM_USER_META_TABLE' => ['type' => 'db'],
        // Template
        'BACKGROUND_IMAGE' => ['type' => 'template'],
        'HEADER_IMAGE' => ['type' => 'template'],
        'HEADER_IMAGE_HEIGHT' => ['type' => 'template'],
        'HEADER_IMAGE_WIDTH' => ['type' => 'template'],
        'HEADER_TEXTCOLOR' => ['type' => 'template'],
        'NO_HEADER_TEXT' => ['type' => 'template'],
        'STYLESHEETPATH' => ['type' => 'template'],
        'TEMPLATEPATH' => ['type' => 'template'],
        'WP_USE_THEMES' => ['type' => 'template'],
        // Filesystem and connections
        'FS_CHMOD_DIR' => ['type' => 'fs'],
        'FS_CHMOD_FILE' => ['type' => 'fs'],
        'FS_CONNECT_TIMEOUT' => ['type' => 'fs'],
        'FS_METHOD' => ['type' => 'fs'],
        'FS_TIMEOUT' => ['type' => 'fs'],
        'FTP_BASE' => ['type' => 'fs'],
        'FTP_CONTENT_DIR' => ['type' => 'fs'],
        'FTP_HOST' => ['type' => 'fs'],
        'FTP_LANG_DIR' => ['type' => 'fs'],
        'FTP_PASS' => ['type' => 'fs'],
        'FTP_PLUGIN_DIR' => ['type' => 'fs'],
        'FTP_PRIKEY' => ['type' => 'fs'],
        'FTP_PUBKEY' => ['type' => 'fs'],
        'FTP_SSH' => ['type' => 'fs'],
        'FTP_SSL' => ['type' => 'fs'],
        'FTP_USER' => ['type' => 'fs'],
        'WP_PROXY_BYPASS_HOSTS' => ['type' => 'fs'],
        'WP_PROXY_HOST' => ['type' => 'fs'],
        'WP_PROXY_PASSWORD' => ['type' => 'fs'],
        'WP_PROXY_PORT' => ['type' => 'fs'],
        'WP_PROXY_USERNAME' => ['type' => 'fs'],
        'WP_HTTP_BLOCK_EXTERNAL' => ['type' => 'fs'],
        'WP_ACCESSIBLE_HOSTS' => ['type' => 'fs'],
        // Debug
        'SAVEQUERIES' => ['type' => 'debug'],
        'SCRIPT_DEBUG' => ['type' => 'debug'],
        'WP_DEBUG' => ['type' => 'debug'],
        'WP_DEBUG_DISPLAY' => ['type' => 'debug'],
        'WP_DEBUG_LOG' => ['type' => 'debug'],
        // Cache and compression
        'WP_CACHE' => ['type' => 'cache'],
        'COMPRESS_CSS' => ['type' => 'cache'],
        'COMPRESS_SCRIPTS' => ['type' => 'cache'],
        'CONCATENATE_SCRIPTS' => ['type' => 'cache'],
        'ENFORCE_GZIP' => ['type' => 'cache'],
        // Security and cookies
        'ADMIN_COOKIE_PATH' => ['type' => 'security'],
        'ALLOW_UNFILTERED_UPLOADS' => ['type' => 'security'],
        'AUTH_COOKIE' => ['type' => 'security'],
        'AUTH_KEY' => ['type' => 'security'],
        'AUTH_SALT' => ['type' => 'security'],
        'COOKIEHASH' => ['type' => 'security'],
        'COOKIEPATH' => ['type' => 'security'],
        'COOKIE_DOMAIN' => ['type' => 'security'],
        'CUSTOM_TAGS' => ['type' => 'security'],
        'DISALLOW_FILE_EDIT' => ['type' => 'security'],
        'DISALLOW_FILE_MODS' => ['type' => 'security'],
        'DISALLOW_UNFILTERED_HTML' => ['type' => 'security'],
        'FORCE_SSL_ADMIN' => ['type' => 'security'],
        'FORCE_SSL_LOGIN' => ['type' => 'security'],
        'LOGGED_IN_COOKIE' => ['type' => 'security'],
        'LOGGED_IN_KEY' => ['type' => 'security'],
        'LOGGED_IN_SALT' => ['type' => 'security'],
        'NONCE_KEY' => ['type' => 'security'],
        'NONCE_SALT' => ['type' => 'security'],
        'PASS_COOKIE' => ['type' => 'security'],
        'PLUGINS_COOKIE_PATH' => ['type' => 'security'],
        'SECURE_AUTH_COOKIE' => ['type' => 'security'],
        'SECURE_AUTH_KEY' => ['type' => 'security'],
        'SECURE_AUTH_SALT' => ['type' => 'security'],
        'SITECOOKIEPATH' => ['type' => 'security'],
        'TEST_COOKIE' => ['type' => 'security'],
        'USER_COOKIE' => ['type' => 'security'],
        // Multisite
        'ALLOW_SUBDIRECTORY_INSTALL' => ['type' => 'ms'],
        'BLOGUPLOADDIR' => ['type' => 'ms'],
        'BLOG_ID_CURRENT_SITE' => ['type' => 'ms'],
        'DOMAIN_CURRENT_SITE' => ['type' => 'ms'],
        'DIEONDBERROR' => ['type' => 'ms'],
        'ERRORLOGFILE' => ['type' => 'ms'],
        'MULTISITE' => ['type' => 'ms'],
        'NOBLOGREDIRECT' => ['type' => 'ms'],
        'PATH_CURRENT_SITE' => ['type' => 'ms'],
        'UPLOADBLOGSDIR' => ['type' => 'ms'],
        'SITE_ID_CURRENT_SITE' => ['type' => 'ms'],
        'SUBDOMAIN_INSTALL' => ['type' => 'ms'],
        'SUNRISE' => ['type' => 'ms'],
        'UPLOADS' => ['type' => 'ms'],
        'WPMU_ACCEL_REDIRECT' => ['type' => 'ms'],
        'WPMU_SENDFILE' => ['type' => 'ms'],
        'WP_ALLOW_MULTISITE' => ['type' => 'ms'],
        // Time
        'MINUTE_IN_SECONDS' => ['type' => 'time'],
        'HOUR_IN_SECONDS' => ['type' => 'time'],
        'DAY_IN_SECONDS' => ['type' => 'time'],
        'WEEK_IN_SECONDS' => ['type' => 'time'],
        'YEAR_IN_SECONDS' => ['type' => 'time']
    ];

    public function data() {

        $constants = get_defined_constants(true);
        $constants = $constants['user'];

        $type = '';
        foreach ($this->constants as $const => $args) {

            if ($type != $args['type']) {
                $value = '';
                switch ($args['type']) {
                    case 'base':
                        $value = __('Base', 'dev-studio');
                        break;
                    case 'statuses':
                        $value = __('Statuses', 'dev-studio');
                        break;
                    case 'paths':
                        $value = __('Directories and URLs', 'dev-studio');
                        break;
                    case 'db':
                        $value = __('Database', 'dev-studio');
                        break;
                    case 'template':
                        $value = __('Template', 'dev-studio');
                        break;
                    case 'fs':
                        $value = __('Filesystem and connections', 'dev-studio');
                        break;
                    case 'debug':
                        $value = __('Debug', 'dev-studio');
                        break;
                    case 'cache':
                        $value = __('Cache and compression', 'dev-studio');
                        break;
                    case 'security':
                        $value = __('Security and cookies', 'dev-studio');
                        break;
                    case 'ms':
                        $value = __('Multisite', 'dev-studio');
                        break;
                    case 'time':
                        $value = __('Time', 'dev-studio');
                        break;
                }
                $this->data[] = [
                    'type' => 'title',
                    'val' => $value
                ];
                $type = $args['type'];
            }

            $col1 = ['val' => $const];
            $col2 =  ['val' => Utils::get_const_value($const)];
            if (!defined($const)) $col2['original'] = true;

            $this->data[] = [
                'cols' => [ $col1, $col2 ]
            ];
        }

        // Other
        $this->data[] = [
            'type' => 'title',
            'val' => __('Other', 'dev-studio')
        ];

        foreach ($constants as $const => $value) {
            if (!isset($this->constants[$const])) {

                // Exclude me
                if (DevStudio()->exclude_me() && DevStudio()->me('constant', $const)) continue;

                $col1 = ['val' => $const];
                $col2 =  ['val' => Utils::get_const_value($const)];
                if (!defined($const)) $col2['original'] = true;

                $this->data[] = [
                    'cols' => [ $col1, $col2 ]
                ];

            }
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

/**
 * Wordpress.Overview -> Conditionals unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Conditionals extends Unit {

    public $name = 'Conditionals';
    public $title = 'Conditionals';

    public function data() {
        $this->data = Wordpress::conditionals();
    }

    public function html() {
        //return print_r($this->data, true);

        $this->data = Utils::get_cond_data($this->data);

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);
    }
}
