<?php

namespace Codelight\GDPR\Components\Support;

use Codelight\GDPR\Admin\AdminTab;

class AdminTabSupport extends AdminTab
{
    protected $slug = 'support';

    public function __construct()
    {
        $this->title = __('Support', 'gdpr-admin');
    }

    public function init()
    {
        $this->registerSettingSection(
            'gdpr-section-support',
            __('Support', 'gdpr-admin'),
            [$this, 'renderTab']
        );
    }

    public function renderTab()
    {
        echo gdpr('view')->render('admin/support/contents');
    }

    public function renderSubmitButton()
    {
        // Intentionally left blank
    }
}
