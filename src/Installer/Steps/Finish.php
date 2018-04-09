<?php

namespace Codelight\GDPR\Installer\Steps;

use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

class Finish extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'finish';

    protected $type = 'wizard';

    protected $template = 'installer/steps/finish';

    protected $activeSteps = 4;

    public function submit()
    {
        gdpr('options')->set('is_installed', true);
    }

    protected function renderFooter()
    {
        echo gdpr('view')->render('installer/footer', ['disableBackButton' => true]);
    }
}