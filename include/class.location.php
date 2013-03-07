<?php

/**
 * The location data interface and management class.
 *
 * Make a location an in-memory object and handle persistence via data I/O to the MySQL tables.
 *
 * @package StoreLocatorPlus
 * @subpackage Location
 * @category DataManagement
 * @author Lance Cleveland <lance@charlestonsw.com>
 *
 * @property int $id
 * @property string $store - the store name
 * @property string $address
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $latitude
 * @property string $longitude
 * @property string $tags
 * @property string $description
 * @property string $email
 * @property string $url
 * @property string $hours
 * @property string $phone
 * @property string $fax
 * @property string $image
 * @property boolean $private
 * @property string $neat_title
 * @property int $linked_postid
 * @property string $pages_url
 * @property boolean $pages_on
 * @property string $option_value
 * @property datetime $lastupdated
 * @property mixed[] $settings - the deserialized option_value field
 *
 * @property mixed[] $pageData - the related store_page custom post type properties.
 * @property-read string $pageType - the custom WordPress page type of locations
 * @property-read string $pageDefaultStatus - the default page status
 *
 * @property-read string $dbFieldPrefix - the database field prefix for locations
 * @property-read string[] $dbFields - an array of properties that are in the db table
 *
 * @property SLPlus $plugin - the parent plugin object
 */
class SLPlus_Location {

    //-------------------------------------------------
    // Properties
    //-------------------------------------------------

    // Our database fields
    //
    private $id;
    private $store;
    private $address;
    private $address2;
    private $city;
    private $state;
    private $zip;
    private $country;
    private $latitude;
    private $longitude;
    private $tags;
    private $description;
    private $email;
    private $url;
    private $hours;
    private $phone;
    private $fax;
    private $image;
    private $private;
    private $neat_title;
    private $linked_postid;
    private $pages_url;
    private $pages_on;
    private $option_value;
    private $lastupdated;

    // The database map
    //
    private $dbFields = array(
            'id',
            'store',
            'address',
            'address2',
            'city',
            'state',
            'zip',
            'country',
            'latitude',
            'longitude',
            'tags',
            'description',
            'email',
            'url',
            'hours',
            'phone',
            'fax',
            'image',
            'private',
            'neat_title',
            'linked_postid',
            'pages_url',
            'pages_on',
            'option_value',
            'lastupdated'
        );

    /**
     * The deserialized option_value field. This can be augmented by multiple add-on packs.
     *
     * Tagalong adds:
     *  array[] ['store_categories']
     *       int[] ['stores']
     *
     * @var mixed[] $settings
     */
    private $settings;

    /**
     * The related store_page custom post type properties.
     *
     * WordPress Standard Custom Post Type Features:
     *   int    ['ID']          - the WordPress page ID
     *   string ['post_type']   - always set to this.PageType
     *   string ['post_status'] - current post status, 'draft', 'published'
     *   string ['post_title']  - the title for the page
     *   string ['post_content']- the page content, defaults to blank
     *
     * Store Pages adds:
     *    post_content attribute is loaded with auto-generated HTML content
     *
     * Tagalong adds:
     *    mixed[] ['tax_input'] - the custom taxonomy values for this location
     *
     * @var mixed[] $pageData
     */
    private $pageData;

    // Assistants for this class
    //
    private $dbFieldPrefix      = 'sl_';
    private $pageType           = 'store_page';
    private $pageDefaultStatus  = 'draft';
    private $plugin;

    //-------------------------------------------------
    // Methods
    //-------------------------------------------------

    /**
     * Initialize a new location
     *
     * @param mixed[] $params - a named array of the plugin options.
     */
    function __construct($params) {
        foreach ($params as $property=>$value) {
            $this->$property = $value;
        }
    }

    /**
     * Create or update the custom store_page page type for this location.
     *
     * @return int $linke_postid - return the page ID linked to this location.
     */
    public function crupdate_Page() {

        // Setup the page properties.
        //
        $this->get_PageData();

        // Create or update the page and set our linked post ID to that page.
        //
        $touched_pageID = wp_insert_post($this->pageData);

        // If we created a page or changed the page ID,
        // set it in our location property and make it
        // persistent.
        //
        if ($touched_pageID != $this->linked_postid) {
            $this->linked_postid = $touched_pageID;
            $this->MakePersistent();
        }

        return $this->linked_postid;
    }

    /**
     * Fetch a location property from the valid object properties list.
     *
     * $currentLocation = new SLPlus_Location();
     * print $currentLocation->id;
     * 
     * @param mixed $property - which property to set.
     * @return null
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * Get the data for the current page, run through augmentation filters.
     *
     * This method applies the slp_location_page_attributes filter.
     *
     * Using that filter allows other parts of the system to change or augment
     * the data before we create or update the page in the WP database.
     *
     * @return mixed[] WordPress custom post type property array
     */
    public function get_PageData() {

        // We have an existing page
        //
        if ($this->linked_postid > 0) {
            $this->pageData = array(
                'ID'            => $this->linked_postid,
            );


        // No page yet, default please.
        //
        } else {
            $this->pageData = array(
                'ID'            => $this->linked_postid,
                'post_type'     => $this->pageType,
                'post_status'   => $this->pageDefaultStatus,
                'post_content'  => ''
            );
        }

        // Apply our location page data filters.
        // This is what allows add-ons to tweak page data.
        //
        $this->pageData = apply_filters('slp_location_page_attributes', $this->pageData);

        // Show some details if debug mode is enabled
        //
        $this->plugin->helper->bugout(
                '<pre>'.print_r($this->pageData,true).'</pre>',
                '',
                'SLPlus_Location.get_PageData()',
                __FILE__,
                __LINE__
                );

        return $this->pageData;
    }

    /**
     * Make the location data persistent.
     *
     * Write the data to the locations table in WordPress.
     */
    function MakePersistent() {

        // Location is set, update it.
        //
        if ($this->id > 0) {
//            $this->plugin->db->insert(
//                    $this->plugin->database['table_ns'],
//                    $this->array_map(array($this,'mapPropertyToField'),$this->DBFields)
//            );
            print "Persisting: <pre>".$this->array_map(array($this,'mapPropertyToField'),$this->dbFields).'</pre>';
        }
    }

    /**
     * Return a named array that sets key = db field name, value = location property
     *
     * @param string $property - name of the location property
     * @return mixed[] - key = string of db field name, value = location property value
     */
    function mapPropertyToField($property) {
        return (array($this->dbFieldPrefix.$property,$this->$property));
    }

    /**
     * Set a location property in the valid object properties list to the given value.
     *
     * $currentLocation = new SLPlus_Location();
     * $currentLocation->store = 'My Place';
     *
     * @param mixed $property
     * @param mixed $value
     * @return \SLPlus_Location
     */
    public function __set($property,$value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * Set our location properties via a named array containing the data.
     *
     * Used to set our properties based on the MySQL SQL fetch to ARRAY_A method.
     *
     * Assumes the properties all start with 'sl_';
     *
     * @param type $locationData
     * @return boolean
     */
    public function set_PropertiesViaArray($locationData) {

        // If we have an array, assume we are on the right track...
        if (is_array($locationData)) {

            // Go through the named array and extract our properties.
            //
            foreach ($locationData as $field => $value) {

                // Get rid of the leading field prefix (usually sl_)
                //
                $property = str_replace($this->dbFieldPrefix,'',$field);

                // Set our property value
                //
                $this->$property = $value;
            }

            // Deserialize the option_value field
            //
            $this->settings = maybe_unserialize($this->option_value);

            return true;
        }
        return false;
    }
}
