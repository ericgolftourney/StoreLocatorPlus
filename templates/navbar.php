<?php
/****************************************************************************
 ** file: core/templates/navbar.php
 **
 ** The top Store Locator Settings navigation bar.
 ***************************************************************************/
 
 global $slplus_plugin;
?>

<ul>
    <li><a href="<?php echo SLPLUS_ADMINPAGE;?>view-locations.php">Locations: Manage</a></li>
    <li><a href="<?php echo SLPLUS_ADMINPAGE;?>add-locations.php">Locations: Add</a></li>
    <li><a href="<?php echo SLPLUS_ADMINPAGE;?>map-designer.php">Settings: Map</a></li>    
    <li><a href="/wp-admin/options-general.php?page=csl-slplus-options">Settings: General</a></li>    
    <?php 
    //--------------------------------
    // Plus Version : Show Reports Tab
    //
    if (!$slplus_plugin->no_license) {
        print '<li><a href="'.SLPLUS_PLUSPAGE.'reporting.php">Reports</a></li>';
    }
    ?>    
</ul>


