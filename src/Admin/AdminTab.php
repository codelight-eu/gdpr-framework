<?php

namespace Codelight\GDPR\Admin;

/**
 * Base class for admin tabs. Extend this.
 *
 * Class AdminTab
 *
 * @package Codelight\GDPR\Admin
 */
abstract class AdminTab implements AdminTabInterface
{
    /* @var string */
    protected $slug;

    /* @var string */
    protected $title;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getOptionsGroupName()
    {
        return 'gdpr_' . $this->getSlug();
    }

    /**
     * Register a setting on the admin page
     *
     * @param        $optionName
     * @param string $args
     */
    public function registerSetting($optionName, $args = [])
    {
        register_setting($this->getOptionsGroupName(), $optionName, $args);
    }

    /**
     * Register a section on the admin page
     *
     * @param $name
     * @param $callback
     */
    public function registerSettingSection($id, $title, $callback = null)
    {
        add_settings_section(
            $id,
            $title,
            $callback,
            $this->getOptionsGroupName()
        );
    }

    /**
     * Register a setting field on the admin page
     *
     * @param $id
     * @param $title
     * @param $callback
     */
    public function registerSettingField($id, $title, $callback = null, $section = '', $args = [])
    {
        add_settings_field(
            $id,
            $title,
            $callback,
            $this->getOptionsGroupName(),
            $section,
            $args
        );
    }

    /**
     * Render the contents including settings fields, sections and submit button.
     * Trigger hooks for rendering content before and after the settings fields.
     *
     * @return string
     */
    public function renderContents()
    {
        ob_start();

        do_action("gdpr/tabs/{$this->getSlug()}/before", $this);
        settings_fields($this->getOptionsGroupName());
        do_settings_sections($this->getOptionsGroupName());
        do_action("gdpr/tabs/{$this->getSlug()}/after", $this);

        $this->renderSubmitButton();

        return ob_get_clean();
    }

    /**
     * Render WP's default submit button
     */
    public function renderSubmitButton()
    {
        submit_button(_x('Save', '(Admin)', 'gdpr-framework'));
    }

    /**
     * Enqueue scripts, run the child class init function, trigger action for adding custom stuff
     */
    public function setup()
    {
        // Automatically run the 'enqueue' method if it exists
        if (method_exists($this, 'enqueue')) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        }

        $this->init();

        // This hook can be used for registering custom settings
        do_action("gdpr/tabs/{$this->getSlug()}/init", $this);

        // Render the admin notices
        add_action('admin_notices', [$this, 'renderAdminNotices']);
    }

    /**
     * Render success notices via admin_notice action
     */
    public function renderAdminNotices()
    {
        if ('tools_page_privacy' !== get_current_screen()->base) {
            return;
        }

        if (!isset($_REQUEST['gdpr_notice'])) {
            return;
        }

        if ('policy_generated' === $_REQUEST['gdpr_notice']) {
            $message = _x('Policy generated!', '(Admin)', 'gdpr-framework');
            $class = 'notice notice-success';
        }

        echo gdpr('view')->render('admin/notice', compact('message', 'class'));
    }
}
