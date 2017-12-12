<?php

namespace Fulcrum\Custom\Taxonomy;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Custom\Taxonomy\Contract\LabelsBuilderContract;

class LabelsBuilder implements LabelsBuilderContract
{
    /**
     * Instance of this taxonomy's runtime configuration.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Array of labels for this taxonomy.
     *
     * @var array
     */
    protected $labels = [];

    /****************************
     * Instantiate & Initialize
     ***************************/

    /**
     * LabelsBuilder constructor.
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
            $this->labels = $this->config->get('labels');
            return $this->labels;
        }

        $this->labels = $this->getDefaultLabels();

        if ($this->areLabelsConfigured()) {
            $this->labels = array_merge($this->labels, $this->config->get('labels'));
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
            'name'                       => $this->config->pluralName,
            'singular_name'              => $this->config->singularName,
            'menu_name'                  => $this->config->pluralName,
            'all_items'                  => sprintf('All %s', $this->config->pluralName),
            'edit_item'                  => sprintf('Edit %s', $this->config->singularName),
            'view_item'                  => sprintf('View %s', $this->config->singularName),
            'update_item'                => sprintf('Update %s', $this->config->singularName),
            'add_new_item'               => sprintf('Add New %s', $this->config->singularName),
            'new_item_name'              => sprintf('New %s', $this->config->singularName),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'search_items'               => sprintf('Search %s', $this->config->pluralName),
            'popular_items'              => sprintf('Popular %s', $this->config->pluralName),
            'separate_items_with_commas' => null,
            'add_or_remove_items'        => null,
            'choose_from_most_used'      => null,
            'not_found'                  => sprintf(
                __('No %s found', 'fulcrum'),
                strtolower($this->config->singularName)
            ),
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
