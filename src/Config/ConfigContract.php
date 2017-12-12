<?php

namespace Fulcrum\Config;

interface ConfigContract
{
    /**
     * Retrieves all of the runtime configuration parameters.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function all();

    /**
     * Get the specified configuration value.
     *
     * @since 3.0.0
     *
     * @param  string $dotNotationKeys
     * @param  mixed $default
     *
     * @return mixed
     */
    public function get($dotNotationKeys, $default = null);

    /**
     * Determine if the given configuration value exists.
     *
     * @since 3.0.0
     *
     * @param  string $dotNotationKeys
     *
     * @return bool
     */
    public function has($dotNotationKeys);

    /**
     * Checks if the parameter key is a valid array, which means:
     *      1. Does it the key exists (which can be dot notation)
     *      2. If the value is an array
     *      3. Is the value empty, i.e. when $validIfNotEmpty is set
     *
     * @since 3.0.0
     *
     * @param string $dotNotationKeys
     * @param bool|null $validWhenEmpty
     *
     * @return bool
     */
    public function isArray($dotNotationKeys, $validWhenEmpty = null);

    /**
     * Merges a new array into this config.
     *
     * @since 3.0.0
     *
     * @param array $arrayToMerge The array to merge into the collection.
     *
     * @return void
     */
    public function merge(array $arrayToMerge);

    /**
     * Push a configuration in via the key.
     *
     * @since 3.0.0
     *
     * @param string $parameterKey Key to be assigned, which also becomes the property
     * @param mixed $value Value to be assigned to the parameter key
     *
     * @return null
     */
    public function push($parameterKey, $value);

    /**
     * Remove a parameter from the configuration.
     *
     * @since 3.0.3
     *
     * @param array|string $dotNotationKeys Key to be unset.
     *
     * @return null
     */
    public function remove($dotNotationKeys);

    /**
     * Set a new value for the given item in the configuration.
     *
     * @since 3.0.0
     *
     * @param array|string $dotNotationKeys Key to be assigned, which also becomes the property
     * @param mixed $value Value to be assigned to the parameter key
     *
     * @return null
     */
    public function set($dotNotationKeys, $value);
}
