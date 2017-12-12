<?php

namespace Fulcrum\Config;

use ArrayObject;
use Fulcrum\Config\Exception\InvalidSourceException;
use Fulcrum\Extender\Arr\DotArray;

class Config extends ArrayObject implements ConfigContract
{
    /**
     * Runtime Configuration Parameters
     *
     * @var array
     */
    protected $items = [];

    /***************************
     * Instantiate & Initialize
     **************************/

    /**
     * Create a new configuration repository.
     *
     * @since 3.0.0
     *
     * @param  string|array $config File path and filename to the config array; or it is the
     *                                  configuration array.
     * @param  string|array $defaults Specify a defaults array, which is then merged together
     *                                  with the initial config array before creating the object.
     *
     * @throws InvalidSourceException
     */
    public function __construct($config, $defaults = '')
    {
        $this->checkSources($config, $defaults);

        $this->items = $this->fetchParameters($config);
        $this->initDefaults($defaults);

        parent::__construct($this->items, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Initialize Default Configuration parameters & merge into the
     * $config parameters
     *
     * @since 3.0.0
     *
     * @param string|array $defaults
     *
     * @return null
     */
    protected function initDefaults($defaults)
    {
        if (!$defaults) {
            return;
        }

        $defaults = $this->fetchParameters($defaults);
        $this->initDefaultsInConfigArray($defaults);
    }

    /**
     * Fetch the runtime parameters or defaults.
     *
     * @since 3.0.0
     *
     * @param string|array $locationOrArray Parameters location or array.
     *
     * @return array
     * @throw InvalidConfigException
     */
    protected function fetchParameters($locationOrArray)
    {
        // Yup, it's an array. Return it.
        if (is_array($locationOrArray)) {
            return $locationOrArray;
        }

        // It's a file.
        $maybeConfig = ConfigFactory::loadConfigFile($locationOrArray);

        Validator::mustBeAnArray($maybeConfig);
        Validator::mustNotBeEmpty($maybeConfig);

        return $maybeConfig;
    }

    /**
     * Initializing the Config with its Defaults
     *
     * @since 3.0.0
     *
     * @param array $defaults
     *
     * @return null
     */
    protected function initDefaultsInConfigArray(array $defaults)
    {
        $this->items = array_replace_recursive($defaults, $this->items);
    }

    /***************************
     * Public Methods
     **************************/

    /**
     * Retrieves all of the runtime configuration parameters
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Get the specified configuration value.
     *
     * @since 3.0.0
     *
     * @param  string|array $parameterKey Parameter key, specified in dot notation, i.e. key.key.key
     * @param  mixed $default
     *
     * @return mixed
     */
    public function get($parameterKey, $default = null)
    {
        if (is_array($parameterKey)) {
            return $this->getParameters($parameterKey);
        }

        return DotArray::get($this->items, $parameterKey, $default);
    }

    /**
     * Checks if the parameters exists.  Uses dot notation for multidimensional keys.
     *
     * @since 3.0.0
     *
     * @param  string $parameterKey Parameter key, specified in dot notation, i.e. key.key.key
     *
     * @return bool
     */
    public function has($parameterKey)
    {
        return DotArray::has($this->items, $parameterKey);
    }

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
    public function isArray($dotNotationKeys, $validWhenEmpty = null)
    {
        $value = DotArray::get($this->items, $dotNotationKeys);

        // If it's not valid when empty, check it here.
        if (false === $validWhenEmpty && empty($value)) {
            return false;
        }

        return DotArray::isArrayAccessible($value);
    }

    /**
     * Merge a new array into this config
     *
     * @since 3.0.0
     *
     * @param array $arrayToMerge The array to merge into the collection.
     *
     * @return void
     */
    public function merge(array $arrayToMerge)
    {
        $this->items = array_replace_recursive($this->items, $arrayToMerge);

        array_walk($this->items, function ($value, $parameterKey) {
            $this->offsetSet($parameterKey, $value);
        });
    }

    /**
     * Push a value onto an array configuration value.
     *
     * @since 3.0.0
     *
     * @param string $parameterKey Key to be assigned, which also becomes the property
     * @param mixed $value Value to be assigned to the parameter key
     *
     * @return void
     */
    public function push($parameterKey, $value)
    {
        $array   = $this->get($parameterKey);
        $array[] = $value;

        $this->set($parameterKey, $array);
    }

    /**
     * Remove a parameter from the configuration.
     *
     * @since 3.0.3
     *
     * @param array|string $dotNotationKeys Key to be unset.
     *
     * @return null
     */
    public function remove($dotNotationKeys)
    {
        DotArray::remove($this->items, $dotNotationKeys);
    }

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
    public function set($dotNotationKeys, $value)
    {
        $keys = is_array($dotNotationKeys) ? $dotNotationKeys : [$dotNotationKeys => $value];

        foreach ($keys as $key => $value) {
            DotArray::set($this->items, $key, $value);
        }
    }

    /***************************
     * Helpers
     **************************/

    /**
     * Checks the sources to ensure loadable.
     *
     * @since 3.0.0
     *
     * @param mixed $config The configuration to check.
     * @param mixed $defaults (Optional) The defaults to check.
     *
     * @return void
     * @throws InvalidSourceException
     */
    protected function checkSources($config, $defaults = '')
    {
        Validator::mustBeStringOrArray($config);
        Validator::mustNotBeEmpty($config);
        if (is_string($config)) {
            Validator::mustBeLoadable($config);
        }

        if (empty($defaults)) {
            return;
        }

        Validator::mustBeStringOrArray($defaults);
        if (is_string($defaults)) {
            Validator::mustBeLoadable($defaults);
        }
    }

    /**
     * Get multiple configuration parameters.
     *
     * @since 3.0.0
     *
     * @param  array $keys Array of key => default pairs to get.
     * @param  array
     *
     * @return mixed
     */
    protected function getParameters(array $keys)
    {
        $parameters = [];

        foreach ($keys as $parameterKey => $default) {
            $parameters[$parameterKey] = $this->get($parameterKey, $default);
        }

        return $parameters;
    }

    /*************************
     * ArrayAccess methods.
     ************************/

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->remove($key);
    }
}
