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
 * @property SLPlus_Location $currentLocation - location object for the current active location
 * @property-read wpdb $db - the global wpdb object
 */
class SLPlus extends wpCSL_plugin__slplus {

    /**
     * The current location.
     * 
     * @var SLPlus_Location $currentLocation
     */
    public $currentLocation;

    /**
     * The global $wpdb object for WordPress.
     *
     * @var wpdb $db
     */
    public $db;

    /**
     * Initialize a new SLPlus Object
     *
     * @param mixed[] $params - a named array of the plugin options for wpCSL.
     */
    function __construct($params) {
        global $wpdb;
        parent::__construct($params);
        $this->currentLocation = new SLPlus_Location(array('plugin'=>$this));
        $this->db = $wpdb;
    }
}
