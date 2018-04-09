<?php

namespace Codelight\GDPR\Admin;

class AdminHelper
{
    public function __construct()
    {
        $this->toolsHelper();
        $this->autoinstallHelper();
        $this->policyHelper();
    }

    protected function toolsHelper()
    {
        $toolsPage = gdpr('options')->get('tools_page');

        // Display the notice only on Tools page
        if (!$toolsPage || !isset($_GET['post']) || $_GET['post'] !== $toolsPage) {
            return;
        }

        $post = get_post($toolsPage);
        $helpUrl = gdpr('helpers')->docs();

        if (!stristr($post->post_content, '[gdpr_privacy_tools]')) {
            gdpr('admin-notice')->add('admin/notices/helper-tools', compact('helpUrl'));
        }
    }

    protected function autoinstallHelper()
    {
        if (!isset($_GET['gdpr-notice']) || empty($_GET['gdpr-notice']) || 'autoinstall' !== $_GET['gdpr-notice']) {
            return;
        }

        $helpUrl = gdpr('helpers')->docs();
        gdpr('admin-notice')->add('admin/notices/helper-autoinstall', compact('helpUrl'));
    }

    protected function policyHelper()
    {
        $policyPage = gdpr('options')->get('policy_page');

        // Display the notice only on Policy page
        if (!$policyPage || !isset($_GET['post']) || $_GET['post'] !== $policyPage) {
            return;
        }

        $post = get_post($policyPage);
        $helpUrl = gdpr('helpers')->docs();

        if (stristr($post->post_content, '[TODO]')) {
            gdpr('admin-notice')->add('admin/notices/helper-policy', compact('helpUrl'));
        }
    }
}
