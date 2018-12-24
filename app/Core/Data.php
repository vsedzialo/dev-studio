<?php
namespace DevStudio\Core;

/**
 * Data class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Data {

    public $data = [];

    public $key;

    public static $units = [
        // PHP
        'PHP.Variables.Server',
        'PHP.Variables.Get',
        'PHP.Variables.Post',
        'PHP.Variables.Cookie',
        'PHP.Variables.Session',
        'PHP.Variables.Files',
        'PHP.Variables.Env',
        'PHP.Variables.Globals',

        'PHP.Constants.Constants',
        'PHP.Files.Included_Files',
        'PHP.Files.Components',
        'PHP.PHPInfo.PHPInfo',

        // Wordpress
        'Wordpress.Overview.Overview',
        'Wordpress.Overview.Conditionals',
        'Wordpress.Overview.Constants',

        'Wordpress.Variables.Browser',
        'Wordpress.Variables.Server',

        'Wordpress.Actions.Actions',
        'Wordpress.Filters.Filters',

        'Wordpress.Theme.Menu_Locations',
        'Wordpress.Theme.Menus',
        'Wordpress.Theme.Sidebars',
        'Wordpress.Theme.Widgets',

        'Wordpress.Shortcodes.Shortcodes',

        'Wordpress.Styles.Enqueued',
        'Wordpress.Styles.Registered',
        'Wordpress.Styles.WP_Styles',

        'Wordpress.Scripts.Enqueued',
        'Wordpress.Scripts.Registered',
        'Wordpress.Scripts.WP_Scripts',

        'Wordpress.Rewrite.Rules',
        'Wordpress.Rewrite.WP_Rewrite',

        'Wordpress.Locale.WP_Locale',

        'Wordpress.Roles.Roles',
        'Wordpress.Roles.WP_Roles',

        // WooCommerce
        'WooCommerce.Overview.Conditionals',
        'WooCommerce.Overview.Constants',
        'WooCommerce.Overview.Options',

        // MySQL
        'MySQL.Overview.Tables',
        'MySQL.Overview.Variables',

        'MySQL.Queries.Queries',
        'MySQL.Queries.Callers',
    ];

    public static $actions = [
        // WP native actions
        'public' => [
            'muplugins_loaded',
            'registered_taxonomy',
            'registered_post_type',
            'plugins_loaded',
            'sanitize_comment_cookies',
            'setup_theme',
            'load_textdomain',
            'after_setup_theme',
            'auth_cookie_malformed',
            'auth_cookie_valid',
            'set_current_user',
            'init',
            'widgets_init',
            'register_sidebar',
            'wp_register_sidebar_widget',
            'wp_default_scripts',
            'wp_default_styles',
            'admin_bar_init',
            'add_admin_bar_menus',
            'wp_loaded',
            'parse_request',
            'send_headers',
            'parse_query',
            'pre_get_posts',
            'posts_selection',
            'wp',
            'template_redirect',
            'get_header',
            'wp_enqueue_scripts',
            'wp_head',
            'wp_print_styles',
            'wp_print_scripts',
            'get_search_form',
            'loop_start',
            'the_post',
            'get_template_part_content',
            'loop_end',
            'get_sidebar',
            'dynamic_sidebar',
            'get_search_form',
            'pre_get_comments',
            'wp_meta',
            'get_footer',
            'get_sidebar',
            'wp_footer',
            'wp_print_footer_scripts',
            'admin_bar_menu',
            'wp_before_admin_bar_render',
            'wp_after_admin_bar_render',
            'shutdown'
        ],
        'admin' => [
            'muplugins_loaded',
            'registered_taxonomy',
            'registered_post_type',
            'plugins_loaded',
            'sanitize_comment_cookies',
            'setup_theme',
            'load_textdomain',
            'after_setup_theme',
            'auth_cookie_valid',
            'set_current_user',
            'init',
            'widgets_init',
            'register_sidebar',
            'wp_register_sidebar_widget',
            'wp_default_scripts',
            'wp_default_styles',
            'admin_bar_init',
            'add_admin_bar_menus',
            'wp_loaded',
            'auth_cookie_valid',
            'auth_redirect',
            'admin_menu',
            'user_admin_menu',
            'network_admin_menu',
            'admin_init',
            'current_screen',
            'load-(page)',
            'send_headers',
            'pre_get_posts',
            'posts_selection',
            'wp',
            'admin_xml_ns',
            'admin_enqueue_scripts',
            'admin_print_styles-(hookname)',
            'admin_print_styles',
            'admin_print_scripts-(hookname)',
            'admin_print_scripts',
            'wp_print_scripts',
            'admin_head-(hookname)',
            'admin_head',
            'admin_menu',
            'in_admin_header',
            'admin_notices',
            'all_admin_notices',
            'restrict_manage_posts',
            'the_post',
            'pre_user_query',
            'in_admin_footer',
            'admin_footer',
            'admin_bar_menu',
            'wp_before_admin_bar_render',
            'wp_after_admin_bar_render',
            'admin_print_footer_scripts',
            'admin_footer-(hookname)',
            'shutdown',
            'wp_dashboard_setup'
        ]
    ];

    /**
     * Get data by key
     *
     * @since 1.0.0
     * @param $key
     * @return mixed
     */
    public function get($key) {
        $this->key = $key;
        if (!empty($this->data[$key]))
            return $this->data[$key];
        else {
            $method = 'data_' . $key;
            if (method_exists($this, $method)) {
                return $this->$method();
            }

        }
    }

    /**
     * Set data with key
     *
     * @since 1.0.0
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * Get units array
     *
     * @since 1.0.0
     * @return array
     */
    public static function units() {
        return self::$units;
    }
}