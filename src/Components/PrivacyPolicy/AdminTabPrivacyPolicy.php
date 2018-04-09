<?php

namespace Codelight\GDPR\Components\PrivacyPolicy;

use Codelight\GDPR\Admin\AdminTab;

class AdminTabPrivacyPolicy extends AdminTab
{
    /* @var string */
    protected $slug = 'privacy-policy';

    /* @var PolicyGenerator */
    protected $policyGenerator;

    public function __construct(PolicyGenerator $policyGenerator)
    {
        $this->policyGenerator = $policyGenerator;

        $this->title = __('Privacy Policy', 'gdpr-admin');

        $this->registerSetting('gdpr_company_name');
        $this->registerSetting('gdpr_contact_email');
        $this->registerSetting('gdpr_company_location');

        $this->registerSetting('gdpr_representative_contact_name');
        $this->registerSetting('gdpr_representative_contact_email');
        $this->registerSetting('gdpr_representative_contact_phone');

        $this->registerSetting('gdpr_dpa_website');
        $this->registerSetting('gdpr_dpa_email');
        $this->registerSetting('gdpr_dpa_phone');

        $this->registerSetting('gdpr_has_dpo');
        $this->registerSetting('gdpr_dpo_name');
        $this->registerSetting('gdpr_dpo_email');

        /*
        $this->registerSetting('gdpr_pp_data_gathered_1');
        $this->registerSetting('gdpr_pp_data_gathered_2');
        $this->registerSetting('gdpr_pp_data_gathered_3');
        $this->registerSetting('gdpr_pp_data_gathered_4');

        $this->registerSetting('gdpr_pp_data_usage_1');
        $this->registerSetting('gdpr_pp_data_usage_2');
        $this->registerSetting('gdpr_pp_data_usage_3');
        $this->registerSetting('gdpr_pp_data_usage_4');

        $this->registerSetting('gdpr_pp_data_partners');
        */

        add_action('gdpr/admin/action/privacy-policy/generate', [$this, 'generatePolicy']);
    }

