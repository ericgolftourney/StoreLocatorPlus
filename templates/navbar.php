<?php
/****************************************************************************
 ** file: core/templates/navbar.php
 **
 ** The top Store Locator Settings navigation bar.
 ***************************************************************************/
 
 global $slplus_plugin;
?>

<ul>
    <li><a href="">Locations: All</a></li>
    <li><a href="">Locations: Add</a></li>
    <li><a href="">Settings: Map</a></li>    
    <li><a href="">Settings: General</a></li>    
    <?php 
    //--------------------------------
    // Plus Version : Show Reports Tab
    //
    if (!$slplus_plugin->no_license) {
        print '<li><a href="">Reports</a></li>';
    }
    ?>    
</ul>


