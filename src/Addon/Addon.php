<?php
/**
 * Add-on abstract class - all plugins should extend this class in order to extend Fulcrum and utilize
 * it's functionality and features.
 *
 * @package     Fulcrum\Addon
 * @since       3.0.0
 * @author      hellofromTonya
 * @link        https://github.com/wpfulcrum/fulcrum
 * @license     MIT
 */

namespace Fulcrum\Addon;

use Fulcrum\Fulcrum;
use Fulcrum\FulcrumContract;
use Fulcrum\Config\ConfigContract;

abstract class Addon
{
    /**
     * Runtime Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Instance of Fulcrum
     *
     * @var FulcrumContract
     */
    protected $fulcrum;

    /**
     * Array of configured providers
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Add-on plugin file.
     *
     * @var string
     */
    protected $pluginFile;

    /**
     * Flag for if the flush_rewrite_rules is required.
     *
     * @var bool
     */
    protected $isFlushRewriteRulesRequired = false;

    /*************************
     * Getters
     ************************/

    public function version()
    {
        return self::VERSION;
    }

    public function minWPVersion()
    {
        return self::MIN_WP_VERSION;
    }

    /*************************
     * Instantiate & Init
     ************************/

    /**
     * Instantiate the plugin
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config Runtime configuration parameters.
     * @param string $pluginFile File for the add-on plugin.
     * @param FulcrumContract $fulcrum Instance of Fulcrum.
     */
    public function __construct(ConfigContract $config, $pluginFile, FulcrumContract $fulcrum = null)
    {
        $this->config     = $config;
        $this->pluginFile = plugin_basename($pluginFile);
        $this->fulcrum    = is_null($fulcrum) ? Fulcrum::getFulcrum() : $fulcrum;

        $this->initAddon();
        $this->initParameters();
        $this->initServiceProviders();
        $this->initAdminServiceProviders();
        $this->registerConcretes();
        $this->initEvents();
    }

    /**
     * Add-ons can overload this method for additional functionality
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initAddon()
    {
        // it's here if you need it.
    }

    /**
     * Add-ons can overload this method to initialize specific events.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initEvents()
    {
        // it's here if you need it.
    }

    /**
     * Initialize the initial parameters by loading each into the Container.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initParameters()
    {
        if (!$this->config->isArray('initialParameters')) {
            return;
        }

        array_walk(
            $this->config->initialParameters,
            function ($value, $uniqueId) {
                $this->fulcrum[$uniqueId] = $value;
            }
        );
    }

    /**
     * Initialize service providers
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initServiceProviders()
    {
        if (!$this->config->has('serviceProviders')) {
            return;
        }

        $this->loadServiceProvidersIntoContainer($this->config->serviceProviders);
    }

    /**
     * Initialize admin service providers
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initAdminServiceProviders()
    {
        if (!is_admin()) {
            return;
        }

        if (!$this->config->has('adminServiceProviders')) {
            return;
        }

        $this->loadServiceProvidersIntoContainer($this->config->adminServiceProviders);
    }

    /**
     * Load each service provider into the container.
     *
     * @since 3.0.0
     *
     * @param array $serviceProvider Array of service provider configurations
     *
     * @return void
     */
    protected function loadServiceProvidersIntoContainer(array $serviceProvider)
    {
        foreach ($serviceProvider as $uniqueId => $providerConfig) {
            $config            = $this->loadConfigFile($providerConfig['config']);
            $this->providers[] = $provider = $providerConfig['provider'];

            $this->fulcrum[$provider]->register($config, $uniqueId);
        }
    }

    /**
     * Register the concretes into Fulcrum.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function registerConcretes()
    {
        if (!$this->config->has('registerConcretes')) {
            return;
        }

        foreach ($this->config->registerConcretes as $uniqueId => $config) {
            $this->fulcrum->registerConcrete($config, $uniqueId);
        }
    }

    /**
     * Load the configuration file.
     *
     * @since 3.0.0
     *
     * @param string|array $config
     *
     * @return mixed
     */
    protected function loadConfigFile($config)
    {
        if (is_array($config)) {
            return $config;
        }

        return require $config;
    }

    /*******************************
     * Activation Workers
     ******************************/

    /**
     * Plugin activation stuff.  When the plugin activates, we may need to handle flushing the rewrites for things
     * like custom post type and/or taxonomies.
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function activate()
    {
        if (!$this->config->has('pluginActivationKeys')) {
            return;
        }

        $this->addRewriteRules();

        foreach ($this->getActivationKeys() as $key) {
            $instance = $this->fulcrum[$key];

            $instance->register();
        }

        delete_option('rewrite_rules');
    }

    /**
     * If you need to add rewrite rules, overload this method.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function addRewriteRules()
    {
        // it's here if you need it.
    }

    /**
     * Get activation keys (getter).
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getActivationKeys()
    {
        if ($this->config->has('pluginActivationKeys')) {
            return $this->config->pluginActivationKeys;
        }

        return [];
    }
}
