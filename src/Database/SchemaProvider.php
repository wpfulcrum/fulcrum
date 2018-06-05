<?php

namespace Fulcrum\Database;

use Fulcrum\Foundation\ServiceProvider\Provider;

class SchemaProvider extends Provider
{
    /**
     * Register the schema on admin_init
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initEvents()
    {
        if (is_admin()) {
            add_action('admin_init', [$this, 'registerQueue']);
        }
    }

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
                 return new Schema($this->createConfig($config));
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
            'config'   => [],
        ];
    }
}
