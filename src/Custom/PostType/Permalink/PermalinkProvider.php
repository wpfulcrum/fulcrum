<?php

namespace Fulcrum\Custom\Post_Type\Permalink;

use Fulcrum\Foundation\ServiceProvider\Provider;

class PermalinkProvider extends Provider
{
    /**
     * Flag to indicate whether to skip the queue and register directly into the Container.
     *
     * @var bool
     */
    protected $skipQueue = true;

    /**
     * Get the concrete based upon the configuration supplied.
     *
     * @since 3.0.0
     *
     * @param array $config Runtime configuration parameters.
     * @param string $uniqueId Container's unique key ID for this instance.
     *
     * @return array
     */
    public function getConcrete(array $config, $uniqueId = '')
    {
        return [
            'autoload' => $config['autoload'],
            'concrete' => function () use ($config) {
                $configObj = $this->instantiateConfig($config);

                return new CustomPermalink(
                    $configObj,
                    new PostNameSlug($configObj),
                    new PermalinkQuery($configObj)
                );
            },
        ];
    }

    /**
     * Get the default structure for the concrete.
     *
     * @since 3.0.0
     *
     * @return array
     */
    protected function getConcreteDefaultStructure()
    {
        return [
            'autoload' => false,
            'config'   => [],
        ];
    }
}
