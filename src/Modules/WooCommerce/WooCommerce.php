<?php

namespace Codelight\GDPR\Modules\WooCommerce;

class WooCommerce
{
    public function __construct()
    {
        add_action('init', [$this, 'addPrivacyToolsAccountEndpoint']);
        add_filter('woocommerce_account_menu_items', [$this, 'addPrivacyToolsAccountMenuItem']);
        add_action('woocommerce_account_privacy-tools_endpoint', [$this, 'renderPrivacyToolsContents']);
        add_filter('gdpr/privacy-tools/url', [$this, 'setPrivacyToolsPageUrl']);
        add_filter('gdpr/styles', [$this, 'isPrivacyToolsPage']);
    }

    public function addPrivacyToolsAccountEndpoint()
    {
        add_rewrite_endpoint('privacy-tools', EP_PAGES);
    }

    public function addPrivacyToolsAccountMenuItem($menuItems)
    {
        $new = ['privacy-tools' => 'Privacy Tools'];

        $menuItems = array_slice($menuItems, 0, 3, true)
            + $new
            + array_slice($menuItems, 3, null, true);

        return $menuItems;
    }

    public function renderPrivacyToolsContents()
    {
        $pageContent = get_post(gdpr('options')->get('tools_page'));
        echo apply_filters('the_content', $pageContent->post_content);
    }

    public function setPrivacyToolsPageUrl($url)
    {
        if (is_user_logged_in()) {
            $url = wc_get_account_endpoint_url('privacy-tools');
        }

        return $url;
    }

    public function isPrivacyToolsPage($isPrivacyTools)
    {
        global $wp_query;

        if ($wp_query && isset($wp_query->query['privacy-tools'])) {
            return true;
        }

        return $isPrivacyTools;
    }
}
