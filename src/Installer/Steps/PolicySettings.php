<?php

namespace Codelight\GDPR\Installer\Steps;

use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

class PolicySettings extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'policy-settings';

    protected $type = 'wizard';

    protected $template = 'installer/steps/policy-settings';

    protected $activeSteps = 2;

    protected function renderContent()
    {
        $policyPage         = gdpr('options')->get('policy_page');
        $policyPageSelector = wp_dropdown_pages([
            'name'              => 'gdpr_policy_page',
            'show_option_none'  => _x('&mdash; Create a new page &mdash;', '(Admin)', 'gdpr'),
            'option_none_value' => 'new',
            'selected'          => $policyPage ? $policyPage : 'new',
            'echo'              => false,
            'class'             => 'gdpr-select js-gdpr-select2',
        ]);

        $hasTermsPage = gdpr('options')->get('has_terms_page');
        $termsPage    = gdpr('options')->get('terms_page');

        // Woo compatibility
        if (!$termsPage && get_option('woocommerce_terms_page_id')) {
            $hasTermsPage  = 'yes';
            $termsPage     = get_option('woocommerce_terms_page_id');
            $termsPageNote = _x(
                'We have automatically selected your WooCommerce Terms & Conditions page.',
                '(Admin)',
                'gdpr'
            );
        } else {
            $termsPageNote = false;
        }

        $termsPageSelector = wp_dropdown_pages([
            'name'              => 'gdpr_terms_page',
            'show_option_none'  => _x('&mdash; Create a new page &mdash;', '(Admin)', 'gdpr'),
            'option_none_value' => 'new',
            'selected'          => $termsPage ? $termsPage : 'new',
            'echo'              => false,
            'class'             => 'gdpr-select js-gdpr-select2',
        ]);

        $companyName     = gdpr('options')->get('company_name');
        $companyLocation = gdpr('options')->get('company_location');
        $countryOptions  = gdpr('helpers')->getCountrySelectOptions($companyLocation);
        $contactEmail    = gdpr('options')->get('contact_email') ?
            gdpr('options')->get('contact_email') :
            get_option('admin_email');

        $representativeContactName  = gdpr('options')->get('representative_contact_name');
        $representativeContactEmail = gdpr('options')->get('representative_contact_email');
        $representativeContactPhone = gdpr('options')->get('representative_contact_phone');

        $dpaWebsite  = gdpr('options')->get('dpa_name');
        $dpaEmail = gdpr('options')->get('dpa_email');
        $dpaPhone = gdpr('options')->get('dpa_phone');
        $dpaData = json_encode(gdpr('helpers')->getDataProtectionAuthorities());

        $hasDPO   = gdpr('options')->get('has_dpo');
        $dpoName  = gdpr('options')->get('dpo_name');
        $dpoEmail = gdpr('options')->get('dpo_email');

        echo gdpr('view')->render(
            $this->template,
            compact(
                'policyPage',
                'policyPageSelector',
                'companyName',
                'companyLocation',
                'contactEmail',
                'countryOptions',
                'hasDPO',
                'dpoEmail',
                'dpoName',
                'representativeContactName',
                'representativeContactEmail',
                'representativeContactPhone',
                'dpaWebsite',
                'dpaEmail',
                'dpaPhone',
                'dpaData',
                'hasTermsPage',
                'termsPage',
                'termsPageSelector',
                'termsPageNote'
            )
        );
    }

    /*
    public function validate()
    {
        if (!is_email($_POST['gdpr_contact_email'])) {
            $this->errors = 'Company email is not a valid email!';
            return false;
        }

        return true;

        //filter_var($url, FILTER_VALIDATE_URL) === FALSE
    }
    */

    public function submit()
    {
        /**
         * Policy page
         */
        if (isset($_POST['gdpr_create_policy_page']) && 'yes' === $_POST['gdpr_create_policy_page']) {
            $id = $this->createPolicyPage();
            gdpr('options')->set('policy_page', $id);
        } else {
            gdpr('options')->set('policy_page', $_POST['gdpr_policy_page']);
        }

        /**
         * 'Generate policy' checkbox
         */
        if (isset($_POST['gdpr_generate_policy']) && 'yes' === $_POST['gdpr_generate_policy']) {
            $this->generatePolicy();
            gdpr('options')->set('policy_generated', true);
        } else {
            gdpr('options')->set('policy_generated', false);
        }

        /**
         * Company information
         */
        gdpr('options')->set('company_name', $_POST['gdpr_company_name']);
        gdpr('options')->set('company_location', $_POST['gdpr_company_location']);

        if (is_email($_POST['gdpr_contact_email'])) {
            gdpr('options')->set('contact_email', $_POST['gdpr_contact_email']);
        }

        /**
         * Data Protection Officer
         */
        if (isset($_POST['gdpr_has_dpo'])) {
            gdpr('options')->set('has_dpo', $_POST['gdpr_has_dpo']);
        }

        gdpr('options')->set('dpo_name', $_POST['gdpr_dpo_name']);

        if (is_email($_POST['gdpr_dpo_email'])) {
            gdpr('options')->set('dpo_email', $_POST['gdpr_dpo_email']);
        }

        /**
         * Representative contact
         */
        gdpr('options')->set('representative_contact_name', $_POST['gdpr_representative_contact_name']);
        gdpr('options')->set('representative_contact_phone', $_POST['gdpr_representative_contact_phone']);

        if (is_email($_POST['gdpr_representative_contact_email'])) {
            gdpr('options')->set('representative_contact_email', $_POST['gdpr_representative_contact_email']);
        }

        /**
         * Data protection authority
         */
        gdpr('options')->set('dpa_website', $_POST['gdpr_dpa_website']);
        gdpr('options')->set('dpa_phone', $_POST['gdpr_dpa_phone']);

        if (is_email($_POST['gdpr_dpa_email'])) {
            gdpr('options')->set('dpa_email', $_POST['gdpr_dpa_email']);
        }


        /**
         * Terms page
         */
        if (isset($_POST['gdpr_has_terms_page'])) {
            gdpr('options')->set('has_terms_page', $_POST['gdpr_has_terms_page']);
        }

        if (isset($_POST['gdpr_has_terms_page']) && 'yes' === $_POST['gdpr_has_terms_page'] && isset($_POST['gdpr_terms_page'])) {
            gdpr('options')->set('terms_page', $_POST['gdpr_terms_page']);
        } else {
            gdpr('options')->delete('terms_page');
        }
    }

    protected function createPolicyPage()
    {
        $id = wp_insert_post([
            'post_title'   => __('Privacy Policy', 'gdpr'),
            'post_type'    => 'page',
        ]);

        return $id;
    }

    protected function generatePolicy()
    {
        wp_update_post([
            'ID'           => gdpr('options')->get('policy_page'),
            'post_content' => gdpr('view')->render(
                'policy/policy'
            ),
        ]);
    }
}
