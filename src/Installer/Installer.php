<?php

namespace Codelight\GDPR\Installer;

use Codelight\GDPR\Admin\AdminTabGeneral;

/**
 * Handle all installation activities
 *
 * Class Installer
 *
 * @package Codelight\GDPR\Installer
 */
class Installer
{
    /* @var array */
    protected $defaultSteps = [
        'Codelight\GDPR\Installer\Steps\Welcome',
        'Codelight\GDPR\Installer\Steps\Disclaimer',
        'Codelight\GDPR\Installer\Steps\ConfigurationPages',
        'Codelight\GDPR\Installer\Steps\ConfigurationSettings',
        'Codelight\GDPR\Installer\Steps\PolicySettings',
        'Codelight\GDPR\Installer\Steps\PolicyContents',
        'Codelight\GDPR\Installer\Steps\Consent',
        'Codelight\GDPR\Installer\Steps\Integrations',
        'Codelight\GDPR\Installer\Steps\Finish',
    ];

    /* @var array */
    protected $steps = [];

    /* @var InstallerWizard */
    protected $wizard;

    /* @var InstallerRouter */
    protected $router;

    /**
     * Check if the installer is enabled and ensure the user has correct permissions to run it
     */
    public function __construct(AdminTabGeneral $adminTab)
    {
        if (!$this->isInstallerEnabled()) {
            return;
        }

        if (!$this->userHasPermissions()) {
            return;
        }

        $this->adminTab = $adminTab;

        $this->maybeDisplayDisclaimer();
        $this->setupHooks();

        if (!$this->isInstalled()) {
            $this->setupSteps();
            $this->runInstaller();
        }
    }

    /**
     * Setup actions and admin tab components
     */
    protected function setupHooks()
    {
        add_action('admin_init', [$this, 'setupAdminGeneralTabButtons'], 0);

        add_action('gdpr/admin/action/accept_disclaimer', [$this, 'acceptDisclaimer']);

        add_action('gdpr/admin/action/restart_wizard', [$this, 'restartWizard']);

        add_action('gdpr/admin/action/auto_install', [$this, 'autoInstall']);
        add_action('gdpr/admin/action/skip_install', [$this, 'skipInstall']);
    }

    protected function runInstaller()
    {
        $this->wizard = new InstallerWizard;
        $this->router = new InstallerRouter($this->steps);

        // If we're currently on one of the installer steps, let the router handle it
        if ($this->router->isInstallerStep()) {
            return;
        }

        if ($this->getCurrentStepSlug()) {
            // If the current step is set, display continue notice
            $step = $this->router->findStep($this->getCurrentStepSlug());
            // If step doesn't exist, then it means the step slugs have changed. Do nothing.
            if (!$step) {
                return;
            }
            $this->displayContinueNotice($step->getUrl());
        } else {
            // If the current step is not set, it means the installer hasn't been started yet
            $this->displayWelcomeNotice();
        }
    }

    /**
     * If the admin has not accepted the disclaimer, render it
     */
    public function maybeDisplayDisclaimer()
    {
        if (!gdpr('options')->get('plugin_disclaimer_accepted') && (isset($_GET['page']) && 'privacy' === $_GET['page'])) {
            $acceptUrl = add_query_arg([
                'gdpr_action' => 'accept_disclaimer',
                'gdpr_nonce'  => wp_create_nonce('gdpr/admin/action/accept_disclaimer'),
            ]);
            gdpr('admin-notice')->add('admin/notices/disclaimer', compact('acceptUrl'));
        }
    }

    /**
     * Mark the disclaimer as accepted
     */
    public function acceptDisclaimer()
    {
        gdpr('options')->set('plugin_disclaimer_accepted', 'yes');
        wp_safe_redirect(gdpr('helpers')->getAdminUrl());
        exit;
    }

    /**
     * Display installer section in admin page
     */
    public function setupAdminGeneralTabButtons()
    {
        /**
         * Display wizard buttons
         */
        $this->adminTab->registerSettingSection(
            'gdpr-section-wizard',
            _x('Setup Wizard', '(Admin)', 'gdpr-framework'),
            [$this, 'renderWizardButtons']
        );
    }

