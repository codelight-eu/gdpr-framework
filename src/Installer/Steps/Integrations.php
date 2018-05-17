<?php

namespace Codelight\GDPR\Installer\Steps;

use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

class Integrations extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'integrations';

    protected $type = 'wizard';

    protected $template = 'installer/steps/integrations';

    protected $activeSteps = 4;

    protected function renderContent()
    {
        $enableThemeCompatibility = gdpr('options')->get('enable_theme_compatibility');
        $currentTheme = gdpr('themes')->getCurrentThemeName();
        $isThemeSupported = gdpr('themes')->isCurrentThemeSupported();

        $hasWooCommerce = false;
        $hasEDD = false;
        $hasSendGrid = class_exists('\Sendgrid_Tools');

        echo gdpr('view')->render(
            $this->template,
            compact(
                'enableThemeCompatibility',
                'hasEDD',
                'hasWooCommerce',
                'currentTheme',
                'isThemeSupported',
                'hasSendGrid'
            )
        );
    }

    public function submit()
    {
        if (isset($_POST['gdpr_enable_theme_compatibility']) && 'yes' === $_POST['gdpr_enable_theme_compatibility']) {
            gdpr('options')->set('enable_theme_compatibility', true);
        } else {
            gdpr('options')->set('enable_theme_compatibility', false);
        }
    }
}
