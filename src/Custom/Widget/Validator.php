<?php

namespace Fulcrum\Custom\Widget;

use Fulcrum\Config\ConfigContract;
use Fulcrum\FulcrumContract;
use InvalidArgumentException;
use RuntimeException;

class Validator
{
    protected static $config;

    /**
     * Checks if the configuration is valid.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config Runtime configuration parameters.
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    public static function isValid(ConfigContract $config)
    {
        self::$config = $config;

        self::validateFirstDepth();

        self::validateWidgetOptions();

        self::validateViews();

        self::$config = null;

        return true;
    }

    /**
     * Checks if the widget's configuration is available in Fulcrum's container.
     *
     * @since 3.0.0
     *
     * @param FulcrumContract $fulcrum Instance of Fulcrum.
     * @param string $widgetUniqueId Unique ID in Fulcrum's container for the widget's configuration.
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    public static function validateFulcrumHasWidgetConfig(FulcrumContract $fulcrum, $widgetUniqueId)
    {
        if ($fulcrum->has($widgetUniqueId)) {
            return true;
        }

        throw new RuntimeException(sprintf(
            '%s %s',
            __(
                'The specified widget configuration file is not available in the container.',
                'fulcrum'
            ),
            $widgetUniqueId
        ));
    }

    /***********************
     * Individual validators
     **********************/

    /**
     * Validate the first depth of parameters.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected static function validateFirstDepth()
    {
        // Validate present and not empty.
        foreach (['id_base', 'name', 'widget_options', 'views'] as $required) {
            self::validatePresentAndNotEmpty($required);
        }

        // Validate present.
        foreach (['control_options', 'defaults'] as $required) {
            self::validatePresent($required);
        }

        // Validate must be an array.
        foreach (['widget_options', 'control_options', 'defaults', 'views'] as $required) {
            if (!is_array(self::$config->get($required))) {
                throw new InvalidArgumentException(sprintf(
                    __('Invalid widget configuration. The "%s" parameter must be an array.', 'fulcrum'),
                    $required
                ));
            }
        }
    }

    /**
     * Validate the Widget Options configuration parameters.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected static function validateWidgetOptions()
    {
        foreach (['widget_options.classname', 'widget_options.description'] as $required) {
            self::validatePresentAndNotEmpty($required);
        }
    }

    /**
     * Validate the Widget views configuration parameters.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected static function validateViews()
    {
        foreach (['views.widget', 'views.form'] as $required) {
            self::validatePresentAndNotEmpty($required);

            $path = self::$config->get($required);
            if (!is_readable(self::$config->get($required))) {
                throw new RuntimeException(sprintf(
                    __('The specified view file is not readable. [View: %s]', 'fulcrum'),
                    print_r($path, true)
                ));
            }
        }
    }

    /**
     * Validate the required parameter is present and has a value configured.
     *
     * @since 3.0.0
     *
     * @param string $parameter Parameter to check, which can be "dot" notation.
     *
     * @return bool
     */
    protected static function validatePresentAndNotEmpty($parameter)
    {
        self::validatePresent($parameter);

        if (!empty(self::$config->get($parameter))) {
            return true;
        }

        throw new InvalidArgumentException(sprintf(
            __('Invalid widget configuration. The "%s" parameter is required and must be configured.', 'fulcrum'),
            $parameter
        ));
    }

    /**
     * Validate the required parameter is present.
     *
     * @since 3.0.0
     *
     * @param string $parameter Parameter to check, which can be "dot" notation.
     *
     * @return bool
     */
    protected static function validatePresent($parameter)
    {
        if (self::$config->has($parameter) === true) {
            return true;
        }

        throw new InvalidArgumentException(sprintf(
            __('Invalid widget configuration. The "%s" parameter is required.', 'fulcrum'),
            $parameter
        ));
    }
}
