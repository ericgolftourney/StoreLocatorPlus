<?php

/**
 * The base plugin class for Store Locator Plus.
 *
 * "gloms onto" the WPCSL base class, extending it for our needs.
 *
 * @package StoreLocatorPlus
 * @category PluginInterface
 * @author Lance Cleveland <lance@charlestonsw.com>
 *
 * @property currentLocation - location object for the current active location
 */
class SLPlus extends wpCSL_plugin__slplus {

    /**
     * The current location.
     * 
     * @var SLPlus_Location $currentLocation
     */
    public $currentLocation;

    /**
     * Initialize a new SLPlus Object
     *
     * @param mixed[] $params - a named array of the plugin options for wpCSL.
     */
    function __construct($params) {
        parent::__construct($params);
        $this->currentLocation = new SLPlus_Location();
    }
}
