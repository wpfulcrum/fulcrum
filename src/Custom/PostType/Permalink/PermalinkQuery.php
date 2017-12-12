<?php
/**
 * Handler for rewrites and WP_Query for custom post type when you want to put
 * the term into the URL, e.g. domain.com/custom_post_type/term/postname.
 *
 * This handler does the following:
 *
 * 1. Allows duplicate postname slugs when in different terms but same custom_post_type.
 * 2.
 *
 * Note: The extra steps for manipulating the $wp_query is because of the duplicate post name slugs.  Without
 * these, when you go to domain.com/custom_post_type/term/postname, you may get more than one post (when you
 * only want the one article).
 *
 * @package     Fulcrum\Custom\PostType\Permalink
 * @since       3.0.0
 * @author      hellofromTonya
 * @link        https://github.com/hellofromtonya/Fulcrum
 * @license     GPL-2.0+
 */

namespace Fulcrum\Custom\PostType\Permalink;

use Fulcrum\Config\ConfigContract;
use WP_Query;

class PermalinkQuery
{
    /**
     * Runtime configuration parameters.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Custom post type
     *
     * @var string
     */
    protected $customPostType;

    /**
     * Taxonomy
     *
     * @var string
     */
    protected $taxonomy;

    /**
     * Taxonomy Query SQL (comes from WP_Tax_Query)
     *
     * @var array
     */
    protected $taxQuerySql = [];

    /**
     * Post_Name_Slug_Handler constructor.
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config         = $config;
        $this->customPostType = $config->customPostType;
        $this->taxonomy       = $config->taxonomy;
    }

    /**
     * Initialize the events.
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function initEvents()
    {
        add_action('pre_get_posts', [$this, 'addTaxTermsToQueryHandler']);
    }

    /**
     * Handle adding in the proper query_vars elements.
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return void
     */
    public function addTaxTermsToQueryHandler(WP_Query $query)
    {
        if (!$this->okToAddTaxTermsQueryVars($query)) {
            $this->add_sort_order($query);

            return;
        }

        $this->add_tax_terms_query_vars($query);

        $this->register_callbacks_for_taxQuerySql_events();
    }

    /**
     * Handle adding in the proper query_vars elements.
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return void
     */
    public function addTaxTermsQueryVars(WP_Query $query)
    {
        $query->query_vars['taxonomy']  = $this->taxonomy;
        $query->query_vars['terms']     = $query->query[$this->taxonomy];
        $query->query_vars['tax_query'] = [
            [
                'taxonomy' => $this->taxonomy,
                'field'    => 'slug',
                'terms'    => $query->query[$this->taxonomy],
            ],
        ];
        $query->query_vars['orderby']   = 'title';
    }

    /**
     * Register the callbacks for the tax query SQL events.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function registerCallbacksForTaxQuerySqlEvents()
    {
        add_filter('posts_where', [$this, 'addTaxQuerySql'], 10, 2);

        add_filter('posts_join', [$this, 'addTaxQuerySql'], 10, 2);
    }

    /**
     * With a single article, no tax_query is called organically out of `WP_Query`.  It skips over
     * the tax sections because it's a single, i.e. `is_single()`.  Therefore, we need to add in
     * the where and join SQL statements to the `$query->request`.
     *
     * This callback hooks into both `posts_where` and `posts_join` filters.
     *
     * @since 3.0.0
     *
     * @param string $clause
     * @param WP_Query $query Instance of WP_Query
     *
     * @return string
     */
    public function addTaxQuerySql($clause, WP_Query $query)
    {
        if (!$this->isSingleQuery($query)) {
            return $clause;
        }

        $which_clause = current_filter() === 'posts_where' ? 'where' : 'join';

        $this->initTaxQuerySql($query);

        if (is_array($this->taxQuerySql) && array_key_exists($which_clause, $this->taxQuerySql)) {
            $clause .= $this->taxQuerySql[$which_clause];
        }

        return $clause;
    }

    /**
     * Sort the query by title and ascending.
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return void
     */
    public function addSortOrder(WP_Query $query)
    {
        if (!$this->isMainFrontEndQuery($query)) {
            return;
        }

        if (!$query->is_archive() || !$this->isCustomSingle($query)) {
            return;
        }

        $query->query_vars['orderby']                = 'title';
        $query->query_vars['order']                  = 'ASC';
        $query->query_vars['posts_per_archive_page'] = -1;
        $query->query_vars['posts_per_page']         = -1;
    }

    /**
     * Checks if this query is the main front-end one.
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return bool
     */
    public function isMainFrontEndQuery(WP_Query $query)
    {
        return !is_admin() && $query->is_main_query();
    }

    /**
     * Checks if this query is the one we want to add in the tax and terms query_vars.
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return bool
     */
    public function okToAddTaxTermsQueryVars(WP_Query $query)
    {
        if (!$this->isMainFrontEndQuery($query) || $query->is_archive) {
            return false;
        }

        return $this->isCustomSingle($query) &&
               !array_key_exists('tax_query', $query->query_vars) &&
               empty($query->tax_query);
    }

    /**
     * Checks if this query is a single query..
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return bool
     */
    protected function isSingleQuery(WP_Query $query)
    {
        if (is_admin() || !is_single()) {
            return false;
        }

        return $this->isCustomSingle($query) &&
               $this->taxonomy == $query->query_vars['taxonomy'] &&
               ['terms', $query->query_vars];
    }

    /**
     * Checks if this query is our custom single.
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return bool
     */
    protected function isCustomSingle(WP_Query $query)
    {
        return array_key_exists('post_type', $query->query) &&
               $this->customPostType == $query->query['post_type'] &&
               array_key_exists($this->taxonomy, $query->query);
    }

    /**
     * Initialize the tax_query parameters within the $query.  We need to do this in order
     * to populate the query and grab the SQL array (which is used in the filters above).
     *
     * @since 3.0.0
     *
     * @param WP_Query $query
     *
     * @return void
     */
    protected function initTaxQuerySql(WP_Query $query)
    {
        if (!empty($this->taxQuerySql)) {
            return;
        }
        global $wpdb;

        $query->parse_tax_query($query->query_vars);

        $this->taxQuerySql = $query->tax_query->get_sql($wpdb->posts, 'ID');
    }
}
