<?php

namespace Codelight\GDPR\Components\PrivacyToolsPage;

use Codelight\GDPR\DataSubject\DataSubject;
use Codelight\GDPR\DataSubject\DataSubjectAuthenticator;
use Codelight\GDPR\DataSubject\DataSubjectIdentificator;
use Codelight\GDPR\DataSubject\DataSubjectManager;
use Codelight\GDPR\DataSubject\DataExporter;

/**
 * Handle the data page on front-end
 *
 * Class DataPageController
 *
 * @package Codelight\GDPR\Components\DataPage
 */
class PrivacyToolsPageController
{
    /* @var DataSubjectAuthenticator */
    protected $dataSubjectAuthenticator;

    /* @var DataSubjectIdentificator */
    protected $dataSubjectIdentificator;

    /* @var DataSubjectManager */
    protected $dataSubjectManager;

    /**
     * DataPageController constructor.
     *
     * @param DataSubjectIdentificator $dataSubjectIdentificator
     * @param DataSubjectManager       $dataSubjectManager
     */
    public function __construct(
        DataSubjectAuthenticator $dataSubjectAuthenticator,
        DataSubjectIdentificator $dataSubjectIdentificator,
        DataSubjectManager $dataSubjectManager,
        DataExporter $dataExporter
    ) {
        $this->dataSubjectAuthenticator = $dataSubjectAuthenticator;
        $this->dataSubjectIdentificator = $dataSubjectIdentificator;
        $this->dataSubjectManager       = $dataSubjectManager;
        $this->dataExporter             = $dataExporter;

        if ( ! gdpr('options')->get('enable')) {
            return;
        }

        $this->setup();
    }

