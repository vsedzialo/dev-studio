<?php
namespace DevStudio\Data;

use DevStudio\Helpers\Utils;

/**
 * WooCommerce data class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class WooCommerce {

    public static $disabled  = [
        'is_woocommerce()',
        'is_store_notice_showing()',
        'is_filtered()',
        'wc_tax_enabled()',
        'wc_shipping_enabled()',
        'wc_prices_include_tax()',
        'wc_site_is_https()',
        'wc_checkout_is_https()'
    ];

    /**
     * Conditionals
     *
     * @since 1.0.0
     * @return mixed
     */
    public static function conditionals() {

        if (!function_exists('did_action')) return [];
        if (DevStudio()->mode === 'public' && !did_action('parse_query')) return [];

        return [
            'is_woocommerce()' => Utils::get_cond_func('is_woocommerce'),
            'is_shop()' => Utils::get_cond_func('is_shop'),
            'is_product_taxonomy()' => Utils::get_cond_func('is_product_taxonomy'),
            'is_product()' => Utils::get_cond_func('is_product'),
            'is_cart()' => Utils::get_cond_func('is_cart'),
            'is_checkout()' => Utils::get_cond_func('is_checkout'),
            'is_checkout_pay_page()' => Utils::get_cond_func('is_checkout_pay_page'),
            'is_account_page()' => Utils::get_cond_func('is_account_page'),
            'is_view_order_page()' => Utils::get_cond_func('is_view_order_page'),
            'is_edit_account_page()' => Utils::get_cond_func('is_edit_account_page'),
            'is_order_received_page()' => Utils::get_cond_func('is_order_received_page'),
            'is_add_payment_method_page()' => Utils::get_cond_func('is_add_payment_method_page'),
            'is_lost_password_page()' => Utils::get_cond_func('is_lost_password_page'),
            'is_store_notice_showing()' => Utils::get_cond_func('is_store_notice_showing'),
            'is_filtered()' => Utils::get_cond_func('is_filtered'),
            'wc_tax_enabled()' => Utils::get_cond_func('wc_tax_enabled'),
            'wc_shipping_enabled()' => Utils::get_cond_func('wc_shipping_enabled'),
            'wc_prices_include_tax()' => Utils::get_cond_func('wc_prices_include_tax'),
            'wc_site_is_https()' => Utils::get_cond_func('wc_site_is_https'),
            'wc_checkout_is_https()' => Utils::get_cond_func('wc_checkout_is_https')
        ];
    }

}