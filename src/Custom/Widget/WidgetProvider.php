<?php

namespace Fulcrum\Custom\Widget;

use Fulcrum\Foundation\ServiceProvider\Provider;
use InvalidArgumentException;

class WidgetProvider extends Provider
{
    /**
     * Flag for whether to load the defaults or not.
     *
     * @var bool
     */
    protected $hasDefaults = false;

    /**
     * Initialize events.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initEvents()
    {
        add_action('widgets_init', [$this, 'registerWidgetsCallback']);
    }

    /**
     * Register each widget classname with WordPress.
     *
     * @since 3.0.0
     *
     * @param array $widgetClassNames Array of widget classnames to be registered.
     * @param string $uniqueId Container's unique key ID for this instance.
     *
     * @return void
     */
    public function register(array $widgetClassNames, $uniqueId = '')
    {
        $this->queued = array_merge((array)$this->queued, $widgetClassNames);
    }

    /**
     * If there are widgets registered, iterate through and register each one.
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function registerWidgetsCallback()
    {
        if ($this->queueHasConcretes()) {
            array_walk($this->queued, [$this, 'registerWidget']);
        }

        do_action('fulcrum_widget_init', $this->fulcrum);
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
        return [];
    }

    /**
     * Register the widget (callback) with WordPress using registerWidget().
     *
     * @since 3.0.0
     *
     * @param string $className Widget class name to be registered.
     *
     * @return void
     */
    protected function registerWidget($className)
    {
        if ($this->isValidWidget($className)) {
            register_widget($className);
        }
    }

    /**
     * Checks if the class name is valid; else it throws an error.
     *
     * @since 3.0.0
     *
     * @param string $className Widget class name
     *
     * @return bool
     */
    protected function isValidWidget($className)
    {
        if (class_exists($className)) {
            return true;
        }

        throw new InvalidArgumentException(sprintf(
            __('The class [%s] does not exist.  Therefore the widget cannot be registered.', 'fulcrum'),
            $className
        ));
    }
}
