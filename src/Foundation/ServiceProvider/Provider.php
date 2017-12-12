<?php

namespace Fulcrum\Foundation\ServiceProvider;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\FulcrumContract;

abstract class Provider implements ProviderContract
{
    /**
     * Flag to indicate whether to skip the queue and register directly into the Container.
     *
     * @var bool
     */
    protected $skipQueue = false;

    /**
     * Default concrete configuration.
     *
     * @var array
     */
    protected $defaultStructure;

    /**
     * Instance of Fulcrum (which is the container)
     *
     * @var FulcrumContract
     */
    protected $fulcrum;

    /**
     * Concrete queue - awaiting registration or instantiation.
     *
     * @var array
     */
    protected $queued = [];

    /**
     * Array of Instances
     *
     * @var array
     */
    protected $instances = [];

    /**
     * Flag for whether to load the defaults or not.
     *
     * @var bool
     */
    protected $hasDefaults = true;

    /**
     * Specifies where the default file is located.
     *
     * @var string
     */
    protected $defaultsLocation = 'config/defaults.php';

    /**
     * Default parameters.
     *
     * @var array|string
     */
    protected $defaults = '';

    /**
     * Array of unique IDs in Container.
     *
     * @var array
     */
    protected $uniqueIds = [];

    /**
     * Provider constructor.
     *
     * @since 3.0.0
     *
     * @param FulcrumContract $fulcrum
     */
    public function __construct(FulcrumContract $fulcrum)
    {
        $this->fulcrum          = $fulcrum;
        $this->defaultStructure = $this->getConcreteDefaultStructure();
        $this->defaults         = $this->initDefaults();

        $this->initEvents();
    }

    /**
     * Initialize the defaults.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initDefaults()
    {
        if (!$this->hasDefaults) {
            return '';
        }

        $this->defaultsLocation = get_calling_class_directory($this) . DIRECTORY_SEPARATOR . $this->defaultsLocation;

        return ConfigFactory::loadConfigFile($this->defaultsLocation);
    }

    /**
     * Initialize events.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initEvents()
    {
        // do nothing.
    }

    /***************************
     * Public
     **************************/

    /**
     * Register a concrete into the container.
     *
     * @since 3.0.0
     *
     * @param array $concreteConfig Concrete's runtime configuration parameters.
     * @param string $uniqueId Container's unique key ID for this instance.
     *
     * @returns mixed
     */
    public function register(array $concreteConfig, $uniqueId)
    {
        $concreteConfig = $this->parseWithDefaultStructure($concreteConfig);

        if (!Validator::okayToRegister($uniqueId, $concreteConfig, $this->defaultStructure, __CLASS__)) {
            return false;
        }

        $concrete = $this->getConcrete($concreteConfig, $uniqueId);

        if (true === $this->skipQueue) {
            return $this->registerConcrete($concrete, $uniqueId);
        }

        return $this->queued[$uniqueId] = $concrete;
    }

    /**
     * Register all of the queued concretes into the Container.
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function registerQueue()
    {
        array_walk($this->queued, [$this, 'registerConcrete']);
    }

    /***************************
     * Helpers
     **************************/

    /**
     * Register the concrete into the Container.
     *
     * @since 3.0.0
     *
     * @param array $concrete Array for the concrete to be registered.
     * @param string $uniqueId Container's unique key ID for this instance.
     *
     * @return mixed
     */
    protected function registerConcrete(array $concrete, $uniqueId)
    {
        $this->uniqueIds[] = $uniqueId;

        return $this->fulcrum->registerConcrete($concrete, $uniqueId);
    }

    /**
     * Get the concrete based upon the configuration supplied.
     *
     * @since 3.0.0
     *
     * @param array $config Runtime configuration parameters.
     * @param string $uniqueId Container's unique key ID for this instance.
     *
     * @return array
     */
    abstract public function getConcrete(array $config, $uniqueId = '');

    /**
     * Get the default structure for the concrete.
     *
     * @since 3.0.0
     *
     * @return array
     */
    protected function getConcreteDefaultStructure()
    {
        return [
            'autoload' => false,
            'config'   => '',
        ];
    }

    /**
     * Merge the given array with the default array structure for the concrete.
     *
     * @since 3.0.0
     *
     * @param array $args Array to merge with the default structure.
     *
     * @return array
     */
    protected function parseWithDefaultStructure(array $args)
    {
        return array_merge($this->defaultStructure, $args);
    }

    /**
     * Checks if the queue has concretes that need to be processed.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function queueHasConcretes()
    {
        return is_array($this->queued) && !empty($this->queued);
    }

    /**
     * Instantiate the config.
     *
     * @since 3.0.0
     *
     * @param array $config Runtime parameters.
     *
     * @return \Fulcrum\Config\ConfigContract
     */
    protected function createConfig(array $config)
    {
        return ConfigFactory::create(
            $config['config'],
            $this->hasDefaults ? $this->defaults : ''
        );
    }

    /**
     * Usually not a good idea to toss this magic method in; however, it's handy for testing.
     *
     * @since 3.0.0
     *
     * @param string $property Name of the property to get.
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
