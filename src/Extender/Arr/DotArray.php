<?php
/**
 * Dot Notation Array Helpers - Static Collection of Helpers for Data Type Array
 *
 * Dot notation allows us the means to walk through an multi-dimensional array and
 * select what we want.  You access each level within the array using a dot ( . ) as
 * the level separator.
 *
 * For example, let's say that you have an array with 3 levels to it and you want
 * to access the 3rd level of an element.  You would pass the following to the workers:
 *
 *      $arrayToBeWorkedOn
 *      level1Key.level2Key.level3Key
 *
 * A dot separates each level.  It's a brilliant solution created by Taylor Otwell,
 * the mastermind of the awesome Laravel framework.
 *
 * @package     Fulcrum\Extender\Arr
 * @since       3.0.0
 * @author      hellofromTonya
 * @link        https://github.com/wpfulcrum/extender
 * @license     MIT
 */

/**
 * This class has been heavily adapted from the Laravel Illuminate framework,
 * which is copyrighted to Taylor Otwell and carries a MIT Licence (MIT).
 */

namespace Fulcrum\Extender\Arr;

use ArrayAccess;

/**
 * Class DotArray
 * @package Fulcrum\Extender\Arr
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class DotArray
{

    /**
     * Add an element to an array using "dot" notation, if it does NOT exist.
     *
     * Note: Unlike DotArray::set(), this method does NOT change the
     *       original array. Rather, a new array is returned.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param string $newElementKey The new element's key. Use "dot" notation for nested keys.
     * @param mixed $newElementValue The new element's value
     *
     * @return array If the key does not exist, the new array is returned;
     *                  else the original array is returned.
     */
    public static function add(array $subjectArray, $newElementKey, $newElementValue)
    {
        if (!is_null(self::get($subjectArray, $newElementKey))) {
            return $subjectArray;
        }

        self::set($subjectArray, $newElementKey, $newElementValue);

        return $subjectArray;
    }

    /**
     * Check if the given key or offset exists in the provided array
     * or array object.
     *
     * @param ArrayAccess|array $arrayOrArrayAccess Subject Array or object
     * @param string|int $keyOrOffset Key to find.
     *
     * @return bool
     */
    public static function exists($arrayOrArrayAccess, $keyOrOffset)
    {
        if ($arrayOrArrayAccess instanceof ArrayAccess) {
            return $arrayOrArrayAccess->offsetExists($keyOrOffset);
        }

        return array_key_exists($keyOrOffset, $arrayOrArrayAccess);
    }

    /**
     * Flatten a multi-dimensional array into a single level.  Keys are not preserved.
     *
     * @since 3.1.6
     *
     * @param  array $subjectArray The subject array to work on.
     * @param  int $depth (Optional) The depth to flatten. Default is INF (infinite), meaning all levels.
     *
     * @return array
     */
    public static function flatten(array $subjectArray, $depth = INF)
    {
        $flattenArray = [];

        foreach ($subjectArray as $value) {
            if (!is_array($value)) {
                $flattenArray[] = $value;
            } elseif ($depth === 1) {
                $flattenArray = array_merge($flattenArray, array_values($value));
            } else {
                $flattenArray = array_merge($flattenArray, static::flatten($value, $depth - 1));
            }
        }

        return $flattenArray;
    }

    /**
     * Flatten a multi-dimensional array into a single level with the keys compressed
     * into dot notation to indicate each depth level.
     *
     * If a prefix is passed into the method, it is appended to each key.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array to work on
     * @param string $keyPrefix (Optional) prefix to prepend to the keys
     *
     * @return array
     */
    public static function flattenIntoDots(array $subjectArray, $keyPrefix = '')
    {
        $flattenArray = [];

        foreach ($subjectArray as $key => $value) {
            $newKey = $keyPrefix . $key;

            if (is_array($value) && !empty($value)) {
                $flattenArray = array_merge($flattenArray, self::flattenIntoDots($value, $newKey . '.'));
                continue;
            }
            $flattenArray[$newKey] = $value;
        }

        return $flattenArray;
    }

    /**
     * Get an data from an array or property from an object
     * using "dot" notation.
     *
     * @param mixed $subjectArrayOrObject Array or Object to work on.
     * @param string|array $dotNotationKeys Dot notation Key(s) to fetch
     * @param mixed $defaultValue (optional) Default value
     *
     * @return mixed
     */
    public static function get($subjectArrayOrObject, $dotNotationKeys, $defaultValue = null)
    {
        if (is_null($dotNotationKeys)) {
            return $subjectArrayOrObject;
        }

        return self::walkArrayOrObject($subjectArrayOrObject, $dotNotationKeys, $defaultValue);
    }

    /**
     * Get all of the elements within array except for the specified ones
     * via the incoming $keys.
     *
     * @since 3.0.0
     *
     * @uses self::remove()
     *
     * @param array $subjectArray The subject array to work on
     * @param array|string $keys Keys to not include in the new array.
     *                           Use dot notation to specific the depth level.
     *                           Use an array of keys when wanting to exclude more elements.
     *
     * @return array
     */
    public static function getExcept(array $subjectArray, $keys)
    {
        self::remove($subjectArray, $keys);

        return $subjectArray;
    }

    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @since 3.0.0
     *
     * @param ArrayAccess|array $subjectArrayOrObject The subject array or ArrayAccess to work on
     * @param string $dotNotationKeys The key(s) to search for (use dot notation)
     *
     * @return bool
     */
    public static function has($subjectArrayOrObject, $dotNotationKeys)
    {
        if (empty($subjectArrayOrObject) || !$dotNotationKeys) {
            return false;
        }

        $defaultValue = 'DotArray::has->doesNotExist';

        $data = self::walkArrayOrObject($subjectArrayOrObject, $dotNotationKeys, $defaultValue);

        return $defaultValue !== $data;
    }

    /**
     * Pluck an array of values from an array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array to be worked on
     * @param string|array $targetKeys Key(s) to pluck the values from the subject array
     * @param string|null $reKeyed (optional) Specifies how you want the elements to be rekeyed
     *
     * @return array
     */
    public static function pluck(array $subjectArray, $targetKeys, $reKeyed = null)
    {
        if (empty($subjectArray)) {
            return [];
        }

        $results = [];

        list($targetKeys, $reKeyed) = self::explodeParameters($targetKeys, $reKeyed);

        foreach ($subjectArray as $element) {
            $elementValue = self::get($element, $targetKeys);

            if (is_null($elementValue)) {
                continue;
            }

            if (is_null($reKeyed)) {
                $results[] = $elementValue;
                continue;
            }
            $elementKey           = self::get($element, $reKeyed);
            $results[$elementKey] = $elementValue;
        }

        return $results;
    }

    /**
     * Get a value from the array, and remove it. For deeply nested array
     * elements, use dot notation for the $key, e.g.
     *
     *      level1Key.level2Key.level3Key
     *
     * @since 3.0.0
     *
     * @uses DotArray::get()
     * @uses DotArray::remove()
     *
     * @param array $subjectArray The subject array
     * @param string $key Value to pull using this key in the array.
     * @param  mixed $default
     *
     * @return mixed
     */
    public static function pull(&$subjectArray, $key, $default = null)
    {
        $value = static::get($subjectArray, $key, $default);

        static::remove($subjectArray, $key);

        return $value;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array to work on
     * @param array|string $keys
     *
     * @return void
     */
    public static function remove(array &$subjectArray, $keys)
    {
        $original =& $subjectArray;
        foreach ((array) $keys as $key) {
            self::removeSegments($subjectArray, $key);

            $subjectArray =& $original;
        }
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * If the key is deeply nested, then only the current nested level will
     * be returned when invoked.
     *
     * The subject array is changed upon success, as it is passed by reference.
     *
     * @since 3.0.0
     *
     * @param array|mixed $subjectArray (By reference) The subject array to work on
     * @param string $key The key to find within the array (can be dot notation)
     * @param mixed $newValue The element's new value
     *
     * @return array The current nested level is returned
     */
    public static function set(&$subjectArray, $key, $newValue)
    {
        if (is_null($key)) {
            return $subjectArray = $newValue;
        }

        $keys         = (array) self::explodeDotNotationKeys($key);
        $numberOfKeys = count($keys);

        foreach ($keys as $key) {
            if ($numberOfKeys > 1) {
                self::initEmptyArray($subjectArray, $key);
                $subjectArray =& $subjectArray[$key];
            }

            if ($numberOfKeys <= 1) {
                $subjectArray[$key] = $newValue;
            }

            $numberOfKeys--;
        }

        return $subjectArray;
    }

    /*****************
     * Helpers
     ***************/

    /**
     * Convert a string of keys into an array, by the '.' delimiter.
     *
     * @since 3.0.0
     *
     * @param string|array $keys Keys to convert.
     *
     * @return array
     */
    protected static function explodeDotNotationKeys($keys)
    {
        if (is_array($keys)) {
            return $keys;
        }

        return explode('.', $keys);
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param  string|array $value
     * @param  string|array|null $key
     *
     * @return array
     */
    protected static function explodeParameters($value, $key)
    {
        if (is_string($value)) {
            $value = self::explodeDotNotationKeys($value);
        }

        if (!is_null($key) && is_array($key)) {
            $key = self::explodeDotNotationKeys($key);
        }

        return [$value, $key];
    }

    /**
     * If the key does not exist at this depth, we will just create an empty array
     * to hold the next value, allowing us to create the arrays to hold final
     * values at the correct depth. Then we'll keep digging into the array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray Array to work check and work on.
     * @param string $key
     *
     * @return array
     */
    protected static function initEmptyArray(array &$subjectArray, $key)
    {
        if (!isset($subjectArray[$key]) || !is_array($subjectArray[$key])) {
            $subjectArray[$key] = [];
        }

        return $subjectArray;
    }

    /**
     * Checks if the specified element, indicated by the key, is a valid array.
     *
     * @since 3.0.0
     *
     * @param array $array
     * @param string $key
     * @param bool $validIfNotEmpty
     *
     * @return bool
     */
    protected static function isArrayElementValidArray(array $array, $key, $validIfNotEmpty = true)
    {
        if (!isset($array[$key])) {
            return false;
        }

        if (!is_array($array[$key])) {
            return false;
        }

        return (!$validIfNotEmpty || !empty($array[$key]));
    }

    /**
     * Forget segments within the array
     *
     * @since 3.0.0
     *
     * @param array $array
     * @param string $key
     */
    protected static function removeSegments(array &$array, $key)
    {
        $parts = self::explodeDotNotationKeys($key);

        while (count($parts) > 1) {
            $part = array_shift($parts);
            if (isset($array[$part]) && is_array($array[$part])) {
                $array =& $array[$part];
            }
        }
        unset($array[array_shift($parts)]);
    }

    /**
     * Walk the array or object using the key( which can be in dot notation). At the end of the walk,
     * if successful, the value is returned; else, the default value is returned.
     *
     * @param mixed $subjectArrayOrObject Array or Object to work on.
     * @param string|array $dotNotationKeys Dot notation Key(s) to fetch
     * @param mixed $defaultValue (optional) Default Value
     *
     * @return mixed
     */
    protected static function walkArrayOrObject($subjectArrayOrObject, $dotNotationKeys, $defaultValue = null)
    {
        if (is_null($dotNotationKeys)) {
            return $defaultValue;
        }

        if (!is_array($dotNotationKeys)) {
            $dotNotationKeys = self::explodeDotNotationKeys($dotNotationKeys);
        }

        $segment = $subjectArrayOrObject;

        foreach ($dotNotationKeys as $keyOrProperty) {
            if (self::isArrayAccessible($segment) && self::exists($segment, $keyOrProperty)) {
                $segment = $segment[$keyOrProperty];
                continue;
            }

            if (is_object($segment) && isset($segment->{$keyOrProperty})) {
                $segment = $segment->{$keyOrProperty};
                continue;
            }

            return $defaultValue;
        }

        return $segment;
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @since 3.0.0
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isArrayAccessible($value)
    {
        if (is_array($value)) {
            return true;
        }

        return $value instanceof ArrayAccess;
    }
}
