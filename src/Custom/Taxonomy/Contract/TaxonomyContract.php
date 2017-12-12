<?php

namespace Fulcrum\Custom\Taxonomy\Contract;

interface TaxonomyContract
{

    /**
     * Time to register this taxonomy
     *
     * @since 3.0.0
     *
     * @uses self::buildArgs() to build up the args needed to register this taxonomy
     * @return null
     */
    public function register();
}
