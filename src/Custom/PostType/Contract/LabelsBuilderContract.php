<?php

namespace Fulcrum\Custom\PostType\Contract;

interface LabelsBuilderContract
{
    /**
     * Build the labels for this post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function build();

    /**
     * Gets the array of labels for this post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getLabels();
}
