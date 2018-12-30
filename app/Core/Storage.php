<?php
namespace DevStudio\Core;

use DevStudio\Helpers\Utils;

/**
 * Storage class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Storage {

    private static $cipher = "aes-192-ecb";

    private static function is_openssl() {
        $opts = DevStudio()->options();
        return isset($opts['data']['general']['openssl']) && $opts['data']['general']['openssl'] === 'yes';
    }

    /**
     * Load data to file
     *
     * @since 1.0.0
     * @param $fname
     */
    public static function save($fname, $data, $key = '') {

        if (!$key) $key = Utils::key($fname);

        DevStudio()->stats['data'][$key]['save_start'] = microtime( true );

        if (self::is_openssl()) {
            $data = self::encrypt($data);
        }
        file_put_contents($fname, $data);

        if (file_exists($fname)) {
            @chmod( $fname, self::file_permission() );
            DevStudio()->stats['data'][$key]['size'] = filesize($fname);
        }

        DevStudio()->stats['data'][$key]['save_end'] = microtime( true );
    }

    /**
     * Load data from file
     *
     * @since 1.0.0
     * @param $fname
     * @return mixed
     */
    public static function load($fname) {

        if (file_exists($fname)) {
            $data = file_get_contents($fname);

            if (self::is_openssl()) {
                $data = self::decrypt($data);
            }
            return $data;
        } else
            return null;
    }

    public static function encrypt( $data) {

        return openssl_encrypt($data, self::$cipher, AUTH_KEY);
    }

    public static function decrypt( $data ) {

        return openssl_decrypt($data, self::$cipher, AUTH_KEY);
    }

    /**
     * Remove previous data
     *
     * @since 1.0.0
     * @param string $mode
     */
    public static function remove_data($mode='') {

        // Exclude app system request
        if ( DevStudio()->me() ) return;

        // Exclude WP system AJAX query
        if (Utils::exclude_wp_ajax()) return;

        if (empty($mode)) $mode = DevStudio()->mode;

        $storage_dir = DevStudio()->dir('storage');
        self::mkdir($storage_dir);
        self::rmdir( $storage_dir . 'data/' . $mode );

        if ($mode==='admin' || $mode==='public') {
            self::rmdir( $storage_dir . 'data/ajax_' . $mode );
        }

        self::mkdir($storage_dir . 'data');
        self::mkdir($storage_dir . 'data/' . $mode);
    }
    
    /**
     * Remove all data
     *
     * @since 1.0.0
     * @param string $mode
     */
    public static function remove_all_data() {
        $storage_dir = DevStudio()->dir('storage');
        self::rmdir( $storage_dir );
    }

    /**
     * Create directories for checkpoint
     *
     * @since 1.0.0
     */
    public static function mkdirs($checkpoint) {
        $storage_dir = DevStudio()->dir('storage');

        $checkpoint_mode_dir = $storage_dir . 'data/' . DevStudio()->mode;
        Storage::mkdir($checkpoint_mode_dir);

        $checkpoint_dir = $checkpoint_mode_dir . '/' . $checkpoint;
        Storage::mkdir($checkpoint_dir);

        // Global
        //$global_dir = $storage_dir . 'data/global';
        //Storage::mkdir($global_dir);
    }

    /**
     * Make directory
     *
     * @since 1.0.0
     * @param $dir
     */
    public static function mkdir($dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, self::directory_permission(), true);
        }
        if (is_dir($dir) && !file_exists($dir.'/index.html')) {
            self::file_put_contents($dir.'/index.html', '');
        }
    }

    /**
     * Remove directories recursively
     *
     * @since 1.0.0
     * @param $dir
     */
    public static function rmdir( $dir ) {
        if (is_dir($dir)) {
            $it    = new \RecursiveDirectoryIterator( $dir, \RecursiveDirectoryIterator::SKIP_DOTS );
            $files = new \RecursiveIteratorIterator( $it, \RecursiveIteratorIterator::CHILD_FIRST );
            foreach ( $files as $file ) {
                if ( $file->isDir() ) {
                    @rmdir( $file->getRealPath() );
                } else {
                    @unlink( $file->getRealPath() );
                }
            }
            @rmdir( $dir );
        }
    }

    public static function file_get_contents( $fname ) {
        if ( ! file_exists( $fname ) ) {
            return '';
        }

        return file_get_contents( $fname );
    }

    public static function file_put_contents( $fname, $content ) {
        return file_put_contents( $fname, $content );
    }
    
    /**
	 * Get directory permissions
	 *
	 * @return int
	 */
    public static function directory_permission() {
		if ( defined( 'FS_CHMOD_DIR' ) ) {
			return FS_CHMOD_DIR;
		}
		return 0755;
	}

	/**
	 * Get file permissions
	 *
	 * @return int
	 */
	public static function file_permission() {
		if ( defined( 'FS_CHMOD_FILE' ) ) {
			return FS_CHMOD_FILE;
		}
		return 0644;
	}

}