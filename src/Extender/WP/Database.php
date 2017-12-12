<?php

namespace Fulcrum\Extender\WP;

class Database
{
    /**
     * Do a hard flush of the WordPress rewrite rules by first deleting
     * the `rewrite_rules` option from the database.  Then invoke the
     * `flush_rewrite_rules()` function to allow the normal processing.
     *
     * This method makes sure that the `rewrite_rules` are wiped before
     * processing the rewrite rules flush.
     *
     * @since 3.1.0
     *
     * @return void
     */
    public static function doHarderRewriteRulesFlush()
    {
        delete_option('rewrite_rules');

        flush_rewrite_rules();
    }

    /**
     * Gets the option value from the `wp_options` database.  This is a hard
     * get, as it queries the database directly to avoid any caching.
     *
     * @since 3.1.0
     *
     * @param string $optionName Name of the option to go get out of the `wp_options` db
     * @param int $defaultValue Default value to return if the option does not
     *                          exist.  The default value is 0.
     *
     * @return int|null|string
     */
    public static function doHardGetOption($optionName, $defaultValue = 0)
    {
        global $wpdb;

        $optionValue = $wpdb->get_var($wpdb->prepare(
            "
				SELECT option_value
				FROM {$wpdb->prefix}options
				WHERE option_name = %s
			",
            $optionName
        ));
        if (!is_null($optionValue)) {
            return $optionValue;
        }

        return $defaultValue;
    }
}
