<?php

namespace Fulcrum\Container;

use Fulcrum\Container\Exception\InvalidConcreteException;

class Validator
{
    public static function validateConcreteConfig(array $config)
    {
        if (!$config['concrete']) {
            throw new InvalidConcreteException(sprintf(
                '%s: %s',
                __('Invalid concrete configuration. The "concrete" cannot be empty.', 'fulcrum'),
                print_r($config, true)
            ));
        }

        if (!is_callable($config['concrete'])) {
            throw new InvalidConcreteException(sprintf(
                '%s: %s',
                __('The specified concrete is not callable', 'fulcrum'),
                print_r($config, true)
            ));
        }
    }

    /**
     * Validates $itemKeys.
     *
     * @since 3.0.5
     *
     * @param string $uniqueId
     * @param string|int|null $itemKeys Keys within the item, which can be "dot" notation.
     *
     * @return bool
     */
    public static function validItemKeys($uniqueId, $itemKeys)
    {
        if (empty($itemKeys)) {
            return false;
        }

        if (is_string($itemKeys) || is_numeric($itemKeys)) {
            return true;
        }

        throw new \InvalidArgumentException(sprintf(
            __(
                'The item key(s), given for "%s" unique ID, is(are) an invalid data type. String or integer are required. %s given: %s', // @codingStandardsIgnoreStart - Generic.Files.LineLength.TooLong
                'fulcrum'
            ),
            strip_tags($uniqueId),
            ucfirst(gettype($itemKeys)),
            print_r($itemKeys, true)
        ));
    }
}
