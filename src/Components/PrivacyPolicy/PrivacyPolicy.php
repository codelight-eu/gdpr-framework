<?php

namespace Codelight\GDPR\Components\PrivacyPolicy;

/**
 * Handles putting together and rendering the privacy policy page
 *
 * Class PrivacyPolicy
 *
 * @package Codelight\GDPR\Components\PrivacyPolicy
 */
class PrivacyPolicy
{
    public function __construct()
    {
        add_filter('gdpr/admin/tabs', [$this, 'registerAdminTab'], 35);

        add_shortcode('gdpr_privacy', [$this, 'doShortcode']);
        add_shortcode('gdpr_privacy_policy_url', [$this, 'renderUrlShortcode']);
        add_shortcode('gdpr_privacy_policy_link', [$this, 'renderLinkShortcode']);
    }

    public function registerAdminTab($tabs)
    {
        $tabs['privacy-policy'] = gdpr()->make(AdminTabPrivacyPolicy::class);

        return $tabs;
    }

    public function doShortcode($attributes)
    {
        $attributes = shortcode_atts([
            'item' => null,
        ], $attributes);

        switch ($attributes['item']) {
            case 'company_name':
                return esc_html(gdpr('options')->get('company_name'));
            case 'company_email':
                return esc_html(gdpr('options')->get('contact_email'));
            case 'company_email_link':
                $email = antispambot(gdpr('options')->get('contact_email'));
                return "<a href='mailto:{$email}'>{$email}</a>";
            case 'dpo_name':
                return esc_html(gdpr('options')->get('dpo_name'));
            case 'dpo_email':
                return esc_html(gdpr('options')->get('dpo_email'));
            case 'dpo_email_link':
                $email = antispambot(gdpr('options')->get('dpo_email'));
                return "<a href='mailto:{$email}'>{$email}</a>";
            case 'rep_name':
                return esc_html(gdpr('options')->get('representative_contact_name'));
            case 'rep_email':
                return esc_html(gdpr('options')->get('representative_contact_email'));
            case 'rep_email_link':
                $email = antispambot(gdpr('options')->get('representative_contact_email'));
                return "<a href='mailto:{$email}'>{$email}</a>";
            case 'authority_website':
                return esc_html(gdpr('options')->get('dpa_website'));
            case 'authority_email':
                return esc_html(gdpr('options')->get('dpa_email'));
            case 'authority_email_link':
                $email = antispambot(gdpr('options')->get('dpa_email'));
                return "<a href='mailto:{$email}'>{$email}</a>";
            case 'authority_phone':
                return esc_html(gdpr('options')->get('dpa_phone'));
            case null:
                return '';
        }

        return '';
    }

    public function renderUrlShortcode()
    {
        return gdpr('helpers')->getPrivacyPolicyPageUrl();
    }

    public function renderLinkShortcode($attributes)
    {
        $attributes = shortcode_atts([
            'title' => __('Privacy Policy', 'gdpr'),
        ], $attributes);

        $url = gdpr('helpers')->getPrivacyPolicyPageUrl();

        return
            "<a href='{$url}'>" .
            esc_html($attributes['title']) .
            "</a>";
    }
}
