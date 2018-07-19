<?php

namespace Codelight\GDPR;

/**
 * General helper functions
 *
 * Class Helpers
 *
 * @package Codelight\GDPR
 */
class Helpers
{
    public function supportUrl($url = '')
    {
        return gdpr('config')->get('help.url') . $url;
    }

    /**
     * Get an associative array of EU countries
     *
     * @return array
     */
    public function getEUCountryList()
    {
        return [
            'AT' => _x('Austria', '(Admin)', 'gdpr-framework'),
            'BE' => _x('Belgium', '(Admin)', 'gdpr-framework'),
            'BG' => _x('Bulgaria', '(Admin)', 'gdpr-framework'),
            'HR' => _x('Croatia','(Admin)', 'gdpr-framework'),
            'CY' => _x('Cyprus', '(Admin)', 'gdpr-framework'),
            'CZ' => _x('Czech Republic', '(Admin)', 'gdpr-framework'),
            'DK' => _x('Denmark', '(Admin)', 'gdpr-framework'),
            'EE' => _x('Estonia', '(Admin)', 'gdpr-framework'),
            'FI' => _x('Finland', '(Admin)', 'gdpr-framework'),
            'FR' => _x('France', '(Admin)', 'gdpr-framework'),
            'DE' => _x('Germany', '(Admin)', 'gdpr-framework'),
            'GR' => _x('Greece', '(Admin)', 'gdpr-framework'),
            'HU' => _x('Hungary', '(Admin)', 'gdpr-framework'),
            'IE' => _x('Ireland', '(Admin)', 'gdpr-framework'),
            'IT' => _x('Italy', '(Admin)', 'gdpr-framework'),
            'LV' => _x('Latvia', '(Admin)', 'gdpr-framework'),
            'LT' => _x('Lithuania', '(Admin)', 'gdpr-framework'),
            'LU' => _x('Luxembourg', '(Admin)', 'gdpr-framework'),
            'MT' => _x('Malta', '(Admin)', 'gdpr-framework'),
            'NL' => _x('Netherlands', '(Admin)', 'gdpr-framework'),
            'PL' => _x('Poland', '(Admin)', 'gdpr-framework'),
            'PT' => _x('Portugal', '(Admin)', 'gdpr-framework'),
            'RO' => _x('Romania', '(Admin)', 'gdpr-framework'),
            'SK' => _x('Slovakia', '(Admin)', 'gdpr-framework'),
            'SI' => _x('Slovenia', '(Admin)', 'gdpr-framework'),
            'ES' => _x('Spain', '(Admin)', 'gdpr-framework'),
            'SE' => _x('Sweden', '(Admin)', 'gdpr-framework'),
            'UK' => _x('United Kingdom', '(Admin)', 'gdpr-framework'),
        ];
    }

    /**
     * Get a list of <option> values for the country selector
     *
     * @param null $current
     *
     * @return mixed
     */
    public function getCountrySelectOptions($current = null)
    {
        $eu      = $this->getEUCountryList();
        $outside = [
            "IS"    => _x('Iceland', '(Admin)', 'gdpr-framework'),
            "NO"    => _x('Norway', '(Admin)', 'gdpr-framework'),
            "LI"    => _x('Liechtenstein', '(Admin)', 'gdpr-framework'),
            "CH"    => _x('Switzerland', '(Admin)', 'gdpr-framework'),
            "US"    => _x('United States', '(Admin)', 'gdpr-framework'),
            "other" => _x('Rest of the world', '(Admin)', 'gdpr-framework'),
        ];

        return gdpr('view')->render('global/country-options', compact('eu', 'outside', 'current'));
    }

    /**
     * Check if a controller from the given country needs a representative in the EU
     *
     * @param $code
     * @return bool
     */
    public function countryNeedsRepresentative($code)
    {
        return in_array($code, ['US', 'other']);
    }

    /**
     * Get the data protection authority information for a given country
     *
     * @param null $countryCode
     * @return array
     */
    public function getDataProtectionAuthorityInfo($countryCode = null)
    {
        if (!$countryCode) {
            $countryCode = gdpr('options')->get('company_location');
        }

        $dpaData = require(gdpr('config')->get('plugin.path') . 'assets/data-protection-authorities.php');

        if (isset($dpaData[$countryCode])) {
            return $dpaData[$countryCode];
        }

        return [];
    }

    /**
     * Get the info regarding all DPAs
     */
    public function getDataProtectionAuthorities()
    {
        return require(gdpr('config')->get('plugin.path') . 'assets/data-protection-authorities.php');
    }

    public function getAdminUrl($suffix = '')
    {
        return admin_url('tools.php?page=privacy' . $suffix);
    }

    public function getDashboardDataPageUrl($suffix = '')
    {
        return admin_url('users.php?page=gdpr-profile' . $suffix);
    }

    public function getPrivacyToolsPageUrl()
    {
        $toolsPageId = gdpr('options')->get('tools_page');
        return apply_filters('gdpr/privacy-tools/url', $toolsPageId ? get_permalink($toolsPageId) : '');
    }

    public function getPrivacyPolicyPageUrl()
    {
        $policyPageId = gdpr('options')->get('policy_page');
        return $policyPageId ? get_permalink($policyPageId) : '';
    }

    public function error()
    {
        wp_die(
            __('An error has occurred. Please contact the site administrator.', 'gdpr-framework')
        );
    }

    public function docs($url = '')
    {
        return 'https://codelight.eu/wordpress-gdpr-framework/' . $url;
    }

    /**
     * Wrapper around wp_mail() to filter the headers
     * Example code for changing the sender email:
     *
     *  add_filter('gdpr/mail/headers', function($headers) {
            $headers[] = 'From: Firstname Lastname <test@example.com>';
            return $headers;
        });
     *
     *
     */
    public function mail($to, $subject, $message, $headers = '', $attachments = [])
    {
        $headers = apply_filters('gdpr/mail/headers', $headers);
        wp_mail($to, $subject, $message, $headers, $attachments);
    }
}
