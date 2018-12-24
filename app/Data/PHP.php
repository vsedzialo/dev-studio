<?php
namespace DevStudio\Data;

/**
 * PHP data class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class PHP {

    public static $data;
    
    public static $err_lvl = [
        'E_ERROR',
        'E_WARNING',
        'E_PARSE',
        'E_NOTICE',
        'E_CORE_ERROR',
        'E_CORE_WARNING',
        'E_COMPILE_ERROR',
        'E_COMPILE_WARNING',
        'E_USER_ERROR',
        'E_USER_WARNING',
        'E_USER_NOTICE',
        'E_STRICT',
        'E_RECOVERABLE_ERROR',
        'E_DEPRECATED',
        'E_USER_DEPRECATED',
        'E_ALL',
    ];
    
    
    
    /**
     * Gather data
     *
     * @since 1.0.0
     */
    public static function data() {
        self::$data = [
            'version'               => phpversion(),
            'error_reporting'       => error_reporting(),
            'sapi_name'             => php_sapi_name(),
            'memory_limit'          => ini_get('memory_limit'),
            'upload_max_filesize'   => ini_get('upload_max_filesize'),
            'post_max_size'         => ini_get('post_max_size'),
            'display_errors'        => ini_get('display_errors'),
            'log_errors'            => ini_get('log_errors'),
            'extensions'            => self::extensions(),
            'memory'                => self::memory(),
            'max_execution_time'    => ini_get('max_execution_time')
        ];

        if (function_exists('php_ini_loaded_file')) {
            self::$data['ini_file'] = php_ini_loaded_file();
        }

        self::$data['error_levels'] = self::error_levels();
        
        return self::$data;
    }

    /**
     * Get errors levels
     *
     * @since 1.0.0
     * @return mixed
     */
    public static function error_levels() {
        foreach ( self::$err_lvl as $lvl ) {
            if ( defined( $lvl ) ) {
                $constant = constant( $lvl );
                $data[$lvl] = self::$data['error_reporting'] && $constant;
            }
        }
        return $data;
    }

    /**
     * Get loaded extensions
     *
     * @since 1.0.0
     * @return array
     */
    public static function extensions() {
        $data = [];
        if ( is_callable( 'get_loaded_extensions' ) ) {
            $exts = get_loaded_extensions();
            foreach ($exts as $extension) {
                $version = phpversion($extension);
                if ($extension=='mysqlnd') $version = explode(' ', $version)[1];
                $data[$extension] = [
                    'version' => $version
                ];
            }
        }
        return $data;
    }

    /**
     * Get memory usage
     *
     * @since 1.0.0
     * @return int
     */
    public static function memory() {
        if ( function_exists( 'memory_get_peak_usage' ) ) {
            return memory_get_peak_usage();
        } elseif ( function_exists( 'memory_get_usage' ) ) {
            return memory_get_usage();
        }
        return 0;
    }
}