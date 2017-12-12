<?php
/**
 * Array Helpers API
 *
 * @package     Fulcrum\Extender\Arr;
 * @since       3.1.6
 * @author      hellofromTonya
 * @link        https://github.com/wpfulcrum/extender
 * @license     MIT
 */

use Fulcrum\Extender\Arr\DotArray;
use Fulcrum\Extender\Arr\FilterArray;

if (!function_exists('array_add')) {
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
    function array_add(array $subjectArray, $newElementKey, $newElementValue)
    {
        return DotArray::add($subjectArray, $newElementKey, $newElementValue);
    }
}

if (!function_exists('array_exists')) {
    /**
     * Check if the given key or offset exists in the provided array
     * or array object.
     *
     * @param ArrayAccess|array $arrayOrArrayAccess Subject Array or object
     * @param string|int $keyOrOffset Key to find.
     *
     * @return bool
     */
    function array_exists($arrayOrArrayAccess, $keyOrOffset)
    {
        return DotArray::exists($arrayOrArrayAccess, $keyOrOffset);
    }
}

if (!function_exists('array_flatten')) {
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
    function array_flatten(array $subjectArray, $depth = INF)
    {
        return DotArray::flatten($subjectArray, $depth);
    }
}

if (!function_exists('array_flatten_into_dots')) {
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
    function array_flatten_into_dots(array $subjectArray, $keyPrefix = '')
    {
        return DotArray::flattenIntoDots($subjectArray, $keyPrefix);
    }
}

if (!function_exists('array_flatten_into_delimited_list')) {
    /**
     * Flatten an array into a delimited list.
     *
     * @since 3.1.6
     *
     * @param  array $subjectArray The subject array to work on.
     * @param string $delimiter Delimiter to be used. Default is ',' for a comma-separated list.
     *
     * @return string
     */
    function array_flatten_into_delimited_list(array $subjectArray, $delimiter = ',')
    {
        return implode($delimiter, DotArray::flatten($subjectArray));
    }
}

if (!function_exists('array_filter_with_keys')) {
    /**
     * Filter the array passing passing each key=>value pair to the
     * given callback. The callback is responsible for the filtering
     * process to determine what elements will pass through.
     *
     * PHP 5.6.0 and up allows us to pass the keys to `array_filter` by
     * specifying the third parameter as ARRAY_FILTER_USE_BOTH. Before
     * that version, you can use this function.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array to work on
     * @param callable $filteringCallback The callback that will filter
     *                                     the elements
     *
     * @return array
     */
    function array_filter_with_keys(array $subjectArray, callable $filteringCallback = null)
    {
        return FilterArray::filter($subjectArray, $filteringCallback);
    }
}

if (!function_exists('array_get')) {
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
    function array_get($subjectArrayOrObject, $dotNotationKeys, $defaultValue = null)
    {
        return DotArray::get($subjectArrayOrObject, $dotNotationKeys, $defaultValue);
    }
}

if (!function_exists('array_get_except')) {
    /**
     * Get all of the elements within array except for the specified ones
     * via the incoming $keys.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array to work on
     * @param array|string $keys Keys to not include in the new array.
     *                           Use dot notation to specific the depth level.
     *                           Use an array of keys when wanting to exclude more elements.
     *
     * @return array
     */
    function array_get_except(array $subjectArray, $keys)
    {
        return DotArray::getExcept($subjectArray, $keys);
    }
}

if (!function_exists('array_get_first_element')) {
    /**
     * Get the first element of the array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param mixed $defaultValue The default value if the array is empty.
     *
     * @return mixed
     */
    function array_get_first_element(array $subjectArray, $defaultValue = null)
    {
        if (empty($subjectArray)) {
            return $defaultValue;
        }

        return array_shift($subjectArray);
    }
}

if (!function_exists('array_get_last_element')) {
    /**
     * Get the last element of the array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param mixed $defaultValue The default value if the array is empty.
     *
     * @return mixed
     */
    function array_get_last_element(array $subjectArray, $defaultValue = null)
    {
        if (empty($subjectArray)) {
            return $defaultValue;
        }

        return end($subjectArray);
    }
}

if (!function_exists('array_get_first_match')) {
    /**
     * Return the first element in an array that passes a given truth test.
     * The callback will perform the truth test to determine if each element's value
     * passes or not.  The first one to pass is returned.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param callable $truthTestCallback The truth test callback
     * @param mixed $defaultValue Default value if there are no matches.
     *
     * @return mixed Returns the first match when successful; else default value.
     */
    function array_get_first_match(array $subjectArray, callable $truthTestCallback, $defaultValue = null)
    {
        foreach ($subjectArray as $key => $value) {
            if (call_user_func($truthTestCallback, $key, $value)) {
                return $value;
            }
        }

        return $defaultValue;
    }
}