    public function init()
    {
        /**
         * General settings
         */
        $this->registerSettingSection(
            'gdpr_section_privacy_policy',
            __('Privacy Policy', 'gdpr-admin'),
            [$this, 'renderHeader']
        );

        /**
         * Company info
         */
        $this->registerSettingSection(
            'gdpr_section_privacy_policy_company',
            __('Company information', 'gdpr-admin')
        );

        $this->registerSettingField(
            'gdpr_company_name',
            __('Company Name', 'gdpr-admin'),
            [$this, 'renderCompanyNameHtml'],
            'gdpr_section_privacy_policy_company'
        );

        $this->registerSettingField(
            'gdpr_company_email',
            __('Company Email', 'gdpr-admin'),
            [$this, 'renderCompanyEmailHtml'],
            'gdpr_section_privacy_policy_company'
        );

        $this->registerSettingField(
            'gdpr_company_location',
            __('Company Location', 'gdpr-admin'),
            [$this, 'renderCompanyLocationHtml'],
            'gdpr_section_privacy_policy_company'
        );

        /**
         * Representative
         */
        $this->registerSettingSection(
            'gdpr_section_privacy_policy_representative',
            false,
            [$this, 'renderRepresentativeOpen']
        );

        $this->registerSettingField(
            'gdpr_representative_contact_name',
            __('Representative Contact Name', 'gdpr-admin'),
            [$this, 'renderRepresentativeContactName'],
            'gdpr_section_privacy_policy_representative'
        );

        $this->registerSettingField(
            'gdpr_representative_contact_email',
            __('Representative Contact Email', 'gdpr-admin'),
            [$this, 'renderRepresentativeContactEmail'],
            'gdpr_section_privacy_policy_representative'
        );

        $this->registerSettingField(
            'gdpr_representative_contact_phone',
            __('Representative Contact Phone', 'gdpr-admin'),
            [$this, 'renderRepresentativeContactPhone'],
            'gdpr_section_privacy_policy_representative'
        );

        $this->registerSettingSection(
            'gdpr_section_privacy_policy_representative_close',
            false,
            [$this, 'renderRepresentativeClose']
        );

        /**
         * DPA
         */
        $this->registerSettingSection(
            'gdpr_section_privacy_policy_dpa',
            __('Data Protection Authority', 'gdpr-admin'),
            [$this, 'renderDpaJS']
        );

        $this->registerSettingField(
            'gdpr_dpa_website',
            __('Data Protection Authority Website', 'gdpr-admin'),
            [$this, 'renderDpaWebsite'],
            'gdpr_section_privacy_policy_dpa'
        );

        $this->registerSettingField(
            'gdpr_dpa_email',
            __('Data Protection Authority Email', 'gdpr-admin'),
            [$this, 'renderDpaEmail'],
            'gdpr_section_privacy_policy_dpa'
        );

        $this->registerSettingField(
            'gdpr_dpa_phone',
            __('Data Protection Authority Phone', 'gdpr-admin'),
            [$this, 'renderDpaPhone'],
            'gdpr_section_privacy_policy_dpa'
        );

        /**
         * DPO
         */
        $this->registerSettingSection(
            'gdpr_section_privacy_policy_dpo',
            __('Data Protection Officer', 'gdpr-admin'),
            function() {
                echo "<a href='https://codelight.eu/wordpress-gdpr-framework/knowledge-base/do-i-need-to-appoint-data-protection-officer-dpo/' target='_blank'>";
                echo __('Knowledge base: Do I need to appoint a Data Protection Officer?');
                echo "</a>";
            }
        );

        $this->registerSettingField(
            'gdpr_has_dpo',
            __('Data Protection Officer', 'gdpr-admin'),
            [$this, 'renderHasDPOHtml'],
            'gdpr_section_privacy_policy_dpo'
        );

        $this->registerSettingField(
            'gdpr_dpo_name',
            __('Data Protection Officer Name', 'gdpr-admin'),
            [$this, 'renderDPONameHtml'],
            'gdpr_section_privacy_policy_dpo',
            ['class' => 'gdpr-dpo hidden']
        );

        $this->registerSettingField(
            'gdpr_dpo_email',
            __('Data Protection Officer Email', 'gdpr-admin'),
            [$this, 'renderDPOEmailHtml'],
            'gdpr_section_privacy_policy_dpo',
            ['class' => 'gdpr-dpo hidden']
        );

        /**
         * Policy contents
         */

        /*
        $this->registerSettingSection(
            'gdpr_section_privacy_policy_contents',
            __('Policy Contents', 'gdpr-admin')
        );

        $this->registerSettingField(
            'gdpr_pp_data_gathered_1',
            __('Information you have provided us with', 'gdpr-admin'),
            [$this, 'renderDataGathered1'],
            'gdpr_section_privacy_policy_contents',
            ['class' => 'gdpr-dpo hidden']
        );
        */
    }

    /*
    public function renderDataGathered1()
    {
        $contents = gdpr('options')->get('pp_data_gathered_1') ? gdpr('options')->get('pp_data_gathered_1') : 'default';

        echo wp_editor(
            $contents,
            'pp_data_gathered_1',
            [
                'media_buttons' => false,
                'editor_height' => 200,
            ]
        );
    }
    */

    public function renderHeader()
    {
        echo gdpr('view')->render('admin/privacy-policy/header');
    }

    /**
     * Company info
     */

    public function renderCompanyNameHtml()
    {
        $value = gdpr('options')->get('company_name') ? esc_attr(gdpr('options')->get('company_name')) : '';
        $placeholder = __('Company Name', 'gdpr-admin');
        echo "<input name='gdpr_company_name' placeholder='{$placeholder}' value='{$value}'>";
    }

    public function renderCompanyEmailHtml()
    {
        $value = gdpr('options')->get('contact_email') ? esc_attr(gdpr('options')->get('contact_email')) : '';
        $placeholder = __('Contact Email', 'gdpr-admin');
        echo "<input type='email' name='gdpr_contact_email' placeholder='{$placeholder}' value='{$value}'>";
    }

