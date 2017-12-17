<?php

namespace Fulcrum\Model;

use ArrayObject;
use Fulcrum\Extender\Arr\DotArray;

class Model extends ArrayObject implements ModelContract
{
    /**
     * Data items.
     *
     * @var array
     */
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Retrieves all of the data items.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Get the specified data item.
     *
     * @since 3.0.0
     *
     * @param  string|array $dotNotationKeys Key to get the data item, specified in "dot" notation, i.e. key.key.key
     * @param  mixed $default
     *
     * @return mixed
     */
    public function get($dotNotationKeys, $default = null)
    {
        if (is_array($dotNotationKeys)) {
            return $this->getParameters($dotNotationKeys);
        }

        return DotArray::get($this->data, $dotNotationKeys, $default);
    }

    /**
     * Checks if the data item exists.
     *
     * @since 3.0.0
     *
     * @param  string|array $dotNotationKeys Key to get the data item, specified in "dot" notation, i.e. key.key.key
     *
     * @return bool
     */
    public function has($dotNotationKeys)
    {
        return DotArray::has($this->data, $dotNotationKeys);
    }

    /**
     * Pushes a new value onto an array in the model.
     *
     * @since 3.0.0
     *
     * @param array|string $dotNotationKeys Key to be assigned, which also becomes the property
     * @param mixed $value Value to be assigned to the parameter key
     *
     * @return array
     */
    public function push($dotNotationKeys, $value)
    {
        $array   = $this->get($dotNotationKeys);
        $array[] = $value;

        $this->set($dotNotationKeys, $array);

        return $array;
    }

    /**
     * Remove a data item from the model.
     *
     * @since 3.0.0
     *
     * @param array|string $dotNotationKeys Key to be unset.
     *
     * @return null
     */
    public function remove($dotNotationKeys)
    {
        DotArray::remove($this->data, $dotNotationKeys);
    }

    /**
     * Set a new item in the model.
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
            DotArray::set($this->data, $key, $value);
        }
    }

    /*****************************
     * Internal functionality
     ****************************/

    /**
     * Get multiple data items.
     *
     * @since 3.0.0
     *
     * @param  array $keys Array of key => default pairs to get.
     * @param  array
     *
     * @return mixed
     */
    private function getParameters(array $keys)
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
