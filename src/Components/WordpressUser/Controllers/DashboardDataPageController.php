<?php

namespace Codelight\GDPR\Components\WordpressUser\Controllers;

use Codelight\GDPR\DataSubject\DataExporter;
use Codelight\GDPR\DataSubject\DataSubject;
use Codelight\GDPR\DataSubject\DataSubjectAuthenticator;

/**
 * Handles Users > Privacy Tools page
 *
 * Class DashboardDataPageController
 *
 * @package Codelight\GDPR\Modules\WordpressUser\Controllers
 */
class DashboardDataPageController
{
    /**
     * DashboardDataPageController constructor.
     *
     * @param DataExporter $dataExporter
     */
    public function __construct(DataExporter $dataExporter, DataSubjectAuthenticator $dataSubjectAuthenticator)
    {
        $this->dataExporter = $dataExporter;
        $this->dataSubjectAuthenticator = $dataSubjectAuthenticator;

        add_action('gdpr/dashboard/privacy-tools/content', [$this, 'renderHeader'], 10);
        add_action('gdpr/dashboard/privacy-tools/content', [$this, 'renderConsentForm'], 20);
        add_action('gdpr/dashboard/privacy-tools/content', [$this, 'renderExportForm'], 30);
        add_action('gdpr/dashboard/privacy-tools/content', [$this, 'renderDeleteForm'], 40);

        add_action('gdpr/dashboard/privacy-tools/action/withdraw_consent', [$this, 'withdrawConsent']);
        add_action('gdpr/dashboard/privacy-tools/action/export', [$this, 'export']);
        add_action('gdpr/dashboard/privacy-tools/action/forget', [$this, 'forget']);

        add_action('admin_notices', [$this, 'renderAdminNotices']);
    }

    /**
     * Render success notices via admin_notice action
     */
    public function renderAdminNotices()
    {
        if ('profile_page_gdpr-profile' !== get_current_screen()->base) {
            return;
        }

        if (!isset($_REQUEST['gdpr_notice'])) {
            return;
        }

        if ('request_sent' === $_REQUEST['gdpr_notice']) {
            $message = __('We have received your request and will reply within 30 days.', 'gdpr-framework');
            $class = 'notice notice-success';
        }

        if ('consent_withdrawn' === $_REQUEST['gdpr_notice']) {
            $message = __('Consent withdrawn.', 'gdpr-framework');
            $class = 'notice notice-success';
        }

        echo gdpr('view')->render('admin/notice', compact('message', 'class'));
    }

    /**
     * Render page header
     */
    public function renderHeader()
    {
        echo gdpr('view')->render(
            "modules/wordpress-user/dashboard/data-page/header"
        );
    }

    /**
     * Render the consent form
     *
     * @param DataSubject $dataSubject
     */
    public function renderConsentForm(DataSubject $dataSubject)
    {
        $consentData = $dataSubject->getVisibleConsentData();

        foreach ($consentData as &$item) {
            $item['withdraw_url'] = add_query_arg([
                'gdpr_action' => 'withdraw_consent',
                'gdpr_nonce'  => wp_create_nonce("gdpr/dashboard/privacy-tools/action/withdraw_consent"),
                'email'       => $dataSubject->getEmail(),
                'consent'     => $item['slug'],
            ]);
        }

        $consentInfo = wpautop(gdpr('options')->get('consent_info'));

        echo gdpr('view')->render(
            "modules/wordpress-user/dashboard/data-page/form-consent",
            compact('consentData', 'consentInfo')
        );
    }

    /**
     * Render the buttons that allow exporting data
     */
    public function renderExportForm()
    {
        $exportHTMLUrl = add_query_arg([
            'gdpr_action' => 'export',
            'gdpr_format' => 'html',
            'gdpr_nonce'  => wp_create_nonce("gdpr/dashboard/privacy-tools/action/export"),
        ]);

        $exportJSONUrl = add_query_arg([
            'gdpr_action' => 'export',
            'gdpr_format' => 'json',
            'gdpr_nonce'  => wp_create_nonce("gdpr/dashboard/privacy-tools/action/export"),
        ]);

        echo gdpr('view')->render(
            "modules/wordpress-user/dashboard/form-export",
            compact('exportHTMLUrl', 'exportJSONUrl')
        );
    }

    /**
     * Render the delete data button
     */
    public function renderDeleteForm()
    {
        $showDelete = !current_user_can('manage_options');
        $url = add_query_arg([
            'gdpr_action' => 'forget',
            'gdpr_nonce'  => wp_create_nonce("gdpr/dashboard/privacy-tools/action/forget"),
        ]);

        echo gdpr('view')->render(
            "modules/wordpress-user/dashboard/data-page/form-delete",
            compact('url', 'showDelete')
        );
    }

    /**
     * @param DataSubject $dataSubject
     */
    public function withdrawConsent(DataSubject $dataSubject)
    {
        $dataSubject->withdrawConsent($_REQUEST['consent']);
        $this->redirect(['gdpr_notice' => 'consent_withdrawn']);
    }

    /**
     * @param DataSubject $dataSubject
     */
    public function export(DataSubject $dataSubject)
    {
        $data = $dataSubject->export($_REQUEST['gdpr_format']);

        if (!is_null($data)) {
            // If there is data, download it
            $this->dataExporter->export($data, $dataSubject, $_REQUEST['gdpr_format']);
        } else {
            // If there's no data, then show notification that your request has been sent.
            $this->redirect(['gdpr_notice' => 'request_sent']);
        }
    }

    /**
     * @param DataSubject $dataSubject
     */
    public function forget(DataSubject $dataSubject)
    {
        $status = $dataSubject->forget();

        if (!$status) {
            $this->redirect(['gdpr_notice' => 'request_sent']);
        } else {
            $this->dataSubjectAuthenticator->deleteSession();
            $this->redirect([], '/');
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
        if (!$baseUrl) {
            $baseUrl = gdpr('helpers')->getDashboardDataPageUrl();
        }

        wp_safe_redirect(add_query_arg($args, $baseUrl));
        exit;
    }
}
