<?php

namespace Fulcrum\Custom\PostType;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Foundation\ServiceProvider\Provider;

class PostTypeProvider extends Provider
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
                return new PostType(
                    $config['postType'],
                    $this->createPostTypeConfig($config['config'], $config['postType']),
                    $this->createColumns($config['config'], $config['postType']),
                    $this->createSupports($config['config']),
                    $this->createLabelsBuilder($config['config'])
                );
            },
        ];
    }

    protected function createPostTypeConfig(array $config, $postType)
    {
        $defaults = [
            'description'  => '',
            'public'       => false,
            'hierarchical' => false,
            'rewrite'      => [
                'slug' => strtolower($postType),
            ],
        ];

        if (!$this->isConfigured($config, 'postTypeArgs')) {
            return ConfigFactory::create($defaults);
        }

        return ConfigFactory::create($config['postTypeArgs'], $defaults);
    }

    protected function createColumns(array $config, $postType)
    {
        $defaults = [
            'columnsFilter' => [],
            'columnsData'   => [],
        ];

        $configObj = $this->isConfigured($config, 'columnsConfig')
            ? ConfigFactory::create($config['columnsConfig'], $defaults)
            : ConfigFactory::create($defaults);

        return new Columns($postType, $configObj);
    }

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

    protected function createSupports(array $config)
    {
        $hierarchical = false;

        if ($this->isConfigured($config, 'postTypeArgs') && isset($config['postTypeArgs']['hierarchical'])) {
            $hierarchical = $config['postTypeArgs']['hierarchical'];
        }

        $defaults = [
            'hierarchical'       => $hierarchical,
            'additionalSupports' => [],
        ];

        if ($this->isConfigured($config, 'supportsConfig')) {
            $config['supportsConfig']['hierarchical'] = $hierarchical;
            $configObj                                = ConfigFactory::create($config['supportsConfig'], $defaults);
        } else {
            $configObj = ConfigFactory::create($defaults);
        }

        return new SupportedFeatures($configObj);
    }

    /**
     * Flush rewrite rules for custom post type.
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function flushRewriteRules()
    {
        foreach ($this->uniqueIds as $uniqueId) {
            $this->fulcrum[$uniqueId]->register();
        }

        do_harder_rewrite_rules_flush();
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
            'autoload'                => false,
            'postTypeName'            => '',
            'config'                  => [
                'postTypeArgs'   => [],
                'labelsConfig'   => [],
                'supportsConfig' => [],
                'columnsConfig'  => [],
            ],
            'enablePermalinkHandlers' => false,
            'permalinkHandlers'       => [],
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
