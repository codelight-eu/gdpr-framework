<?php

namespace Codelight\GDPR\Components\Consent;

class ConsentAdmin
{
    public function __construct()
    {
        gdpr()->bind(AdminTabConsent::class);

        add_filter('gdpr/admin/tabs', [$this, 'registerAdminTab'], 20);
    }

    public function registerAdminTab($tabs)
    {
        $tabs['consent'] = gdpr(AdminTabConsent::class);

        return $tabs;
    }
}