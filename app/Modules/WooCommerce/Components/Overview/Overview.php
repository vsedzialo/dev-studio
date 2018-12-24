<?php
namespace DevStudio\Modules\WooCommerce\Components\Overview;

use DevStudio\Core\Abstracts\Component;
use DevStudio\Core\Abstracts\Unit;
use DevStudio\Helpers\Utils;
use DevStudio\Data\WooCommerce;

/**
 * WooCommerce.Overview component class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Overview extends Component {

	public $name = 'Overview';
    public $title = 'Overview';

	public function __construct(  ) {

		parent::__construct(  );

	}
}

/**
 * WooCommerce.Overview -> Overview unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Overview extends Unit {

    public $name = 'Overview';
    public $title = 'Overview';

    public function data() {

        $conditionals = [
            'is_woocommerce()' => function_exists('is_woocommerce') && is_woocommerce() ? true : false,
            'is_shop()' => function_exists('is_shop') && is_shop() ? true : false,
            'is_product_taxonomy()' => function_exists('is_product_taxonomy') && is_product_taxonomy() ? true : false,
            'is_product()' => function_exists('is_product') && is_product() ? true : false,
            'is_cart()' => function_exists('is_cart') && is_cart() ? true : false,
            'is_checkout()' => function_exists('is_checkout') && is_checkout() ? true : false,
            'is_checkout_pay_page()' => function_exists('is_checkout_pay_page') && is_checkout_pay_page() ? true : false,
            'is_account_page()' => function_exists('is_account_page') && is_account_page() ? true : false,
            'is_view_order_page()' => function_exists('is_view_order_page') && is_view_order_page() ? true : false,
            'is_edit_account_page()' => function_exists('is_edit_account_page') && is_edit_account_page() ? true : false,
            'is_order_received_page()' => function_exists('is_order_received_page') && is_order_received_page() ? true : false,
            'is_add_payment_method_page()' => function_exists('is_add_payment_method_page') && is_add_payment_method_page() ? true : false,
            'is_lost_password_page()' => function_exists('is_lost_password_page') && is_lost_password_page() ? true : false,
            'is_store_notice_showing()' => function_exists('is_store_notice_showing') && is_store_notice_showing() ? true : false,
            'is_filtered()' => function_exists('is_filtered') && is_filtered() ? true : false,
            'wc_tax_enabled()' => function_exists('wc_tax_enabled') && wc_tax_enabled() ? true : false,
            'wc_shipping_enabled()' => function_exists('wc_shipping_enabled') && wc_shipping_enabled() ? true : false,
            'wc_prices_include_tax()' => function_exists('wc_prices_include_tax') && wc_prices_include_tax() ? true : false,
            'wc_site_is_https()' => function_exists('wc_site_is_https') && wc_site_is_https() ? true : false,
            'wc_checkout_is_https()' => function_exists('wc_checkout_is_https') && wc_checkout_is_https() ? true : false,
        ];

        $true = [];
        $false = [];
        foreach ($conditionals as $key => $value) {
            if ($value) {
                $true[] = $key;
            } else {
                $false[] = $key;
            }
        }

        foreach ($true as $key) {
            $this->data[] = [
                'class' => 'mark',
                'cols' => [
                    ['val' => $key],
                    ['val' => __('True', 'dev-studio')]
                ]
            ];
        }
        foreach ($false as $key) {
            $this->data[] = [
                'class' => 'mask',
                'cols' => [
                    ['val' => $key],
                    ['val' => __('False', 'dev-studio')]
                ]
            ];
        }
    }

    public function html() {
        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }

}

/**
 * WooCommerce.Overview -> Conditionals unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Conditionals extends Unit {

    public $name = 'Conditionals';
    public $title = 'Conditionals';

    public function data() {

        $this->data = WooCommerce::conditionals();
    }

    public function html() {

        $this->data = Utils::get_cond_data($this->data);

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }

}

/**
 * WooCommerce.Overview -> Constants unit class
 *
 * @category   Wordpress
 * @package    dev-studio
 * @author     Viktor Sedzialo <viktor.sedzialo@gmail.com>
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Unit_Constants extends Unit {

    public $name = 'Constants';
    public $title = 'Constants';

    public $constants = [
        'WC_PLUGIN_FILE' => ['type' => 'wc'],
        'WC_ABSPATH' => ['type' => 'wc'],
        'WC_PLUGIN_BASENAME' => ['type' => 'wc'],
        'WC_VERSION' => ['type' => 'wc'],
        'WC_ROUNDING_PRECISION' => ['type' => 'wc'],
        'WC_DISCOUNT_ROUNDING_MODE' => ['type' => 'wc'],
        'WC_TAX_ROUNDING_MODE' => ['type' => 'wc'],
        'WC_DELIMITER' => ['type' => 'wc'],
        'WC_LOG_DIR' => ['type' => 'wc'],
        'WC_SESSION_CACHE_GROUP' => ['type' => 'wc'],
        'WC_TEMPLATE_DEBUG_MODE' => ['type' => 'wc'],
        'WC_TEMPLATE_PATH' => ['type' => 'wc'],
        'WOOCOMMERCE_VERSION' => ['type' => 'wc'],
    ];

    public function data() {

        foreach ($this->constants as $const => $args) {
            $col1 = ['val' => $const];
            $col2 = ['val' => Utils::get_const_value($const)];
            if (!defined($const)) $col2['original'] = true;

            $this->data[] = [
                'cols' => [ $col1, $col2 ]
            ];
        }
    }

    public function html() {

        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }
}

class Unit_Options extends Unit {

    public $name = 'Options';
    public $title = 'Options';

    public $options = [
        'current_theme_supports_woocommerce',
        'mailchimp_woocommerce_db_mailchimp_carts',
        'mailchimp_woocommerce_plugin_do_activation_redirect',
        'mailchimp_woocommerce_version',
        'mailchimp-woocommerce',
        'mailchimp-woocommerce-store_id',
        'widget_woocommerce_layered_nav',
        'widget_woocommerce_layered_nav_filters',
        'widget_woocommerce_price_filter',
        'widget_woocommerce_product_categories',
        'widget_woocommerce_product_search',
        'widget_woocommerce_product_tag_cloud',
        'widget_woocommerce_products',
        'widget_woocommerce_rating_filter',
        'widget_woocommerce_recent_reviews',
        'widget_woocommerce_recently_viewed_products',
        'widget_woocommerce_top_rated_products',
        'widget_woocommerce_widget_cart',
        'woocommerce_admin_notice_jetpack_install_error',
        'woocommerce_admin_notice_ppec_paypal_install_error',
        'woocommerce_admin_notice_storefront_install_error',
        'woocommerce_admin_notices',
        'woocommerce_all_except_countries',
        'woocommerce_allow_tracking',
        'woocommerce_allowed_countries',
        'woocommerce_anonymize_completed_orders',
        'woocommerce_api_enabled',
        'woocommerce_bacs_accounts',
        'woocommerce_bacs_settings',
        'woocommerce_braintree_credit_card_settings',
        'woocommerce_calc_discounts_sequentially',
        'woocommerce_calc_taxes',
        'woocommerce_cart_page_id',
        'woocommerce_cart_redirect_after_add',
        'woocommerce_catalog_columns',
        'woocommerce_catalog_rows',
        'woocommerce_checkout_highlight_required_fields',
        'woocommerce_checkout_order_received_endpoint',
        'woocommerce_checkout_page_id',
        'woocommerce_checkout_pay_endpoint',
        'woocommerce_checkout_privacy_policy_text',
        'woocommerce_cheque_settings',
        'woocommerce_cod_settings',
        'woocommerce_currency',
        'woocommerce_currency_pos',
        'woocommerce_db_version',
        'woocommerce_default_country',
        'woocommerce_default_customer_address',
        'woocommerce_delete_inactive_accounts',
        'woocommerce_demo_store',
        'woocommerce_dimension_unit',
        'woocommerce_downloads_grant_access_after_payment',
        'woocommerce_downloads_require_login',
        'woocommerce_email_background_color',
        'woocommerce_email_base_color',
        'woocommerce_email_body_background_color',
        'woocommerce_email_footer_text',
        'woocommerce_email_from_address',
        'woocommerce_email_from_name',
        'woocommerce_email_header_image',
        'woocommerce_email_text_color',
        'woocommerce_enable_ajax_add_to_cart',
        'woocommerce_enable_checkout_login_reminder',
        'woocommerce_enable_coupons',
        'woocommerce_enable_guest_checkout',
        'woocommerce_enable_myaccount_registration',
        'woocommerce_enable_review_rating',
        'woocommerce_enable_reviews',
        'woocommerce_enable_shipping_calc',
        'woocommerce_enable_signup_and_login_from_checkout',
        'woocommerce_erasure_request_removes_download_data',
        'woocommerce_erasure_request_removes_order_data',
        'woocommerce_file_download_method',
        'woocommerce_flat_rate_1_settings',
        'woocommerce_flat_rate_2_settings',
        'woocommerce_force_ssl_checkout',
        'woocommerce_gateway_order',
        'woocommerce_gateway_stripe_retention',
        'woocommerce_hide_out_of_stock_items',
        'woocommerce_hold_stock_minutes',
        'woocommerce_logout_endpoint',
        'woocommerce_manage_stock',
        'woocommerce_maybe_regenerate_images_hash',
        'woocommerce_meta_box_errors',
        'woocommerce_myaccount_add_payment_method_endpoint',
        'woocommerce_myaccount_delete_payment_method_endpoint',
        'woocommerce_myaccount_downloads_endpoint',
        'woocommerce_myaccount_edit_account_endpoint',
        'woocommerce_myaccount_edit_address_endpoint',
        'woocommerce_myaccount_lost_password_endpoint',
        'woocommerce_myaccount_orders_endpoint',
        'woocommerce_myaccount_page_id',
        'woocommerce_myaccount_payment_methods_endpoint',
        'woocommerce_myaccount_set_default_payment_method_endpoint',
        'woocommerce_myaccount_view_order_endpoint',
        'woocommerce_notify_low_stock',
        'woocommerce_notify_low_stock_amount',
        'woocommerce_notify_no_stock',
        'woocommerce_notify_no_stock_amount',
        'woocommerce_paypal_settings',
        'woocommerce_permalinks',
        'woocommerce_ppec_paypal_settings',
        'woocommerce_price_decimal_sep',
        'woocommerce_price_display_suffix',
        'woocommerce_price_num_decimals',
        'woocommerce_price_thousand_sep',
        'woocommerce_prices_include_tax',
        'woocommerce_product_type',
        'woocommerce_queue_flush_rewrite_rules',
        'woocommerce_registration_generate_password',
        'woocommerce_registration_generate_username',
        'woocommerce_registration_privacy_policy_text',
        'woocommerce_review_rating_required',
        'woocommerce_review_rating_verification_label',
        'woocommerce_review_rating_verification_required',
        'woocommerce_sell_in_person',
        'woocommerce_ship_to_countries',
        'woocommerce_ship_to_destination',
        'woocommerce_shipping_cost_requires_address',
        'woocommerce_shipping_debug_mode',
        'woocommerce_shipping_tax_class',
        'woocommerce_shop_page_id',
        'woocommerce_single_image_width',
        'woocommerce_specific_allowed_countries',
        'woocommerce_specific_ship_to_countries',
        'woocommerce_stock_email_recipient',
        'woocommerce_stock_format',
        'woocommerce_store_address',
        'woocommerce_store_address_2',
        'woocommerce_store_city',
        'woocommerce_store_postcode',
        'woocommerce_stripe_settings',
        'woocommerce_tax_based_on',
        'woocommerce_tax_classes',
        'woocommerce_tax_display_cart',
        'woocommerce_tax_display_shop',
        'woocommerce_tax_round_at_subtotal',
        'woocommerce_tax_total_display',
        'woocommerce_terms_page_id',
        'woocommerce_thumbnail_image_width',
        'woocommerce_tracker_last_send',
        'woocommerce_tracker_ua',
        'woocommerce_trash_cancelled_orders',
        'woocommerce_trash_failed_orders',
        'woocommerce_trash_pending_orders',
        'woocommerce_unforce_ssl_checkout',
        'woocommerce_version',
        'woocommerce_weight_unit'
    ];

    public function data() {
        global $wpdb;

        $opt = "'" . implode("','", $this->options) . "'";
        $sql = "SELECT * FROM $wpdb->options WHERE option_name in (" . $opt . ")";
        $query = $wpdb->get_results($sql, ARRAY_A);

        if (!empty($query)) {
            foreach ($query as $data) {
                $value = maybe_unserialize($data['option_value'], true);
                //if (!is_array($value)) $value = $data['option_value'];

                $this->data[] = [
                    'cols' => [
                        ['val' => $data['option_name']],
                        ['val' => $value]
                    ]
                ];
            }
        }
    }

    public function html() {
        return DevStudio()->template('data/table', [
            'rows' => $this->data
        ]);

    }

}