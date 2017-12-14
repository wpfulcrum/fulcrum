<?php
/**
 * Fulcrum runtime configuration parameters.
 *
 * @package     Fulcrum
 * @since       3.0.0
 * @author      hellofromTonya
 * @link        https://github.com/wpfulcrum/fulcrum
 * @license     MIT
 */

namespace Fulcrum;

return [

    /*********************************************************
     * Initial Core Parameters, which are loaded into the
     * Container before anything else occurs.
     *
     * Format:
     *    $unique_id => $value
     ********************************************************/
    'initialParameters' => [
        'isDevEnv'           => defined('FULCRUM_ENV') && FULCRUM_ENV === 'dev',
        'fulcrum.pluginPath' => FULCRUM_PLUGIN_DIR,
        'fulcrum.pluginUrl'  => FULCRUM_PLUGIN_URL,
        'fulcrum.configDir'  => FULCRUM_PLUGIN_DIR . 'config/',
    ],

    /*********************************************************
     * Handlers - Handlers need to be loaded first as they
     * handle registering the service providers.
     ********************************************************/
    'handlers'          => [
        'provider.handler' => [
            'autoload' => true,
            'concrete' => function ($container) {
                return new ServiceProvidersManager($container['fulcrum']);
            },
        ],
    ],

    /*********************************************************
     * Service Providers - these providers are the object factories for the
     * add-on plugins and theme to use.
     ********************************************************/
    'serviceProviders'  => [
//        'provider.asset'             => 'Fulcrum\Asset\AssetProvider',
        'provider.postType'          => 'Fulcrum\Custom\PostType\PostTypeProvider',
        'provider.postTypePermalink' => 'Fulcrum\Custom\PostType\Permalink\PermalinkProvider',
        'provider.shortcode'         => 'Fulcrum\Custom\Shortcode\ShortcodeProvider',
        'provider.taxonomy'          => 'Fulcrum\Custom\Taxonomy\TaxonomyProvider',
        'provider.template'          => 'Fulcrum\Custom\Template\TemplateLoaderProvider',
        'provider.adminTemplate'     => 'Fulcrum\Custom\Template\AdminProvider',
        'provider.widget'            => 'Fulcrum\Custom\Widget\WidgetProvider',
    ],

    'adminServiceProviders' => [],
];
