<?php
namespace DS_AVAFields\Core;

/**
 * Utils class
 *
 * Any common useful functions
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Utils {

    public static function hex2rgb( $hex, $opacity = 1 ) {
        $hex = str_replace( "#", "", $hex );
        if ( strlen( $hex ) == 3 ) {
            $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
            $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
            $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
        } else {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }
        $rgba = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')';

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgba; // returns an array with the rgb values
    }

    public static function params_default( $params, $default ) {
        return array_replace_recursive( $default, $params );
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

    public static function yes_no( $value ) {
        if ( $value === 'on' || $value === 'yes' || $value === true || $value === 'true' || $value === 1 ) {
            return 'yes';
        } else {
            return 'no';
        }
    }
}