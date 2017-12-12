<?php

namespace Fulcrum\Custom\PostType\Contract;

interface ColumnsContract
{
    /**
     * Initialization this post type's columns.
     *
     * @since 3.0.0
     *
     * @return null
     */
    public function init();

    /**
     * Modify the columns for this custom post type.
     *
     * @since 3.0.0
     *
     * @param array $columns Array of Columns
     *
     * @return array
     */
    public function columnsFilter($columns);

    /**
     * Filter the data that shows up in the columns.
     *
     * @since 3.0.0
     *
     * @param string $columnName The name of the column to display.
     * @param int $postId The current post ID.
     *
     * @return null
     *
     * @throws ConfigurationException
     */
    public function columnsData($columnName, $postId);

    /**
     * Filter for making the columns sortable.
     *
     * @since  3.0.0
     *
     * @param array $sortableColumns Sortable columns
     *
     * @return array
     */
    public function makeColumnsSortable($sortableColumns);

    /**
     * Sort columns by the configuration.
     *
     * @since 3.0.0
     *
     * @param $vars
     *
     * @return mixed
     */
    public function sortColumnsBy($vars);
}
