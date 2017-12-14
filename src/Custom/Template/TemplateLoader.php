<?php

namespace Fulcrum\Custom\Template;

use Fulcrum\Config\ConfigContract;

class TemplateLoader
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
     * Instantiate the Template Loader - empowering plugins to load templates.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config Runtime configuration parameters.
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;

        $this->initEvents();
    }

    /**
     * Wire up to the {@see "template_include"} filter to let plugins load templates.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initEvents()
    {
        add_filter('template_include', [$this, 'includeTemplate']);
    }

    /**
     * Pass back the template file to the front-end loader.
     *
     * @since 3.0.0
     *
     * @param string $template
     *
     * @return string
     */
    public function includeTemplate($template)
    {
        // If this template is a "index.php" file, just return it.
        if (str_ends_with($template, 'index.php')) {
            return $template;
        }

        if (is_tax()) {
            return $this->getTaxTemplate($template);
        }

        if (is_page()) {
            return $this->getPageTemplate($template);
        }

        if (is_single()) {
            return $this->getSingleTemplate($template);
        }

        if (is_archive()) {
            return $this->getArchiveTemplate($template);
        }

        return $template;
    }

    /**************************
     * Template Getters
     *************************/

    /**
     * Get the taxonomy template, if it's available in the plugin. Else, return the original.
     *
     * @since 3.0.0
     *
     * @param string $originalTemplate Original template passed to us.
     *
     * @return string
     */
    protected function getTaxTemplate($originalTemplate)
    {
        if ($this->config->useTax !== true) {
            return $originalTemplate;
        }

        if ($this->config->has('taxonomy') && is_tax($this->config->taxonomy)) {
            return $this->locateTemplate($originalTemplate, sprintf('taxonomy-%s.php', $this->config->taxonomy));
        }

        return $originalTemplate;
    }

    /**
     * Get the page's template, if it's available in the plugin. Else, return the original.
     *
     * @since 3.0.0
     *
     * @param string $originalTemplate Original template passed to us.
     *
     * @return string
     */
    protected function getPageTemplate($originalTemplate)
    {
        // Bail out if this plugin is not configured for page templates.
        if ($this->config->usePageTemplates !== true) {
            return $originalTemplate;
        }

        $postId = $this->getPostId();
        if ($postId < 1) {
            return $originalTemplate;
        }

        // The web page's template is stored in its post meta. Go get it.
        $currentWebPageTemplate = get_post_meta($postId, '_wp_page_template', true);
        if (empty($currentWebPageTemplate)) {
            return $originalTemplate;
        }

        // If the plugin has this template, then return it.
        $pluginTemplate = $this->config->templateFolderPath . $currentWebPageTemplate;
        if (is_readable($pluginTemplate)) {
            return $pluginTemplate;
        }

        return $originalTemplate;
    }

    /**
     * Get the single template, if it's available in the plugin. Else, return the original.
     *
     * @since 3.0.0
     *
     * @param string $originalTemplate Original template passed to us.
     *
     * @return string
     */
    protected function getSingleTemplate($originalTemplate)
    {
        if ($this->config->useSingle !== true) {
            return $originalTemplate;
        }

        if (!$this->isPostTypeConfigured()) {
            return $originalTemplate;
        }

        return $this->locateTemplate($originalTemplate, sprintf('single-%s.php', $this->config->postType));
    }

    /**
     * Get the post type's template, if it's available in the plugin. Else, return the original.
     *
     * @since 3.0.0
     *
     * @param string $originalTemplate Archive template's slug.
     *
     * @return string
     */
    protected function getArchiveTemplate($originalTemplate)
    {
        if ($this->config->useArchive !== true) {
            return $originalTemplate;
        }

        if (!$this->isPostTypeConfigured()) {
            return $originalTemplate;
        }

        if (is_post_type_archive($this->config->postType)) {
            return $this->locateTemplate($originalTemplate, sprintf('archive-%s.php', $this->config->postType));
        }

        return $originalTemplate;
    }

    /**************************
     * Internal Helpers
     *************************/

    /**
     * Get the Post's ID from the global `$post`.
     *
     * @since 3.0.0
     *
     * @global $post
     * @return int
     */
    private function getPostId()
    {
        global $post;

        if (empty($post)) {
            return 0;
        }
        return (int)$post->ID;
    }

    /**
     * Checks if the post type is configured.
     *
     * @since 3.0.0
     *
     * @param int|null $postId (Optional) ID of the current post.
     *
     * @return bool
     */
    protected function isPostTypeConfigured($postId = null)
    {
        if (empty($this->config->postType)) {
            return false;
        }

        if (is_null($postId)) {
            $postId = $this->getPostId();
        }
        if ($postId < 1) {
            return false;
        }

        return ($this->config->postType === (string)get_post_type($postId));
    }

    /**
     * Locate the template in either the theme or plugin.  The theme overrides the plugin.
     *
     * @since 3.0.0
     *
     * @param string $originalTemplate Original template passed to us.
     * @param string $templateFilename (Optional) Name of the template file to search for in the plugin.
     *
     * @return string
     */
    private function locateTemplate($originalTemplate, $templateFilename = '')
    {
        if (empty($templateFilename)) {
            $templateFilename = $this->buildTemplateFilePathAndName(
                $this->extractTemplateSlugFromFullpath($originalTemplate)
            );
        }

        // The theme should override the plugin.
        $themeTemplate = $this->getThemeTemplate($originalTemplate);
        if (!empty($themeTemplate)) {
            return $themeTemplate;
        }

        // If the plugin has this template, return it.
        $pluginTemplate = $this->config->templateFolderPath . $templateFilename;
        if (is_readable($pluginTemplate)) {
            return $pluginTemplate;
        }

        // Whoops, not in the theme or plugin.  Just return the original.
        return $originalTemplate;
    }

    /**
     * Build the templates full path and filename
     *
     * @since 3.0.0
     *
     * @param string $templateSlug
     *
     * @return string
     */
    private function buildTemplateFilePathAndName($templateSlug)
    {
        $postType = $this->config->postType;

        if (is_array($postType)) {
            global $post;
            $postType = get_post_type($post->ID);
        }

        return $templateSlug . '-' . $postType . '.php';
    }

    /**
     * Extract template's slug from the fullpath
     *
     * @since 3.0.0
     *
     * @param string $templateFullpath
     *
     * @return string
     */
    private function extractTemplateSlugFromFullpath($templateFullpath)
    {
        $parts    = explode('/', $templateFullpath);
        $template = array_pop($parts);

        return rtrim($template, '.php');
    }

    /**
     * If the theme has the template, return it.
     *
     * @since 3.0.0
     *
     * @param string $template Template to search for in the theme.
     *
     * @return string|void
     */
    private function getThemeTemplate($template)
    {
        $themeTemplate = locate_template([$template]);

        if ($themeTemplate && is_readable($themeTemplate)) {
            return $themeTemplate;
        }
    }
}
