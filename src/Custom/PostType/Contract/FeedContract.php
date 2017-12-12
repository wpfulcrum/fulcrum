<?php

namespace Fulcrum\Custom\PostType\Contract;

interface FeedContract
{
    /**
     * Handles adding (or removing) this CPT to/from the RSS Feed.
     *
     * @since 3.0.0
     *
     * @param array $queryVars Query variables from parse_request
     *
     * @return array
     */
    public function addOrRemoveToFromRssFeed($queryVars);
}
