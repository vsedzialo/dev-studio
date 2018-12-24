<?php
namespace DS_AVAFields\Core;

/**
 * Data class
 *
 * Data storage
 *
 * @category   Wordpress
 * @package    ava-fields
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Data {

    public static function animations( $type = '' ) {
        $animations = [
            'bounce'             => [ 'type' => 'in' ],
            'flash'              => [ 'type' => 'in' ],
            'pulse'              => [ 'type' => 'in' ],
            'rubberBand'         => [ 'type' => 'in' ],
            'shake'              => [ 'type' => 'in' ],
            'headShake'          => [ 'type' => 'in' ],
            'swing'              => [ 'type' => 'in' ],
            'tada'               => [ 'type' => 'in' ],
            'wobble'             => [ 'type' => 'in' ],
            'jello'              => [ 'type' => 'in' ],
            'bounceIn'           => [ 'type' => 'in' ],
            'bounceInDown'       => [ 'type' => 'in' ],
            'bounceInLeft'       => [ 'type' => 'in' ],
            'bounceInRight'      => [ 'type' => 'in' ],
            'bounceInUp'         => [ 'type' => 'in' ],
            'bounceOut'          => [ 'type' => 'out' ],
            'bounceOutDown'      => [ 'type' => 'out' ],
            'bounceOutLeft'      => [ 'type' => 'out' ],
            'bounceOutRight'     => [ 'type' => 'out' ],
            'bounceOutUp'        => [ 'type' => 'out' ],
            'fadeIn'             => [ 'type' => 'in' ],
            'fadeInDown'         => [ 'type' => 'in' ],
            'fadeInDownBig'      => [ 'type' => 'in' ],
            'fadeInLeft'         => [ 'type' => 'in' ],
            'fadeInLeftBig'      => [ 'type' => 'in' ],
            'fadeInRight'        => [ 'type' => 'in' ],
            'fadeInRightBig'     => [ 'type' => 'in' ],
            'fadeInUp'           => [ 'type' => 'in' ],
            'fadeInUpBig'        => [ 'type' => 'in' ],
            'fadeOut'            => [ 'type' => 'out' ],
            'fadeOutDown'        => [ 'type' => 'out' ],
            'fadeOutDownBig'     => [ 'type' => 'out' ],
            'fadeOutLeft'        => [ 'type' => 'out' ],
            'fadeOutLeftBig'     => [ 'type' => 'out' ],
            'fadeOutRight'       => [ 'type' => 'out' ],
            'fadeOutRightBig'    => [ 'type' => 'out' ],
            'fadeOutUp'          => [ 'type' => 'out' ],
            'fadeOutUpBig'       => [ 'type' => 'out' ],
            'flip'               => [ 'type' => 'in' ],
            'flipInX'            => [ 'type' => 'in' ],
            'flipInY'            => [ 'type' => 'in' ],
            'flipOutX'           => [ 'type' => 'out' ],
            'flipOutY'           => [ 'type' => 'out' ],
            'lightSpeedIn'       => [ 'type' => 'in' ],
            'lightSpeedOut'      => [ 'type' => 'out' ],
            'rotateIn'           => [ 'type' => 'in' ],
            'rotateInDownLeft'   => [ 'type' => 'in' ],
            'rotateInDownRight'  => [ 'type' => 'in' ],
            'rotateInUpLeft'     => [ 'type' => 'in' ],
            'rotateInUpRight'    => [ 'type' => 'in' ],
            'rotateOut'          => [ 'type' => 'out' ],
            'rotateOutDownLeft'  => [ 'type' => 'out' ],
            'rotateOutDownRight' => [ 'type' => 'out' ],
            'rotateOutUpLeft'    => [ 'type' => 'out' ],
            'rotateOutUpRight'   => [ 'type' => 'out' ],
            'hinge'              => [ 'type' => 'in' ],
            'jackInTheBox'       => [ 'type' => 'in' ],
            'rollIn'             => [ 'type' => 'in' ],
            'rollOut'            => [ 'type' => 'out' ],
            'zoomIn'             => [ 'type' => 'in' ],
            'zoomInDown'         => [ 'type' => 'in' ],
            'zoomInLeft'         => [ 'type' => 'in' ],
            'zoomInRight'        => [ 'type' => 'in' ],
            'zoomInUp'           => [ 'type' => 'in' ],
            'zoomOut'            => [ 'type' => 'out' ],
            'zoomOutDown'        => [ 'type' => 'out' ],
            'zoomOutLeft'        => [ 'type' => 'out' ],
            'zoomOutRight'       => [ 'type' => 'out' ],
            'zoomOutUp'          => [ 'type' => 'out' ],
            'slideInDown'        => [ 'type' => 'in' ],
            'slideInLeft'        => [ 'type' => 'in' ],
            'slideInRight'       => [ 'type' => 'in' ],
            'slideInUp'          => [ 'type' => 'in' ],
            'slideOutDown'       => [ 'type' => 'out' ],
            'slideOutLeft'       => [ 'type' => 'out' ],
            'slideOutRight'      => [ 'type' => 'out' ],
            'slideOutUp'         => [ 'type' => 'out' ],
        ];

        $result = [];
        if ( $type === 'in' || $type === 'out' ) {
            foreach ( $animations as $animation => $params ) {
                if ( $type == $params['type'] ) {
                    $result[ $animation ] = $params;
                }
            }
            return $result;
        }

        return $animations;

    }

    public static function standart_fonts() {
        $data = [
            'Arial, Helvetica, sans-serif' 			=> 'Arial, Helvetica',
            'Arial Black, Gadget, sans-serif' 		=> 'Arial Black',
            'Bookman Old Style, serif' 				=> 'Bookman Old Style',
            'Comic Sans MS, cursive' 				=> 'Comic Sans MS',
            'Courier, monospace' 					=> 'Courier',
            'Garamond, serif' 						=> 'Garamond',
            'Georgia, serif' 						=> 'Georgia',
            'Impact, Charcoal, sans-serif' 			=> 'Impact, Charcoal',
            'Lucida Console, Monaco, monospace' 	=> 'Lucida Console',
            'Lucida Sans Unicode, Lucida Grande, sans-serif' => 'Lucida Sans Unicode',
            'MS Sans Serif, Geneva, sans-serif' 	=> 'MS Sans Serif',
            'MS Serif, New York, sans-serif' 		=> 'MS Serif, New York',
            'Palatino Linotype, Book Antiqua, Palatino, serif' => 'Palatino Linotype',
            'Tahoma,Geneva, sans-serif' 			=> 'Tahoma, Geneva',
            'Times New Roman, Times,serif' 			=> 'Times New Roman',
            'Trebuchet MS, Helvetica, sans-serif' 	=> 'Trebuchet MS, Helvetica',
            'Verdana, Geneva, sans-serif' 			=> 'Verdana, Geneva',
        ];
        return $data;
    }
}