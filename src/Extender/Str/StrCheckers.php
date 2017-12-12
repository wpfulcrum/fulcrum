<?php

namespace Fulcrum\Extender\Str;

class StrCheckers
{
    /**
     * Checks if the given string contains the given substring.
     *
     * When passing an array of needles, the first needle match
     * returns `true`.  Therefore, only one word in the array
     * needs to match.
     *
     * @since 3.0.0
     *
     * @param string $haystack The given string to check
     * @param string|array $needles The substring(s) to check for
     * @param string $characterEncoding (optional) Character encoding
     *                                  Default is 'UTF-8'.
     *
     * @return bool
     */
    public static function hasSubstring($haystack, $needles, $characterEncoding = 'UTF-8')
    {
        foreach ((array)$needles as $needle) {
            if ($needle == '') {
                continue;
            }

            if (mb_strpos($haystack, $needle, 0, $characterEncoding) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the given string ends with the given substring.
     *
     * When passing an array of needles, the first needle match
     * returns `true`.  Therefore, only one word in the array
     * needs to match.
     *
     * @since 3.0.0
     *
     * @param string $haystack The given string to check
     * @param string|array $needles The substring(s) to check for
     *
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            $substring = mb_substr($haystack, -mb_strlen($needle));

            if (self::doesStringMatchNeedle($substring, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the given string begins with the given substring.
     *
     * When passing an array of needles, the first needle match
     * returns `true`.  Therefore, only one word in the array
     * needs to match.
     *
     * @since 3.0.0
     *
     * @param string $haystack The given string to check
     * @param string|array $needles The substring(s) to check for
     *
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle == '') {
                continue;
            }

            $substring = mb_substr($haystack, 0, mb_strlen($needle));

            if (self::doesStringMatchNeedle($substring, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the given string matches the given pattern.
     *
     * Asterisks can be used to indicate wildcards.
     *
     * @since 3.0.0
     *
     * @param string $pattern The pattern to check
     * @param string $givenString the string to compare to the pattern
     *
     * @return bool
     */
    public static function matchesPattern($pattern, $givenString)
    {
        if ($pattern == $givenString) {
            return true;
        }

        $pattern = preg_quote($pattern, '#');
        $pattern = str_replace('\*', '.*', $pattern) . '\z';

        return (bool)preg_match('#^' . $pattern . '#', $givenString);
    }

    protected static function doesStringMatchNeedle($string, $needle)
    {
        return $string === (string)$needle;
    }
}
