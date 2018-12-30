<?php
/*
 * Dev Studio plugin for Wordpress
 *
 * @package   dev-studio
 * @link      https://wordpress.org/plugins/dev-studio/
 * @author    Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @copyright 2018 Viktor Sedzialo
 * @license   GPL v2 or later
 *
 * Plugin Name: Dev Studio
 * Description: Development environment for Wordpress developers
 * Version:     1.0.0
 * Plugin URI:  https://wordpress.org/plugins/dev-studio/
 * Author:      SolidBunch <solidbunch@gmail.com>
 * Author URI:  https://solidbunch.com
 * Text Domain: dev-studio
 * Requires PHP: 7.0
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'DEV_STUDIO_VERSION' ) ) {
	define( 'DEV_STUDIO_VERSION', '1.0' );
}
if ( ! defined( 'DEV_STUDIO_PLUGIN_DIR' ) ) {
    define( 'DEV_STUDIO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'DEV_STUDIO_PLUGIN_URL' ) ) {
    define( 'DEV_STUDIO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'SAVEQUERIES' ) ) {
    define( 'SAVEQUERIES', true );
}

/**
 * Autoload register
 *
 * @return void
 */
spl_autoload_register( function ( $class ) {

    // project-specific namespace prefix
    $prefix = 'DevStudio\\';
    $base_dir = __DIR__ . '/app/';

    // does the class use the namespace prefix?
    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr( $class, $len );
    $file = $base_dir . str_replace( ['\\','_'], ['/','-'], $relative_class ) . '.php';

    // if the file exists, require it
    if ( file_exists( $file ) ) {
        require $file;
    }
} );

register_deactivation_hook( __FILE__, function() {
    \DevStudio\Core\Storage::remove_all_data();
});

if ( ! function_exists( 'DevStudio' ) ) {
	function DevStudio() {
		return \DevStudio\DevStudio::instance();
	}
}
DevStudio()->init();