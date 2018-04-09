<?php


namespace Codelight\GDPR\Installer\Steps;


use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

class Disclaimer extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'disclaimer';

    protected $type = 'wizard';

    protected $template = 'installer/steps/disclaimer';

    protected $activeSteps = 0;

    public function submit()
    {
        gdpr('options')->set('plugin_disclaimer_accepted', 'yes');
    }
}