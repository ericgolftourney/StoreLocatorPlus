=== Store Locator Plus ===
Plugin Name: Store Locator Plus
Contributors: cybersprocket
Donate link: http://www.cybersprocket.com/products/store-locator-plus/
Tags: store locator, store locater, google, google maps, dealer locator, dealer locater, zip code search, shop locator, shop finder, zipcode, location finder, places, stores, maps, mapping, mapper, plugin, posts, post, page, coordinates, latitude, longitude, geo, geocoding, shops, ecommerce, e-commerce, business locations, store locator plus, store locater plus
Requires at least: 3.0
Tested up to: 3.0.3
Stable tag: 1.3

Store Locator Plus is based on the popular Google Maps Store Locator with a few customizations we needed for our clients. Unfortunately the original author is on haitus, so we've had to create our ownupdate. Hopefully other WordPress users will find our additions useful.

== Description ==

= Provides Mapping, Display, and Search of Locations For: =
* Those of you who create sites for clients using WordPress
* Those of you who want to show your important locations (stores, buildings, points of interest, etc.) in an easily searchable manner.

Also referred to as a dealer locator (locater), shop finder, and zip code or zipcode search.
Its strength is in its flexibility to allow you to easily manage a few or a thousand or more locations through the admin interface.

= Great Built-In Functionality & Features =
* You can use it for numerous countries, which will continue to be added as Google adds new countries to their Google Maps API.  See the documentation for the latest
* Supports international languages and character sets 
* Allows you to use unique map icons or your own custom map icons --- great for branding your map
* Gives your map the desired look by using our Map Designer&trade; interface in the WordPress admin section
* Pick other cool Google Maps options, such as an inset box, zoom level, map types (street, satellite, hybrid, physical), and more
* You can use miles or kilometers
* Automatically restricts loading of Javascript & CSS to only pages that display the map (or that might need access to the JS & CSS) for better site performance
* Option to show dropdown list of cities allows visitors to quickly see where your locations are and choose their search accordingly

= Upgrades =
Viadat, the original author, was selling updated add-ons and themes but as of this release was on haitus.  You can check out their work here: http://www.viadat.com/products-page/

= Related Links =

== Installation ==

= Main Plugin =

1. Upload the `store-locator-plus` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Sign up for a Google Maps API Key for your domain at http://code.google.com/apis/maps/signup.html
4. Add your locations through the 'Add Locations' page in the Store Locator admin panel
5. Place the code '[STORE-LOCATOR]' (case-sensitive) in the body of a page or a post to display your store locator

= Addons =

1. Unzip & Upload the entire add-on folder to the `/wp-content/uploads/sl-uploads/addons` directory.
2. Activate the add-on by updating the Activation Key that you receive after purchase at the bottom of the "News & Upgrades" Page.

= Themes =

1. Unzip & Upload the entire theme folder to the `wp-content/uploads/sl-uploads/themes` directory.
2. Select theme from the theme dropdown menu under the "Design" section on the "Map Designer"&trade; Page.

= Icons =

1. There are some default icons in the `/wp-content/plugins/store-locator/icons` directory. 
2. Add your own custom icons in to `wp-content/uploads/sl-uploads/custom-icons`.

= Custom CSS (Stylesheet) =

You can modify the default 'store-locator.css' and place it under `/wp-content/uploads/sl-uploads/custom-css/`. The store locator will give priority to the 'store-locator.css' in the 'custom-css/' folder over the default 'store-locator.css' in the main 'store-locator/' folder. This allows you to upgrade the main store locator plugin without worrying about losing your custom styling. 

== Changelog ==

= 1.3  (December 2010) =

* Initial release based on Google Maps Store Locator for WordPress v1.2.39.3
* Add country field to address data.

