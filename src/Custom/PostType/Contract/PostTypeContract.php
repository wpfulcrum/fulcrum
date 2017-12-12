<?php

namespace Fulcrum\Custom\PostType\Contract;

interface PostTypeContract
{
    /**
     * Register Custom Post Type
     *
     * @since 3.0.0
     *
     * @uses self::buildArgs() Builds up the needed args from defaults & configuration
     *
     * @return void
     */
    public function register();

    /**
     * Get all of the supports.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getTheSupports();
}
