<?php

/**
 * Plugin Name:       The GDPR Framework
 * Plugin URI:        https://codelight.eu/wordpress-gdpr-framework/
 * Description:       Tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.
 * Version:           1.0.5
 * Author:            Codelight
 * Author URI:        https://codelight.eu/
 * Text Domain:       gdpr-framework
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

/**
 * Helper function for prettying up errors
 *
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$gdpr_error = function($message, $subtitle = '', $title = '') {
    $title = $title ?: _x('WordPress GDPR &rsaquo; Error', '(Admin)', 'gdpr-framework');
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare(phpversion(), '5.6.33', '<')) {
    $gdpr_error(
        _x('You must be using PHP 5.6.33 or greater.', '(Admin)', 'gdpr-framework'),
        _x('Invalid PHP version', '(Admin)', 'gdpr-framework')
    );
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare(get_bloginfo('version'), '4.3', '<')) {
    $gdpr_error(
        _x('You must be using WordPress 4.3.0 or greater.', '(Admin)', 'gdpr-framework'),
        _x('Invalid WordPress version', '(Admin)', 'gdpr-framework')
    );
}

/**
 * Load dependencies
 */
if (!class_exists('\Codelight\GDPR\Container')) {

    if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
        $gdpr_error(
            _x(
                'You appear to be running a development version of GDPR. You must run <code>composer install</code> from the plugin directory.',
                '(Admin)',
                'gdpr-framework'
            ),
            _x(
                'Autoloader not found.',
                '(Admin)',
                'gdpr-framework'
            )
        );
    }
    require_once $composer;
}

require_once('bootstrap.php');
