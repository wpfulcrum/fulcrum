<?php

use Fulcrum\Config\ConfigFactory;

if (!function_exists('fulcrum_create_config')) {
    /**
     * Load and return the Config object
     *
     * @since 3.0.0
     *
     * @param  string|array $config File path and filename to the config array; or it is the
     *                                  configuration array.
     * @param  string|array $defaults Specify a defaults array, which is then merged together
     *                                  with the initial config array before creating the object.
     *
     * @returns Fulcrum Returns the Config object
     */
    function fulcrum_create_config($config, $defaults = '')
    {
        return ConfigFactory::create($config, $defaults);
    }
}

if (!function_exists('fulcrum_load_config_file')) {
    /**
     * Attempt to load the specified configuration file into memory. If successful, return it.
     *
     * @since 3.0.0
     *
     * @param string $filePath
     * @param string $errorMessage
     *
     * @return array
     */
    function fulcrum_load_config_file($filePath, $errorMessage = '')
    {
        return ConfigFactory::loadConfigFile($filePath, $errorMessage);
    }
}
