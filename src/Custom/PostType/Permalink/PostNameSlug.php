<?php

namespace Fulcrum\Custom\Post_Type\Permalink;

use Fulcrum\Config\ConfigContract;

class PostNameSlug
{
    /**
     * Runtime configuration parameters.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Custom post type
     *
     * @var string
     */
    protected $customPostType;

    /**
     * Taxonomy
     *
     * @var string
     */
    protected $taxonomy;

    /**
     * PostNameSlug constructor.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config         = $config;
        $this->customPostType = $config->customPostType;
        $this->taxonomy       = $config->taxonomy;
    }

    /**
     * Initialization events.
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function initEvents()
    {
        add_filter('wp_unique_post_slug', [$this, 'checkUniquePostSlug',], 10, 6);
    }

    /**
     * Checks the unique post slug against the custom post type permalink structure.  If it matches, then
     * the original slug is returns; else $slug is returned.
     *
     * Registered to `wp_unique_post_slug`
     *
     * @since 3.0.0
     *
     * @param string $slug
     * @param int $postId
     * @param string $postStatus
     * @param string $postType
     * @param $postParent
     * @param string $originalSlug
     *
     * @return string
     */
    public function checkUniquePostSlug($slug, $postId, $postStatus, $postType, $postParent, $originalSlug)
    {
        if ($this->customPostType !== $postType) {
            return $slug;
        }

        $termId = $this->get_term_id($postId);
        if (false === $termId) {
            return $slug;
        }

        if (!$this->isDuplicatePostName($originalSlug, $postType, $postId, $termId)) {
            return $originalSlug;
        }

        return $slug;
    }

    /**
     * Checks if the post name exists (is a duplicate) in the database for the
     * same term.
     *
     * This handler knows there will be duplicate post_name values due to the custom permalink
     * structure; however, we do not want duplicates for the same term.
     *
     * @since 3.0.
     *
     * @param string $slug Article's post_name (slug).
     * @param string $postType Custom post type.
     * @param int $postId Post ID
     * @param int $termId Term ID for this post.
     *
     * @return null|string
     */
    protected function isDuplicatePostName($slug, $postType, $postId, $termId)
    {
        global $wpdb;

        $sqlQuery = "
			SELECT p.post_name
			FROM $wpdb->posts AS p
			INNER JOIN $wpdb->term_relationships AS tr ON (p.ID = tr.object_id)
			WHERE post_name = %s AND post_type = %s AND ID != %d AND tr.term_taxonomy_id IN (%d)
			LIMIT 1;
			";
        return $wpdb->get_var($wpdb->prepare($sqlQuery, $slug, $postType, $postId, $termId));
    }

    /**
     * Get the term ID for this taxonomy.
     *
     * @since 3.0.0
     *
     * @param int $postId Post ID
     *
     * @return bool|string
     */
    protected function getTermId($postId)
    {
        $terms = wp_get_post_terms($postId, $this->taxonomy, [
            'fields' => 'ids',
        ]);

        if (is_array($terms)) {
            return array_shift($terms);
        }

        return false;
    }
}
