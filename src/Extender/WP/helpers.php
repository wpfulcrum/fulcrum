<?php
/**
 * WordPress Helper functions - extra functions to make your job easier.
 *
 * @package     Fulcrum\Extender\WP
 * @since       3.1.0
 * @author      hellofromTonya
 * @link        https://github.com/wpfulcrum/extender
 * @license     MIT
 */

if (!function_exists('get_all_supports_for_post_type')) {
    /**
     * Get all of the supports for the given post type.
     *
     * @since 3.0.0
     *
     * @param string $postType Post type for which to fetch its supported featured.
     *
     * @return array
     */
    function get_all_supports_for_post_type($postType)
    {
        $enabledPostTypes = get_all_post_type_supports($postType);
        if (is_array($enabledPostTypes) && !empty($enabledPostTypes)) {
            return array_keys($enabledPostTypes);
        }

        return [];
    }
}

if (!function_exists('get_all_custom_post_types')) {
    /**
     * Gets all custom post types.
     *
     * @since 3.0.0
     *
     * @return array
     */
    function get_all_custom_post_types()
    {
        return get_post_types(
            [
                '_builtin' => false,
            ]
        );
    }
}

if (!function_exists('get_current_web_page_id')) {
    /**
     * Get the current web page's ID.
     *
     * @since 3.1.0
     *
     * @return int
     */
    function get_current_web_page_id()
    {
        return (int) get_queried_object_id();
    }
}

if (!function_exists('get_joined_list_of_terms')) {
    /**
     * Get a joined list of all the terms for the requested
     * post ID and term name
     *
     * @since 3.1.6
     *
     * @param string $taxonomy Name of the taxonomy
     * @param integer $postId
     *
     * @return string Returns the list of terms or ''.
     */
    function get_joined_list_of_terms($taxonomy, $postId)
    {
        if (!$taxonomy || $postId < 1) {
            return '';
        }

        $terms = get_the_terms((int) $postId, $taxonomy);
        if ($terms && !is_wp_error($terms)) {
            $termsArr = [];
            foreach ($terms as $term) {
                $termsArr[] = $term->name;
            }

            return join(', ', $termsArr);
        }

        return '';
    }
}

if (!function_exists('get_terms_by_post_type')) {
    /**
     * Get all of the terms for the given post type(s) to extend `get_terms()`.
     *
     * Note: By default, empty terms are not included.  To include them, add the following to
     * the `$args` argument:
     *
     *      'hide_empty' => false,
     *
     * {@see 'WP_Term_Query::__construct()'} for the list of acceptable arguments.
     *
     * @since 3.1.6
     *
     * @param string|array $postType The post type(s) for which to get the terms.
     * @param array $args Array of get_terms() arguments. See WP_Term_Query::__construct() for information
     *                          on accepted arguments. Default empty.
     *
     * @return array|int|WP_Error   List of WP_Term instances and their children.
     *                              Returns WP_Error, if any of $taxonomies do not exist.
     */
    function get_terms_by_post_type($postType, array $args = [])
    {
        // Add the filter so that we can add our SQL to make this work.
        add_filter('terms_clauses', 'add_post_type_to_terms_sql', 99999, 3);

        // Override these args.
        $args['post_type']  = (array)$postType;
        $args['hide_empty'] = true;

        $terms = get_terms($args);

        // Now remove the filter to restore the default behavior.
        remove_filter('terms_clauses', 'add_post_type_to_terms_sql', 99999);

        return $terms;
    }

    /**
     * This callback allows you to add "post_type" as a term query argument to limit the terms to only
     * those bound to the post type(s) you specify.
     *
     * When you get_terms(), you can limit it to one or more post types by adding:
     *      'post_type' => 'portfolio',
     * or
     *      'post_type' => ['portfolio', 'bio'],
     * as an argument.
     *
     * If `post_type` is supplied, this callback adds it to the SQL query; else, nothing happens as it bails out.
     *
     * @since 3.1.6
     *
     * @param array $clauses Terms query SQL clauses.
     * @param array $taxonomies An array of taxonomies.
     * @param array $args An array of terms query arguments.
     *
     * @return mixed
     */
    function add_post_type_to_terms_sql(array $clauses, array $taxonomies, array $args)
    {
        if (!array_key_exists('post_type', $args) || !$args['post_type']) {
            return $clauses;
        }

        $postTypes = prepare_array_for_sql_where_in((array) $args['post_type']);

        global $wpdb;
        $clauses['join']  .=
            " INNER JOIN {$wpdb->term_relationships} AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id" .
            " INNER JOIN {$wpdb->posts} AS p ON p.ID = tr.object_id";
        $clauses['where'] .= " AND p.post_type IN ( {$postTypes} ) GROUP BY t.term_id";

        return $clauses;
    }
}

