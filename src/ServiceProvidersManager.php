<?php
/**
 * Service Provider Manager - Handles loading the service providers into the container.
 *
 * @package     Fulcrum\FulcrumServiceProviders
 * @since       3.0.0
 * @author      hellofromTonya
 * @link        https://UpTechLabs.io
 * @license     MIT
 */

namespace Fulcrum;

use InvalidArgumentException;

class ServiceProvidersManager
{
    /**
     * Instance of Fulcrum (which is the container)
     *
     * @var FulcrumContract
     */
    protected $fulcrum;

    /**
     * Factory constructor.
     *
     * @since 3.0.0
     *
     * @param FulcrumContract $fulcrum
     */
    public function __construct(FulcrumContract $fulcrum)
    {
        $this->fulcrum = $fulcrum;
    }

    /**
     * Register the providers.
     *
     * @since 3.0.0
     *
     * @param array $providers Array of providers.
     *
     * @return void
     */
    public function register(array $providers)
    {
        array_walk($providers, [$this, 'registerIntoContainer']);
    }

    /**
     * Register the provider into the container.
     *
     * @since 3.0.0
     *
     * @param string $classname Provider's classname
     * @param string $uniqueId Unique ID for the provider when in the container.
     *
     * @return void
     */
    protected function registerIntoContainer($classname, $uniqueId)
    {
        $concrete = $this->getConcreteConfig($classname);

        if (is_array($concrete)) {
            $this->fulcrum->registerConcrete($concrete, $uniqueId);
        }
    }

    /**
     * Build the provider's concrete configuration array.
     *
     * @since 3.0.0
     *
     * @param string $classname Provider's classname
     *
     * @return null|array
     */
    protected function getConcreteConfig($classname)
    {
        if (!$this->isValidClass($classname)) {
            return;
        }

        return [
            'autoload' => true,
            'concrete' => function ($container) use ($classname) {
                return new $classname($this->fulcrum);
            },
        ];
    }

    /**
     * Checks if the classname is valid.
     *
     * @since 3.0.0
     *
     * @param string $classname Provider's classname
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    protected function isValidClass($classname)
    {
        if (class_exists($classname)) {
            return true;
        }

        throw new InvalidArgumentException(sprintf(
            __('The classname of [%s] was not found and could not be registered as a service provider.', 'fulcrum'),
            $classname
        ));
    }
}
