<?php

namespace Codelight\GDPR\Installer\Steps;

use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

/**
 * Handle the first step on installer screen
 *
 * Class Welcome
 *
 * @package Codelight\GDPR\Installer\Steps
 */
class Welcome extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'welcome';

    protected $type = 'wizard';

    protected $template = 'installer/steps/welcome';

    protected $activeSteps = 0;

    protected function renderFooter()
    {
        echo gdpr('view')->render('installer/footer', ['disableBackButton' => true]);
    }
}