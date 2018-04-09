<?php

namespace Codelight\GDPR\Installer;

interface InstallerStepInterface
{
    public function run();

    public function validateNonce();

    public function validate();

    public function submit();

    public function getErrors();

    public function getUrl();

    public function getSlug();

    public function getType();
}