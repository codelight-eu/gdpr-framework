<?php

namespace Codelight\GDPR\Admin;

interface AdminTabInterface
{
    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * Use this function to register settings and fields
     *
     * @return void
     */
    public function init();

    /**
     * Wrapper around init() function.
     * You probably don't need to override this.
     *
     * @return void
     */
    public function setup();

    /**
     * Wrapper around render() function.
     * Automatically calls settings_fields(),  do_settings_sections() and submit_button().
     * Override this if you don't want the functions to be called.
     *
     * @return string
     */
    public function renderContents();
}