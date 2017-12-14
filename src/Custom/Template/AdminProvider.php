<?php

namespace Fulcrum\Custom\Template;

use Fulcrum\Foundation\ServiceProvider\Provider as BaseProvider;

class AdminProvider extends BaseProvider
{
    /**
     * Flag to indicate whether to skip the queue and register directly into the Container.
     *
     * @var bool
     */
    protected $skipQueue = true;

    /**
     * Specifies where the default file is located.
     *
     * @var string
     */
    protected $defaultsLocation = 'config/admin-defaults.php';

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
                return new AdminTemplate(
                    $this->createConfig($config)
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
            'autoload' => true,
            'config'   => [
                'usePageTemplates' => false,
                'templates'        => [],
            ],
        ];
    }
}
