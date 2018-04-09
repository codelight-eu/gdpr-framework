<?php

namespace Codelight\GDPR\DataSubject;

class DataSubjectAdmin
{
    public function __construct()
    {
        add_filter('gdpr/admin/tabs', [$this, 'registerTab'], 30);
    }

    public function registerTab($tabs)
    {
        $tabs['data-subject'] = gdpr()->make(AdminTabDataSubject::class);

        return $tabs;
    }
}