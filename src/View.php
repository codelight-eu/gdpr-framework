<?php

namespace Codelight\GDPR;

/**
 * Handles locating templates from either the theme or plugin,
 * injecting and extracting data and rendering them.
 *
 * Class View
 * @package Codelight\GDPR
 */
class View
{
    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->dirs = $this->getTemplateDirectories();
    }

    /**
     * Render a given template.
     *
     * @param       $template
     * @param array $data
     * @param null  $templateDir
     *
     * @return string
     */
    public function render($template, $data = [], $templateDir = null)
    {
        if (is_null($templateDir)) {
            foreach ($this->dirs as $dir) {
                if (file_exists($dir . $template . '.php')) {
                    $templateDir = $dir;
                    break;
                }
            }
        }

        extract($data);
        ob_start();
        require $templateDir . $template . '.php';

        return ob_get_clean();
    }

    /**
     * Get valid template directories and pass them through a filter
     *
     * @return array
     */
    protected function getTemplateDirectories()
    {
        $directories = array_filter([
            get_stylesheet_directory() . '/gdpr-framework/',
            get_template_directory() . '/gdpr-framework/',
            gdpr('config')->get('plugin.template_path'),
        ], 'is_dir');

        return array_unique(apply_filters('gdpr/views', $directories));
    }
}
