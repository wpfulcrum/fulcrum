<?php

namespace Fulcrum\Extender\Arr;

class FilterArray
{

    protected static $filteringCallback;

    public static function filter(array $subjectArray, callable $filteringCallback = null)
    {
        $filteredArray           = [];
        self::$filteringCallback = $filteringCallback;

        foreach ($subjectArray as $key => $value) {
            if (self::addElementToFilteredArray($key, $value)) {
                $filteredArray[$key] = $value;
            }
        }

        self::$filteringCallback = null;
        return $filteredArray;
    }

    /**
     * Invoke the callback to check if the current element should be
     * added to the resultant filtered array.
     *
     * @since 3.0.0
     *
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    protected static function addElementToFilteredArray($key, $value)
    {
        if (is_null(self::$filteringCallback)) {
            return ($value);
        }

        if (is_string(self::$filteringCallback) && function_exists(self::$filteringCallback)) {
            $callback = self::$filteringCallback;

            return $callback($key, $value);
        }

        return call_user_func(self::$filteringCallback, $key, $value);
    }

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
    public static function escapeAttributes(array &$subjectArray, $goDeep = null)
    {
        return self::filterStrings($subjectArray, $goDeep, 'esc_attr');
    }

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
    public static function stripTags(array &$subjectArray, $goDeep = null)
    {
        return self::filterStrings($subjectArray, $goDeep, 'strip_tags');
    }

    /**
     * Trim all of the string values in the given array.
     * Set $goDeep to `true` to process all elements through
     * each depth of the array.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray Array to work on.
     * @param bool|null $goDeep Walk deeply through each level when true.
     *
     * @return array
     */
    public static function trim(array &$subjectArray, $goDeep = null)
    {
        return self::filterStrings($subjectArray, $goDeep, 'trim');
    }

    /*****************
     * Helpers
     ***************/

    /**
     * Get the array walk PHP function.
     *
     * @since 3.0.0
     *
     * @param bool $goDeep
     *
     * @return string
     */
    protected static function getArrayWalkFunction($goDeep)
    {
        return $goDeep === true
            ? 'array_walk_recursive'
            : 'array_walk';
    }

    /**
     * Filter the strings within the given array. Strings will be trimmed with
     * an optional filter function to process too.
     *
     * @since 3.0.0
     *
     * @param array $subjectArray Array to work on.
     * @param bool|null $goDeep Walk deeply through each level when true.
     * @param callable|null $filterFunction
     *
     * @return array
     */
    protected static function filterStrings(array &$subjectArray, $goDeep = null, $filterFunction = null)
    {
        $arrayWalkFunction = self::getArrayWalkFunction($goDeep);

        $arrayWalkFunction($subjectArray, function (&$item) use ($filterFunction) {
            if (!is_string($item)) {
                return;
            }

            if (is_callable($filterFunction)) {
                $item = $filterFunction($item);
            }

            $item = trim($item);
        });

        return $subjectArray;
    }
}
