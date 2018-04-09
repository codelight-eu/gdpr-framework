<?php

namespace Codelight\GDPR;

use Codelight\GDPR\Admin\AdminError;
use Codelight\GDPR\Admin\AdminNotice;
use Codelight\GDPR\Admin\Modal;
use Codelight\GDPR\Admin\WordpressAdmin;
use Codelight\GDPR\Admin\WordpressAdminPage;
use Codelight\GDPR\Components\Consent\ConsentAdmin;
use Codelight\GDPR\Installer\Installer;
use Codelight\GDPR\Installer\AdminInstallerNotice;

/**
 * Register and set up admin components.
 * This class is instantiated at admin_init priority 0
 *
 * Class SetupAdmin
 *
 * @package Codelight\GDPR
 */
class SetupAdmin
{
    /**
     * SetupAdmin constructor.
     */
    public function __construct()
    {
        $this->registerComponents();
        $this->runComponents();
    }

    /**
     * Register components in the container
     */
    protected function registerComponents()
    {
        gdpr()->singleton(WordpressAdmin::class);
        gdpr()->singleton(WordpressAdminPage::class);
        gdpr()->singleton(Installer::class);

        // Not a singleton.
        gdpr()->alias(AdminNotice::class, 'admin-notice');
        gdpr()->alias(AdminError::class, 'admin-error');
        gdpr()->alias(AdminInstallerNotice::class, 'installer-notice');
        gdpr()->alias(Modal::class, 'admin-modal');
        gdpr()->alias(WordpressAdminPage::class, 'admin-page');

        gdpr()->bind(ConsentAdmin::class);
    }

    /**
     * Run components
     */
    protected function runComponents()
    {
        gdpr(WordpressAdmin::class);
        gdpr(WordpressAdminPage::class);
        gdpr(Installer::class);
        gdpr(ConsentAdmin::class);
    }
}
