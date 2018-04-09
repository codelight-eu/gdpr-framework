<?php

/**
 * Plugin Name:       The GDPR Framework
 * Plugin URI:        https://codelight.eu/wordpress-gdpr-framework/
 * Description:       The GDPR Framework
 * Version:           1.0.0
 * Author:            Codelight
 * Author URI:        https://codelight.eu/
 * Text Domain:       gdpr
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
    $title = $title ?: __('WordPress GDPR &rsaquo; Error', 'gdpr-admin');
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare(phpversion(), '5.6.33', '<')) {
    $gdpr_error(__('You must be using PHP 5.6.33 or greater.', 'gdpr-admin'), __('Invalid PHP version', 'gdpr-admin'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare(get_bloginfo('version'), '4.3', '<')) {
    $gdpr_error(__('You must be using WordPress 4.3.0 or greater.', 'gdpr-admin'), __('Invalid WordPress version', 'gdpr-admin'));
}

/**
 * Load dependencies
 */
if (!class_exists('\Codelight\GDPR\Container')) {

    if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
        $gdpr_error(
            __('You appear to be running a development version of GDPR. You must run <code>composer install</code> from the plugin directory.', 'gdpr-admin'),
            __('Autoloader not found.', 'gdpr-admin')
        );
    }
    require_once $composer;
}

/**
 * Set up config object, store plugin URL and path there
 * along with various other items
 */
\Codelight\GDPR\Container::getInstance()
    ->bindIf('config', function () {
        return new \Codelight\GDPR\Config([
            'plugin' => [
                'url'           => plugin_dir_url(__FILE__),
                'path'          => plugin_dir_path(__FILE__),
                'template_path' => plugin_dir_path(__FILE__) . 'views/',
            ],
            'help' => [
                'url' => 'https://codelight.eu/wordpress-gdpr-framework/'
            ]
        ]);
    }, true);

/**
 * Set up the application container
 *
 * @param string                   $abstract
 * @param array                    $parameters
 * @param Codelight\GDPR\Container $container
 * @return Codelight\GDPR\Container|mixed
 */
function gdpr($abstract = null, $parameters = [], Codelight\GDPR\Container $container = null)
{
    $container = $container ?: Codelight\GDPR\Container::getInstance();

    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("gdpr.{$abstract}", $parameters);
}

/**
 * Start the plugin on plugins_loaded at priority 0.
 */
add_action('plugins_loaded', function() use ($gdpr_error){
    new \Codelight\GDPR\Setup();
}, 0);

/**
 * Install the database table and custom role
 */
register_activation_hook(__FILE__, function () {
    $model = new \Codelight\GDPR\Components\Consent\UserConsentModel();
    $model->createTable();

    if (apply_filters('gdpr/data-subject/anonymize/change_role', true) && !get_role('anonymous')) {

        add_role(
            'anonymous',
            __('Anonymous', 'gdpr-admin'),
            []
        );
    }
});
