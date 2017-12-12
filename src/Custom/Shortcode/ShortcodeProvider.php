<?php

namespace Fulcrum\Custom\Shortcode;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Foundation\ServiceProvider\Provider;

class ShortcodeProvider extends Provider
{
    /**
     * Flag for whether to load the defaults or not.
     *
     * @var bool
     */
    protected $hasDefaults = false;

    /**
     * Flag to indicate whether to skip the queue and register directly into the Container.
     *
     * @var bool
     */
    protected $skipQueue = true;

    /**
     * Initialize events.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initEvents()
    {
        add_filter('widget_text', 'do_shortcode');
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
                $class = $this->getShortcodeClass($config);

                return new $class(
                    ConfigFactory::create($config['config'])
                );
            },
        ];
    }

    /**
     * Get the shortcode's class.
     *
     * @since 3.0.0
     *
     * @param array $config
     *
     * @return string
     */
    protected function getShortcodeClass(array $config)
    {
        if (empty($config['classname'])) {
            return 'Fulcrum\Custom\Shortcode\Shortcode';
        }

        return $config['classname'];
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
            'autoload'  => true,
            'classname' => 'Fulcrum\Custom\Shortcode\Shortcode',
            'config'    => '',
        ];
    }
}
