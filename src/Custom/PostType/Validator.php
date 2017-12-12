<?php

namespace Fulcrum\Custom\PostType;

use Fulcrum\Config\ConfigContract;
use InvalidArgumentException;

class Validator
{
    /**
     * Checks if the starting state is valid
     *
     * @since 3.0.0
     *
     * @param string $postType Post type name (all lowercase & no spaces).
     * @param ConfigContract $config Runtime configuration parameters.
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    public static function isValid($postType, ConfigContract $config)
    {
        if (!$postType) {
            throw new InvalidArgumentException(
                __('For Custom Post Type Configuration, the Post type cannot be empty.', 'fulcrum')
            );
        }

        return self::isConfigurationValid($config);
    }

    /**
     * Checks if $config is valid.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     *
     * @return bool
     */
    protected static function isConfigurationValid(ConfigContract $config)
    {
        return !empty($config->all());
    }
}
