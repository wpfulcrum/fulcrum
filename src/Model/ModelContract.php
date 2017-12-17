<?php

namespace Fulcrum\Model;

interface ModelContract
{
    /**
     * Retrieves all of the data items.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getAll();

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
    public function get($dotNotationKeys, $default = null);

    /**
     * Checks if the data item exists.
     *
     * @since 3.0.0
     *
     * @param  string|array $dotNotationKeys Key to get the data item, specified in "dot" notation, i.e. key.key.key
     *
     * @return bool
     */
    public function has($dotNotationKeys);

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
    public function push($dotNotationKeys, $value);

    /**
     * Remove a data item from the model.
     *
     * @since 3.0.0
     *
     * @param array|string $dotNotationKeys Key to be unset.
     *
     * @return null
     */
    public function remove($dotNotationKeys);

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
    public function set($dotNotationKeys, $value);
}
