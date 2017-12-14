<?php

namespace Fulcrum\Custom\Template;

use Fulcrum\Config\ConfigContract;

class AdminTemplate
{
    /**
     * Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /***************************
     * Instantiate & Initialize
     **************************/

    /**
     * Instantiate the Template Manager.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config Runtime configuration parameters.
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;

        if ($this->config->usePageTemplates === true && !empty($this->config->templates)) {
            $this->initEvents();
        }
    }

    /**
     * Wire up the callbacks to the event hooks.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initEvents()
    {
        // 4.6 and older
        if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {
            add_filter('page_attributes_dropdown_pages_args', [$this, 'registerTemplates'], 50);
        } else {
            add_filter('theme_page_templates', [$this, 'mergeTemplates'], 50);
        }

        add_filter('wp_insert_post_data', [$this, 'registerTemplates'], 50);
    }

    /**
     * Register plugin's templates into the Dropdown list for Page Templates
     *
     * Hooked into {@see 'page_attributes_dropdown_pages_args'}
     * Filter the arguments used to generate a Pages drop-down element.
     *
     * Refer to {@see WP_Theme->get_page_templates()} for more details and handling.
     *
     * @since 3.0.0
     *
     * @param array $args Array of arguments used to generate the pages drop-down.
     *
     * @return array
     */
    public function registerTemplates(array $args)
    {
        // We have to deal with the cache.
        $cacheKey = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        $pageTemplates = wp_cache_get($cacheKey, 'themes');
        if (empty($pageTemplates)) {
            $pageTemplates = [];
        }
        wp_cache_delete($cacheKey, 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $pageTemplates = $this->mergeTemplates($pageTemplates);

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cacheKey, $pageTemplates, 'themes', 1800);

        return $args;
    }

    /**
     * Merge plugin templates to register with WordPress.
     *
     * @since 3.0.0
     *
     * @param array $pageTemplates Array of templates.
     *
     * @return array
     */
    public function mergeTemplates(array $pageTemplates)
    {
        return array_merge($pageTemplates, $this->config->templates);
    }
}
