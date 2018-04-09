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
            'AT' => __('Austria', 'gdpr-admin'),
            'BE' => __('Belgium', 'gdpr-admin'),
            'BG' => __('Bulgaria', 'gdpr-admin'),
            'HR' => __('Croatia', 'gdpr-admin'),
            'CY' => __('Cyprus', 'gdpr-admin'),
            'CZ' => __('Czech Republic', 'gdpr-admin'),
            'DK' => __('Denmark', 'gdpr-admin'),
            'EE' => __('Estonia', 'gdpr-admin'),
            'FI' => __('Finland', 'gdpr-admin'),
            'FR' => __('France', 'gdpr-admin'),
            'DE' => __('Germany', 'gdpr-admin'),
            'GR' => __('Greece', 'gdpr-admin'),
            'HU' => __('Hungary', 'gdpr-admin'),
            'IE' => __('Ireland', 'gdpr-admin'),
            'IT' => __('Italy', 'gdpr-admin'),
            'LV' => __('Latvia', 'gdpr-admin'),
            'LT' => __('Lithuania', 'gdpr-admin'),
            'LU' => __('Luxembourg', 'gdpr-admin'),
            'MT' => __('Malta', 'gdpr-admin'),
            'NL' => __('Netherlands', 'gdpr-admin'),
            'PL' => __('Poland', 'gdpr-admin'),
            'PT' => __('Portugal', 'gdpr-admin'),
            'RO' => __('Romania', 'gdpr-admin'),
            'SK' => __('Slovakia', 'gdpr-admin'),
            'SI' => __('Slovenia', 'gdpr-admin'),
            'ES' => __('Spain', 'gdpr-admin'),
            'SE' => __('Sweden', 'gdpr-admin'),
            'UK' => __('United Kingdom', 'gdpr-admin'),
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
            "IS"    => __('Iceland', 'gdpr-admin'),
            "NO"    => __('Norway', 'gdpr-admin'),
            "LI"    => __('Liechtenstein', 'gdpr-admin'),
            "CH"    => __('Switzerland', 'gdpr-admin'),
            "US"    => __('United States', 'gdpr-admin'),
            "other" => __('Rest of the world', 'gdpr-admin'),
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
        return $toolsPageId ? get_permalink($toolsPageId) : '';
    }

    public function getPrivacyPolicyPageUrl()
    {
        $policyPageId = gdpr('options')->get('policy_page');
        return $policyPageId ? get_permalink($policyPageId) : '';
    }

    public function error()
    {
        wp_die(
            __('An error has occurred. Please contact the site administrator.', 'gdpr')
        );
    }

    public function docs($url = '')
    {
        return 'https://codelight.eu/wordpress-gdpr-framework/' . $url;
    }
}
