<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\Stubs;

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class CallbackStub
{
    public static function isEvenStatic($key, $value)
    {
        return $value % 2 === 0;
    }

    public function isEven($key, $value)
    {
        return $value % 2 === 0;
    }

    public function isPositiveInteger($key, $value)
    {
        return is_int($value) && $value > 0;
    }

    public static function isArray($key, $value)
    {
        return is_array($value);
    }

    public function isAssociative($key, $value)
    {
        return is_string($key);
    }

    public static function hasAccessStatic($key, $value)
    {
        if (!isset($value['has_access'])) {
            return false;
        }
        return $value['has_access'];
    }

    public function hasAccess($key, $value)
    {
        if (!isset($value['has_access'])) {
            return false;
        }
        return $value['has_access'];
    }
}

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function isEvenCallback($key, $value)
{
    return $value % 2 === 0;
}

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function isDivisibleBy3Callback($key, $value)
{
    return $value % 3 === 0;
}

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function doesNotHaveAccess($key, $value)
{
    if (!isset($value['has_access'])) {
        return false;
    }

    return !$value['has_access'];
}
