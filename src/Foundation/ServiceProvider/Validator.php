<?php

namespace Fulcrum\Foundation\ServiceProvider;

use Fulcrum\Config\Validator as ConfigValidator;
use Fulcrum\Foundation\Exception\MissingRequiredParameterException;
use InvalidArgumentException;

class Validator
{
    /**
     * Checks if it's okay to register the concrete:
     *
     * 1. The ID is unique.
     * 2. The concrete's config is valid.
     * 3. The default structure is valid.
     *
     * @since 3.0.0
     *
     * @param string $uniqueId Container's unique key ID for this instance.
     * @param array $concreteConfig Concrete's runtime configuration parameters.
     * @param array $defaultStructure Default concrete configuration.
     * @param string $class Service provider's __CLASS__.
     *
     * @return bool
     */
    public static function okayToRegister($uniqueId, array $concreteConfig, array $defaultStructure, $class)
    {
        Validator::isUniqueIdValid($uniqueId, $class);

        return Validator::isConcreteConfigValid($uniqueId, $concreteConfig, $defaultStructure, $class);
    }

    /**
     * Checks if the unique id is valid.  Else it throws an error.
     *
     * @since 3.0.0
     *
     * @param string $uniqueId Container's unique key ID for this instance.
     * @param string $class Service provider's __CLASS__.
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    public static function isUniqueIdValid($uniqueId, $class)
    {
        if (!$uniqueId) {
            throw new InvalidArgumentException(sprintf(
                __('For the service provider [%s], the container unique ID cannot be empty.', 'fulcrum'),
                $class
            ));
        }

        if (!is_string($uniqueId)) {
            throw new InvalidArgumentException(sprintf(
                __('For the service provider [%s], the container unique ID must be a string. %s given.', 'fulcrum'),
                $class,
                gettype($uniqueId)
            ));
        }

        return true;
    }

    /**
     * Checks if the parameters are valid.
     *
     * @since 3.0.0
     *
     * @param string $uniqueId Container's unique key ID for this instance.
     * @param array $concreteConfig Concrete's runtime configuration parameters.
     * @param array $defaultStructure Default concrete configuration.
     * @param string $class Service provider's __CLASS__.
     *
     * @throws MissingRequiredParameterException
     * @uses ConfigValidator
     * @return bool
     */
    public static function isConcreteConfigValid($uniqueId, array $concreteConfig, array $defaultStructure, $class)
    {
        foreach (array_keys($defaultStructure) as $key) {
            if (!array_key_exists($key, $concreteConfig)) {
                throw new MissingRequiredParameterException(sprintf(
                    __(
                        "The required %s parameter is missing in the service provider's configuration for unique ID [%s]. [Class %s]", // @codingStandardsIgnoreLine - Generic.Files.LineLength.TooLong
                        'fulcrum'
                    ),
                    $uniqueId,
                    $key,
                    $class
                ));
            }
        }

        ConfigValidator::mustNotBeEmpty(
            $concreteConfig['config'],
            sprintf(
                __('The configuration source for unique ID [%s] cannot be empty. [Service Provider: %s]', 'fulcrum'),
                $uniqueId,
                $class
            )
        );

        if (is_string($concreteConfig['config'])) {
            return ConfigValidator::mustBeLoadable($concreteConfig['config']);
        }

        return ConfigValidator::mustBeAnArray($concreteConfig['config']);
    }
}
