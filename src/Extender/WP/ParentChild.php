<?php

namespace Fulcrum\Extender\WP;

class ParentChild
{
    /**
     * Checks if the given post is a child.
     *
     * @since 3.1.3
     *
     * @param int|WP_Post|null $postOrPostId Post Instance or Post ID to check
     *
     * @return boolean
     */
    public static function isChildPost($postOrPostId = null)
    {
        $post = self::getPost($postOrPostId);
        if (empty($post)) {
            return false;
        }

        return $post->post_parent > 0;
    }

    /**
     * Checks if the given post is a parent.
     *
     * @since 3.1.3
     *
     * @param int|WP_Post|null $postOrPostId Post Instance or Post ID to check
     *
     * @return boolean
     */
    public static function isParentPost($postOrPostId = null)
    {
        $post = self::getPost($postOrPostId);
        if (empty($post)) {
            return false;
        }

        return $post->post_parent === 0;
    }

    /**
     * Checks if the given post has children
     *
     * @since 3.1.3
     *
     * @param int|WP_Post|null $postOrPostId Post Instance or Post ID to check
     *
     * @return boolean
     */
    public static function postHasChildren($postOrPostId = null)
    {
        $number_of_children = self::getNumberOfPostChildren($postOrPostId);

        return $number_of_children > 0;
    }

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
    public static function getNumberOfPostChildren($postOrPostId = null)
    {
        $postId = self::extractPostId($postOrPostId);
        if ($postId < 1) {
            return false;
        }

        global $wpdb;
        return (int) $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_parent = %d", $postId)
        );
    }

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
    public static function getNextParentPost($inSameTerm = false, $excludedTerms = '', $taxonomy = 'category')
    {
        add_filter('get_next_post_where', [__CLASS__, 'addParentPostToAdjacentSql']);

        return get_adjacent_post($inSameTerm, $excludedTerms, false, $taxonomy);
    }

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
    public static function getPreviousParentPost($inSameTerm = false, $excludedTerms = '', $taxonomy = 'category')
    {
        add_filter('get_previous_post_where', [__CLASS__, 'addParentPostToAdjacentSql']);

        return get_adjacent_post($inSameTerm, $excludedTerms, true, $taxonomy);
    }

    /**
     * Get the post ID from the given post or post ID.
     * If none is passed in, then it grabs the current ID.
     *
     * @since 3.1.3
     *
     * @param WP_Post|int|null $postOrPostId Given post or post ID
     *
     * @return int|null
     */
    public static function extractPostId($postOrPostId = null)
    {
        if (is_object($postOrPostId)) {
            return property_exists($postOrPostId, 'ID')
                ? (int) $postOrPostId->ID
                : null;
        }

        if ($postOrPostId > 0) {
            return (int) $postOrPostId;
        }

        return get_the_ID();
    }

    protected static function getPost($postID)
    {
        $post = get_post($postID);
        if (!$post || is_wp_error($post)) {
            return null;
        }

        return $post;
    }

    /**
     * Adds a post parent WHERE SQL check to the adjacent SQL.
     *
     * In WordPress, the column `post_parent` is 0 when the content is
     * the root parent.
     *
     * Callback for the WordPress filter events `get_previous_post_where` and
     * `get_next_post_where`.
     *
     * @since 3.1.3
     *
     * @param string $whereSql
     *
     * @return string
     */
    public static function addParentPostToAdjacentSql($whereSql)
    {
        $whereSql .= ' AND p.post_parent = 0';

        return $whereSql;
    }
}
