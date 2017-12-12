<?php

namespace Fulcrum\Config;

use Fulcrum\Config\Exception\InvalidConfigException;
use Fulcrum\Config\Exception\InvalidFileException;
use Fulcrum\Config\Exception\InvalidSourceException;

class Validator
{
    public static function mustBeStringOrArray($source, $isDefaults = null, $message = '')
    {
        if (is_string($source) || is_array($source)) {
            return true;
        }

        if (!$message) {
            $message = sprintf(
                __(
                    'Invalid %1$sconfiguration source. Source must be an array of %1$sconfiguration parameters or a string filesystem path to load the configuration parameters.', // @codingStandardsIgnoreLine - Generic.Files.LineLength.TooLong
                    'fulcrum'
                ),
                $isDefaults === true ? ' default ' : ''
            );
        }

        throw new InvalidSourceException(sprintf('%s: %s', $message, print_r($source, true)));
    }

    public static function mustBeAnArray($source, $message = '')
    {
        if (is_array($source)) {
            return true;
        }

        if (!$message) {
            $message = __('Invalid configuration. The configuration must an array.', 'fulcrum');
        }

        throw new InvalidConfigException(sprintf('%s: %s', $message, print_r($source, true)));
    }

    public static function mustNotBeEmpty($source, $message = '')
    {
        if (!empty($source)) {
            return true;
        }

        if (!$message) {
            $message = __('Empty configuration source error.  The configuration source cannot be empty.', 'fulcrum');
        }

        throw new InvalidSourceException(sprintf('%s: %s', $message, print_r($source, true)));
    }

    public static function mustBeLoadable($file, $message = '')
    {
        if (is_readable($file)) {
            return true;
        }

        if (!$message) {
            $message = __('The specified configuration file is not readable', 'fulcrum');
        }

        throw new InvalidFileException(sprintf('%s: %s', $message, $file));
    }
}
