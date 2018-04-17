<?php

namespace Codelight\GDPR\Admin;

class AdminTabGeneral extends AdminTab
{
    protected $slug = 'general';

    public function __construct()
    {
        $this->title = _x('General', '(Admin)', 'gdpr-framework');

        $this->registerSetting('gdpr_enable');

        $this->registerSetting('gdpr_tools_page');
        $this->registerSetting('gdpr_policy_page');
        $this->registerSetting('gdpr_terms_page');

        $this->registerSetting('gdpr_export_action');
        $this->registerSetting('gdpr_export_action_email');

        $this->registerSetting('gdpr_delete_action');
        $this->registerSetting('gdpr_delete_action_reassign');
        $this->registerSetting('gdpr_delete_action_reassign_user');
        $this->registerSetting('gdpr_delete_action_email');

        $this->registerSetting('gdpr_enable_stylesheet');
        $this->registerSetting('gdpr_enable_theme_compatibility');
    }

    public function init()
    {
        /**
         * General
         */
        $this->registerSettingSection(
            'gdpr_section_general',
            _x('General Settings', '(Admin)', 'gdpr-framework')
        );

        $this->registerSettingField(
            'gdpr_enable',
            _x('Enable Privacy Tools', '(Admin)', 'gdpr-framework'),
            [$this, 'renderEnableCheckbox'],
            'gdpr_section_general'
        );

        /**
         * GDPR system pages
         */
        $this->registerSettingSection(
            'gdpr_section_pages',
            _x('Pages', '(Admin)', 'gdpr-framework')
        );

        $this->registerSettingField(
            'gdpr_tools_page',
            _x('Privacy Tools Page', '(Admin)', 'gdpr-framework') . '*',
            [$this, 'renderPrivacyToolsPageSelector'],
            'gdpr_section_pages'
        );

        $this->registerSettingField(
            'gdpr_policy_page',
            _x('Privacy Policy Page', '(Admin)', 'gdpr-framework') . '*',
            [$this, 'renderPolicyPageSelector'],
            'gdpr_section_pages'
        );

        $this->registerSettingField(
            'gdpr_terms_page',
            _x('Terms & Conditions Page', '(Admin)', 'gdpr-framework'),
            [$this, 'renderTermsPageSelector'],
            'gdpr_section_pages'
        );

        /**
         * View & Export
         */
        $this->registerSettingSection(
            'gdpr_section_export',
            _x('View & Export Data', '(Admin)', 'gdpr-framework')
        );

        $this->registerSettingField(
            'gdpr_export_action',
            _x('Export action', '(Admin)', 'gdpr-framework'),
            [$this, 'renderExportActionSelector'],
            'gdpr_section_export'
        );

        $this->registerSettingField(
            'gdpr_export_action_email',
            _x('Email to notify', '(Admin)', 'gdpr-framework'),
            [$this, 'renderExportActionEmail'],
            'gdpr_section_export',
            ['class' => 'js-gdpr-export-action-email hidden']
        );

        /**
         * Delete data
         */
        $this->registerSettingSection(
            'gdpr_section_delete',
            _x('Delete & Anonymize Data', '(Admin)', 'gdpr-framework')
        );

        $this->registerSettingField(
            'gdpr_delete_action',
            _x('Delete action', '(Admin)', 'gdpr-framework'),
            [$this, 'renderDeleteActionSelector'],
            'gdpr_section_delete'
        );

        $this->registerSettingField(
            'gdpr_delete_action_reassign',
            _x('Delete or reassign content?', '(Admin)', 'gdpr-framework'),
            [$this, 'renderDeleteActionReassign'],
            'gdpr_section_delete',
            ['class' => 'js-gdpr-delete-action-reassign hidden']
        );

        $this->registerSettingField(
            'gdpr_delete_action_reassign_user',
            _x('Reassign content to', '(Admin)', 'gdpr-framework'),
            [$this, 'renderDeleteActionReassignUser'],
            'gdpr_section_delete',
            ['class' => 'js-gdpr-delete-action-reassign-user hidden']
        );

        $this->registerSettingField(
            'gdpr_delete_action_email',
            _x('Email to notify', '(Admin)', 'gdpr-framework'),
            [$this, 'renderDeleteActionEmail'],
            'gdpr_section_delete',
            ['class' => 'js-gdpr-delete-action-email hidden']
        );

        /**
         * Stylesheet
         */

        $this->registerSettingSection(
            'gdpr_section_stylesheet',
            _x('Styling', '(Admin)', 'gdpr-framework')
        );

        $this->registerSettingField(
            'gdpr_enable_theme_compatibility',
            _x('Enable basic styling on Privacy Tools page', '(Admin)', 'gdpr-framework'),
            [$this, 'renderStylesheetSelector'],
            'gdpr_section_stylesheet'
        );

        if (gdpr('themes')->isCurrentThemeSupported()) {

            /**
             * Compatibility settings
             */
            $this->registerSettingSection(
                'gdpr_section_compatibility',
                _x('Compatibility', '(Admin)', 'gdpr-framework')
            );

            $this->registerSettingField(
                'gdpr_enable_theme_compatibility',
                _x('Enable automatic theme compatibility', '(Admin)', 'gdpr-framework'),
                [$this, 'renderThemeCompatibilitySelector'],
                'gdpr_section_compatibility'
            );
        }
    }

