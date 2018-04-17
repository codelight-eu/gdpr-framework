<?php

namespace Codelight\GDPR\Components\Support;

use Codelight\GDPR\Admin\AdminTab;

class AdminTabSupport extends AdminTab
{
    protected $slug = 'support';

    public function __construct()
    {
        $this->title = _x('Support', '(Admin)', 'gdpr-framework');
    }

    public function init()
    {
        $this->registerSettingSection(
            'gdpr-section-support',
            _x('Support', '(Admin)', 'gdpr-framework'),
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
