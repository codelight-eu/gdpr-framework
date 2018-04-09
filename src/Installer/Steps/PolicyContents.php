<?php


namespace Codelight\GDPR\Installer\Steps;


use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

class PolicyContents extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'policy-contents';

    protected $type = 'wizard';

    protected $template = 'installer/steps/policy-contents';

    protected $activeSteps = 2;

    protected function renderContent()
    {
        $policyUrl = get_permalink(gdpr('options')->get('policy_page'));
        $editPolicyUrl = get_edit_post_link(gdpr('options')->get('policy_page'));
        $policyGenerated = gdpr('options')->get('policy_generated');

        echo gdpr('view')->render(
            $this->template,
            compact('policyUrl', 'editPolicyUrl', 'policyGenerated')
        );
    }
}
