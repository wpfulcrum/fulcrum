<?php

use Fulcrum\Extender\Arr\DotArray;

if (!function_exists('get_class_basename')) {
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param string|object $class
     *
     * @return string
     */
    function get_class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('get_object_item')) {
    /**
     * Get an item from an object using "dot" notation.
     *
     * @since 3.0.0
     *
     * @param object $object The haystack.
     * @param string $dotNotationKey "Dot" notation key(s) to fetch.
     * @param mixed $default Default value to return if the key/property does not exist.
     *
     * @return mixed
     */
    function get_object_item($object, $dotNotationKey, $default = null)
    {
        if (is_null($dotNotationKey) || '' === trim($dotNotationKey)) {
            return $object;
        }

        foreach (explode('.', $dotNotationKey) as $segment) {
            if (!is_object($object) || !isset($object->{$segment})) {
                return fulcrum_value($default);
            }
            $object = $object->{$segment};
        }

        return $object;
    }
}

if (!function_exists('get_data_item')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed $target The haystack.
     * @param string $dotNotationKey "Dot" notation key(s) to fetch.
     * @param  mixed $default Default value to return if the key/property does not exist.
     *
     * @return mixed
     */
    function get_data_item($target, $dotNotationKey, $default = null)
    {
        if (is_null($dotNotationKey)) {
            return $target;
        }

        $key = is_array($dotNotationKey) ? $dotNotationKey : explode('.', $dotNotationKey);

        while (!is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (!is_array($target)) {
                    return get_default_value($default);
                }

                $result = Arr::pluck($target, $key);
                return in_array('*', $key) ? DotArray::collapse($result) : $result;
            }

            if (DotArray::isArrayAccessible($target) && DotArray::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return get_default_value($default);
            }
        }

        return $target;
    }
}

if (!function_exists('get_calling_class_info')) {
    /**
     * Get calling class' info.
     *
     * @since 3.0.0
     *
     * @param mixed $object Instance of the object to work on.
     *
     * @return ReflectionClass|void
     */
    function get_calling_class_info($object)
    {
        if (is_object($object)) {
            return new ReflectionClass(get_class($object));
        }
    }
}

if (!function_exists('get_calling_class_directory')) {
    /**
     * Get calling class' directory.
     *
     * Handy when working within a root class.
     *
     * @since 3.0.0
     *
     * @param mixed $object Instance of the object to work on.
     *
     * @return string|void
     */
    function get_calling_class_directory($object)
    {
        $classInfo = get_calling_class_info($object);
        if ($classInfo) {
            return dirname($classInfo->getFileName());
        }
    }
}

if (!function_exists('get_default_value')) {
    /**
     * Return the default value of the given value.
     *
     * If the value is a closure, then invoke it.
     *
     * @param mixed $value Value to evaluate.
     *
     * @return mixed
     */
    function get_default_value($value)
    {
        if ($value instanceof Closure) {
            return $value();
        }

        return $value;
    }
}

if (!function_exists('with')) {
    /**
     * Return the given value.
     *
     * Optionally passed through the given callback. Useful for chaining.
     *
     * @since 3.0.0
     *
     * @param  mixed $value
     * @param  callable|null $callback
     *
     * @return mixed
     */
    function with($value, callable $callback = null)
    {
        if (is_null($callback)) {
            return $value;
        }

        return $callback($value);
    }
}

if (!function_exists('fulcrum_is_dev_environment')) {
    /**
     * Checks if the current environment is set to local dev or not.
     *
     * @since 3.0.0
     *
     * @returns bool
     */
    function fulcrum_is_dev_environment()
    {
        if (!defined('FULCRUM_ENV')) {
            return false;
        }

        return FULCRUM_ENV === 'dev';
    }
}
