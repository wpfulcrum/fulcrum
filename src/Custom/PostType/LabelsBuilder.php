<?php

namespace Fulcrum\Custom\PostType;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Custom\PostType\Contract\LabelsBuilderContract;

class LabelsBuilder implements LabelsBuilderContract
{
    /**
     * Instance of this post type's runtime configuration.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Array of labels for this post type.
     *
     * @var array
     */
    protected $labels = [];

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
     * Build the labels for this post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function build()
    {
        if ($this->config->useBuilder !== true) {
            $this->labels = $this->config->labels;
            return $this->labels;
        }

        $this->labels = $this->getDefaultLabels();

        if ($this->areLabelsConfigured()) {
            $this->labels = array_merge($this->labels, $this->config->labels);
        }

        return $this->labels;
    }

    /**
     * Gets the array of labels for this post type.
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /*****************************************************
     * Helpers
     ***************************************************/

    protected function getDefaultLabels()
    {
        return [
            'name'               => $this->config->pluralName,
            'singular_name'      => $this->config->singularName,
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New ' . $this->config->singularName,
            'edit_item'          => 'Edit ' . $this->config->singularName,
            'new_item'           => 'New ' . $this->config->singularName,
            'view_item'          => 'View ' . $this->config->singularName,
            'search_items'       => 'Search ' . $this->config->singularName,
            'not_found'          => sprintf('No %s found', strtolower($this->config->singularName)),
            'not_found_in_trash' => sprintf('No %s found in Trash', strtolower($this->config->pluralName)),
            'parent_item_colon'  => '',
            'all_items'          => 'All ' . $this->config->pluralName,
            'menu_name'          => $this->config->pluralName,
        ];
    }

    /**
     * Checks if the labels are configured.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function areLabelsConfigured()
    {
        if (!$this->config->has('labels')) {
            return false;
        }

        if (!$this->config->isArray('labels')) {
            return false;
        }

        return !empty($this->config->labels);
    }
}
