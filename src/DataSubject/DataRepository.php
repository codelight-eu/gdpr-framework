<?php

namespace Codelight\GDPR\DataSubject;

/**
 * Class DataRepository
 *
 * @package Codelight\GDPR\DataSubject
 */
class DataRepository
{
    /**
     * DataRepository constructor.
     *
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Export all stored data. Triggers 'gdpr/data-subject/data' filter.
     */
    public function getData($email)
    {
        return apply_filters('gdpr/data-subject/data', [], $email);
    }

    /**
     * Trigger the configured 'export' action
     *
     * @param $email
     * @return array|null
     */
    public function export($email, $format, $force = false)
    {
        $action = gdpr('options')->get('export_action');
        $data = null;

        if ($force) {
            $action = 'download';
        }

        switch($action) {
            case 'download':
                $data = $this->getData($email);
                break;
            case 'download_and_notify':
                $data = $this->getData($email);
                $this->notifyExportAction($email, $format);
                break;
            case 'notify':
                $this->notifyExportRequest($email, $format);
                break;
            default:
                $this->notifyExportRequest($email, $format);
                break;
        }

        return $data;
    }

    /**
     * Trigger the configured 'forget' action
     *
     * @param $email
     *
     * @return bool
     */
    public function forget($email, $forceAction = null)
    {
        $action = gdpr('options')->get('delete_action');

        if ($forceAction) {
            $action = $forceAction;
        }

        switch($action) {
            case 'delete':
                $this->delete($email);
                return true;
            case 'delete_and_notify':
                $userId = $this->delete($email);
                $this->notifyForgetAction($email, $userId);
                return true;
            case 'anonymize':
                $this->anonymize($email);
                return true;
            case 'anonymize_and_notify':
                $userId = $this->anonymize($email);
                $this->notifyForgetAction($email, $userId);
                return true;
            case 'notify':
                $this->notifyForgetRequest($email);
                return false;
            default:
                $this->notifyForgetRequest($email);
                return false;
        }
    }

    /**
     * @param $email
     */
    protected function anonymize($email)
    {
        $userId = null;

        if (email_exists($email)) {
            $userId = get_user_by('email', $email)->ID;
        }

        $anonymizedId = wp_generate_password(12, false);
        do_action('gdpr/data-subject/anonymize', $email, $anonymizedId, $userId);

        return $userId;
    }

    /**
     * @param $email
     */
    protected function delete($email)
    {
        $userId = null;

        if (email_exists($email)) {
            $userId = get_user_by('email', $email)->ID;
        }

        do_action('gdpr/data-subject/delete', $email, $userId);

        return $userId;
    }

    /**
     * @param $email
     */
    protected function notifyExportAction($email, $format)
    {
        gdpr('helpers')->mail(
            gdpr('options')->get('export_action_email'),
            __("Data exported", 'gdpr-framework'),
            gdpr('view')->render('email/action-export', compact('email', 'format')),
            ['Content-Type: text/html; charset=UTF-8']
        );
    }

    /**
     * @param $email
     */
    protected function notifyExportRequest($email, $format)
    {
        $adminTabLink = esc_url(gdpr('helpers')->getAdminUrl('&gdpr-tab=data-subject&search=' . $email));

        gdpr('helpers')->mail(
            gdpr('options')->get('export_action_email'),
            __("Data export request", 'gdpr-framework'),
            gdpr('view')->render('email/request-export', compact('email', 'format', 'adminTabLink')),
            ['Content-Type: text/html; charset=UTF-8']
        );
    }

    /**
     * @param $email
     */
    protected function notifyForgetAction($email, $userId = null)
    {
        gdpr('helpers')->mail(
            gdpr('options')->get('delete_action_email'),
            __("Data removed", 'gdpr-framework'),
            gdpr('view')->render('email/action-forget', compact('email', 'userId')),
            ['Content-Type: text/html; charset=UTF-8']
        );
    }

    /**
     * @param $email
     */
    protected function notifyForgetRequest($email)
    {
        $adminTabLink = esc_url(gdpr('helpers')->getAdminUrl('&gdpr-tab=data-subject&search=' . $email));

        gdpr('helpers')->mail(
            gdpr('options')->get('delete_action_email'),
            __("Data removal request", 'gdpr-framework'),
            gdpr('view')->render('email/request-forget', compact('email', 'adminTabLink')),
            ['Content-Type: text/html; charset=UTF-8']
        );
    }
}
