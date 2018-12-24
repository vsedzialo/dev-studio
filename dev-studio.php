<?php
/*
 * Dev Studio plugin for Wordpress
 *
 * @package   dev-studio
 * @link      https://github.com/vsedzialo/dev-studio
 * @author    Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @copyright 2018 Viktor Sedzialo
 * @license   MIT
 *
 * Plugin Name: Dev Studio
 * Description: Development environment for Wordpress developers
 * Version:     1.0.0
 * Plugin URI:  https://github.com/vsedzialo/dev-studio
 * Author:      Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * Author URI:  https://github.com/vsedzialo
 * Text Domain: dev-studio
 *
 * Copyright (c) 2018 Viktor Sedzialo
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
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

if ( ! function_exists( 'DevStudio' ) ) {
	function DevStudio() {
		return \DevStudio\DevStudio::instance();
	}
}
DevStudio()->init();