/*****************************************************************************
 * File: store-locator.js
 * 
 * Check that our PHP connector works, if so load map stuff.
 *
 *****************************************************************************/

// Check the WordPress environment was loaded
//
if (typeof add_base == 'undefined') {
    alert('SLPLUS: The PHP JavaScript connector did not load.');
} else {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = sl_js_path + 'store-locator-map.js';
    head.appendChild(script);
}
