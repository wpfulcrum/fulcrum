<?php

namespace Fulcrum\Custom\Shortcode;

use Fulcrum\Config\ConfigContract;
use InvalidArgumentException;
use RuntimeException;

class Validator
{
    /**
     * Checks if the starting state is valid
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config Runtime configuration parameters.
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    public static function isValid(ConfigContract $config)
    {
        if (!$config->has('shortcode') || empty($config->shortcode)) {
            throw new InvalidArgumentException(
                __('Invalid shortcode configuration. The "shortcode" parameter is required.', 'fulcrum')
            );
        }

        if (!$config->has('defaults')) {
            throw new InvalidArgumentException(sprintf(
                __('Invalid shortcode configuration for %s.  The "defaults" parameter is required.', 'fulcrum'),
                $config->shortcode
            ));
        }

        if (!is_array($config->defaults)) {
            throw new InvalidArgumentException(sprintf(
                __(
                    'Invalid shortcode configuration for %s.  The "defaults" parameter must be an array.',
                    'fulcrum'
                ),
                $config->shortcode
            ));
        }

        return self::viewIsValid($config);
    }

    /**
     * Validates the view file.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     *
     * @return bool
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected static function viewIsValid(ConfigContract $config)
    {
        if (self::noViewRequired($config)) {
            return true;
        }

        if (!$config->has('view')
        ) {
            throw new InvalidArgumentException(
                __('Invalid config for shortcode as a "view" parameter is required.', 'fulcrum')
            );
        }

        if (!is_readable($config->view)) {
            throw new RuntimeException(sprintf(
                __('The specified view file is not readable. [View: %s]', 'fulcrum'),
                print_r($config->view, true)
            ));
        }

        return true;
    }

    /**
     * Checks if no view is required for this shortcode.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    public static function noViewRequired(ConfigContract $config)
    {
        return $config->has('noView') && $config->noView === true;
    }
}
