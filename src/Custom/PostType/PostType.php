<?php

namespace Fulcrum\Custom\PostType;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Custom\PostType\Contract\ColumnsContract;
use Fulcrum\Custom\PostType\Contract\LabelsBuilderContract;
use Fulcrum\Custom\PostType\Contract\PostTypeContract;
use Fulcrum\Config\Fulcrum;
use Fulcrum\Custom\PostType\Contract\SupportedFeaturesContract;
use InvalidArgumentException;

class PostType implements PostTypeContract
{
    /**
     * Instance of this post type's runtime configuration.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Post type name (all lowercase & no spaces).
     *
     * @var string
     */
    protected $postType;

    /**
     * Instance of this post type's supported features handler.
     *
     * @var SupportedFeaturesContract
     */
    protected $supportedFeatures;

    /**
     * Instance of this post type's admin columns handler.
     *
     * @var ColumnsContract
     */
    protected $columns;

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
     * PostType constructor.
     *
     * @since 3.0.0
     *
     * @param string $postType Post type name (all lowercase & no spaces).
     * @param ConfigContract $config Runtime configuration parameters.
     * @param ColumnsContract $columns Admin columns handler.
     * @param SupportedFeaturesContract $supportedFeatures Instance of the post type supports handler.
     * @param LabelsBuilderContract $labelsBuilder Instance of the labels builder.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        $postType,
        ConfigContract $config,
        ColumnsContract $columns,
        SupportedFeaturesContract $supportedFeatures,
        LabelsBuilderContract $labelsBuilder
    ) {
        if (!Validator::isValid($postType, $config)) {
            return;
        }

        $this->config            = $config;
        $this->columns           = $columns;
        $this->postType          = $postType;
        $this->supportedFeatures = $supportedFeatures;
        $this->labelsBuilder     = $labelsBuilder;
        $this->init();
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
        global $wp_post_types;

        if (isset($wp_post_types[$this->postType])) {
            unset($wp_post_types[$this->postType]);
        }
    }

    /**
     * Initialize this post type.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function init()
    {
        add_action('init', [$this, 'register']);

        $this->columns->init();
    }

    /*****************************************************
     * Register Methods
     ***************************************************/

    /**
     * Register Custom Post Type
     *
     * @since 3.0.0
     *
     * @uses self::buildArgs() Builds up the needed args from defaults & configuration
     *
     * @return null
     */
    public function register()
    {
        $this->buildArgs();

        register_post_type($this->postType, $this->registrationArgs);
    }

    /**
     * Get all of the supports
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function getTheSupports()
    {
        return $this->supportedFeatures->getSupports();
    }

    /*****************************************************
     * Helper Methods
     ***************************************************/

    /**
     * Build the args for the register_post_type
     *
     * @since 3.0.0
     */
    protected function buildArgs()
    {
        $this->registrationArgs = $this->config->all();

        $this->registrationArgs['labels']   = $this->labelsBuilder->build();
        $this->registrationArgs['supports'] = $this->supportedFeatures->build();

        $this->convertTaxonomyIntoArray();
    }

    /*****************************************************
     * Taxonomy Methods
     ***************************************************/

    /**
     * Checks and, if necessary, converts the taxomony(ies) into an array.
     *
     * @since 3.0.0
     */
    protected function convertTaxonomyIntoArray()
    {
        if (!$this->config->has('taxonomies')) {
            return;
        }

        if (empty($this->config->taxonomies)) {
            return;
        }

        if (!is_string($this->config->taxonomies)) {
            return;
        }

        $this->registrationArgs['taxonomies'] = explode(',', $this->config->taxonomies);
    }
}
