<?php

namespace Fulcrum\Extender\Str;

class StrConverters
{
    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of camel-cased words.
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * Ascii character replacements.
     *
     * @var array
     */
    protected static $asciiReplaces = [];

    protected static $underscoresCache = [];

    /**
     * Transliterate a UTF-8 value to ASCII.
     *
     * @since 1.0.0
     *
     * @param string $stringToConvert The string to convert
     *
     * @return string
     */
    public static function toAscii($stringToConvert)
    {
        foreach (self::getAsciiReplacements() as $key => $replacements) {
            $stringToConvert = str_replace($replacements, $key, $stringToConvert);
        }

        return preg_replace('/[^\x20-\x7E]/u', '', $stringToConvert);
    }

    /**
     * Convert a string into camelCase, for example:
     *      `foo_bar` to `fooBar`
     *
     * @since 1.0.0
     *
     * @param string $stringToConvert The string to convert
     *
     * @return string
     */
    public static function toCamelCase($stringToConvert)
    {
        $key = $stringToConvert;

        if (isset(self::$camelCache[$key])) {
            return self::$snakeCache[$key];
        }

        $stringToConvert = self::toStudlyCase($stringToConvert);

        self::$camelCache[$key] = lcfirst($stringToConvert);

        return self::$camelCache[$key];
    }

    /**
     * Convert a string to snake_case, for example:
     *      `fooBar` to `foo_bar`
     *      `FooBar` to `foo_bar`
     *      `foo_Bar` to `foo__bar`
     *
     * @since 1.0.0
     *
     * @param string $stringToConvert The string to convert
     * @param string $delimiter (Optional) Word delimiter.
     *                          Default: '_'
     *
     * @return string
     */
    public static function toSnakeCase($stringToConvert, $delimiter = '_')
    {
        $key = $stringToConvert;

        if (isset(self::$snakeCache[$key][$delimiter])) {
            return self::$snakeCache[$key][$delimiter];
        }

        $stringToConvert = trim($stringToConvert);

        if (self::isLowercase($stringToConvert)) {
            $stringToConvert = preg_replace('/\s+/u', $delimiter, $stringToConvert);
        } else {
            $stringToConvert = preg_replace('/\s+/u', '', $stringToConvert);
            // Pattern = find the capital letters and then back up one space.
            $stringToConvert = preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $stringToConvert);
            $stringToConvert = convert_to_lower_case($stringToConvert);
        }

        self::$snakeCache[$key][$delimiter] = $stringToConvert;

        return self::$snakeCache[$key][$delimiter];
    }

    /**
     * Convert a string to underscore, for example:
     *      `fooBar` to `foo_bar`
     *      `FooBar` to `foo_bar`
     *      `foo_Bar` to `foo_bar`
     *
     * Unlike snake_case, underscores removes the dash
     * and underscores first to avoid doubles.
     *
     * @since 1.0.0
     *
     * @param string $stringToConvert The string to convert
     *
     * @return string
     */
    public static function toUnderscore($stringToConvert)
    {
        $key = $stringToConvert;

        if (isset(self::$underscoresCache[$key])) {
            return self::$underscoresCache[$key];
        }

        $stringToConvert = trim($stringToConvert);

        // need to skip the beginning and end of the
        list($beginningUnderscores, $endingUnderscores) = self::getBeginningAndEndingUnderscores($stringToConvert);

        $stringToConvert = str_replace(['-', '_'], ' ', $stringToConvert);
        $stringToConvert = preg_replace('/(.)(?=[A-Z])/u', '$1' . ' ', $stringToConvert);

        $strings         = explode(' ', $stringToConvert);
        $strings         = array_filter_with_keys($strings);
        $stringToConvert = implode('_', $strings);

        self::$underscoresCache[$key] = $beginningUnderscores .
                                        convert_to_lower_case($stringToConvert) . $endingUnderscores;

        return self::$underscoresCache[$key];
    }

    protected static function getBeginningAndEndingUnderscores($stringToCheck)
    {
        $beginning = '';
        foreach ([0, 1] as $index) {
            if ($stringToCheck[$index] == '_') {
                $beginning .= '_';
            }
        }

        $length    = strlen($stringToCheck);
        $threshold = 2;
        if ($length <= $threshold) {
            return [$beginning, ''];
        }

        $ending = '';
        foreach ([$length - 2, $length - 1] as $index) {
            if ($stringToCheck[$index] == '_') {
                $ending .= '_';
            }
        }

        return [$beginning, $ending];
    }

    /**
     * Convert a value to StudlyCase, for example:
     *      `foo_bar` to `FooBar`
     *      `fooBar` to `FooBar`
     *
     * @since 1.0.0
     *
     * @param string $stringToConvert The string to convert
     * @param string|null $characterEncoding Set to null to use unicode.
     *                                      Default is 'UTF-8'.
     *
     * @return string
     */
    public static function toStudlyCase($stringToConvert, $characterEncoding = 'UTF-8')
    {
        $key = $stringToConvert;

        if (isset(self::$studlyCache[$key])) {
            return self::$studlyCache[$key];
        }

        $stringToConvert = str_replace(['-', '_'], ' ', $stringToConvert);

        if (!empty($characterEncoding)) {
            $stringToConvert = mb_convert_case($stringToConvert, MB_CASE_TITLE, $characterEncoding);
        } else {
            $stringToConvert = ucwords($stringToConvert);
        }

        self::$studlyCache[$key] = str_replace(' ', '', $stringToConvert);

        return self::$studlyCache[$key];
    }

    /**
     * Returns the ascii character replacements.
     *
     * @since 1.0.0
     *
     * @return array
     */
    protected static function getAsciiReplacements()
    {
        if (!empty(self::$asciiReplaces)) {
            return self::$asciiReplaces;
        }

        self::$asciiReplaces = (array) require __DIR__ . '/config/ascii-replacers.php';

        if (function_exists('apply_filters')) {
            self::$asciiReplaces = apply_filters(
                'ascii_character_replacements_filter',
                self::$asciiReplaces
            );
        }

        return self::$asciiReplaces;
    }

    protected static function isLowercase($stringToCheck)
    {
        return convert_to_lower_case($stringToCheck) === $stringToCheck;
    }
}