    protected function setup()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);

        // Listen to 'identify' action and send an email
        add_action('gdpr/frontend/action/identify', [$this, 'sendIdentificationEmail']);

        add_action('gdpr/frontend/privacy-tools-page/content', [$this, 'renderConsentForm'], 10, 2);
        add_action('gdpr/frontend/privacy-tools-page/content', [$this, 'renderExportForm'], 20, 2);
        add_action('gdpr/frontend/privacy-tools-page/content', [$this, 'renderDeleteForm'], 30, 2);

        add_action('gdpr/frontend/privacy-tools-page/action/withdraw_consent', [$this, 'withdrawConsent'], 10, 2);
        add_action('gdpr/frontend/privacy-tools-page/action/export', [$this, 'export'], 10, 2);
        add_action('gdpr/frontend/privacy-tools-page/action/forget', [$this, 'forget'], 10, 2);
    }

    public function enqueue()
    {
        if ( ! gdpr('options')->get('enable_stylesheet') || ! is_page(gdpr('options')->get('tools_page'))) {
            return;
        }

        wp_enqueue_style(
            'gdpr-framework-privacy-tools',
            gdpr('config')->get('plugin.url') . 'assets/privacy-tools.css'
        );
    }

    /**
     * If the given email address exists as a data subject, send an authentication email to that address
     */
    public function sendIdentificationEmail()
    {
        // Additional safety check
        if ( ! is_email($_REQUEST['email'])) {
            $this->redirect(['gdpr_notice' => 'invalid_email']);
        }

        if ($this->dataSubjectIdentificator->isDataSubject($_REQUEST['email'])) {
            $this->dataSubjectIdentificator->sendIdentificationEmail($_REQUEST['email']);
        } else {
            $this->dataSubjectIdentificator->sendNoDataFoundEmail($_REQUEST['email']);
        }

        $this->redirect(['gdpr_notice' => 'email_sent']);
    }

    /**
     * Render the page contents.
     * This is only called via the shortcode.
     */
    public function render()
    {
        $dataSubject = $this->dataSubjectAuthenticator->authenticate();
        $this->renderNotices();

        if ($dataSubject) {
            $this->renderPrivacyTools($dataSubject);
        } else {
            $this->renderIdentificationForm();
        }
    }

    /**
     * Display notices to the user.
     * The contents of the notices are currently hardcoded inside the template.
     */
    protected function renderNotices()
    {
        if ( ! isset($_REQUEST['gdpr_notice'])) {
            return;
        }

        echo gdpr('view')->render('privacy-tools/notices');
    }

    /**
     * Render the contents of the identification form
     */
    protected function renderIdentificationForm()
    {
        $nonce = wp_create_nonce('gdpr/frontend/action/identify');
        echo gdpr('view')->render('privacy-tools/form-identify', compact('nonce', 'notices'));
    }

    /**
     * Render the contents of the Privacy Tools page
     *
     * @param DataSubject $dataSubject
     */
    protected function renderPrivacyTools(DataSubject $dataSubject)
    {
        $email = $dataSubject->getEmail();
        echo gdpr('view')->render('privacy-tools/privacy-tools', compact('dataSubject', 'email'));
    }

    /**
     * Render the form that allows withdrawing consent
     *
     * @param DataSubject $dataSubject
     */
    public function renderConsentForm(DataSubject $dataSubject)
    {
        $consentData = $dataSubject->getVisibleConsentData();

        foreach ($consentData as &$item) {
            $item['withdraw_url'] = add_query_arg([
                'gdpr_action' => 'withdraw_consent',
                'gdpr_nonce'  => wp_create_nonce("gdpr/frontend/privacy-tools-page/action/withdraw_consent"),
                'email'       => $dataSubject->getEmail(),
                'consent'     => $item['slug'],
            ]);
        }

        $consentInfo = wpautop(gdpr('options')->get('consent_info'));

        echo gdpr('view')->render(
            "privacy-tools/form-consent",
            compact('consentData', 'consentInfo')
        );
    }

    /**
     * Render the form that allows the data subject to export their data
     *
     * @param DataSubject $dataSubject
     */
    public function renderExportForm(DataSubject $dataSubject)
    {
        $email = $dataSubject->getEmail();
        $nonce = wp_create_nonce("gdpr/frontend/privacy-tools-page/action/export");

        echo gdpr('view')->render(
            "privacy-tools/form-export",
            compact('email', 'nonce')
        );
    }

    /**
     * Render the form that allows the data subject to delete their data
     *
     * @param DataSubject $dataSubject
     */
    public function renderDeleteForm(DataSubject $dataSubject)
    {
        // Let's not allow admins to delete themselves
        if (current_user_can('manage_options')) {
            echo gdpr('view')->render("privacy-tools/notice-admin-role");

            return;
        }

        $action = 'forget';
        $email  = $dataSubject->getEmail();
        $nonce  = wp_create_nonce("gdpr/frontend/privacy-tools-page/action/forget");

        echo gdpr('view')->render(
            "privacy-tools/form-delete",
            compact('action', 'email', 'nonce')
        );
    }

    /**
     * Withdraw the consent
     *
     * @param DataSubject $dataSubject
     */
    public function withdrawConsent(DataSubject $dataSubject)
    {
        $dataSubject->withdrawConsent($_REQUEST['consent']);
        $this->redirect(['gdpr_notice' => 'consent_withdrawn']);
    }

    /**
     * Trigger the export action.
     *
     * @param DataSubject $dataSubject
     */
    public function export(DataSubject $dataSubject)
    {
        $data = $dataSubject->export($_REQUEST['gdpr_format']);

        if ( ! is_null($data)) {
            // If there is data, download it
            $this->dataExporter->export($data, $dataSubject, $_REQUEST['gdpr_format']);
        } else {
            // If there's no data, then show notification that your request has been sent.
            $this->redirect(['gdpr_notice' => 'request_sent']);
        }
    }

    /**
     * Trigger the forget action.
     *
     * @param DataSubject $dataSubject
     */
    public function forget(DataSubject $dataSubject)
    {
        $deleted = $dataSubject->forget();

        if ($deleted) {
            $this->dataSubjectAuthenticator->deleteSession();
            $this->redirect(['gdpr_notice' => 'data_deleted']);
        } else {
            // If request was sent to admin, then show notification
            $this->redirect(['gdpr_notice' => 'request_sent']);
        }

    }

    /**
     * Redirect the visitor to an appropriate location
     *
     * @param array $args
     * @param null  $baseUrl
     */
    protected function redirect($args = [], $baseUrl = null)
    {
        if ( ! $baseUrl) {
            $privacyToolsUrl = gdpr('options')->get('tools_page');
            // Avoid infinite loop redirect
            $baseUrl = $privacyToolsUrl ? get_permalink($privacyToolsUrl) : home_url();
        }

        wp_safe_redirect(add_query_arg($args, $baseUrl));
        exit;
    }
}
