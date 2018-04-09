<?php

namespace Codelight\GDPR\Installer;

/**
 * Handle the plumbing of an installer step
 *
 * Class InstallerStep
 *
 * @package Codelight\GDPR\Installer
 */
abstract class InstallerStep
{
    /* @var string */
    protected $stepType;

    /* @var string */
    protected $slug;

    /* @var string */
    protected $type;

    /* @var string */
    protected $template;

    /* @var int */
    protected $activeSteps;

    /**
     * Render a step for viewing
     */
    public function run()
    {
        $this->enqueue();
        $this->renderHeader();
        $this->renderContent();
        $this->renderNonce();
        $this->renderFooter();
    }

    /**
     * Validate the form submission
     *
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * Validate the nonce
     *
     * @return bool
     */
    public function validateNonce()
    {
        return isset($_POST['gdpr_nonce']) && wp_verify_nonce($_POST['gdpr_nonce'], $this->slug);
    }

    /**
     * Process the form submission
     */
    public function submit()
    {

    }


    /**
     * Display error notice or something
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Register WP's default assets and plugin installer assets
     */
    protected function enqueue()
    {
        wp_enqueue_style('common');
        wp_enqueue_style('buttons');

        /**
         * GDPR installer custom styles
         */
        wp_enqueue_style(
            'gdpr-installer',
            gdpr('config')->get('plugin.url') . 'assets/gdpr-installer.css'
        );

        wp_enqueue_style(
            'select2css',
            '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css'
        );

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script(
            'select2',
            '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js',
            ['jquery']
        );
        wp_enqueue_script(
            'conditional-show',
            gdpr('config')->get('plugin.url') . 'assets/conditional-show.js',
            ['jquery']
        );

        //global $wp_scripts;
        //$ui = $wp_scripts->query('jquery-ui-core');
        //wp_enqueue_style('jquery-ui-smoothness', "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css", false, null);

        wp_enqueue_script(
            'jquery-repeater',
            gdpr('config')->get('plugin.url') . 'assets/jquery.repeater.min.js',
            ['jquery']
        );

        /**
         * Installer javascript
         */
        wp_enqueue_script(
            'gdpr-installer',
            gdpr('config')->get('plugin.url') . 'assets/gdpr-installer.js',
            ['jquery', 'select2']
        );
    }

    /**
     * Render the installer page header - html head, form, logo
     */
    protected function renderHeader()
    {
        echo gdpr('view')->render('installer/header', ['activeSteps' => $this->activeSteps]);
    }

    /**
     * Render the installer page content - should be overridden by child class
     */
    protected function renderContent()
    {
        echo gdpr('view')->render($this->template);
    }

    /**
     * Create and render the nonce based on the name of the current step
     */
    protected function renderNonce()
    {
        $nonce = wp_create_nonce($this->slug);
        echo gdpr('view')->render('installer/nonce', compact('nonce'));
    }

    /**
     * Render the footer - nav buttons and closing tags
     */
    protected function renderFooter()
    {
        echo gdpr('view')->render('installer/footer');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return gdpr('config')->get('installer.wizardUrl') . $this->slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        if (is_null($this->slug)) {
            trigger_error("GDPR: Slug not defined for step!", E_USER_ERROR);
        }

        return $this->slug;
    }

    /**
     * @return string
     */
    public function getType()
    {
        if (is_null($this->type)) {
            trigger_error("GDPR: Type not defined for step {$this->slug}", E_USER_ERROR);
        }

        return $this->type;
    }
}
