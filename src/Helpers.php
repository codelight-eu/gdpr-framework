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
            'AT' => _x('Austria', '(Admin)', 'gdpr'),
            'BE' => _x('Belgium', '(Admin)', 'gdpr'),
            'BG' => _x('Bulgaria', '(Admin)', 'gdpr'),
            'HR' => _x('Croatia','(Admin)', 'gdpr'),
            'CY' => _x('Cyprus', '(Admin)', 'gdpr'),
            'CZ' => _x('Czech Republic', '(Admin)', 'gdpr'),
            'DK' => _x('Denmark', '(Admin)', 'gdpr'),
            'EE' => _x('Estonia', '(Admin)', 'gdpr'),
            'FI' => _x('Finland', '(Admin)', 'gdpr'),
            'FR' => _x('France', '(Admin)', 'gdpr'),
            'DE' => _x('Germany', '(Admin)', 'gdpr'),
            'GR' => _x('Greece', '(Admin)', 'gdpr'),
            'HU' => _x('Hungary', '(Admin)', 'gdpr'),
            'IE' => _x('Ireland', '(Admin)', 'gdpr'),
            'IT' => _x('Italy', '(Admin)', 'gdpr'),
            'LV' => _x('Latvia', '(Admin)', 'gdpr'),
            'LT' => _x('Lithuania', '(Admin)', 'gdpr'),
            'LU' => _x('Luxembourg', '(Admin)', 'gdpr'),
            'MT' => _x('Malta', '(Admin)', 'gdpr'),
            'NL' => _x('Netherlands', '(Admin)', 'gdpr'),
            'PL' => _x('Poland', '(Admin)', 'gdpr'),
            'PT' => _x('Portugal', '(Admin)', 'gdpr'),
            'RO' => _x('Romania', '(Admin)', 'gdpr'),
            'SK' => _x('Slovakia', '(Admin)', 'gdpr'),
            'SI' => _x('Slovenia', '(Admin)', 'gdpr'),
            'ES' => _x('Spain', '(Admin)', 'gdpr'),
            'SE' => _x('Sweden', '(Admin)', 'gdpr'),
            'UK' => _x('United Kingdom', '(Admin)', 'gdpr'),
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
            "IS"    => _x('Iceland', '(Admin)', 'gdpr'),
            "NO"    => _x('Norway', '(Admin)', 'gdpr'),
            "LI"    => _x('Liechtenstein', '(Admin)', 'gdpr'),
            "CH"    => _x('Switzerland', '(Admin)', 'gdpr'),
            "US"    => _x('United States', '(Admin)', 'gdpr'),
            "other" => _x('Rest of the world', '(Admin)', 'gdpr'),
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
