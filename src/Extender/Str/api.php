<?php
/**
 * String Support API - a collection of functions to help
 * you get your work done faster with less code (and frustrations).
 *
 * @package     Fulcrum\Extender\Str
 * @since       3.0.0
 * @author      hellofromTonya
 * @link        https://github.com/wpfulcrum/extender
 * @license     MIT
 */

use Fulcrum\Extender\Str\StrCheckers;
use Fulcrum\Extender\Str\StrConverters;

/**********************
 * Miscellaneous Helpers
 *********************/

if (!function_exists('get_substr')) {
    /**
     * Get part of a string using safe character encoding
     * to ensure the characters are properly searched.
     *
     * PHP substr() uses Unicode character, which can cause search
     * issues with certain characters in languages, such as A-umlaut (Ä).
     *
     * @since 3.0.0
     *
     * @param string $haystack String to search.
     * @param int $startPosition Starting position to begin the search
     * @param int|null $length
     * @param string $characterEncoding default is 'UTF-8'
     *
     * @return string
     */
    function get_substr($haystack, $startPosition, $length = null, $characterEncoding = 'UTF-8')
    {
        return mb_substr(
            $haystack,
            $startPosition,
            $length,
            $characterEncoding
        );
    }
}

if (!function_exists('get_substring_inbetween')) {
    /**
     * Extract a substring between a starting and ending substring.
     *
     * If the starting needle does not exist, an empty string is returned.
     *
     * If the ending substring does not exist, then the substring from the starting needle
     * is returned.
     *
     * @since 3.0.0
     *
     * @param string $haystack Given string to extract the substring from
     * @param string $startingNeedle The starting character or string to begin the extraction
     * @param string $endingNeedle The ending character or string to end the extraction
     *
     * @return string
     */
    function get_substring_inbetween($haystack, $startingNeedle, $endingNeedle)
    {
        $startingPosition = mb_strpos($haystack, $startingNeedle);
        if ($startingPosition === false) {
            return '';
        }

        $startingPosition += mb_strlen($startingNeedle);
        $endingPosition   = mb_strpos($haystack, $endingNeedle, $startingPosition);
        if ($endingPosition !== false) {
            $endingPosition -= mb_strlen($haystack);

            return mb_substr($haystack, $startingPosition, $endingPosition);
        }

        return mb_substr($haystack, $startingPosition);
    }
}

/**********************
 * String Checkers
 *********************/

if (!function_exists('has_substring')) {
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
     *
     * @return bool
     */
    function has_substring($haystack, $needles)
    {
        return StrCheckers::hasSubstring($haystack, $needles);
    }
}

if (!function_exists('str_ends_with')) {
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
    function str_ends_with($haystack, $needles)
    {
        return StrCheckers::endsWith($haystack, $needles);
    }
}

if (!function_exists('str_starts_with')) {
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
    function str_starts_with($haystack, $needles)
    {
        return StrCheckers::startsWith($haystack, $needles);
    }
}

if (!function_exists('str_matches_pattern')) {
    /**
     * Check if the given simply pattern matches the given string.
     * Use asterisks to indicate wildcards.
     *
     * For example, str_matches_wildcard( 'foo*', 'foobar' ) returns true.
     *
     * @since 3.0.0
     *
     * @param string $pattern The pattern to check
     * @param string $haystack the string to compare to the pattern
     *
     * @return bool
     */
    function str_matches_wildcard($pattern, $haystack)
    {
        return StrCheckers::matchesPattern($pattern, $haystack);
    }
}

/**********************
 * String Truncaters
 *********************/

if (!function_exists('truncate_by_characters')) {
    /**
     * Truncate the given string by the specified the number of characters.
     *
     * @since 3.0.0
     *
     * @param string $subjectString The string to truncate
     * @param int $characterLimit Number of characters to limit the string to
     * @param string $endingCharacters (Optional) Characters to append to the end of the truncated string.
     *
     * @return string
     */
    function truncate_by_characters($subjectString, $characterLimit = 100, $endingCharacters = '...')
    {
        if (mb_strwidth($subjectString, 'UTF-8') <= $characterLimit) {
            return $subjectString;
        }

        $limitedString = mb_strimwidth($subjectString, 0, $characterLimit, '', 'UTF-8');

        return rtrim($limitedString) . $endingCharacters;
    }
}

