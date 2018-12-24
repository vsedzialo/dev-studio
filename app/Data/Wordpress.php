<?php
namespace DevStudio\Data;

use DevStudio\Core\Storage;
use DevStudio\Helpers\Utils;

/**
 * Wordpress data class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Wordpress {

    public static $disabled  = [];

    /**
     * Wordpress conditionals
     *
     * @since 1.0.0
     * @return mixed
     */
    public static function conditionals() {
        global $wp_query;

        if ( ! isset( $wp_query ) ) return [];

        //if (!function_exists('did_action')) return [];
        //if (DevStudio()->mode === 'public' && !did_action('parse_query')) return [];

        return [
            'is_page()' => Utils::get_cond_func('is_page'),
            'is_admin()' => Utils::get_cond_func('is_admin'),
            'is_archive()' => Utils::get_cond_func('is_archive'),
            'is_attachment()' => Utils::get_cond_func('is_attachment'),
            'is_blog_admin()' => Utils::get_cond_func('is_blog_admin'),
            'is_author()' => Utils::get_cond_func('is_author'),
            'is_category()' => Utils::get_cond_func('is_category'),
            'is_comment_feed()' => Utils::get_cond_func('is_comment_feed'),
            'is_customize_preview()' => Utils::get_cond_func('is_customize_preview'),
            'is_date()' => Utils::get_cond_func('is_date'),
            'is_day()' => Utils::get_cond_func('is_day'),
            'is_embed()' => Utils::get_cond_func('is_embed'),
            'is_feed()' => Utils::get_cond_func('is_feed'),
            'is_front_page()' => Utils::get_cond_func('is_front_page'),
            'is_home()' => Utils::get_cond_func('is_home'),
            'is_month()' => Utils::get_cond_func('is_month'),
            'is_multisite()' => Utils::get_cond_func('is_multisite'),
            'is_network_admin()' => Utils::get_cond_func('is_network_admin'),

            'is_page_template()' => Utils::get_cond_func('is_page_template'),
            'is_paged()' => Utils::get_cond_func('is_paged'),
            'is_post_type_archive()' => Utils::get_cond_func('is_post_type_archive'),
            'is_preview()' => Utils::get_cond_func('is_preview'),
            'is_robots()' => Utils::get_cond_func('is_robots'),
            'is_rtl()' => Utils::get_cond_func('is_rtl'),
            'is_search()' => Utils::get_cond_func('is_search'),
            'is_single()' => Utils::get_cond_func('is_single'),
            'is_singular()' => Utils::get_cond_func('is_singular'),
            'is_ssl()' => Utils::get_cond_func('is_ssl'),
            'is_sticky()' => Utils::get_cond_func('is_sticky'),
            'is_tag()' => Utils::get_cond_func('is_tag'),
            'is_tax()' => Utils::get_cond_func('is_tax'),
            'is_time()' => Utils::get_cond_func('is_time'),
            'is_trackback()' => Utils::get_cond_func('is_trackback'),
            'is_user_admin()' => Utils::get_cond_func('is_user_admin'),
            'is_year()' => Utils::get_cond_func('is_year'),
            'is_404()' => Utils::get_cond_func('is_404'),
            'wp_is_mobile()' => Utils::get_cond_func('wp_is_mobile')
        ];
    }


}