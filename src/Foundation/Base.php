<?php

namespace Fulcrum\Foundation;

use Fulcrum\Config\ConfigContract;
use Fulcrum\FulcrumContract;

abstract class Base
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
     * Path to this class' defaults folder
     *
     * @var string
     */
    protected static $defaultsFolder = 'defaults/';

    /**
     * Defaults filename
     *
     * @var string
     */
    protected static $defaultsFile = '';

    /**************
     * Getters
     *************/

    /**
     * Get a property if it exists; else return the default_value
     *
     * @since 3.0.0
     *
     * @param string $property
     * @param mixed $default_value
     *
     * @return mixed|null
     */
    public function get($property, $default_value = null)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return $default_value;
    }

    /**
     * Slower magical getter
     *
     * @since 3.0.0
     *
     * @param string $property
     *
     * @return null|mixed
     */
    public function __get($property)
    {
        return $this->get($property);
    }

    /*********************************
     * Instantiation & Initialization
     ********************************/

    /**
     * Instantiate the object.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     * @param FulcrumContract $fulcrum
     *
     * @return self|null
     */
    public function __construct(ConfigContract $config, FulcrumContract $fulcrum = null)
    {
        $this->config  = $config;
        $this->fulcrum = $fulcrum;
    }

    /*********************************
     * Public Methods
     ********************************/

    /**
     * Wrapper which checks if the parameter exists in $config.
     *
     * @since 3.0.0
     *
     * @param string $parameter
     *
     * @return bool
     */
    public function configHas($parameter)
    {
        if (empty($this->config)) {
            return false;
        }

        return $this->config->has($parameter);
    }

    /*************************
     * Defaults File Locator
     ************************/

    /**
     * Get the Defaults File path + name
     *
     * @since 3.0.0
     *
     * @param string $defaultsFolder (Optional) Specify the path to the defaults file
     *
     * @return string
     */
    public static function getDefaultsFile($defaultsFolder = '')
    {
        if (!$defaultsFolder) {
            $defaultsFolder = FULCRUM_PLUGIN_DIR . 'config/' . static::$defaultsFolder;
        }

        if (!$defaultsFolder) {
            return '';
        }

        $defaultFile = static::$defaultsFile ?: self::getClassname() . '.php';

        return trailingslashit($defaultsFolder) . $defaultFile;
    }

    /**
     * Get the class shortname and format in a filename structure
     *
     * @since 3.0.0
     *
     * @return mixed
     */
    protected static function getClassname()
    {
        $className = explode('\\', get_called_class());
        $className = array_pop($className);

        return str_replace('_', '-', strtolower($className));
    }

    /**
     * Get child's directory.
     *
     * @since 3.0.0
     *
     * @return string
     */
    protected function getChildDirectory()
    {
        $classInfo = new \ReflectionClass(get_class($this));
        return trailingslashit(dirname($classInfo->getFileName()));
    }
}