    public function renderEnableCheckbox()
    {
        $enabled = gdpr('options')->get('enable');
        echo gdpr('view')->render('admin/general/enable', compact('enabled'));
    }

    public function renderPrivacyToolsPageSelector()
    {
        wp_dropdown_pages([
            'name'              => 'gdpr_tools_page',
            'show_option_none'  => _x('&mdash; Select &mdash;', '(Admin)', 'gdpr-framework'),
            'option_none_value' => '0',
            'selected'          => gdpr('options')->get('tools_page'),
            'class'             => 'js-gdpr-select2 gdpr-select',
            'post_status'       => 'publish,draft',
        ]);
        echo gdpr('view')->render('admin/general/description-data-page');
    }

    /**
     * Render the GDPR policy page selector dropdown
     */
    public function renderPolicyPageSelector()
    {
        wp_dropdown_pages([
            'name'              => 'gdpr_policy_page',
            'show_option_none'  => _x('&mdash; Select &mdash;', '(Admin)', 'gdpr-framework'),
            'option_none_value' => '0',
            'selected'          => gdpr('options')->get('policy_page'),
            'class'             => 'js-gdpr-select2 gdpr-select',
            'post_status'       => 'publish,draft',
        ]);
        echo gdpr('view')->render('admin/privacy-policy/description-policy-page');
    }

    public function renderTermsPageSelector()
    {
        wp_dropdown_pages([
            'name'              => 'gdpr_terms_page',
            'show_option_none'  => _x('&mdash; Select &mdash;', '(Admin)', 'gdpr-framework'),
            'option_none_value' => '0',
            'selected'          => gdpr('options')->get('terms_page'),
            'class'             => 'js-gdpr-select2 gdpr-select',
            'post_status'       => 'publish,draft',
        ]);
        echo gdpr('view')->render('admin/general/description-terms-page');
    }

    public function renderExportActionSelector()
    {
        $exportAction = gdpr('options')->get('export_action');
        echo gdpr('view')->render('admin/general/export-action', compact('exportAction'));
        echo gdpr('view')->render('admin/general/description-export-action');
    }

    public function renderExportActionEmail()
    {
        $exportActionEmail = gdpr('options')->get('export_action_email');
        echo gdpr('view')->render('admin/general/export-action-email', compact('exportActionEmail'));
    }

    public function renderDeleteActionSelector()
    {
        $deleteAction = gdpr('options')->get('delete_action');
        echo gdpr('view')->render('admin/general/delete-action', compact('deleteAction'));
        echo gdpr('view')->render('admin/general/description-delete-action');
    }

    public function renderDeleteActionReassign()
    {
        $reassign = gdpr('options')->get('delete_action_reassign');
        echo gdpr('view')->render('admin/general/delete-action-reassign', compact('reassign'));
    }

    public function renderDeleteActionReassignUser()
    {
        wp_dropdown_users([
            'name'              => 'gdpr_delete_action_reassign_user',
            'show_option_none'  => _x('&mdash; Select &mdash;', '(Admin)', 'gdpr-framework'),
            'option_none_value' => '0',
            'selected'          => gdpr('options')->get('delete_action_reassign_user'),
            'class'             => 'js-gdpr-select2 gdpr-select',
            'role__in'          => apply_filters('gdpr/options/reassign/roles', ['administrator', 'editor']),
        ]);
    }

    public function renderDeleteActionEmail()
    {
        $deleteActionEmail = gdpr('options')->get('delete_action_email');
        echo gdpr('view')->render('admin/general/delete-action-email', compact('deleteActionEmail'));
    }

    public function renderStylesheetSelector()
    {
        $enabled = gdpr('options')->get('enable_stylesheet');
        echo gdpr('view')->render('admin/general/stylesheet', compact('enabled'));
    }

    public function renderThemeCompatibilitySelector()
    {
        $enabled = gdpr('options')->get('enable_theme_compatibility');
        echo gdpr('view')->render('admin/general/theme-compatibility', compact('enabled'));
    }
}
