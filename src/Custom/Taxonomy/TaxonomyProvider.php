<?php

namespace Fulcrum\Custom\Taxonomy;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Config\ConfigFactory;
use Fulcrum\Foundation\ServiceProvider\Provider;
use Fulcrum\Config\Config;

class TaxonomyProvider extends Provider
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
     * @param string $uniqueId Optional. Container's unique key ID for this instance.
     *
     * @return array
     */
    public function getConcrete(array $config, $uniqueId = '')
    {
        return [
            'autoload' => $config['autoload'],
            'concrete' => function () use ($config) {
                return new Taxonomy(
                    $config['taxonomyName'],
                    $this->createConfig($config['config']),
                    $this->createLabelsBuilder($config['config'])
                );
            },
        ];
    }

    /**
     * Instantiate the config.
     *
     * @since 3.0.0
     *
     * @param array $config Runtime parameters.
     *
     * @return ConfigContract
     */
    protected function createConfig(array $config)
    {
        if (!$this->isConfigured($config, 'taxonomyConfig')) {
            return ConfigFactory::create($this->defaults);
        }

        return ConfigFactory::create($config['taxonomyConfig'], $this->defaults);
    }

    /**
     * Create the LabelsBuilder.
     *
     * @since 3.0.0
     *
     * @param array $config Runtime parameters.
     *
     * @return LabelsBuilder
     */
    protected function createLabelsBuilder(array $config)
    {
        $defaults = [
            'useBuilder'   => true,
            'pluralName'   => '',
            'singularName' => '',
            'labels'       => [],
        ];

        $configObj = $this->isConfigured($config, 'labelsConfig')
            ? ConfigFactory::create($config['labelsConfig'], $defaults)
            : ConfigFactory::create($defaults);

        return new LabelsBuilder($configObj);
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
            'autoload'     => false,
            'taxonomyName' => '',
            'config'       => [
                'taxonomyConfig' => [],
                'labelsConfig'   => [],
            ],
        ];
    }

    protected function isConfigured(array $config, $key)
    {
        if (!isset($config[$key])) {
            return false;
        }

        if (!is_array($config[$key])) {
            return false;
        }

        return !empty($config[$key]);
    }
}