    public function renderCompanyLocationHtml()
    {
        $country = gdpr('options')->get('company_location') ? gdpr('options')->get('company_location') : '';
        $countrySelectOptions = gdpr('helpers')->getCountrySelectOptions($country);
        echo gdpr('view')->render('admin/privacy-policy/company-location', compact('countrySelectOptions'));
    }

    /**
     * Representative contact
     */

    public function renderRepresentativeOpen()
    {
        echo "<span class='gdpr-representative hidden'>";
        echo "<h3>";
        echo __('Representative Contact', 'gdpr-admin');
        echo "</h3>";
        echo "<a href='https://codelight.eu/wordpress-gdpr-framework/knowledge-base/do-i-need-to-appoint-an-eu-based-representative/' target='_blank'>";
        echo __('Knowledge base: Do I need to appoint an EU-based representative?');
        echo "</a>";
    }

    public function renderRepresentativeContactName()
    {
        $value = gdpr('options')->get('representative_contact_name') ? esc_attr(gdpr('options')->get('representative_contact_name')) : '';
        $placeholder = __('Representative Contact Name', 'gdpr-admin');
        echo "<input name='gdpr_representative_contact_name' placeholder='{$placeholder}' value='{$value}'>";
    }

    public function renderRepresentativeContactEmail()
    {
        $value = gdpr('options')->get('representative_contact_email') ? esc_attr(gdpr('options')->get('representative_contact_email')) : '';
        $placeholder = __('Representative Contact Email', 'gdpr-admin');
        echo "<input type='email' name='gdpr_representative_contact_email' placeholder='{$placeholder}' value='{$value}'>";
    }

    public function renderRepresentativeContactPhone()
    {
        $value = gdpr('options')->get('representative_contact_phone') ? esc_attr(gdpr('options')->get('representative_contact_phone')) : '';
        $placeholder = __('Representative Contact Phone', 'gdpr-admin');
        echo "<input name='gdpr_representative_contact_phone' placeholder='{$placeholder}' value='{$value}'>";
    }

    public function renderRepresentativeClose()
    {
        echo "</span>";
    }

    /**
     * Data Protection Authority
     */
    public function renderDpaJS()
    {
        echo "<a href='https://codelight.eu/wordpress-gdpr-framework/knowledge-base/do-i-need-to-appoint-an-eu-based-representative/' target='_blank'>";
        echo 'See the <a href="http://ec.europa.eu/justice/data-protection/article-29/structure/data-protection-authorities/index_en.htm" target="_blank">list of contacts here</a>';
        echo "</a>";



        $dpaData = json_encode(gdpr('helpers')->getDataProtectionAuthorities());
        echo gdpr('view')->render('admin/privacy-policy/dpa', compact('dpaData'));
    }

    public function renderDpaWebsite()
    {
        $value = gdpr('options')->get('dpa_website') ? esc_attr(gdpr('options')->get('dpa_website')) : '';
        $placeholder = __('Data Protection Authority Website', 'gdpr-admin');
        echo "<input name='gdpr_dpa_website' id='gdpr_dpa_website' placeholder='{$placeholder}' value='{$value}' data-set='{$value}'>";
    }

    public function renderDpaEmail()
    {
        $value = gdpr('options')->get('dpa_email') ? esc_attr(gdpr('options')->get('dpa_email')) : '';
        $placeholder = __('Data Protection Authority Email', 'gdpr-admin');
        echo "<input type='email' name='gdpr_dpa_email' id='gdpr_dpa_email' placeholder='{$placeholder}' value='{$value}' data-set='{$value}'>";
    }

    public function renderDpaPhone()
    {
        $value = gdpr('options')->get('dpa_phone') ? esc_attr(gdpr('options')->get('dpa_phone')) : '';
        $placeholder = __('Data Protection Authority Phone', 'gdpr-admin');
        echo "<input name='gdpr_dpa_phone' id='gdpr_dpa_phone' placeholder='{$placeholder}' value='{$value}' data-set='{$value}'>";
    }

