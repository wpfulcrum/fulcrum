<?php

namespace Fulcrum\Custom\PostType\Contract;

interface SupportedFeaturesContract
{
    /**
     * Build the supports argument.  If it is not configured, then grab all of the
     * supports from the built-in 'post' post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function build();

    /**
     * Gets the array of supports for this post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getSupports();
}
