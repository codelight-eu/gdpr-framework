<?php

namespace Codelight\GDPR\Installer\Steps;

use Codelight\GDPR\Installer\InstallerStep;
use Codelight\GDPR\Installer\InstallerStepInterface;

class ConfigurationPages extends InstallerStep implements InstallerStepInterface
{
    protected $slug = 'configuration-pages';

    protected $type = 'wizard';

    protected $template = 'installer/steps/configuration-pages';

    protected $activeSteps = 1;

    protected function renderContent()
    {
        $privacyToolsPage         = gdpr('options')->get('tools_page');
        $privacyToolsPageSelector = wp_dropdown_pages([
            'name'              => 'gdpr_tools_page',
            'show_option_none'  => _x('&mdash; Create a new page &mdash;', '(Admin)', 'gdpr-framework'),
            'option_none_value' => 'new',
            'selected'          => $privacyToolsPage ? $privacyToolsPage : 'new',
            'echo'              => false,
            'class'             => 'gdpr-select js-gdpr-select2',
        ]);

        echo gdpr('view')->render(
            $this->template,
            compact(
                'policyPage',
                'policyPageSelector',
                'privacyToolsPage',
                'privacyToolsPageSelector'
            )
        );
    }

    public function submit()
    {
        if (isset($_POST['gdpr_create_tools_page']) && 'yes' === $_POST['gdpr_create_tools_page']) {
            $id = $this->createPrivacyToolsPage();
            gdpr('options')->set('tools_page', $id);
        } else {
            gdpr('options')->set('tools_page', $_POST['gdpr_tools_page']);
        }
    }

    protected function createPrivacyToolsPage()
    {
        $id = wp_insert_post([
            'post_content' => '[gdpr_privacy_tools]',
            'post_title'   => __('Privacy Tools', 'gdpr-framework'),
            'post_type'    => 'page',
        ]);

        return $id;
    }
}
