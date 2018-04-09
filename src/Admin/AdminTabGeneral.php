<?php

namespace Codelight\GDPR\Admin;

class AdminTabGeneral extends AdminTab
{
    protected $slug = 'general';

    public function __construct()
    {
        $this->title = __('General', 'gdpr-admin');

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

        $this->registerSetting('gdpr_enable_theme_compatibility');
    }

    public function init()
    {
        /**
         * General
         */
        $this->registerSettingSection(
            'gdpr_section_general',
            __('General Settings', 'gdpr-admin')
        );

        $this->registerSettingField(
            'gdpr_enable',
            __('Enable Privacy Tools', 'gdpr-admin'),
            [$this, 'renderEnableCheckbox'],
            'gdpr_section_general'
        );

        /**
         * GDPR system pages
         */
        $this->registerSettingSection(
            'gdpr_section_pages',
            __('Pages', 'gdpr-admin')
        );

        $this->registerSettingField(
            'gdpr_tools_page',
            __('Privacy Tools Page', 'gdpr-admin') . '*',
            [$this, 'renderPrivacyToolsPageSelector'],
            'gdpr_section_pages'
        );

        $this->registerSettingField(
            'gdpr_policy_page',
            __('Privacy Policy Page', 'gdpr-admin') . '*',
            [$this, 'renderPolicyPageSelector'],
            'gdpr_section_pages'
        );

        $this->registerSettingField(
            'gdpr_terms_page',
            __('Terms & Conditions Page', 'gdpr-admin'),
            [$this, 'renderTermsPageSelector'],
            'gdpr_section_pages'
        );

        /**
         * View & Export
         */
        $this->registerSettingSection(
            'gdpr_section_export',
            __('View & Export Data', 'gdpr-admin')
        );

        $this->registerSettingField(
            'gdpr_export_action',
            __('Export action', 'gdpr-admin'),
            [$this, 'renderExportActionSelector'],
            'gdpr_section_export'
        );

        $this->registerSettingField(
            'gdpr_export_action_email',
            __('Email to notify', 'gdpr-admin'),
            [$this, 'renderExportActionEmail'],
            'gdpr_section_export',
            ['class' => 'js-gdpr-export-action-email hidden']
        );

        /**
         * Delete data
         */
        $this->registerSettingSection(
            'gdpr_section_delete',
            __('Delete & Anonymize Data', 'gdpr-admin')
        );

        $this->registerSettingField(
            'gdpr_delete_action',
            __('Delete action', 'gdpr-admin'),
            [$this, 'renderDeleteActionSelector'],
            'gdpr_section_delete'
        );

        $this->registerSettingField(
            'gdpr_delete_action_reassign',
            __('Delete or reassign content?', 'gdpr-admin'),
            [$this, 'renderDeleteActionReassign'],
            'gdpr_section_delete',
            ['class' => 'js-gdpr-delete-action-reassign hidden']
        );

        $this->registerSettingField(
            'gdpr_delete_action_reassign_user',
            __('Reassign content to', 'gdpr-admin'),
            [$this, 'renderDeleteActionReassignUser'],
            'gdpr_section_delete',
            ['class' => 'js-gdpr-delete-action-reassign-user hidden']
        );

        $this->registerSettingField(
            'gdpr_delete_action_email',
            __('Email to notify', 'gdpr-admin'),
            [$this, 'renderDeleteActionEmail'],
            'gdpr_section_delete',
            ['class' => 'js-gdpr-delete-action-email hidden']
        );

        if (gdpr('themes')->isCurrentThemeSupported()) {

            /**
             * General settings
             */
            $this->registerSettingSection(
                'gdpr_section_compatibility',
                __('Compatibility', 'gdpr-admin')
            );

            $this->registerSettingField(
                'gdpr_enable_theme_compatibility',
                __('Enable automatic theme compatibility', 'gdpr-admin'),
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
            'show_option_none'  => __('&mdash; Select &mdash;'),
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
            'show_option_none'  => __('&mdash; Select &mdash;'),
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
            'show_option_none'  => __('&mdash; Select &mdash;'),
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
            'show_option_none'  => __('&mdash; Select &mdash;'),
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

    public function renderThemeCompatibilitySelector()
    {
        $enabled = gdpr('options')->get('enable_theme_compatibility');
        echo gdpr('view')->render('admin/general/theme-compatibility', compact('enabled'));
    }
}
