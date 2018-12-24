<?php
namespace DevStudio\Core;

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

	public static $storage = [];

    /**
     * Load data from storage
     *
     * @param $args
     * @return mixed
     */
	public static function load( $args ) {
        if (!isset($args['checkpoint'])) $args['checkpoint'] = DevStudio()->checkpoint->checkpoint;
        if (empty($args['checkpoint']) || !isset($args['type'])) return null;

        if (!isset(self::$storage[$args['checkpoint']]) || !isset(self::$storage[$args['checkpoint']][$args['type']])) return null;

	    return self::$storage[$args['checkpoint']][$args['type']];
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

	    if (!isset(self::$storage[$args['checkpoint']])) self::$storage[$args['checkpoint']] = [];

        self::$storage[$args['checkpoint']][$args['type']] = $args['data'];
    }

}