if (!function_exists('array_get_last_match')) {
    /**
     * Return the last element in an array that passes a given truth test.
     * The callback will perform the truth test to determine if each element's value
     * passes or not.  The last one to pass is returned.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param callable $truthTestCallback The truth test callback
     * @param mixed $defaultValue Default value if there are no matches.
     *
     * @return mixed Returns the last match when successful; else default value.
     */
    function array_get_last_match(array $subjectArray, callable $truthTestCallback, $defaultValue = null)
    {
        $reversedArray = array_reverse($subjectArray, true);

        return array_get_first_match(
            $reversedArray,
            $truthTestCallback,
            $defaultValue
        );
    }
}

if (!function_exists('array_get_only')) {
    /**
     * Get a subset of the elements from the given array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param array|string $keys
     *
     * @return array
     */
    function array_get_only(array $subjectArray, $keys)
    {
        return array_intersect_key(
            $subjectArray,
            array_flip((array) $keys)
        );
    }
}

if (!function_exists('array_has')) {
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
    function array_has($subjectArrayOrObject, $dotNotationKeys)
    {
        return DotArray::has($subjectArrayOrObject, $dotNotationKeys);
    }
}

if (!function_exists('array_pluck')) {
    /**
     * Pluck an array of values from an array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array to be worked on.
     * @param string|array $targetKeys Key(s) to pluck the values from the subject array.
     * @param string|null $reKeyed (optional) Specifies how you want the elements to be rekeyed.
     *
     * @return array
     */
    function array_pluck(array $subjectArray, $targetKeys, $reKeyed = null)
    {
        return DotArray::pluck($subjectArray, $targetKeys, $reKeyed);
    }
}

if (!function_exists('array_prepend')) {
    /**
     * Prepend an item onto the beginning of an array.
     *
     * For indexed arrays, the numerical keys will be re-indexed starting at zero.
     * Literal keys are not changed.
     *
     * Caution: When passing a key, make sure the key does not exist.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param mixed $newElementValue The value to prepend onto the array
     * @param string|int $newElementKey (Optional) The key for the new element.
     *
     * @return array
     */
    function array_prepend($subjectArray, $newElementValue, $newElementKey = '')
    {
        if ($newElementKey) {
            return [$newElementKey => $newElementValue] + $subjectArray;
        }

        array_unshift($subjectArray, $newElementValue);

        return $subjectArray;
    }
}

if (!function_exists('array_pull')) {
    /**
     * Get a value from the array, and remove it. For deeply nested array
     * elements, use dot notation for the $key, e.g.
     *
     *      level1Key.level2Key.level3Key
     *
     * @since 3.0.0
     *
     * @param array $subjectArray The subject array
     * @param string $key Value to pull using this key in the array.
     * @param mixed $default
     *
     * @return mixed
     */
    function array_pull(&$subjectArray, $key, $default = null)
    {
        return DotArray::pull($subjectArray, $key, $default);
    }
}

if (!function_exists('array_remove')) {
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
    function array_remove(array &$subjectArray, $keys)
    {
        DotArray::remove($subjectArray, $keys);
    }
}

if (!function_exists('array_set')) {
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
     * @param mixed $newValue The element's new value.
     *
     * @return array The current nested level is returned
     */
    function array_set(&$subjectArray, $key, $newValue)
    {
        return DotArray::set($subjectArray, $key, $newValue);
    }
}

if (!function_exists('array_esc_attr')) {
    /**
     * The string values will be filtered through the WordPress `esc_attr()`.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray Array to work on.
     * @param bool|null $goDeep When true, all strings values at each level of
     *                     array are sanitized.
     *
     * @return array
     */
    function array_esc_attr(array &$subjectArray, $goDeep = null)
    {
        return FilterArray::escapeAttributes($subjectArray, $goDeep);
    }
}

if (!function_exists('array_strip_tags')) {
    /**
     * Trim and then strip all of the tags from each
     * string value in the given array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray Array to work on.
     * @param bool|null $goDeep When true, all strings values at each level of
     *                     array are stripped.
     *
     * @return array
     */
    function array_strip_tags(array &$subjectArray, $goDeep = null)
    {
        return FilterArray::stripTags($subjectArray, $goDeep);
    }
}

if (!function_exists('array_trim')) {
    /**
     * Trim all of the string values in the given array.
     * Set $goDeep to `true` to process all elements through
     * each depth of the array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray Array to work on.
     * @param bool|null $goDeep When true, all strings values at each level of
     *                     array are trimmed.
     *
     * @return array
     */
    function array_trim(array &$subjectArray, $goDeep = null)
    {
        return FilterArray::trim($subjectArray, $goDeep);
    }
}

if (!function_exists('is_array_accessible')) {
    /**
     * Determine whether the given value is array accessible.
     *
     * @since 3.0.0
     *
     * @param mixed $value
     *
     * @return bool
     */
    function is_array_accessible($value)
    {
        return DotArray::isArrayAccessible($value);
    }
}
