<?php

namespace Fulcrum\Custom\Widget;

use WP_Widget;
use RuntimeException;
use Fulcrum\Fulcrum;
use Fulcrum\FulcrumContract;
use Fulcrum\Config\ConfigContract;

class Widget extends WP_Widget implements WidgetContract
{
    /**
     * Instance of Fulcrum
     *
     * @var FulcrumContract
     */
    protected $fulcrum;

    /**
     * Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Holds widget settings defaults, populated in constructor.
     *
     * @var array
     */
    protected $defaults;

    /****************************
     * Instantiate & Initialize
     ****************************/

    /**
     * Widget constructor. Set the default widget options and create widget.
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        $this->initProperties();

        $this->init();

        parent::__construct(
            $this->config->id_base,
            $this->config->name,
            $this->config->widget_options,
            $this->config->control_options
        );
    }

    /**
     * Initialize the Properties
     *
     * Sadly we must be coupled to Fulcrum directly as Widgets are instantiated in the
     * registration process within the Widget Factory itself.  We could use the global
     * to gain access directly to the "public" widget registry within the factory;
     * however,then we are coupled to that implementation, meaning if down the road the
     * process changes within WordPress Core, our widget will break.
     *
     * A compromise then is to fetch hub and then instantiate the config stored
     * in its Container here within this method.
     *
     * @since 3.0.0
     *
     * @return null
     * @throws RuntimeException
     */
    protected function initProperties()
    {
        $this->fulcrum      = Fulcrum::getFulcrum();
        $widgetContainerKey = get_class($this);

        // If it's not valid, an error is thrown.
        Validator::validateFulcrumHasWidgetConfig($this->fulcrum, $widgetContainerKey);

        $this->config = $this->fulcrum->get($widgetContainerKey);

        // If it's not valid, an error is thrown.
        Validator::isValid($this->config);
    }

    /**
     * Initialize the widget
     *
     * @since 3.0.0
     *
     * @@return null
     */
    protected function init()
    {
        /* do nothing */
    }

    /****************************
     * Render to Front-end
     ****************************/

    /**
     * Echo the widget content.
     *
     * @since 3.0.0
     *
     * @param array $args Display arguments including
     *                          before_title, after_title, before_widget, & after_widget.
     * @param array $instance The settings for the particular instance of the widget
     *
     * @return null
     */
    public function widget($args, $instance)
    {
        $this->initInstance($instance);

        echo $this->modifyBeforeWidgetHtml($args['before_widget'], $instance);

        if (!empty($instance['title'])) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        $this->renderWidget($args, $instance);

        echo $args['after_widget'];
    }

    /**
     * Render the HTML for the widget
     *
     * @since 3.0.0
     *
     * @param array $args Display arguments including
     *                          before_title, after_title, before_widget, & after_widget.
     * @param array $instance The settings for the particular instance of the widget
     *
     * @return null
     */
    protected function renderWidget(array &$args, array &$instance)
    {
        require $this->config->views['widget'];
    }

    /****************************
     * Render to Back-end
     ****************************/

    /**
     * Echo the settings update form.
     *
     * @since 3.0.0
     *
     * @param array $instance Current settings.
     *
     * @return null
     */
    public function form($instance)
    {
        $this->initInstance($instance);

        require $this->config->views['form'];
    }

    /****************************
     * Helpers
     ****************************/

    /**
     * Update a particular instance.
     *
     * This function should check that $new_instance is set correctly.
     * The newly calculated value of $instance should be returned.
     * If false is returned, the instance won't be saved / updated.
     *
     * @since 3.0.0
     *
     * @param array $newInstance New settings for this instance as input by the user via form().
     * @param array $oldInstance Old settings for this instance.
     *
     * @return array                    Settings to save or bool false to cancel saving
     */
    public function update($newInstance, $oldInstance)
    {
        if (!is_array($newInstance)) {
            return $newInstance;
        }

        foreach (array_keys($this->config->defaults) as $key) {
            if (!isset($newInstance[$key])) {
                continue;
            }
            $newInstance[$key] = trim($newInstance[$key]);
            $newInstance[$key] = strip_tags($newInstance[$key]);
        }

        return $newInstance;
    }

    /**
     * Initialize the widget's instance by merging it with the defaults
     *
     * @since 3.0.0
     *
     * @param $instance
     */
    protected function initInstance(&$instance)
    {
        $instance = empty($instance)
            ? $this->config->defaults
            : wp_parse_args((array) $instance, $this->config->defaults);
    }

    /**
     * Modifies the before widget HTML by inserting the class, when
     * configured.
     *
     * @since 3.0.0
     *
     * @param string $beforeWidget
     * @param array $instance
     *
     * @return string
     */
    protected function modifyBeforeWidgetHtml($beforeWidget, array $instance)
    {
        if (array_key_exists('class', $instance) && !empty($instance['class'])) {
            $beforeWidget = str_replace(
                'class="widget ',
                'class="' . esc_attr($instance['class']) . ' widget ',
                $beforeWidget
            );
        }

        return $beforeWidget;
    }
}
