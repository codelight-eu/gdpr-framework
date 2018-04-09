<?php

namespace Codelight\GDPR\DataSubject;

use Codelight\GDPR\Admin\AdminTab;

/**
 * Class AdminTabDataSubject
 *
 * @package Codelight\GDPR\DataSubject
 */
class AdminTabDataSubject extends AdminTab
{
    /* @var string */
    protected $slug = 'data-subject';

    /* @var DataSubjectManager */
    protected $dataSubjectManager;

    /**
     * AdminTabDataSubject constructor.
     *
     * @param DataSubjectManager $dataSubjectManager
     */
    public function __construct(DataSubjectManager $dataSubjectManager)
    {
        $this->title = __('Data Subjects', 'gdpr-admin');
        $this->dataSubjectManager = $dataSubjectManager;

        // Workaround to allow this page to be submitted
        $this->registerSetting('gdpr_email');

        // Register handler for this action
        add_action('gdpr/admin/action/search', [$this, 'searchRedirect']);
    }

    public function init()
    {
        $this->registerSettingSection(
            'gdpr-section-data-subjects',
            __('Data Subjects', 'gdpr-admin'),
            [$this, 'renderTab']
        );
    }

    public function renderTab()
    {
        if (isset($_GET['search']) && $_GET['search']) {
            $results = $this->getRenderedResults($_GET['search'], $this->dataSubjectManager->getByEmail($_GET['search']));
        } else {
            $results = '';
        }

        $nonce = wp_create_nonce('gdpr/admin/action/search');
        echo gdpr('view')->render(
            'admin/data-subjects/search-form',
            compact('nonce', 'results', 'exportUrl', 'deleteUrl')
        );
    }

    public function getRenderedResults($email, DataSubject $dataSubject)
    {
        $hasData = $dataSubject->hasData();
        $links = [];

        if ($hasData) {
            if ($dataSubject->getUserId()) {
                $userName = get_userdata($dataSubject->getUserId())->user_login;
                $links['profile'] = get_edit_user_link($dataSubject->getUserId());
                $adminCap = user_can($dataSubject->getUserId(), 'manage_options');

            } else {
                $userName = false;
                $adminCap = false;
            }

            /**
             * TODO: these actions are currently triggered in DashboardProfilePageController
             * Should replace this with a generic AdminController!
             * Also consider namespacing gdpr_action in this case, i.e. profile/delete vs data-subject-tab/delete
             */
            $links['view'] = add_query_arg([
                'gdpr_action' => 'export',
                'gdpr_format' => 'html',
                'gdpr_email'  => $_GET['search'],
                'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/export"),
            ]);

            $links['export'] = add_query_arg([
                'gdpr_action' => 'export',
                'gdpr_format' => 'json',
                'gdpr_email'  => $_GET['search'],
                'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/export"),
            ]);

            $links['anonymize'] = add_query_arg([
                'gdpr_email' => $_GET['search'],
                'gdpr_action' => 'forget',
                'gdpr_force_action' => 'anonymize',
                'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/forget"),
            ]);

            $links['delete'] = add_query_arg([
                'gdpr_email' => $_GET['search'],
                'gdpr_action' => 'forget',
                'gdpr_force_action' => 'delete',
                'gdpr_nonce'  => wp_create_nonce("gdpr/admin/action/forget"),
            ]);
        }

        return gdpr('view')->render('admin/data-subjects/search-results', compact('email', 'hasData', 'links', 'userName', 'adminCap'));
    }

    public function renderSubmitButton()
    {
        // Intentionally left blank
    }

    public function searchRedirect()
    {
        if (isset($_POST['gdpr_email']) && $_POST['gdpr_email']) {
            wp_safe_redirect(gdpr('helpers')->getAdminUrl('&gdpr-tab=data-subject&search=' . $_POST['gdpr_email']));
            exit;
        }
    }
}
