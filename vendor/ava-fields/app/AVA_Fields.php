<?php
namespace DS_AVAFields;

use DS_AVAFields\Core\Utils;

/**
 * AVA Fields main class
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class AVA_Fields {

    /**
     * Version
     */
    public $version = '1.0.0';

    /**
     * Fields containers
     */
    public $containers = [];

    /**
     * Fields handlers
     */
    public $handlers = [];

    /**
     * Styles array
     */
    public $styles = [];

    /**
     * Scripts array
     */
    public $scripts = [];

    /**
     * Core singleton class
     */
    private static $instance;


    public $utils;


    /**
     * Class constructor
     *
     * @since  1.0.0
     */
    private function __construct() {
        // Load assets
        add_action( 'init', [ $this, 'loaded' ] );

        // Load assets
        add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'load_assets' ] );

        // Ajax hooks
        add_action( 'wp_ajax_avaf-save', [ '\DS_AVAFields\Core\Options', 'save' ] );
        add_action( 'wp_ajax_nopriv_avaf-save', [ '\DS_AVAFields\Core\Options', 'save' ] );
    }

    /**
     * Get the instane of AVA_Fields
     *
     * @since 1.0.0
     * @return self
     */
    public static function instance() {
        if ( ! ( self::$instance instanceof self ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Plugin initialization
     *
     * @since 1.0.0
     */
    public function init() {
    }

    /**
     * Plugin loaded
     *
     * @since 1.0.0
     */
    public function loaded() {
        do_action( 'ava-fields/init' );
    }

    /**
     * Create new container
     *
     * @since 1.0.0
     * @param $config
     * @return object
     */
    public function make( $type, $params ) {
        /*
        if ( !empty($params['access']) ) {
            $ac = new \DS_AVAFields\Core\Access( $params['access'] );
        }
        */

        switch($type) {
            case 'custom':
            case 'page':
            case 'dashboard-page':
            case 'theme-page':
            case 'posts-page':
            case 'media-page':
            case 'pages-page':
            case 'comments-page':
            case 'plugins-page':
            case 'users-page':
            case 'tools-page':
            case 'settings-page':
            case 'post-type-page':
                $container_class = '\DS_AVAFields\Containers\Page';
                break;
            case 'user-meta':
                $container_class = '\DS_AVAFields\Containers\User_Meta';
                break;
            case 'options-general':
                $container_class = '\DS_AVAFields\Containers\Options';
                break;
            default:
                wp_die('Wrong container type');
        }

        // Create container
        if ( class_exists( $container_class ) ) {
            $container = new $container_class( $type, $params );

            if ( $container ) {
                $this->containers[ $params['container']['id'] ] = $container;
            }
            return $container;
        }
    }

    /**
     * Create field statically
     *
     * @since 1.0.0
     * @param $type
     * @param array $params
     * @return mixed
     */
    public static function field( $type, $params=[] ) {
        $class_name = 'AVA_Field_'.str_replace('-', '_', $type);

        if ( ! class_exists( $class_name ) ) {
            $file = DS_AVA_Fields()->dir('fields') .  $type . '/' . $type . '.php';
            if ( file_exists( $file ) ) {
                require_once( $file );
            }
        }

        if ( class_exists( $class_name ) ) {
            return new $class_name( null, null, $params['id'], $params );
        }
    }

    /**
     * Get container instance by id
     *
     * @since 1.0.0
     * @param $container_id
     * @return object
     */
    public function container( $container_id ) {
        return $this->containers[ $container_id ];
    }

    /**
     * Get section instance by id
     *
     * @param $container_id
     * @param $section_id
     * @return object
     */
    public function section( $container_id, $section_id ) {
        return $this->containers[ $container_id ]->sections[ $section_id ];
    }

    /**
     * Add style
     *
     * @since 1.0.0
     * @param $handle
     * @param $src
     */
    public function add_style( $handle, $src ) {
        $this->styles[$handle] = $src;
    }

    /**
     * Add JS script
     *
     * @since 1.0.0
     * @param $handle
     * @param $src
     */
    public function add_script( $handle, $src  ) {
        $this->scripts[$handle] = $src;
    }

    /**
     * Enqueue scripts & styles
     *
     * @since 1.0.0
     */
    public function load_assets() {
        wp_enqueue_style( 'ava-fields', $this->url( 'assets' ) . 'css/styles.css', [], $this->version() );

        wp_enqueue_style( 'bootstrap-grid', $this->url( 'assets' ) . 'css/bootstrap-grid.css', [], $this->version() );

        wp_enqueue_style( 'ava-animate', $this->url( 'assets' ) . 'libs/animate/animate.css', [], $this->version() );

        wp_register_script( 'ava-fields', $this->url( 'assets' ) . 'js/scripts.js', [ 'jquery' ], $this->version(), true );
        wp_enqueue_script( 'ava-fields' );

        $this->load_handlers();
        $this->load_styles();
        $this->load_scripts();
    }

    /**
     * Enqueue fields handlers
     *
     * @since 1.0.0
     */
    public function load_handlers() {

        if ( ! empty( $this->handlers ) ) {
            $this->handlers = array_unique( $this->handlers );

            $content = '';
            foreach ( $this->handlers as $dir ) {
                $content .= trim( Utils::file_get_contents( $dir ) ) . "\r\n\r\n";
            }
            Utils::file_put_contents( $this->dir( 'assets' ) . 'js/handlers.js', $content );

            wp_register_script( 'ava-fields-handlers', $this->url( 'assets' ) . 'js/handlers.js', [
                'jquery',
                'ava-fields'
            ], $this->version(), true );
            wp_enqueue_script( 'ava-fields-handlers' );
        }
    }

    /**
     * Enqueue styles
     *
     * @since 1.0.0
     */
    public function load_styles() {

        if ( ! empty( $this->styles ) ) {
            $this->styles = array_unique( $this->styles );

            $content = '';
            foreach ( $this->styles as $dir ) {
                $content .= trim( Utils::file_get_contents( $dir ) ) . "\r\n\r\n";
            }
            Utils::file_put_contents( $this->dir( 'assets' ) . 'css/styles-bundle.css', $content );

            wp_enqueue_style( 'ava-fields-styles-bundle', $this->url( 'assets' ) . 'css/styles-bundle.css', [], $this->version() );
        }
    }

    /**
     * Enqueue scripts
     *
     * @since 1.0.0
     */
    public function load_scripts() {

        if ( ! empty( $this->scripts ) ) {
            $this->scripts = array_unique( $this->scripts );

            $content = '';
            foreach ( $this->scripts as $dir ) {
                $content .= trim( Utils::file_get_contents( $dir ) ) . "\r\n\r\n";
            }
            Utils::file_put_contents( $this->dir( 'assets' ) . 'js/scripts-bundle.css', $content );

            wp_enqueue_script( 'ava-fields-scripts-bundle', $this->url( 'assets' ) . 'js/scripts-bundle.css', ['jquery'], $this->version(), true );
        }
    }

    /**
     * Get plugin directories
     *
     * @since 1.0.0
     * @param null $type
     * @return string
     */
    public function dir( $type = null ) {
        switch ( $type ) {
            case 'assets':
                return $this->dir( 'ava-fields' ) . 'assets/';
                break;
            case 'fields':
                return $this->dir( 'ava-fields' ) . 'fields/';
                break;
            case 'icons':
                return $this->dir( 'ava-fields' ) . 'assets/images/icons/';
                break;
            default:
                return __DIR__ . '/../';
        }
    }

    /**
     * Get plugin urls
     *
     * @since 1.0.0
     * @param null $type
     * @return string
     */
    public function url( $type = null ) {
        switch ( $type ) {
            case 'assets':
                return plugin_dir_url( __FILE__ ) . '../assets/';
                break;
            case 'fields':
                return plugin_dir_url( __FILE__ ) . '../fields/';
                break;
            case 'icons':
                return plugin_dir_url( __FILE__ ) . '../assets/images/icons/';
                break;
            default:
                return plugin_dir_url( __FILE__ );
        }
    }

    /**
     * Get plugin current version
     *
     * @since 1.0.0
     * @return string
     */
    public function version() {
        return $this->version;
    }

    /**
     * Cloning disabled
     */
    private function __clone() {
    }

    /**
     * Serialization disabled
     */
    public function __sleep() {
        return [];
    }

    /**
     * De-serialization disabled
     */
    public function __wakeup() {
    }
}