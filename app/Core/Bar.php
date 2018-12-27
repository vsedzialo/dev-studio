<?php
namespace DevStudio\Core;

/**
 * Bar class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Bar {

    /**
     * Bar constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

    }

    /**
     * Form Bar data
     *
     * @since 1.0.0
     */
    public function bar_data() {
        // Get page data
        $data = DevStudio()->app_load('page', true, 'app');
        $items = DevStudio()->options()['bar']['items'];

        $html = '';

        // Page Generation Time
        if (is_array($data)) {
            if (!empty($items['page_time']) && $items['page_time']==='yes' && !empty($data['page_time'])) {
                $time = function_exists( 'number_format_i18n' ) ? number_format_i18n( $data['page_time'], 2 ) : number_format( $data['page_time'], 2 );
                $html .= '<div><span class="fa fa-clock-o"></span>'.$time.'s</div>';
            }
            if (!empty($items['db_queries_count']) && $items['db_queries_count']==='yes' && !empty($data['db_queries_count'])) {
                $html .= '<div><span class="fa fa-database"></span>'.$data['db_queries_count'].'Q</div>';
            }
            if (!empty($items['db_queries_time']) && $items['db_queries_time']==='yes' && !empty($data['db_queries_time'])) {
                $time = function_exists( 'number_format_i18n' ) ? number_format_i18n( $data['db_queries_time'], 5 ) : number_format( $data['db_queries_time'], 5 );
                $html .= '<div><span class="fa fa-database"></span><span class="fa fa-clock-o"></span>'.$time.'s</div>';
            }
        }

        // Wordpress Conditionals
        if (!empty($items['conditionals']) && $items['conditionals']==='yes' && !empty($data['conditionals'])) {
            $html .= '<div class="ds-lightest-blue"><span class="fa fa-wordpress"></span> ';
            $html .= implode(', ', $data['conditionals']);
            $html .= '</div>';
        }

        // WooCommerce Conditionals
        if (!empty($items['wc_conditionals']) && $items['wc_conditionals']==='yes' && !empty($data['wc_conditionals'])) {
            $html .= '<div class="ds-light-yellow"><span class="fa fa-shopping-cart"></span> ';
            $html .= implode(', ', $data['wc_conditionals']);
            $html .= '</div>';
        }

        return $html;
    }
}