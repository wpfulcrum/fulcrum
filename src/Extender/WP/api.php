<?php
/**
 * WordPress Extender API - a collection of functions to help
 * you get your work done faster with less code (and frustrations).
 *
 * @package     Fulcrum\Extender\WP
 * @since       3.1.0
 * @author      hellofromTonya
 * @link        https://github.com/wpfulcrum/extender
 * @license     MIT
 */

use Fulcrum\Extender\WP\Conditionals;
use Fulcrum\Extender\WP\Database;
use Fulcrum\Extender\WP\ParentChild;

/****************************
 * Conditional Functions
 ***************************/

if (!function_exists('is_posts_page')) {
    /**
     * Checks if the current web page request is for the Posts Page, i.e
     * the page that displays the posts.  This page is sometimes called
     * the "blog" page.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    function is_posts_page()
    {
        return Conditionals::isPostsPage();
    }
}

if (!function_exists('is_root_web_page')) {
    /**
     * Checks if the current web page request is for the Posts Page, i.e
     * the page that displays the posts.  This page is sometimes called
     * the "blog" page.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    function is_root_web_page()
    {
        return Conditionals::isRootPage();
    }
}

if (!function_exists('is_static_front_page')) {
    /**
     * Checks if the current web page request is for the Posts Page, i.e
     * the page that displays the posts.  This page is sometimes called
     * the "blog" page.
     *
     * @since 3.1.0
     *
     * @return bool
     */
    function is_static_front_page()
    {
        return Conditionals::isStaticFrontPage();
    }
}

/****************************
 * Database Functions
 ***************************/

if (!function_exists('do_harder_rewrite_rules_flush')) {
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
    function do_harder_rewrite_rules_flush()
    {
        Database::doHarderRewriteRulesFlush();
    }
}

if (!function_exists('do_hard_get_option')) {
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
    function do_hard_get_option($optionName, $defaultValue = 0)
    {
        return Database::doHardGetOption($optionName, $defaultValue);
    }
}

if (!function_exists('prepare_array_for_sql_where_in')) {
    /**
     * Prepare the raw values in the given array. Then join them in a string.
     *
     * The return string can then be inserted into a SQL WHERE IN ( {$prepared} ).
     *
     * @since 3.1.6
     *
     * @param array $rawData Raw array values to prepare.
     *
     * @return string
     */
    function prepare_array_for_sql_where_in(array $rawData)
    {
        return "'" . implode("', '", array_map('esc_sql', $rawData)) . "'";
    }
}

/****************************
 * Parent-Child Functions
 ***************************/

if (!function_exists('is_child_post')) {
    /**
     * Checks if the given post is a child.
     *
     * @since 3.1.3
     *
     * @param int|WP_Post|null $postOrPostId Post Instance or Post ID to check
     *
     * @return bool
     */
    function is_child_post($postOrPostId = null)
    {
        return ParentChild::isChildPost($postOrPostId);
    }
}

if (!function_exists('is_parent_post')) {
    /**
     * Checks if the given post is a parent.
     *
     * @since 3.1.3
     *
     * @param int|WP_Post|null $postOrPostId Post Instance or Post ID to check
     *
     * @return bool
     */
    function is_parent_post($postOrPostId = null)
    {
        return ParentChild::isParentPost($postOrPostId);
    }
}

if (!function_exists('post_has_children')) {
    /**
     * Checks if the given post has children
     *
     * @since 3.1.3
     *
     * @param int|WP_Post|null $postOrPostId Post Instance or Post ID to check
     *
     * @return bool
     */
    function post_has_children($postOrPostId = null)
    {
        return ParentChild::postHasChildren($postOrPostId);
    }
}

if (!function_exists('get_number_of_children_for_post')) {
    /**
     * Fetches the number of children for a given post or post ID.
     * If no post/post ID is passed in, then it uses the current post.
     *
     * @since 3.1.3
     *
     * @param int|WP_Post|null $postOrPostId Post Instance or Post ID to check
     *
     * @return int|false
     */
    function get_number_of_children_for_post($postOrPostId = null)
    {
        return ParentChild::getNumberOfPostChildren($postOrPostId);
    }
}

if (!function_exists('get_next_parent_post')) {
    /**
     * Get the next adjacent parent post.
     *
     * This function extends the SQL WHERE query of the WordPress get_adjacent_post()
     * function. It registers a callback to the `get_next_post_where` event filter,
     * which then adds a new WHERE parameter.
     *
     * @uses get_next_post()
     * @uses `get_next_post_where` filter
     * @uses fulcrum_add_parent_post_to_adjacent_sql()
     *
     * @since 3.1.3
     *
     * @param bool $inSameTerm Optional. Whether post should be in a same taxonomy term. Default false.
     * @param array|string $excludedTerms Optional. Array or comma-separated list of excluded term IDs. Default empty.
     * @param string $taxonomy Optional. Taxonomy, if $inSameTerm is true. Default 'category'.
     *
     * @return null|string|WP_Post Post object if successful. Null if global $post is not set. Empty string if no
     *                             corresponding post exists.
     */
    function get_next_parent_post($inSameTerm = false, $excludedTerms = '', $taxonomy = 'category')
    {
        return ParentChild::getNextParentPost($inSameTerm, $excludedTerms, $taxonomy);
    }
}

if (!function_exists('get_previous_parent_post')) {
    /**
     * Get the previous adjacent parent post.
     *
     * This function extends the SQL WHERE query of the WordPress get_adjacent_post()
     * function. It registers a callback to the `get_previous_post_where` event filter,
     * which then adds a new WHERE parameter.
     *
     * @uses get_previous_post()
     * @uses `get_previous_post_where` filter
     * @uses fulcrum_add_parent_post_to_adjacent_sql()
     *
     * @since 3.1.3
     *
     * @param bool $inSameTerm Optional. Whether post should be in a same taxonomy term. Default false.
     * @param array|string $excludedTerms Optional. Array or comma-separated list of excluded term IDs. Default empty.
     * @param string $taxonomy Optional. Taxonomy, if $inSameTerm is true. Default 'category'.
     *
     * @return null|string|WP_Post Post object if successful. Null if global $post is not set. Empty string if no
     *                             corresponding post exists.
     */
    function get_previous_parent_post($inSameTerm = false, $excludedTerms = '', $taxonomy = 'category')
    {
        return ParentChild::getPreviousParentPost($inSameTerm, $excludedTerms, $taxonomy);
    }
}

if (!function_exists('extract_post_id')) {
    /**
     * Get the post ID from the given post or post ID.
     * If none is passed in, then it grabs the current ID.
     *
     * @since 3.1.3
     *
     * @param WP_Post|int|null $postOrPostId Given post or post ID
     *
     * @return int
     */
    function extract_post_id($postOrPostId = null)
    {
        return ParentChild::extractPostId($postOrPostId);
    }
}
