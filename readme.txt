=== Store Locator Plus ===
Plugin Name: Store Locator Plus
Contributors: cybersprocket
Donate link: http://www.cybersprocket.com/products/store-locator-plus/
Tags: store locator, store locater, google, google maps, dealer locator, dealer locater, zip code search, shop locator, shop finder, zipcode, location finder, places, stores, maps, mapping, mapper, plugin, posts, post, page, coordinates, latitude, longitude, geo, geocoding, shops, ecommerce, e-commerce, business locations, store locator plus, store locater plus
Requires at least: 3.0
Tested up to: 3.0.4
Stable tag: 1.4

This plugin puts a search form and an interactive Google map on your site so you can show visitors your store locactions.    

== Description ==

This plugin puts a search form and an interactive Google map on your site so you 
can show visitors your store locactions.  Users search for stores within a 
specified radius, enter your address, or select a city or country from the 
pulldown.  Full admin panel data entry and management of stores from a few to
a few thousand.

= Great Built-In Functionality & Features =

* You can use it for numerous countries, which will continue to be added as Google adds new countries to their Google Maps API.  See the documentation for the latest
* Supports international languages and character sets.
* Allows you to use unique map icons or your own custom map icons.
* Tweak the map settings via the admin panel including:
** Map type (terrain, satellite, street, etc.)
** Inset map
** Starting zoom level
* You can use miles or kilometers
* Pulldown list of cities and/or countries on search form can be toggled on/off.

= Looking For Customized WordPress Plugins? =

If you are looking for custom WordPress development for your own plugins, give 
us a call.   Not only can we offer competitive rates but we can also leverage 
our existing framework for WordPress applications which reduces development time 
and costs.

Learn more at: http://www.cybersprocket.com/services/wordpress-developers/

= Related Links =

* <a href="http://redmine.cybersprocket.com/products/store-locator-plus/" title="Store Locator Plus Product Info" alt="Store Locator Plus Product Info">Store Locator Plus Product Info</a>
* <a href="http://redmine.cybersprocket.com/projects/mc-closeststore/wiki" title="Store Locator Plus Support Pages" alt="Store Locator Plus Support Pages">Store Locator Plus Support Pages</a>
* <a href="http://wordpress.org/extend/plugins/profile/cybersprocket" title="Other Cyber Sprocket Plugins" alt="Other Cyber Sprocket Plugins">Other Cyber Sprocket Plugins</a> 
* <a href="http://www.cybersprocket.com/services/wordpress-developers/" title="Custom WordPress Development" alt="Custom WordPress Development">Custom WordPress Development</a>

== Installation ==

= Main Plugin =

1. Upload the `store-locator-plus` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Sign up for a Google Maps API Key for your domain at http://code.google.com/apis/maps/signup.html
4. Add your locations through the 'Add Locations' page in the Store Locator admin panel
5. Place the code '[STORE-LOCATOR]' (case-sensitive) in the body of a page or a post to display your store locator

= Icons =

1. There are some default icons in the `/wp-content/plugins/store-locator/icons` directory. 
2. Add your own custom icons in to `wp-content/uploads/sl-uploads/custom-icons`.

= Custom CSS (Stylesheet) =

You can modify the default style sheet included with the plugin at 
./css/csl-slplus.css' and place it under `/wp-content/uploads/sl-uploads/custom-css/`. 
The store locator will give priority to the 'csl-slplus.css' in the 'custom-css/' 
folder over the default 'csl-slplus.css' file that is included.  This allows you 
to upgrade the main store locator plugin without worrying about losing your 
custom styling. 

== Screenshots ==

1. Admin Menus
2. Adding Locations
3. Manage Locations
4. Map Settings
5. Default Search Form
6. Search By Address
7. Map Mouse Over

== Changelog ==

= 1.4  (January 2010) =

* Revamp manage locations header 
* Removed Store Locator Plugin addons support, addons support causing problems.

= 1.3  (December 2010) =

* Add country field to address data.
* Clean up various coding errors since WordPress 3.0 release
** Deprecated function calls
** non-quoted array references
* Initial release based on Google Maps Store Locator for WordPress v1.2.39.3


