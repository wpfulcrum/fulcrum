<?php

namespace Fulcrum\Container;

use Fulcrum\Extender\Arr\DotArray;
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
     * @since 3.0.0
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
     * @since 3.0.3 "dot" notation
     *
     * @param string $uniqueId The unique identifier for the parameter or object
     * @param string|null $itemKeys Keys within the item, which can be "dot" notation.
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws \InvalidArgumentException if the identifier is not defined
     */
    public function get($uniqueId, $itemKeys = null)
    {
        // If there are itemKeys, then get deeply and return.
        if (!empty($itemKeys) && is_string($itemKeys)) {
            return DotArray::get($this->offsetGet($uniqueId), $itemKeys);
        }

        return $this->offsetGet($uniqueId);
    }

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
    public function has($uniqueId, $itemKeys = null)
    {
        $hasUniqueId = $this->offsetExists($uniqueId);

        // If there are itemKeys, check deeply.
        if (!empty($itemKeys) && is_string($itemKeys)) {
            return DotArray::has($this->get($uniqueId), $itemKeys);
        }

        return $hasUniqueId;
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

    /**
     * Stores data or callable in the container.
     *
     * @since 3.0.3
     *
     * @param string $uniqueId The unique identifier for this item in the Container.
     * @param mixed $stuffToBeStored Stuff to be stored.
     * @param string|null $itemKeys Keys within the item, which can be "dot" notation.
     *
     * @return bool
     */
    public function store($uniqueId, $stuffToBeStored, $itemKeys = null)
    {
        // If there are no itemKeys, then set the value.
        if (!empty($itemKeys) && is_string($itemKeys)) {
            return $this->storeByDotNotation($uniqueId, $itemKeys, $stuffToBeStored);
        }
        $this->offsetSet($uniqueId, $stuffToBeStored);
        return true;
    }

    /**
     * Store by dot notation.
     *
     * @since 3.0.3
     *
     * @param string $uniqueId The unique identifier for this item in the Container.
     * @param string|null $itemKeys Keys within the item, which can be "dot" notation.
     * @param mixed $stuffToBeStored Stuff to be stored.
     *
     * @return bool
     */
    private function storeByDotNotation($uniqueId, $itemKeys, $stuffToBeStored)
    {
        $items = $this->has($uniqueId) ? $this->get($uniqueId) : [];
        if (!is_array($items)) {
            return false;
        }

        DotArray::set($items, $itemKeys, $stuffToBeStored);
        $this->offsetSet($uniqueId, $items);
        return true;
    }
}
