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
    'initial_parameters' => [
        'is_dev_env'         => defined('FULCRUM_ENV') && FULCRUM_ENV === 'dev',
        'fulcrum.plugin_dir' => FULCRUM_PLUGIN_DIR,
        'fulcrum.plugin_url' => FULCRUM_PLUGIN_URL,
        'fulcrum.config_dir' => FULCRUM_PLUGIN_DIR . 'config/',
    ],

    /*********************************************************
     * Handlers - Handlers need to be loaded first as they
     * handle registering the service providers.
     ********************************************************/
    'handlers'           => [
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
    'service_providers'  => [
        'provider.asset'             => 'Fulcrum\Asset\AssetProvider',
        'provider.postType'          => 'Fulcrum\Custom\PostType\PostTypeProvider',
        'provider.postTypePermalink' => 'Fulcrum\Custom\PostType\Permalink\PermalinkProvider',
        'provider.shortcode'         => 'Fulcrum\Custom\Shortcode\ShortcodeProvider',
        'provider.taxonomy'          => 'Fulcrum\Custom\Taxonomy\TaxonomyProvider',
        'provider.template'          => 'Fulcrum\Custom\Template\TemplateProvider',
        'provider.widget'            => 'Fulcrum\Custom\Widget\WidgetProvider',
    ],

    'admin_service_providers' => [
//		'provider.metabox' => 'Fulcrum\Custom\Meta\MetaboxProvider',
//        'provider.schema' => 'Fulcrum\Database\SchemaProvider',
    ],
];
