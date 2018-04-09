<?php

namespace Codelight\GDPR\Installer\Steps;

use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

class ConfigurationSettings extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'configuration-settings';

    protected $type = 'wizard';

    protected $template = 'installer/steps/configuration-settings';

    protected $activeSteps = 1;

    protected function renderContent()
    {
        $privacyToolsPageUrl = get_permalink(gdpr('options')->get('tools_page'));

        $deleteAction      = gdpr('options')->get('delete_action');
        $deleteActionEmail = gdpr('options')->get('delete_action_email');

        $exportAction      = gdpr('options')->get('export_action');
        $exportActionEmail = gdpr('options')->get('export_action_email');

        $reassign = gdpr('options')->get('delete_action_reassign');
        $reassignUser = gdpr('options')->get('delete_action_reassign_user');

        echo gdpr('view')->render(
            $this->template,
            compact(
                'deleteAction',
                'deleteActionEmail',
                'exportAction',
                'exportActionEmail',
                'privacyToolsPageUrl',
                'reassign',
                'reassignUser'
            )
        );
    }

    public function submit()
    {
        if (isset($_POST['gdpr_export_action'])) {
            gdpr('options')->set('delete_action', $_POST['gdpr_export_action']);
        }

        if (isset($_POST['gdpr_export_action_email'])) {
            gdpr('options')->set('delete_action_email', $_POST['gdpr_export_action_email']);
        }

        if (isset($_POST['gdpr_delete_action'])) {
            gdpr('options')->set('delete_action', $_POST['gdpr_delete_action']);
        }

        if (isset($_POST['gdpr_delete_action_email'])) {
            gdpr('options')->set('delete_action_email', $_POST['gdpr_delete_action_email']);
        }

        if (isset($_POST['gdpr_delete_action_reassign'])) {
            gdpr('options')->set('delete_action_reassign', $_POST['gdpr_delete_action_reassign']);
        }

        if (isset($_POST['gdpr_delete_action_reassign_user'])) {
            gdpr('options')->set('delete_action_reassign_user', $_POST['gdpr_delete_action_reassign_user']);
        }
    }
}
