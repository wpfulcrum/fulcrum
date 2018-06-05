<?php

namespace Fulcrum\Database;

use Fulcrum\Config\ConfigContract;

class Schema
{
    /**
     * Runtime configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Db Version Option Key
     *
     * @var string
     */
    protected $optionName = '';

    /**
     * Charset Collate
     *
     * @var string
     */
    protected $charsetCollate;

    /**
     * Use Seed Tables when creating
     *
     * @var bool
     */
    protected $useSeedTables = false;

    /**
     * Path to the Seed Data
     *
     * @var string
     */
    protected $seedDataPath;

    /*****************************
     * Instantiate & Initializers
     ****************************/

    /**
     * Schema constructor.
     *
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
        $this->initProperties();

        if ($this->hasVersionChanged()) {
            $this->updateSchema();
        }
    }

    /**
     * Initialize the properties.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initProperties()
    {
        global $wpdb;

        $this->charsetCollate = $wpdb->get_charset_collate();
    }

    /**
     * Run the schema.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function updateSchema()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $this->addSchema();

        if ($this->config->useSeedTables === true) {
            $this->seedTables();
        }

        update_option($this->config->optionName, $this->config->version);
    }

    /*****************************
     * Workers
     ****************************/

    /**
     * Add Schema
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function addSchema()
    {
        $this->appendCharsetCollate();

        dbDelta($this->config->sql);
    }

    /*****************************
     * Seeder
     ****************************/

    /**
     * Seed Tables
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function seedTables()
    {
        array_walk($this->config->seeder, [$this, 'doSeedTable']);
    }

    /**
     * Time to seed the table
     *
     * @since 3.0.0
     *
     * @param array $config
     * @param string $tablename
     *
     * @return null
     */
    protected function doSeedTable(array $config, $tablename)
    {
        if ($config['seedOnlyOnEmpty'] && !$this->isDbTableEmpty($tablename)) {
            return;
        }

        $seedData = $this->loadSeedData($config['seedFile']);
        if (empty($seedData)) {
            return;
        }

        array_walk($seedData, function ($dbRow) use ($tablename) {
            global $wpdb;

            $dbRow['date_created'] = current_time('mysql');
            $dbRow['date_updated'] = current_time('mysql');

            $wpdb->insert($tablename, $dbRow);
        });
    }

    /**
     * Checks if the database table is empty
     *
     * @since 3.0.0
     *
     * @param string $tablename
     *
     * @return bool
     */
    protected function isDbTableEmpty($tablename)
    {
        global $wpdb;

        return is_null($wpdb->get_row("SELECT * FROM {$tablename} LIMIT 1"));
    }

    /**
     * Loads and returns the seed data.
     *
     * @since 3.0.0
     *
     * @param string $seedFile
     *
     * @return array|null
     */
    protected function loadSeedData($seedFile)
    {
        if (is_readable($seedFile)) {
            return include $seedFile;
        }
    }

    /*****************************
     * Helpers
     ****************************/

    /**
     * Append the Charset Collate string to each SQL item.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function appendCharsetCollate()
    {
        array_walk($this->config->sql, function (&$sql) {
            $sql .= $this->charsetCollate . ';';
        });
    }

    /**
     * Check the db version - it does a hard check as well
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function hasVersionChanged()
    {
        $version             = get_option($this->config->optionName);
        $hasVersionChanged = $version !== $this->config->version;

        if ($hasVersionChanged) {
            $hasVersionChanged = $this->hasVersionChangedHardCheck();
        }

        return $hasVersionChanged;
    }

    /**
     * Making sure version has really changed.
     * Gets around aggressive caching issue on some sites that cause setup to run multiple times.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    protected function hasVersionChangedHardCheck()
    {
        return $this->getVersionFromDb() !== $this->config->version;
    }

    /**
     * Get the version the database.
     *
     * @since 3.0.0
     *
     * @return mixed
     */
    protected function getVersionFromDb()
    {
        global $wpdb;

        $sqlQuery = $wpdb->prepare(
            "
				SELECT option_value
				FROM {$wpdb->prefix}options
				WHERE option_name = %s
			", $this->optionName
        );

        return $wpdb->get_var($sqlQuery);
    }
}
