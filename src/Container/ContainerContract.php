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
     *
     * @param string $uniqueId The unique identifier for the parameter or object
     * @return mixed The value of the parameter or an object
     *
     * @throws \InvalidArgumentException if the identifier is not defined
     */
    public function get($uniqueId);

    /**
     * Checks if a parameter or an object is set.
     *
     * @since 3.0.0
     *
     * @param  string $uniqueId The unique identifier for the parameter or object
     * @return bool
     */
    public function has($uniqueId);

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
}
