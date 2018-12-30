<?php
namespace DevStudio\Core;

use DevStudio\Helpers\Utils;

/**
 * Template class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Template {

	public function __construct() {
	}

	public function load( $tmpl_name, $data = [] ) {

		$fname = DevStudio()->dir('templates') . $tmpl_name.'.php';
		if (!file_exists($fname)) return;

		ob_start();
		require( $fname );
        return ob_get_clean();
		//return preg_replace('# {2,}#', ' ', ob_get_clean());
	}

    function add_info_block($id, $val) {
        $html = '';
        $html .= '<div id="info-'.esc_attr($id).'" style="display:none">';
        //$html .= '<h3></h3';
        if (is_array($val))
            $html .= '<pre class="array">'.print_r($val, true).'</pre>';
        else if (is_object($val))
            $html .= '<pre class="object">'.print_r($val, true).'</pre>';
        else
            $html .= $val;
        $html .= '</div>';

        return $html;
    }

}