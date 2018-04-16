<?php

namespace Codelight\GDPR\Components\WordpressUser;

use Codelight\GDPR\DataSubject\DataSubjectManager;
use Codelight\GDPR\Components\WordpressUser\Controllers\DashboardDataPageController;
use Codelight\GDPR\Components\WordpressUser\Controllers\DashboardProfilePageController;

/**
 * Handles everything related to a WordPress user account
 *
 * Class WordpressUser
 *
 * @package Codelight\GDPR\Modules\WordpressUser
 */
class WordpressUser
{
    /* @var string */
    protected $name = 'wordpress-user';

    /* @var DataManager */
    protected $dataManager;

    /* @var DataSubjectManager */
    protected $dataSubjectManager;

    /**
     * WordpressUser constructor.
     *
     * @param DataSubjectManager $dataSubjectManager
     * @param DataManager        $dataManager
     */
    public function __construct(DataSubjectManager $dataSubjectManager, DataManager $dataManager)
    {
        $this->dataSubjectManager = $dataSubjectManager;
        $this->dataManager = $dataManager;

        gdpr()->make(DashboardProfilePageController::class);
        gdpr()->make(RegistrationForm::class);

        if (gdpr('options')->get('enable')) {
            gdpr()->make(DashboardDataPageController::class);

            // Register Privacy Tools page in admin
            add_action('admin_menu', [$this, 'registerDashboardDataPage']);
        }

        // Register render action on Profile edit page
        add_action('show_user_profile', [$this, 'triggerProfileRenderAction'], PHP_INT_MAX);
        add_action('edit_user_profile', [$this, 'triggerProfileRenderAction'], PHP_INT_MAX);

        add_filter('gdpr/data-subject/data', [$this, 'getExportData'], 1, 2);
        add_action('gdpr/data-subject/delete', [$this, 'deleteUser'], 100);
        add_action('gdpr/data-subject/anonymize', [$this, 'anonymizeUser'], 100, 2);
    }

    /**
     * Register Privacy Tools dashboard page under Users
     */
    public function registerDashboardDataPage()
    {
        add_users_page(
            _x('Privacy Tools', '(Admin)', 'gdpr'),
            _x('Privacy Tools', '(Admin)', 'gdpr'),
            'read',
            'gdpr-profile',
            [$this, 'renderDashboardDataPage']
        );
    }

    /**
     * Render the contents of Privacy Tools dashboard page
     */
    public function renderDashboardDataPage()
    {
        $dataSubject = $this->dataSubjectManager->getByLoggedInUser();

        if ($dataSubject) {
            do_action('gdpr/dashboard/privacy-tools/content', $dataSubject);
        }
    }

    /**
     * On profile page, trigger an action with the same format as the Router provides
     * so that we have consistency with the rest of the hooks.
     */
    public function triggerProfileRenderAction(\WP_User $user)
    {
        if (current_user_can('edit_users') || current_user_can('delete_users')) {
            $dataSubject = $this->dataSubjectManager->getByEmail($user->user_email);
            do_action("gdpr/dashboard/profile-page/content", $dataSubject);
        }
    }

    public function getExportData($data, $email)
    {
        return $data + $this->dataManager->getData($this->dataSubjectManager->getByEmail($email));
    }

    public function deleteUser($email)
    {
        $dataSubject = $this->dataSubjectManager->getByEmail($email);
        $this->dataManager->deleteUser($dataSubject);
    }

    public function anonymizeUser($email, $anonymizedId)
    {
        $dataSubject = $this->dataSubjectManager->getByEmail($email);
        $this->dataManager->anonymizeUser($dataSubject, $anonymizedId);
    }
}
