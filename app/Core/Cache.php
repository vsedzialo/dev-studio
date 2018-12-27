<?php
namespace DevStudio\Core;

/**
 * Cache class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Cache {

	public static $cache = [];

    /**
     * Load data from storage
     *
     * @param $args
     * @return mixed
     */
	public static function load( $args ) {
        if (!isset($args['checkpoint'])) $args['checkpoint'] = DevStudio()->checkpoint->checkpoint;
        if (empty($args['checkpoint']) || !isset($args['type'])) return null;

        if (!isset(self::$cache[$args['checkpoint']]) || !isset(self::$cache[$args['checkpoint']][$args['type']])) return null;

	    return self::$cache[$args['checkpoint']][$args['type']];
	}

    /**
     * Save data to storage
     *
     * @param $args
     * @return mixed
     */
    public static function save( $args ) {
        if (!isset($args['checkpoint'])) $args['checkpoint'] = DevStudio()->checkpoint->checkpoint;
        if (empty($args['checkpoint']) || !isset($args['type']) || !isset($args['data'])) return;

	    if (!isset(self::$cache[$args['checkpoint']])) self::$cache[$args['checkpoint']] = [];

        self::$cache[$args['checkpoint']][$args['type']] = $args['data'];
    }

}