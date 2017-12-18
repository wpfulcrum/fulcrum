<?php
/**
 * Fulcrum Plugin
 *
 * @package         Fulcrum
 * @author          hellofromTonya
 * @license         MIT
 * @link            https://github.com/wpfulcrum/fulcrum
 *
 * @wordpress-plugin
 * @codingStandardsIgnoreStart
 * Plugin Name:     Fulcrum Plugin
 * Plugin URI:      https://github.com/wpfulcrum/fulcrum
 * Description:     Fulcrum - The customization central repository to extend and custom WordPress. This plugin provides the centralized infrastructure for the custom plugins and theme.
 * Version:         3.0.0
 * Author:          hellofromTonya
 * Author URI:      https://github.com/wpfulcrum/fulcrum
 * Text Domain:     fulcrum
 * Requires WP:     4.9
 * Requires PHP:    5.6
 * @codingStandardsIgnoreEnd
 */

namespace Fulcrum;

use Fulcrum\Config\ConfigFactory;

if (!defined('ABSPATH')) {
    exit('Cheatin&#8217; uh?');
}

require_once __DIR__ . '/vendor/autoload.php';

fulcrum_declare_plugin_constants('FULCRUM', __FILE__);

/**
 * Launch the plugin.
 *
 * @since 3.0.0
 *
 * @return FulcrumContract
 */
function launch()
{
    return new Fulcrum(
        ConfigFactory::create(__DIR__ . '/config/plugin.php')
    );
}

launch();
