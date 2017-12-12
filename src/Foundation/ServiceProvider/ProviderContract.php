<?php

namespace Fulcrum\Foundation\ServiceProvider;

interface ProviderContract
{
    /**
     * Register a Post Type instance into the container.
     *
     * @since 3.0.0
     *
     * @param array $concreteConfig Concrete's runtime configuration parameters.
     * @param string $uniqueId Container's unique key ID for this instance.
     *
     * @returns mixed
     */
    public function register(array $concreteConfig, $uniqueId);

    /**
     * Get the concrete based upon the configuration supplied.
     *
     * @since 3.0.0
     *
     * @param array $config Runtime configuration parameters.
     * @param string $uniqueId Optional. Container's unique key ID for this instance.
     *
     * @return array
     */
    public function getConcrete(array $config, $uniqueId = '');
}
