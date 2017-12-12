<?php

namespace Fulcrum\Custom\Taxonomy;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Custom\Taxonomy\Contract\LabelsBuilderContract;
use Fulcrum\Custom\Taxonomy\Contract\TaxonomyContract;

class Taxonomy implements TaxonomyContract
{
    /**
     * Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Taxonomy name (all lowercase & no spaces)
     *
     * @var string
     */
    protected $taxonomyName;

    /**
     * Name of the object type for the taxonomy object
     *
     * @var string|array
     */
    protected $objectType;

    /**
     * Instance of the labels builder.
     *
     * @var LabelsBuilderContract
     */
    protected $labelsBuilder;

    /**
     * Built registration arguments.
     *
     * @var array
     */
    protected $registrationArgs;

    /****************************
     * Instantiate & Initialize
     ***************************/

    /**
     * Taxonomy constructor.
     *
     * @since 3.0.0
     *
     * @param $taxonomyName Taxonomy name (all lowercase & no spaces)
     * @param ConfigContract $config Runtime configuration parameters.
     * @param LabelsBuilderContract $labelsBuilder Instance of the labels builder.
     */
    public function __construct($taxonomyName, ConfigContract $config, LabelsBuilderContract $labelsBuilder)
    {
        Validator::run($taxonomyName, $config);

        $this->taxonomyName  = $taxonomyName;
        $this->objectType    = $config->get('objectType');
        $this->config        = $config;
        $this->labelsBuilder = $labelsBuilder;

        $this->initEvents();
    }

    /**
     * Remove this CPT from the post types upon object destruct
     *
     * @since 3.0.0
     *
     * @uses global $wp_post_type
     * @return null
     */
    public function __destruct()
    {
        global $wp_taxonomies;

        if (isset($wp_taxonomies[$this->taxonomyName])) {
            unset($wp_taxonomies[$this->taxonomyName]);
        }
    }

    /**
     * Setup Hooks & Filters
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initEvents()
    {
        add_action('init', [$this, 'register']);
    }

    /*****************************************************
     * Public Methods
     ***************************************************/

    /**
     * Time to register this taxonomy
     *
     * @since 1.0.1
     *
     * @uses self::build_args() to build up the args needed to register this taxonomy
     * @return null
     */
    public function register()
    {
        $this->buildArgs();

        return register_taxonomy($this->taxonomyName, $this->objectType, $this->registrationArgs);
    }

    /****************
     * Helpers
     ****************/

    /**
     * Build the args for the register_taxonomy
     *
     * @since 3.0.0
     */
    protected function buildArgs()
    {
        $this->registrationArgs = $this->config->get('args');

        $this->registrationArgs['labels'] = $this->labelsBuilder->build();
    }
}
