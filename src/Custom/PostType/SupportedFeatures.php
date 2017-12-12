<?php

namespace Fulcrum\Custom\PostType;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Custom\PostType\Contract\SupportedFeaturesContract;

class SupportedFeatures implements SupportedFeaturesContract
{
    /**
     * Instance of this post type's runtime configuration.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Default supports.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Array of supported features.
     *
     * @var array
     */
    protected $supports = [];

    /****************************
     * Instantiate & Initialize
     ***************************/

    /**
     * PostTypeSupports constructor.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config Runtime configuration parameters.
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }

    /**
     * Build the supports argument.  If it is not configured, then grab all of the
     * supports from the built-in 'post' post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function build()
    {
        if (isset($this->config->supports)) {
            $this->supports = $this->config->supports;
            $this->addPageAttributes();
            return $this->supports;
        }

        $this->buildSupportsByConfiguration();
        return $this->supports;
    }

    /**
     * Gets the array of supports for this post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getSupports()
    {
        return $this->supports;
    }

    /*****************************************************
     * Helpers
     ***************************************************/

    /**
     * Build the supports from the configuration.  The starting defaults are from the 'post' supports.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function buildSupportsByConfiguration()
    {
        $this->supports = get_all_post_type_supports('post');

        if ($this->areAdditionalSupportsEnabled()) {
            $this->supports = array_merge($this->supports, $this->config->additionalSupports);
        }

        $this->filterOutExcludedSupports();

        $this->supports = array_keys($this->supports);

        $this->addPageAttributes();
    }

    /**
     * Adds the 'page-attributes' support when required.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function addPageAttributes()
    {
        if ($this->isPageAttributesSupportRequired()) {
            $this->supports[] = 'page-attributes';
        }
    }

    /**
     * Filters out the unwanted (excluded) supports.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function filterOutExcludedSupports()
    {
        $this->supports = array_filter($this->supports, function ($includeSupport) {
            return $includeSupport;
        });
    }

    /*****************************************************
     * State Checkers
     ***************************************************/

    /**
     * Checks if the exclude_supports parameter is configured.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function areAdditionalSupportsEnabled()
    {
        return $this->config->has('additionalSupports') &&
               $this->config->isArray('additionalSupports');
    }

    /**
     * Checks if the page-attributes support is required.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function isPageAttributesSupportRequired()
    {
        if (!$this->isHierarchical()) {
            return false;
        }

        return empty($this->config->supports) ||
               !in_array('page-attributes', $this->config->supports);
    }

    /**
     * Checks if this post type is hierarchical.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function isHierarchical()
    {
        if (!$this->config->has('hierarchical')) {
            return false;
        }
        return $this->config->hierarchical === true;
    }
}
