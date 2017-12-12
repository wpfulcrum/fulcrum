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
}