    /**
     * Render the installer section
     */
    public function renderWizardButtons()
    {
        $restartUrl = add_query_arg([
            'gdpr_action' => 'restart_wizard',
            'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/restart_wizard"),
        ]);

        echo gdpr('view')->render(
            'admin/wizard-buttons',
            compact('restartUrl')
        );
    }

    /**
     * Restart and redirect to first step
     */
    public function restartWizard()
    {
        gdpr('options')->delete('installer_step');
        gdpr('options')->delete('is_installed');

        wp_safe_redirect(self_admin_url());
        exit;
    }

    /**
     * Allow plugins to modify the steps
     */
    protected function setupSteps()
    {
        $steps = apply_filters('gdpr/installer/steps', $this->defaultSteps);

        foreach ($steps as $index => $step) {
            $this->steps[$index] = new $step;
        }
    }

    /**
     * The installer can be disabled by filter.
     * Check if it's enabled
     *
     * @return bool
     */
    protected function isInstallerEnabled()
    {
        return apply_filters('gdpr/installer/enabled', true);
    }

    /**
     * Check if the current user has correct permissions to run the installer
     *
     * @return bool
     */
    protected function userHasPermissions()
    {
        return current_user_can(apply_filters('gdpr/installer/permissions', 'manage_options'));
    }

    /**
     * Check if the installer is already ran
     *
     * @return bool
     */
    protected function isInstalled()
    {
        return gdpr('options')->get('is_installed');
    }

    /**
     * @return string
     */
    public function getCurrentStepSlug()
    {
        return gdpr('options')->get('installer_step');
    }

    /**
     * Render an admin notice that will display the welcome message
     */
    protected function displayWelcomeNotice()
    {
        // Make sure we display the notice only to admins
        if (!current_user_can(apply_filters('gdpr/capability', 'manage_options'))) {
            return;
        }

        $installerUrl = $this->steps[0]->getUrl();
        $autoInstallUrl = add_query_arg([
            'gdpr_action' => 'auto_install',
            'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/auto_install"),
        ]);
        $skipUrl = add_query_arg([
            'gdpr_action' => 'skip_install',
            'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/skip_install"),
        ]);

        gdpr('admin-notice')->add(
            'installer/welcome-notice',
            compact('installerUrl', 'autoInstallUrl', 'skipUrl')
        );
    }

    /**
     * Render an admin notice that will display the continue button
     *
     * @param $url
     */
    protected function displayContinueNotice($url)
    {
        // Make sure we display the notice only to admins
        if (!current_user_can(apply_filters('gdpr/capability', 'manage_options'))) {
            return;
        }

        $skipUrl = add_query_arg([
            'gdpr_action' => 'skip_install',
            'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/skip_install"),
        ]);

        gdpr('admin-notice')->add('installer/continue-notice', ['buttonUrl' => $url, 'skipUrl' => $skipUrl]);
    }

    /**
     * Automatically create pages for Privacy Policy and set the corresponding options
     */
    public function autoInstall()
    {
        $policyPageId = wp_insert_post([
            'post_title'   => __('Privacy Policy', 'gdpr-framework'),
            'post_type'    => 'page',
            'post_status'  => 'publish',
        ]);

        gdpr('options')->set('policy_page', $policyPageId);

        $toolsPageId = wp_insert_post([
            'post_content' => '[gdpr_privacy_tools]',
            'post_title'   => __('Privacy Tools', 'gdpr-framework'),
            'post_type'    => 'page',
            'post_status'  => 'publish',
        ]);
        gdpr('options')->set('tools_page', $toolsPageId);

        // Woocommerce compatibility - automatically add their terms page
        if (get_option('woocommerce_terms_page_id')) {
            gdpr('options')->set('terms_page', get_option('woocommerce_terms_page_id'));
        }

        gdpr('options')->set('is_installed', 'yes');
        wp_safe_redirect(gdpr('helpers')->getAdminUrl('&gdpr-tab=privacy-policy&gdpr-notice=autoinstall'));
        exit;
    }

    /**
     * Do nothing, but mark the installer as completed
     */
    public function skipInstall()
    {
        gdpr('options')->set('is_installed', 'yes');
        wp_safe_redirect(gdpr('helpers')->getAdminUrl());
        exit;
    }
}
