<?php

namespace Fulcrum\Custom\Taxonomy;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Config\Exception\InvalidConfigException;

class Validator
{
    /**
     * Validates the taxonomy's configuration.
     *
     * @since 3.0.0
     *
     * @param string $taxonomyName Taxonomy name (all lowercase & no spaces).
     * @param ConfigContract $config Runtime configuration parameters.
     *
     * @throws InvalidConfigException
     * @return bool
     */
    public static function run($taxonomyName, ConfigContract $config)
    {
        if (!$taxonomyName) {
            throw new InvalidConfigException(
                __('For Custom Taxonomy Configuration, the taxonomy name cannot be empty.', 'fulcrum')
            );
        }

        return self::isConfigurationValid($taxonomyName, $config);
    }

    /**
     * Checks if $config is valid.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     *
     * @return bool
     * @throws InvalidConfigException
     */
    protected static function isConfigurationValid($taxonomyName, ConfigContract $config)
    {
        if (!$config->has('objectType') || !$config->isArray('objectType')) {
            throw new InvalidConfigException(
                sprintf(
                    __(
                        'The "objectType" must be configured as an array of post types in the [%s] taxonomy configuration.', // @codingStandardsIgnoreLine - Generic.Files.LineLength.TooLong
                        'fulcrum'
                    ),
                    $taxonomyName
                )
            );
        }

        if (!$config->has('args') || !$config->isArray('args')) {
            throw new InvalidConfigException(
                sprintf(
                    __('The "args" must be configured for [%s] taxonomy.', 'fulcrum'),
                    $taxonomyName
                )
            );
        }

        return true;
    }
}
