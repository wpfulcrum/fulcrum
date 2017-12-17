<?php

namespace Fulcrum\Container;

interface ContainerContract
{
    /**
     * Get the Core Instance
     *
     * @since 3.0.0
     *
     * @return self
     */
    public static function getContainer();

    /**
     * Gets a parameter or an object.
     *
     * @since 3.0.0
     * @since 3.0.3 "dot" notation
     *
     * @param string $uniqueId The unique identifier for the parameter or object
     * @param string|null $itemKeys Keys within the item, which can be "dot" notation.
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws \InvalidArgumentException if the identifier is not defined
     */
    public function get($uniqueId, $itemKeys = null);

    /**
     * Checks if a parameter or an object is set.
     *
     * @since 3.0.0
     * @since 3.0.3 "dot" notation
     *
     * @param  string $uniqueId The unique identifier for the parameter or object
     * @param string|null $itemKeys Keys within the item, which can be "dot" notation.
     *
     * @return bool
     */
    public function has($uniqueId, $itemKeys = null);

    /**
     * Register Concrete closures into the Container
     *
     * @since 3.0.0
     *
     * @param array $config
     * @param string $uniqueId
     * @return mixed
     */
    public function registerConcrete(array $config, $uniqueId);

    /**
     * Stores data or callable in the container.
     *
     * @since 3.0.3
     *
     * @param string $uniqueId The unique identifier for this item in the Container.
     * @param mixed $stuffToBeStored Stuff to be stored.
     * @param string|null $itemKeys Keys within the item, which can be "dot" notation.
     */
    public function store($uniqueId, $stuffToBeStored, $itemKeys = null);
}
