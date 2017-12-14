<?php

namespace Fulcrum;

if (!defined('ABSPATH')) {
    wp_die("Oh, silly, there's nothing to see here.");
}

use Fulcrum\Config\ConfigContract;
use Fulcrum\Container\DIContainer;

class Fulcrum extends DIContainer implements FulcrumContract
{
    /**
     * The plugin's version.
     *
     * @var string
     */
    const VERSION = '3.0.0';

    /**
     * The plugin's minimum WordPress requirement.
     *
     * @var string
     */
    const MIN_WP_VERSION = '4.9';

    /**
     * Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Instance of Fulcrum
     *
     * @var FulcrumContract
     */
    public static $fulcrum;

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

    public static function getFulcrum()
    {
        return self::$fulcrum;
    }

    /**************************
     * Instantiate & Initialize
     *************************/

    /**
     * Instantiate the plugin
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
        parent::__construct();

        $this['fulcrum']                     = self::$fulcrum = $this;
        $this['isFlushRewriteRulesRequired'] = false;

        $this->loadInitialParameters($config);
        $this->initHandlers();
        $this->initServiceProviders();
        $this->initEvents();
    }

    public function loadInitialParameters(ConfigContract $config)
    {
        if (!$config->isArray('initialParameters')) {
            return;
        }

        array_walk(
            $config->initialParameters,
            function ($value, $uniqueId) {
                $this[$uniqueId] = $value;
            }
        );
    }

    protected function initHandlers()
    {
        $config = $this->config->handlers;

        if ($this->isDevEnv()) {
            $config = array_merge($config, $this->config->devEnv['handlers']);
        }

        array_walk($config, function ($concrete, $uniqueId) {
            $this->registerConcrete($concrete, $uniqueId);
        });
    }

    protected function initServiceProviders()
    {
        $this['provider.handler']->register($this->config->serviceProviders);

        if (is_admin()) {
            $this['provider.handler']->register($this->config->adminServiceProviders);
        }
    }

    protected function initEvents()
    {
        add_action('plugins_loaded', [$this, 'loadAddOns'], 1);
        add_filter('pre_update_option_rewrite_rules', [$this, 'initPluginRewritesAndFlush'], 1);
    }

    /***************
     * Public
     *************/

    public function loadAddOns()
    {
        do_action('fulcrum_is_loaded', $this);
    }

    /**
     * If a flush_rewrite_rules is in process, we run our rewrite event
     * to ensure the rewrite rules and tasks are included.
     *
     * Why not use the activation/deactivation process?  There are far too
     * many places in WordPress where flush_rewrite_rules() is triggered.
     * When that happens, it may not include our rewrite rules because of the
     * way we are adding them. To account for all possible scenarios (like
     * saving permalinks, upgrade, and plugin management), we are registered
     * to the actual flush_rewrite_rules mechanism.
     *
     * @since 3.0.0
     *
     * @param mixed $value Rewrite rules value
     *
     * @return mixed
     */
    public function initPluginRewritesAndFlush($value)
    {
        if (!$value) {
            do_action('fulcrum_init_rewrites');
        }

        return $value;
    }

    /**
     * Checks if this site is in development environment.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    public function isDevEnv()
    {
        return $this['isDevEnv'];
    }
}
