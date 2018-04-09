<?php

namespace Codelight\GDPR\Options;

/**
 * Main class for handling options on a non-multisite install.
 * Adapted from https://carlalexander.ca/designing-classes-wordpress-options-api/
 *
 * Class Options
 *
 * @package Codelight\GDPR\Options
 */
class Options extends OptionsBase
{
    /**
     * Gets the option for the given name. Returns the default value if the value does not exist.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null, $enableFilter = true)
    {
        $value = get_option($this->prefix($name), $default);

        if ($enableFilter) {
            $value = apply_filters("gdpr/options/get/{$name}", $value);
        }
        
        if (is_array($default) && !is_array($value)) {
            $value = (array) $value;
        }

        return $value;
    }

    /**
     * Sets an option. Overwrites the existing option if the name is already in use.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function set($name, $value, $enableFilter = true)
    {
        if ($enableFilter) {
            $value = apply_filters("gdpr/options/set/{$name}", $value, get_option($this->prefix($name)));
        }

        update_option(
            $this->prefix($name),
            $value,
            false
        );
    }

    /**
     * Removes the option with the given name.
     *
     * @param string $name
     */
    public function delete($name)
    {
        delete_option($this->prefix($name));
    }
}