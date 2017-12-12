<?php

namespace Fulcrum\Custom\PostType\Permalink;

use Fulcrum\Config\ConfigContract;
use WP_Post;

class CustomPermalink
{
    /**
     * Runtime configuration parameters.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Instance of the Post Name Slug
     *
     * @var Post_Name_Slug
     */
    protected $postNameSlug;

    /**
     * Instance of the Query Handler
     *
     * @var Permalink_Query
     */
    protected $permalinkQuery;

    /**
     * Custom_Permalink constructor.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     * @param PostNameSlug $postNameSlug
     * @param PermalinkQuery $permalinkQuery
     */
    public function __construct(ConfigContract $config, PostNameSlug $postNameSlug, PermalinkQuery $permalinkQuery)
    {
        $this->config         = $config;
        $this->postNameSlug   = $postNameSlug;
        $this->permalinkQuery = $permalinkQuery;

        $this->initEvents();
    }

    /**
     * Add taxonomy to the post link (rewrite), when configured.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initEvents()
    {
        $this->postNameSlug->initEvents();
        $this->permalinkQuery->initEvents();

        if ($this->isRewriteWithTaxonomy()) {
            add_filter('post_type_link', [$this, 'addTaxonomyToPostTypeLink'], 10, 2);
        }

        if ($this->config->debugger) {
            $this->debugger();
        }
    }

    /**
     * Filter the permalink for a post with a custom post type.
     *
     * @since 3.0.0
     *
     * @param string $postLink The post's permalink.
     * @param WP_Post $post The post in question.
     *
     * @return string
     */
    public function addTaxonomyToPostTypeLink($postLink, WP_Post $post)
    {
        if ($post->post_type != $this->config->customPostType) {
            return $postLink;
        }

        $taxonomy = $this->config->rewriteWithTaxonomy['taxonomyName'];

        $terms = get_the_terms($post, $taxonomy);

        if (!fulcrum_are_terms_present($terms)) {
            return $postLink;
        }
        $term = current($terms);

        $link = sprintf('%s/%s/%s', $this->getPostTypeRewrite(), $term->slug, $post->postName);

        return home_url(user_trailingslashit($link));

        return $postLink;
    }

    /**
     * Fetches the post type rewrite.
     *
     * @since 3.0.0
     *
     * @return string
     */
    protected function getPostTypeRewrite()
    {
        if ($this->config->has('postTypeRewrite')) {
            return $this->config->postTypeRewrite;
        }

        return $this->config->customPostType;
    }

    /**
     * Checks if the "rewriteWithTaxonomy" is required.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function isRewriteWithTaxonomy()
    {
        return $this->config->has('rewriteWithTaxonomy') &&
               $this->config->isArray('rewriteWithTaxonomy') &&
               $this->config->rewriteWithTaxonomy['enable'];
    }

    /**
     * The debugger hooks in some data point views for display using Kint.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function debugger()
    {
        $displayFuncName = function_exists('d') ? 'd' : 'var_dump';

        add_action('parse_request', function ($wp) use ($displayFuncName) {
            if (is_admin()) {
                return;
            }

            $displayFuncName($wp->matched_rule);
            $displayFuncName($wp->matched_query);
        }, 9999);

        add_action('pre_get_posts', function ($query) use ($displayFuncName) {
            if (is_admin()) {
                return;
            }

            $displayFuncName($query);
            if ($this->permalinkQuery->okToAddTaxTermsQueryVars($query)) {
                $displayFuncName($query);
            }
        }, 9);
    }
}
