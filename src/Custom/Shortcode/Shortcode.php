<?php

namespace Fulcrum\Custom\Shortcode;

use Fulcrum\Config\ConfigContract;

class Shortcode implements ShortcodeContract
{
    /**
     * Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Shortcode attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Shortcode content
     *
     * @var string|null
     */
    protected $content;

    /**
     * Instantiate the Shortcode object
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config Runtime configuration parameters.
     */
    public function __construct(ConfigContract $config)
    {
        if (!Validator::isValid($config)) {
            return;
        }

        $this->config = $config;
        add_shortcode($this->config->shortcode, [$this, 'renderCallback']);
        $this->initShortcode();
    }

    /**
     * Extendable for initializing the shortcode upon instantiation.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initShortcode()
    {
        // available for the extending child class.
    }

    /**
     * Shortcode callback which merges the attributes, calls the render() method to build
     * the HTML, and then returns it.
     *
     * @since 3.0.3
     *
     * @param array|string $attributes Shortcode attributes
     * @param string|null $content Content between the opening & closing shortcode declarations
     *
     * @return string Shortcode HTML
     */
    public function renderCallback($attributes, $content = null)
    {
        if (is_admin()) {
            return;
        }

        $this->attributes = shortcode_atts($this->config->defaults, $attributes, $this->config->shortcode);
        $this->content    = $this->config->doShortcodeWithinContent
            ? do_shortcode($content)
            : $content;

        return $this->render();
    }

    /**************
     * Helpers
     *************/

    /**
     * Build the Shortcode HTML and then return it.
     *
     * NOTE: This is the method to extend for enhanced shortcodes (i.e. which extend this class).
     *
     * @since 3.0.0
     *
     * @return string Shortcode HTML
     */
    protected function render()
    {
        ob_start();
        require $this->config->view;
        return ob_get_clean();
    }

    /**
     * Get the ID from the attributes.
     *
     * @since 3.0.0
     *
     * @return string
     */
    protected function getId()
    {
        if (!$this->attributes['id']) {
            return '';
        }

        return sprintf(' id="%s"', esc_attr($this->attributes['id']));
    }

    /**
     * Get the class name from the attributes.
     *
     * @since 3.0.0
     *
     * @param bool|null $addSpace When true, adds an empty space in front of the class(es).
     *
     * @return string
     */
    protected function getClass($addSpace = null)
    {
        if (!$this->attributes['class']) {
            return '';
        }

        $classes = esc_attr($this->attributes['class']);

        return $addSpace === true ? " $classes" : $classes;
    }

    /**
     * Get and prepare an attribute to render out to the browser.
     *
     * @since 3.0.0
     *
     * @param string $attribute
     * @param string $before
     *
     * @return string
     */
    protected function getAndEscapeAttribute($attribute, $before = ' ')
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            return '';
        }

        if (!$this->attributes[$attribute]) {
            return '';
        }

        return $before . esc_attr($this->attributes[$attribute]);
    }

    /**
     * Checks if a no view is required.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function noViewRequired()
    {
        return Validator::noViewRequired($this->config);
    }
}
