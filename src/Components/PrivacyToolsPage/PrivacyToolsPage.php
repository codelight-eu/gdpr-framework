<?php

namespace Codelight\GDPR\Components\PrivacyToolsPage;

class PrivacyToolsPage
{
    public function __construct()
    {
        gdpr()->singleton(PrivacyToolsPageController::class);
        gdpr()->make(PrivacyToolsPageShortcode::class);

        gdpr()->singleton(PrivacyToolsPageShortcode::class);
        gdpr()->make(PrivacyToolsPageShortcode::class);
    }
}