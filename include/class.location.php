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
 * @property string $store
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
 */
class SLPlus_Location {

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

    // Deserialized database elements
    //
    private $settings;

    // Assistants for this class
    //
    private $dbFieldPrefix = 'sl_';

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
