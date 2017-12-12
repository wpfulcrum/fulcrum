<?php

namespace Fulcrum\Custom\PostType;

use Fulcrum\Config\ConfigContract;
use Fulcrum\Custom\PostType\Contract\ColumnsContract;

class Columns implements ColumnsContract
{
    /**
     * Instance of this post type's runtime configuration.
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Post type name (all lowercase & no spaces)
     *
     * @var string
     */
    protected $postType;

    /**
     * Internal flag if the columns_data is configured
     *
     * @var bool
     */
    private $isColumnsDataConfigured = false;

    /**
     * Internal flag if the sortable_data is configured
     *
     * @var bool
     */
    private $isSortableColumnsConfigured = false;

    /**
     * Internal flag if the sort_columns_by is configured
     *
     * @var bool
     */
    private $isSortColumnsByConfigured = false;

    /****************************
     * Instantiate & Initialize
     ***************************/

    /**
     * PostTypeSupports constructor.
     *
     * @since 3.0.0
     *
     * @param string $postType Post type name (all lowercase & no spaces).
     * @param ConfigContract $config Runtime configuration parameters.
     */
    public function __construct($postType, ConfigContract $config)
    {
        $this->config   = $config;
        $this->postType = $postType;
    }

    /**
     * Initialization this post type's columns.
     *
     * @since 3.0.0
     *
     * @return null
     */
    public function init()
    {
        $this->addColumnsFilter();

        $this->addColumnData();

        $this->initSorting();
    }

    /**
     * Initialized Config
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initConfig()
    {
        $this->isColumnsDataConfigured = $this->config->isArray('columnsData');

        $this->isSortableColumnsConfigured = $this->config->isArray('sortableColumns');

        $this->isSortColumnsByConfigured = $this->config->isArray('sortColumnsBy');
    }

    /**
     * Filter the data that shows up in the columns
     *
     * @since 3.0.0
     *
     * @param string $columnName The name of the column to display.
     * @param int $postId The current post ID.
     *
     * @return null|void
     * @throws ConfigurationException
     */
    public function columnsData($columnName, $postId)
    {
        $columnConfig = $this->getColumnConfig($columnName, $postId);
        if (false === $columnConfig) {
            return;
        }

        if (!$this->isCallbackCallable($columnConfig['callback'])) {
            return;
        }

        $response = call_user_func_array($columnConfig['callback'], $columnConfig['args']);
        if ($columnConfig['echo']) {
            echo $response;
        }
    }

    /**
     * Modify the columns for this custom post type.
     *
     * @since 3.0.0
     *
     * @param array $columns Array of Columns
     *
     * @return array Amended Array
     */
    public function columnsFilter($columns)
    {
        foreach ($this->config->columnsFilter as $column => $value) {
            if ('cb' == $column && true == $value) {
                $columns['cb'] = '<input type="checkbox" />';
            } else {
                $columns[$column] = $value;
            }
        }

        return $columns;
    }

    /**
     * Check if the column name is valid for our configuration
     *
     * @since 3.0.0
     *
     * @param string $columnName
     *
     * @return bool
     */
    protected function isColumnNameValid($columnName)
    {
        if (!$this->isColumnsDataConfigured) {
            return false;
        }

        if (!array_key_exists($columnName, $this->config->columnsData) ||
            !is_array($this->config->columnsData[$columnName])) {
            return false;
        }

        return !empty($this->config->columnsData[$columnName]) &&
               isset($this->config->columnsData[$columnName]['callback']) &&
               !empty($this->config->columnsData[$columnName]['callback']);
    }

    /**
     * Get the config for the injected column name
     *
     * @since 3.0.0
     *
     * @param string $columnName
     * @param int $postId
     *
     * @return array|bool
     */
    protected function getColumnConfig($columnName, $postId)
    {
        if (!$this->isColumnNameValid($columnName)) {
            return false;
        }

        $columnConfig = array_merge(
            [
                'callback' => '',
                'echo'     => true,
                'args'     => [],
            ],
            $this->config->columnsData[$columnName]
        );

        $columnConfig['args'][] = $postId;

        return $columnConfig;
    }

    /**
     * Add columns filter when it is configured for use.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function addColumnsFilter()
    {
        if ($this->config->has('columnsFilter') && $this->config->isArray('columnsFilter')) {
            add_filter("manage_{$this->postType}_posts_columns", [$this, 'columnsFilter']);
        }
    }

    /**
     * Add columns data when it is configured for use.
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function addColumnData()
    {
        if ($this->config->has('columnsData') && $this->config->isArray('columnsData')) {
            add_action("manage_{$this->postType}_posts_custom_column", [$this, 'columnsData'], 10, 2);
        }
    }

    /*****************************************************
     * Column Sorting Handlers
     ***************************************************/

    /**
     * Initialize the sorting features (i.e. to customize it).
     *
     * @since 3.0.0
     *
     * @return void
     */
    protected function initSorting()
    {
        if (!$this->isSortableColumnsConfigured) {
            return;
        }

        add_filter("manage_edit-{$this->postType}_sortable_columns", [$this, 'makeColumnsSortable']);

//		add_filter( 'request', array( $this, 'sort_columns_by' ), 50 );
    }

    /**
     * Filter for making the columns sortable
     *
     * @since  3.0.0
     *
     * @param  array $sortableColumns Sortable columns
     *
     * @return array Amended $sortableColumns
     */
    public function makeColumnsSortable($sortableColumns)
    {
        foreach (array_keys($this->config['sortableSolumns']) as $key) {
            $sortableColumns[$key] = $key;
        }

        return $sortableColumns;
    }

    /**
     * Sort columns by the configuration
     *
     * @since 3.0.0
     *
     * @param $vars
     *
     * @return mixed
     */
    public function sortColumnsBy($vars)
    {
        if (!isset($vars['post_type']) || $this->post_type !== $vars['post_type']) {
            return $vars;
        }

        //* TODO-Tonya Add code for sorting columns by
//        foreach( (array) $this->config['sort_columns_by'] as $key => $sc_vars) {
//            if ( isset( $vars['orderby'] ) && $sc_vars['meta_key'] == $vars['orderby'] ) {
//                // $vars = array_merge($vars, array(
//                //     'meta_key'  => $sc_vars['meta_key'],
//                //     'orderby'   => $sc_vars['orderby']
//                // ));
//            }
//        }

        return $vars;
    }

    /**
     * Checks if the callback is callback
     *
     * @since 3.0.0
     *
     * @param string $callback
     *
     * @return bool
     * @throws ConfigurationException
     */
    protected function isCallbackCallable($callback)
    {
        if (is_callable($callback)) {
            return true;
        }

        throw new ConfigurationException(
            sprintf(
                __(
                    'The callback [%s], for the custom post type [%s], was not found, as call_user_func_array() expects a valid callback function/method.',  // @codingStandardsIgnoreLine - Generic.Files.LineLength.TooLong
                    'fulcrum'
                ),
                $callback,
                $this->postType
            )
        );
    }
}
