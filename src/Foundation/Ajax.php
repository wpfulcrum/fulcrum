<?php

namespace Fulcrum\Foundation;

use Fulcrum\FulcrumContract;
use Fulcrum\Config\ConfigContract;

abstract class AJAX
{
    /**
     * Configuration parameters
     *
     * @var ConfigContract
     */
    protected $config;

    /**
     * Instance of Fulcrum
     *
     * @var FulcrumContract
     */
    protected $fulcrum;

    /**
     * Error message
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * Return data packet
     *
     * @var array
     */
    protected $dataPacket = [];

    /**
     * Error code
     *
     * @var int
     */
    protected $errorCode = 0;

    /******************************
     * Instantiation & Initialization
     *****************************/

    /**
     * Instantiate the AJAX Object
     *
     * @since 3.0.0
     *
     * @param ConfigContract $config
     * @param FulcrumContract $fulcrum
     */
    public function __construct(ConfigContract $config, FulcrumContract $fulcrum = null)
    {
        $this->config  = $config;
        $this->fulcrum = $fulcrum;

        $this->init();
        $this->initEvents();
    }

    /**
     * Initialize
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function init()
    {
        // do nothing
    }

    /**
     * Initialize the events
     *
     * @since 3.0.0
     *
     * @return null
     */
    abstract protected function initEvents();

    /******************************
     * Helpers
     *****************************/

    /**
     * AJAX Response Handler - Builds the response and returns it to the JavaScript
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function ajaxResponseHandler()
    {
        echo json_encode([
            'success'      => $this->errorMessage ? 0 : 1,
            'errorMessage' => $this->errorMessage,
            'errorCode'    => $this->errorCode,
            'data'         => $this->errorMessage ? '' : $this->dataPacket,
        ]);

        die();
    }

    /**
     * Initialize AJAX
     *
     * @since 31.0.0
     *
     * @return null
     */
    protected function initAjax()
    {
        $this->initProperties();

        $this->securityCheck();
    }

    /**
     * Initialize the properties
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function initProperties()
    {
        $this->errorMessage = '';
        array_walk($this->config->dataPacket, [$this, 'initDataPacket']);
        $this->errorCode = 0;
    }

    /**
     * Initialize data packet
     *
     * @since 3.0.0
     *
     * @param string $filter
     * @param string $key
     *
     * @return null
     */
    protected function initDataPacket($filter, $key)
    {
        if (!array_key_exists($key, $_POST)) {
            $this->errorMessage = $this->config->messages[$key];
            $this->ajax_response_handler();
        }
        $this->dataPacket[$key] = $filter($_POST[$key]);
    }

    /**
     * Checks the AJAX Referer.  If invalid, dies with a security message.
     *
     * @since 3.0.0
     *
     * @return null
     */
    protected function securityCheck()
    {
        check_ajax_referer($this->config->nonceKey, 'security');
    }
}
