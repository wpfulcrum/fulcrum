<?php

namespace Fulcrum\Container;

use Pimple\Container as Pimple;

class DIContainer extends Pimple implements ContainerContract
{
    /**
     * Instance of Container
     *
     * @var ContainerContract
     */
    protected static $instance;

    /**************************
     * Instantiate & Initialize
     *************************/

    /**
     * Instantiate the container.
     *
     * @since 3.0.0
     *
     * @param array $initialParameters (Optional) An array of initial parameters to store in the container.
     *
     * @return self
     */
    public function __construct(array $initialParameters = [])
    {
        self::$instance = $this;
        parent::__construct($initialParameters);
    }

    /****************************
     * Public Methods
     ***************************/

    /**
     * Get the Core Instance
     *
     * @since 1.1.0
     *
     * @return self
     */
    public static function getContainer()
    {
        return self::$instance;
    }

    /**
     * Gets a parameter or an object.
     *
     * @since 3.0.0
     *
     * @param string $uniqueId The unique identifier for the parameter or object
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws \InvalidArgumentException if the identifier is not defined
     */
    public function get($uniqueId)
    {
        return $this->offsetGet($uniqueId);
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @since 3.0.0
     *
     * @param  string $uniqueId The unique identifier for the parameter or object
     *
     * @return bool
     */
    public function has($uniqueId)
    {
        return $this->offsetExists($uniqueId);
    }

    /**
     * Register a concrete into the Container.
     *
     * @since 3.0.0
     *
     * @param array $config Registration configuration.
     * @param string $uniqueId Unique container key.
     *
     * @return mixed
     */
    public function registerConcrete(array $config, $uniqueId)
    {
        $config = array_merge([
            'autoload' => false,
            'concrete' => '',
        ], $config);

        Validator::validateConcreteConfig($config);

        $this[$uniqueId] = $config['concrete'];

        if (true === $config['autoload']) {
            return $this[$uniqueId];
        }
    }
}