if (!function_exists('get_url_relative_to_home_url')) {
    /**
     * Get the URL relative to the site's root (home url).
     *
     * Performance function.
     *
     * This function uses `get_home_url()` and caches it.  It allows us to speed up
     * building links and menus as we don't have to call `home_url( 'some-path' );`
     * over and over again.
     *
     * @since 3.1.0
     *
     * @param  string $path Optional. Path relative to the home URL. Default empty.
     * @param  string|null $scheme Optional. Scheme to give the home URL context. Accepts
     *                              'http', 'https', 'relative', 'rest', or null. Default null.
     *
     * @return string Home URL link with optional path appended.
     */
    function get_url_relative_to_home_url($path = '', $scheme = null)
    {
        static $homeUrl;

        if (!$homeUrl) {
            $homeUrl = get_home_url(null, '', $scheme);
        }

        if (!$homeUrl) {
            return '';
        }

        return sprintf('%s/%s', $homeUrl, ltrim($path, '/'));
    }
}

if (!function_exists('get_post_id')) {
    /**
     * Get the Post ID
     *
     * If in the back-end, it will use $_REQUEST;
     * else it uses either the incoming post ID or $post->ID
     *
     * @since 3.0.0
     *
     * @param int $postId (optional)
     *
     * @return int Returns the post ID, if one is found; else 0.
     */
    function get_post_id($postId = 0)
    {
        if (is_admin()) {
            return get_post_id_when_in_backend($postId);
        }

        return $postId < 1 ? get_the_ID() : $postId;
    }
}

if (!function_exists('get_post_id_when_in_backend')) {
    /**
     * Get the Post ID
     *
     * If in the back-end, it will use $_REQUEST;
     * else it uses either the incoming post ID or $post->ID
     *
     * @since 3.0.0
     *
     * @param int $postId (optional)
     *
     * @return int Returns the post ID, if one is found; else 0.
     */
    function get_post_id_when_in_backend($postId = 0)
    {
        if (!is_admin()) {
            return $postId;
        }

        $possibleRequestKeys = [
            'post_ID',
            'post_id',
            'post',
        ];

        foreach ($possibleRequestKeys as $key) {
            if (!isset($_REQUEST[$key])) {
                continue;
            }

            if (is_numeric($_REQUEST[$key])) {
                return (int) $_REQUEST[$key];
            }
        }

        return $postId;
    }
}

if (!function_exists('fulcrum_declare_plugin_constants')) {
    /**
     * Get the plugin's URL, obtained from the plugin's root file.
     *
     * @since 3.1.3
     *
     * @param string $prefix Constant prefix
     * @param string $rootPath Plugin's root file
     *
     * @returns string Returns the plugin URL
     */
    function fulcrum_declare_plugin_constants($prefix, $rootPath)
    {
        if (!defined($prefix . '_PLUGIN_DIR')) {
            define($prefix . '_PLUGIN_DIR', plugin_dir_path($rootPath));
        }

        if (!defined($prefix . '_PLUGIN_URL')) {
            define($prefix . '_PLUGIN_URL', fulcrum_get_plugin_url($rootPath));
        }
    }
}

if (!function_exists('fulcrum_get_plugin_url')) {
    /**
     * Get the plugin's URL, obtained from the plugin's root file.
     *
     * @since 3.1.3
     *
     * @param string $rootPath Plugin's root file
     *
     * @returns string Returns the plugin URL
     */
    function fulcrum_get_plugin_url($rootPath)
    {
        $pluginUrl = plugin_dir_url($rootPath);
        if (!is_ssl()) {
            return $pluginUrl;
        }

        return str_replace('http://', 'https://', $pluginUrl);
    }
}
