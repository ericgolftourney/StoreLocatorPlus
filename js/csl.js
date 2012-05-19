/*****************************************************************
 * file: effortless-google-maps.js
 *
 * Prep our map interface.
 *
 *****************************************************************/

/***************************
  * Cyber Sprocket Labs Namespace
  *
  * For stuff to do awesome stuff
  *
  */
var csl = {
	
	/***************************
  	 * function: Ajax
  	 * usage:
	 * 		Sends an ajax request
  	 * parameters:
  	 * 		action: A usable action { action: 'csl_ajax_search', lat: 'start lat', long: 'start long', dist:'distance to search' }
  	 *		callback: will be of the form: { success: true, response: {marker list}}
  	 * returns: none
  	 */
	Ajax: function() {
		/***************************
		 * function: Ajax.send
		 * usage:
		 * 		Sends an ajax request
		 * parameters:
		 * 		action: A usable action { action: 'csl_ajax_search', lat: 'start lat', long: 'start long', dist:'distance to search' }
		 *		callback: will be of the form: { success: true, response: {marker list}}
		 * returns: none
		 */
		this.send = function(action, callback) {
			jQuery.post(csl_ajax.ajaxurl, action,
			function (response) {
				console.log('response:');
				console.log(response);
				callback(response);
			});
		}
		
		this.GetXmlHttpObject = function() {
			var objXMLHttp=null;
			if (window.XMLHttpRequest) {
				objXMLHttp=new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			return objXMLHttp;
		}
		
		this.stateChanged = function() {
			if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
				document.getElementById("ajaxMsg").innerHTML="Submission Successful.";
			} 
		}
		
		this.xmlHttp;
		
		this.showArticles = function(start) {
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null)
			{
				alert ("Browser does not support HTTP Request");
				return false;
			} 
			var url="/display_document_info.php";
			url=url+"?start="+start;
			url=url+"&sid="+Math.random();
			xmlHttp.onreadystatechange=stateChanged;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
		
		this.doc_counter = function(the_loc) {
			if (the_loc.search.indexOf('u=')!=-1) {
				parts=the_loc.href.split('u=');
				u_part=parts[1].split('&')[0];
			} 
			else {
				dirs=the_loc.href.split('/');
				u_part=dirs[dirs.length-1];
				u_part=u_part.split('?')[0].split('.')[0];
			}
	
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null)
			{
				alert ("Browser does not support HTTP Request");
				return false;
			} 
			var url="/scripts/doc_counter.php";
			url=url+"?u="+u_part;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
	},
	
	MouseAnimation: function()
	{
		this.anim2 = function(imgObj, url) {
			imgObj.src=url;
		}
		
		this.anim = function(name, type) {
			if (type==0)
				document.images[name].src="/core/images/"+name+".gif";
			if (type==1)
				document.images[name].src="/core/images/"+name+"_over.gif";
			if (type==2)
				document.images[name].src="/core/images/"+name+"_down.gif";
		}
	},
  	  
	/***************************
  	* Animation enum technically
  	* usage:
  	* 		Animation enumeration 
  	*/
  	Animation: { Bounce: 1, Drop: 2, None: 0 },
  	  
	/***************************
  	 * Marker for google maps
  	 * usage:
  	 * create a google maps marker
  	 * parameters:
  	 * 	animationType: The Animation type to do the animation
	 *		map: the csl.Map type to put it on
	 *		title: the title of the marker for mouse over
	 *		iconUrl: todo: load a custom icon, null for default
	 *		position: the lat/long to put the marker at
  	 */
  	Marker: function (animationType, map, title, iconUrl, position) {
		this.__animationType = animationType;
  	  	this.__map = map;
  	  	this.__title = title;
  	  	this.__icon = iconUrl;
  	  	this.__position = position;
  	  	this.__gmarker = null;
  	  	
  	  	this.__init = function() {
			this.__gmarker = new google.maps.Marker(
  	  	  	{
				position: this.__position,
  	  	  	  	map: this.__map.gmap,
  	  	  	  	animation: this.__animationType,
  	  	  	  	position: this.__position,
  	  	  	  	title: this.__title,
  	  	  	});
  	  	}
  	  	  
  	  	this.__init();
  	},
	
	Utils: function() {
		/*****************************************************************************
		* File: store-locator-emailform.js
		* 
		* Create the lightbox email form.
		*
		*****************************************************************************/
		this.show_email_form = function(to) {
			emailWin=window.open("about:blank","",
				"height=220,width=310,scrollbars=no,top=50,left=50,status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0");
			with (emailWin.document) {
				writeln("<html><head><title>Send Email To " + to + "</title></head>");
                
				writeln("<body scroll='no' onload='self.focus()' onblur='close()'>");
        
				writeln("<style>");
				writeln(".form_entry{ width: 300px; clear: both;} ");
				writeln(".form_submit{ width: 300px; text-align: center; padding: 12px;} ");
				writeln(".to{ float: left; font-size: 12px; color: #444444; } ");
				writeln("LABEL{ float: left; width: 75px;  text-align:right; ");
				writeln(      " font-size: 11px; color: #888888; margin: 3px 3px 0px 0px;} ");
				writeln("INPUT type=['text']{ float: left; width: 225px; text-align:left; } ");
				writeln("INPUT type=['submit']{ padding-left: 120px; } ");
				writeln("TEXTAREA { width: 185px; clear: both; padding-left: 120px; } ");
				writeln("</style>");
        
				writeln("<form id='emailForm' method='GET'");
				writeln(    " action='"+add_base+"/send-email.php'>");
        
				writeln("    <div id='email_form_content'>");

				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_to'>To:</label>");
				writeln("            <input type='hidden' name='email_to' value='"+to+"'/>");
				writeln("            <div class='to'>"+to+"</div>");
				writeln("        </div>");           
					
        
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_name'>Your Name:</label>");
				writeln("            <input name='email_name' value='' />");
				writeln("        </div>");
        
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_from'>Your Email:</label>");
				writeln("            <input name='email_from' value='' />");
				writeln("        </div>");             
					
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_subject'>Subject:</label>");
				writeln("            <input name='email_subject'  value='' />");
				writeln("        </div>");        
					
				writeln("        <div class='form_entry'>");
				writeln("            <label for='email_message'>Message:</label>");
				writeln("            <textarea name='email_message'></textarea>");
				writeln("        </div>");                
				writeln("    </div>");    
		
				writeln("    <div class='form_submit'>");
				writeln("        <input type='submit' value='Send Message'>");
				writeln("    </div>");
				writeln("</form>");
				writeln("</body></html>");
				close();
			}     
		}
	
		/**************************************
		 * function: escapeExtended()
		 *
		 * Escape any extended characters, such as ü in für.
		 * Standard US ASCII characters (< char #128) are unchanged
		 *
		 */ 
		this.escapeExtended = function(string)
		{
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";
 
			for (var n = 0; n < string.length; n++) {
 
				var c = string.charCodeAt(n);
 
				if (c < 128) {
					utftext += string.charAt(n);
				}
				else
				{
					utftext += escape(string.charAt(n));
				}
 
			}
 
			return utftext; 
		}
	},
  	  
	/***************************
  	 * Popup info window Object
  	 * usage:
  	 * create a google info window
  	 * parameters:
  	 * 	content: the content to show by default
  	 */
  	Info: function (content) {
		this.__content = content;
  	  	this.__position = position;
  	  	
  	  	this.__anchor = null;
  	  	this.__gwindow = null;
  	  	this.__gmap = null;
  	  	
  	  	this.openWithNewContent = function(map, object, content) {
			this.__content = content;
  	  		this.__gwindow = setContent = this.__content;
  	  	  	this.open(map, object);
  	  	}
  	  	  
  	  	this.open = function(map, object) {
			this.__gmap = map.gmap;
  	  	  	this.__anchor = object;
  	  	  	this.__gwindow.open(this.__gmap, this.__anchor);
  	  	}
  	  	  
  	  	this.close = function() {
			this.__gwindow.close();
  	  	}
  	  	  
  	  	this.__init = function() {
			this.__gwindow = new google.maps.InfoWindow(
  	  	  	{
				content: this.__content,
  	  	  	});
  	  	}
  	  	  
  	  	this.__init();
  	},
  	  
  	/***************************
  	 * Map Object
  	 * usage:
  	 * create a google maps object linked to a map/canvas id
  	 * parameters:
  	 * 	aMapNumber: the id/canvas of the map object to load from php side
  	 */
  	Map: function(aMapCanvas) {
		//private: map number to look up at init
  	  	this.__mapCanvas = aMapCanvas;
		
		//function callbacks
		this.tilesLoaded = null;
  	  	
  	  	//php passed vars set in init
  	  	this.address = null; //y
  	  	this.zoom = null; //y
  	  	this.view = null; //y
  	  	this.canvasID = null;
  	  	this.draggable = true; //n
  	  	this.overviewMapControl = true; //n
  	  	this.panControl = true; //n
  	  	this.rotateControl = true; //n
  	  	this.scaleControl = true; //n
  	  	this.scrollwheel = true; //n
  	  	this.streetViewEnabled = true; //n
  	  	this.tilt = 45; //n
  	  	this.zoomAllowed = true; //n
  	  	this.disableDefaultUI = false; //n
  	  	this.zoomStyle = 0; // 0 = default, 1 = small, 2 = large
		this.markers;
  	  	
  	  	//gmap set variables
  	  	this.options = null;
  	  	this.gmap = null;
  	  	this.centerMarker = null;
		this.marker = null;
		this.infowindow = new google.maps.InfoWindow();
  	  	
  	  	/***************************
  	  	 * function: __geocodeResult
  	  	 * usage:
		 * Called when the geocode is complete
  	  	 * parameters:
  	  	 * 	results: some usable results (see google api reference)
  	  	 *		status:  the status of the geocode (ok means g2g)
  	  	 * returns: none
  	  	 */
  	  	this.__geocodeResult = function(results, status) {
			if (status == 'OK' && results.length > 0)
  	  	  	{
				this.options = {
					center: results[0].geometry.viewport.getCenter(),
  	  	  	  	  	zoom: parseInt(this.zoom),
  	  	  	  	  	MapTypeId: this.view,
  	  	  	  	  	disableDefaultUI: this.disableDefaultUI
  	  	  	  	};
  	  	  	  	this.gmap = new google.maps.Map(map, this.options);
				  
				//this forces any bad css from themes to fix the "gray bar" issue by setting the css max-width to none
				var _this = this;
				google.maps.event.addListener(this.gmap, 'bounds_changed', function() {
					_this.__waitForTileLoad.call(_this);
				});
				  
				  
  	  	  	  	//this.addMarkerAtCenter();
				this.loadMarkers(null);
  	  	  	} else {
				alert("Address could not be processed: " + status);
  	  	  	}
  	  	}
  	  	  
		/***************************
  	  	 * function: __waitForTileLoad
  	  	 * usage:
		 * Notifies as the map changes that we'd like to be nofified when the tiles are completely loaded
  	  	 * parameters:
  	  	 * 	none
  	  	 * returns: none
  	  	 */
		this.__waitForTileLoad = function() {
			var _this = this;
			if (this.__tilesLoaded == null)
			{
				this.__tilesLoaded = google.maps.event.addListener(this.gmap, 'tilesloaded', function() {
					_this.__tilesAreLoaded.call(_this);
				});
			}
		}
		  
		/***************************
  	  	 * function: __tilesAreLoaded
  	  	 * usage:
		 * All the tiles are loaded, so fix their css
  	  	 * parameters:
  	  	 * 	none
  	  	 * returns: none
  	  	 */
		this.__tilesAreLoaded = function() {
			jQuery(map).find('img').css({'max-width': 'none'});
			google.maps.event.removeListener(this.__tilesLoaded);
			this.__tilesLoaded = null;
		}
		  
  	  	/***************************
  	  	 * function: addMarkerAtCenter
  	  	 * usage:
  	  	 * Puts a pretty marker right smack in the middle
  	  	 * parameters:
  	  	 * 	none
  	  	 * returns: none
  	  	 */
  	  	this.addMarkerAtCenter = function() {
			this.centerMarker = new csl.Marker(csl.Animation.Drop, this, "", null, this.gmap.getCenter());
  	  	}
		
		this.clearMarkers = function() {
			if (this.markers) {
				for (markerNumber in markerList) {
					markerList[markerNumber].__gmarker.setMap(null);
				}
				markerList.length = 0;
			}
		}
		
		this.putMarkers = function(markerList, animation) {
			this.markers = [];
			for (markerNumber in markerList) {
				console.log(markerList[markerNumber]);
				var position = new google.maps.LatLng(markerList[markerNumber].lat, markerList[markerNumber].lng);
				console.log(position);
				this.markers.push(new csl.Marker(animation, this, "", null, position));
				_this = this;
				google.maps.event.addListener(this.markers[markerNumber].__gmarker, 'click', 
				(function (infoData, marker) {
					return function() {
						_this.__handleInfoClicks.call(_this, infoData, marker);
					}
				})(markerList[markerNumber], this.markers[markerNumber]));
			}
		}
		
		this.bounceMarkers = function(markerList) {
			console.log('bounce');
			this.putMarkers(markerList, csl.Animation.Bounce);
		}
		
		this.dropMarkers = function(markerList) {
			console.log('dropping');
			this.putMarkers(markerList, csl.Animation.Drop);
		}
		
		this.__handleInfoClicks = function(infoData, marker) {
			console.log(infoData);
			console.log(marker);
			console.log(this);
			this.infowindow.setContent(this.createMarkerContent(infoData));
			//this.infowindow.setContent('hi');
			this.infowindow.open(this.gmap, marker.__gmarker);
		}
  	  	  
  	  	/***************************
  	  	 * function: __init()
  	  	 * usage:
  	  	 * Called at the end of the 'class' due to some browser's quirks
  	  	 * parameters: none
  	  	 * returns: none
  	  	 */
  	  	this.__init = function() {
			this.address = 'wilmington, nc';
  	  	  	this.zoom = slplus.zoom_level;
  	  	  	this.view = slplus.view;
  	  	  	this.disableDefaultUI = false;
  	  	}
  	  	  
  	  	/***************************
  	  	 * function doGeocode()
  	  	 * usage:
  	  	 * Call to start the geocode of the address and display it on the map if possible
  	  	 * make sure to call init first
  	  	 * parameters: none
  	  	 * returns: none
  	  	 */
  	  	this.doGeocode = function() {
			var geocoder = new google.maps.Geocoder();
  	  	  	var _this = this;
  	  	  	geocoder.geocode(
				{
					'address': this.address
  	  	  	  	},
  	  	  	  	function (result, status) {							// This is a little complicated, 
  	  	  	  	_this.__geocodeResult.call(_this, result, status); }	// but it forces the callback to keep its scope
  	  	  	);
  	  	}
		
		this.createMarkerContent = function(aMarker) {
			var html = '';
			if (aMarker.url.indexOf("http://") == -1)
			{
				aMarker.url = "http://" + aMarker.url;
			}
			
			if (aMarker.url.indexOf("http://") != -1 && aMarker.url.indexOf(".") != -1) { 
				html += "| <a href='"+aMarker.url+"' target='"+(slplus.use_same_window?'_self':'_blank')+"' class='storelocatorlink'><nobr>" + slplus.website_label +"</nobr></a>";
			} else {
				aMarker.url = "";
			}
			
			if (aMarker.email.indexOf("@") != -1 && aMarker.email.indexOf(".") != -1) {
				html += "| <a href='mailto:"+aMarker.email+"' target='_blank' class='storelocatorlink'><nobr>" + aMarker.email +"</nobr></a>";
			} else {
				html += "| <a href='javascript:slp_show_email_form("+'"'+aMarker.email+'"'+");' class='storelocatorlink'><nobr>" + aMarker.email +"</nobr></a><br/>";
			}
			
			if (aMarker.image.indexOf(".") != -1) {
				html+="<br/><img src='"+aMarker.image+"' class='sl_info_bubble_main_image'>";
			} else {
				aMarker.image = "";
			}
			
			if (aMarker.description != '') {
				html+="<br/>"+aMarker.description+"";
			} else {
				aMarker.description = '';
			}
			
			if (aMarker.hours != '') {
				html+="<br/><span class='location_detail_label'>Hours:</span> "+aMarker.hours;
			} else {
				aMarker.hours = "";
			}
			
			if (aMarker.phone != '') {
				html+="<br/><span class='location_detail_label'>Phone:</span> "+aMarker.phone;
			}

			var address = aMarker.address;
			if (aMarker.address == '') { aMarker.address = ""; } else address += ', ';
			address += aMarker.address2;
			if (aMarker.address2 == '') { aMarker.address2 = ""; } else address += ', ';
			address += aMarker.city;
			if (aMarker.city == '') { aMarker.city = ""; } else address += ', ';
			address += aMarker.state;
			if (aMarker.state == '') { aMarker.state = ""; } else address += ', ';
			address += aMarker.zip;
			if (aMarker.zip == '') { aMarker.zip = ""; }
			
			if (slplus.show_tags) {
				if (jQuery.trim(tags) != '') {
					html += '<br/>'+tags;
				}
			}
			
			//todo: actually include home address
			var complete_html = '<div id="sl_info_bubble"><!--tr><td--><strong>' + aMarker.name + '</strong><br>' + address + '<br/> <a href="http://' + slplus.map_domain + '/maps?saddr=' + encodeURIComponent(this.gmap.getCenter()) + '&daddr=' + encodeURIComponent(aMarker.street + ', ' + aMarker.street2 + ', ' + aMarker.city + ', ' + aMarker.state + ', ' + aMarker.zip) + '" target="_blank" class="storelocatorlink">Directions</a> ' + html + '<br/><!--/td></tr--></div>';
			
			return complete_html;
		}
		
		this.loadMarkers = function(center) {
			if (center == null) {
				var center = this.gmap.getCenter();
			}
			console.log('searching: ' + center.lat() +','+ center.lng());
			var action = {action:'csl_ajax_search',lat:center.lat(),lng:center.lng(),radius:'500'};
			var _this = this;
			var ajax = new csl.Ajax();
			ajax.send(action, function (response) {
				_this.dropMarkers.call(_this, response.response);
			});
		}
		
		this.createSideBar = function(aMarker) { 
		}
  	  	  
  	  	//dumb browser quirk trick ... wasted two hours on that one
  	  	this.__init();
	}
}
 
//global vars
var cslmap;
 
/***************************
 * function InitializeTheMap()
 *
 * Setup the map settings and get id rendered.
 *
 */
function InitializeTheMap() {
	cslmap = new csl.Map();
	cslmap.doGeocode();
}

/* 
 * When the document has been loaded...
 *
 */
jQuery(document).ready(function(){
	InitializeTheMap();
});