if (!function_exists('truncate_by_words')) {
    /**
     * Truncate the given string by the specified the number of words.
     *
     * @since 3.0.0
     *
     * @param string $subjectString The string to truncate
     * @param int $wordLimit Number of characters to limit the string to
     * @param string $endingCharacters (Optional) Characters to append to the end of the truncated string.
     *
     * @return string
     */
    function truncate_by_words($subjectString, $wordLimit = 100, $endingCharacters = '...')
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $wordLimit . '}/u', $subjectString, $matches);

        if (!isset($matches[0])) {
            return $subjectString;
        }

        if (mb_strlen($subjectString) === mb_strlen($matches[0])) {
            return $subjectString;
        }

        return rtrim($matches[0]) . $endingCharacters;
    }
}

/**********************
 * String Converters
 *********************/

if (!function_exists('convert_to_ascii')) {
    /**
     * Transliterate a UTF-8 value to ASCII.
     *
     * @since 3.0.0
     *
     * @param string $stringToConvert The string to convert
     *
     * @return string
     */
    function convert_to_ascii($stringToConvert)
    {
        return StrConverters::toAscii($stringToConvert);
    }
}

if (!function_exists('convert_to_camel_case')) {
    /**
     * Convert a string into camelCase, for example:
     *      `foo_bar` to `fooBar`
     *
     * @since 3.0.0
     *
     * @param string $stringToConvert The string to convert
     *
     * @return string
     */
    function convert_to_camel_case($stringToConvert)
    {
        return StrConverters::toCamelCase($stringToConvert);
    }
}

if (!function_exists('convert_to_lower_case')) {
    /**
     * Convert the given string to lower case.
     *
     * This function uses mb_strtolower() and safe character encoding
     * to ensure the characters in all languages convert properly.
     *
     * PHP strolower() uses Unicode character properties, which
     * can cause issues with certain characters in languages, such
     * as A-umlaut (Ä).
     *
     * @since 3.0.0
     *
     * @param string $stringToConvert The string to convert
     * @param string|null $characterEncoding default is 'UTF-8'
     *                    null - uses Unicode.
     *
     * @return string
     */
    function convert_to_lower_case($stringToConvert, $characterEncoding = 'UTF-8')
    {
        if (empty($characterEncoding)) {
            return strtolower($stringToConvert);
        }

        return mb_strtolower($stringToConvert, $characterEncoding);
    }
}

if (!function_exists('convert_to_snake_case')) {
    /**
     * Convert a string to snake_case, for example:
     *      `fooBar` to `foo_bar`
     *      `FooBar` to `foo_bar`
     *
     * @since 3.0.0
     *
     * @param string $stringToConvert The string to convert
     * @param string $delimiter (Optional) Word delimiter.
     *                          Default: '_'
     *
     * @return string
     */
    function convert_to_snake_case($stringToConvert, $delimiter = '_')
    {
        return StrConverters::toSnakeCase($stringToConvert, $delimiter);
    }
}

if (!function_exists('convert_to_studly_case')) {
    /**
     * Convert a value to StudlyCase, for example:
     *      `foo_bar` to `FooBar`
     *      `foo-Bar` to `FooBar`
     *
     * @since 3.0.0
     *
     * @param string $stringToConvert The string to convert
     * @param string|null $characterEncoding Set to null to use unicode.
     *                                      Default is 'UTF-8'.
     *
     * @return string
     */
    function convert_to_studly_case($stringToConvert, $characterEncoding = 'UTF-8')
    {
        return StrConverters::toStudlyCase($stringToConvert, $characterEncoding);
    }
}

if (!function_exists('convert_to_underscore')) {
    /**
     * Convert a string to snake_case, for example:
     *      `fooBar` to `foo_bar`
     *      `FooBar` to `foo_bar`
     *
     * @since 3.0.0
     *
     * @param string $stringToConvert The string to convert
     *
     * @return string
     */
    function convert_to_underscore($stringToConvert)
    {
        return StrConverters::toUnderscore($stringToConvert);
    }
}

if (!function_exists('convert_to_upper_case')) {
    /**
     * Convert the given string to upper case.
     *
     * This function uses mb_strtoupper() and safe character encoding
     * to ensure the characters in all languages convert properly.
     *
     * PHP stroupper() uses Unicode character properties, which
     * can cause issues with certain characters in languages, such
     * as A-umlaut (Ä).
     *
     * @since 3.0.0
     *
     * @param string $stringToConvert The string to convert
     * @param string|null $characterEncoding default is 'UTF-8'
     *                    null - uses Unicode.
     *
     * @return string
     */
    function convert_to_upper_case($stringToConvert, $characterEncoding = 'UTF-8')
    {
        if (empty($characterEncoding)) {
            return strtoupper($stringToConvert);
        }

        return mb_strtoupper($stringToConvert, $characterEncoding);
    }
}