    /**
     * Data Protection Officer
     */

    public function renderHasDPOHtml()
    {
        $hasDPO = gdpr('options')->get('has_dpo');
        echo gdpr('view')->render('admin/privacy-policy/has-dpo', compact('hasDPO'));
    }

    public function renderDPONameHtml()
    {
        $value = gdpr('options')->get('dpo_name') ? esc_attr(gdpr('options')->get('dpo_name')) : '';
        $placeholder = __('DPO Name', 'gdpr-admin');
        echo "<input name='gdpr_dpo_name' placeholder='{$placeholder}' value='{$value}'>";
    }

    public function renderDPOEmailHtml()
    {
        $value = gdpr('options')->get('dpo_email') ? esc_attr(gdpr('options')->get('dpo_email')) : '';
        $placeholder = __('DPO Name', 'gdpr-admin');
        echo "<input type='email' name='gdpr_dpo_email' placeholder='{$placeholder}' value='{$value}'>";
    }


    public function generatePolicy()
    {
        $policyPage = gdpr('options')->get('policy_page');

        // todo: handle errors
        if ( ! $policyPage) {
            return;
        }

        $policy = gdpr('view')->render(
            'policy/policy'
        );

        wp_update_post([
            'ID'           => $policyPage,
            'post_content' => $policy,
        ]);

        wp_safe_redirect(gdpr('helpers')->getAdminUrl('&gdpr-tab=privacy-policy&gdpr_notice=policy_generated'));
    }

    /**
     * Render either the settings or the generated policy
     */
    public function renderContents()
    {
        if (isset($_GET['generate']) && 'yes' == $_GET['generate']) {
            return $this->renderPolicy();
        } else {
            return $this->renderSettings();
        }
    }

    /**
     * Render the contents including settings fields, sections and submit button.
     * Trigger hooks for rendering content before and after the settings fields.
     *
     * @return string
     */
    public function renderSettings()
    {
        ob_start();

        do_action("gdpr/tabs/{$this->getSlug()}/before", $this);
        $this->settingsFields($this->getOptionsGroupName());
        do_settings_sections($this->getOptionsGroupName());
        do_action("gdpr/tabs/{$this->getSlug()}/after", $this);

        $this->renderSubmitButton();

        return ob_get_clean();
    }

    public function renderPolicy()
    {
        $policyPageId = gdpr('options')->get('policy_page');
        if ($policyPageId) {
            $policyUrl = get_edit_post_link($policyPageId);
        } else {
            $policyUrl = false;
        }

        $editor = $this->getPolicyEditor();
        $backUrl = gdpr('helpers')->getAdminUrl('&gdpr-tab=privacy-policy');

        return gdpr('view')->render('admin/privacy-policy/generated', compact('editor', 'policyUrl', 'backUrl'));
    }

    protected function getPolicyEditor()
    {
        ob_start();

        wp_editor(
            wp_kses_post($this->policyGenerator->generate()),
            'gdpr_policy',
            [
                'media_buttons' => false,
                'editor_height' => 600,
                'teeny' => true,
                'editor_css' => '<style>#mceu_16 { display: none; }</style>'
            ]
        );

        return ob_get_clean();
    }

    /**
     * Render WP's default submit button
     */
    public function renderSubmitButton()
    {
        submit_button(__('Save & Generate Policy', 'gdpr-admin'));
    }

    /**
     * In order to set up a proper redirect to the generated policy
     * after saving settings, we modify the way wp_nonce_field is called and insert our own referer input.
     *
     * @param $optionGroup
     */
    public function settingsFields($optionGroup)
    {
        echo "<input type='hidden' name='option_page' value='" . esc_attr($optionGroup) . "' />";
        echo '<input type="hidden" name="action" value="update" />';
        wp_nonce_field("$optionGroup-options", '_wpnonce', false);
        echo '<input type="hidden" name="_wp_http_referer" value="'. esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) . '&generate=yes' ) . '" />';
    }
}
