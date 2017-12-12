<?php

namespace Fulcrum\Custom\PostType;

class Feed
{
    /**
     * Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Post type name (all lowercase & no spaces)
     *
     * @var string
     */
    protected $postType;

    /**
     * Internal flag if the query_vars has a post_type key
     *
     * @var bool
     */
    protected $queryVarsHasPostTypes = false;

    /****************************
     * Instantiate & Initialize
     ***************************/

    /**
     * Instantiate the Feed for this custom post type.
     *
     * @since 3.0.0
     *
     * @param string $postTypeName Post type name (all lowercase & no spaces).
     * @param ConfigContract $config Runtime configuration parameters.
     */
    public function __construct($postTypeName, ConfigContract $config)
    {
        $this->config   = $config;
        $this->postType = $postTypeName;

        if (Validator::isStartingStateValid()) {
            $this->initConfig();
            $this->initEvents();
        }
    }

    protected function initConfig()
    {
        if (!$this->config->has('addFeed') || true !== $this->config->addFeed) {
            $this->config->addFeed = false;
        }
    }

    protected function initEvents()
    {
        add_filter('request', [$this, 'addOrRemoveToFromRssFeed']);
    }

    /*****************************************************
     * Public Methods
     ***************************************************/

    /**
     * Handles adding (or removing) this CPT to/from the RSS Feed
     *
     * @since 3.0.0
     *
     * @param array $queryVars Query variables from parse_request
     *
     * @return array $queryVars
     */
    public function addOrRemoveToFromRssFeed($queryVars)
    {
        if (!isset($queryVars['feed'])) {
            return $queryVars;
        }

        $this->addOrRemoveFeedHandler($queryVars);

        return $queryVars;
    }

    /*****************************************************
     * Workers
     ***************************************************/

    /**
     * Checks whether to add or remove the post type from feed. If yes, then it either adds or removes it.
     *
     * @since 3.0.0
     *
     * @param array $queryVars
     */
    protected function addOrRemoveFeedHandler(&$queryVars)
    {
        $postTypeIndex = false;

        if (!$this->isPostTypeInQueryVar($queryVars) && $this->queryVarsHasPostTypes) {
            $postTypeIndex = array_search($this->post_type, (array) $queryVars['post_type']);
        }

        if ($this->isSetToAddToFeed($postTypeIndex)) {
            return $this->addPostTypeToFeed($queryVars);
        }

        if ($this->isSetToRemoveFromFeed($postTypeIndex)) {
            return $this->removePostTypeFromFeed($queryVars, $postTypeIndex);
        }
    }

    /**
     * Add post type to the feed.
     *
     * @since 3.0.0
     *
     * @param array $queryVars
     *
     * @return void
     */
    protected function addPostTypeToFeed(array &$queryVars)
    {
        if (!$this->queryVarsHasPostTypes) {
            $queryVars['post_type'] = ['post', $this->postType];
        } else {
            $queryVars['post_type'][] = $this->postType;
        }
    }

    /**
     * Remove the post type from the feed.
     *
     * @since 3.0.0
     *
     * @param array $queryVars
     * @param bool|int $postTypeIndex
     *
     * @return void
     */
    protected function removePostTypeFromFeed(array &$queryVars, $postTypeIndex)
    {
        unset($queryVars['post_type'][$postTypeIndex]);

        $queryVars['post_type'] = array_values($queryVars['post_type']);
    }

    /**
     * Checks if this post type is in the `$queryVars['post_type']`.
     *
     * @since 3.0.0
     *
     * @param array $queryVars
     *
     * @return bool
     */
    protected function isPostTypeInQueryVar(array $queryVars)
    {
        if (!$this->doesQueryVarsHavePostTypes($queryVars)) {
            return false;
        }

        return in_array($this->postType, (array) $queryVars['post_type']);
    }

    /**
     * Checks if the query_vars already has `post_type` key and it is an array.
     *
     * @since 3.0.0
     *
     * @param array $queryVars
     *
     * @return bool
     */
    public function doesQueryVarsHavePostTypes(array $queryVars)
    {
        $this->queryVarsHasPostTypes = array_key_exists('post_type', $queryVars) && is_array($queryVars['post_type']);

        return $this->queryVarsHasPostTypes;
    }

    /**
     * Checks if conditions are set to add the custom post type from the feed.
     *
     * @since 3.0.0
     *
     * @param bool|int $index
     *
     * @return bool
     */
    protected function isSetToAddToFeed($index)
    {
        return false === $index && $this->config->addFeed;
    }

    /**
     * Checks if conditions are set to remove the custom post type from the feed.
     *
     * @since 3.0.0
     *
     * @param bool|int $index
     *
     * @return bool
     */
    protected function isSetToRemoveFromFeed($index)
    {
        return $this->queryVarsHasPostTypes &&
               false !== $index &&
               !$this->config->addFeed;
    }
}
