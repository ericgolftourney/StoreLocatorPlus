<?php

//Second step in importing locations - inserting into database
if ($_POST[finish_import]=="1") {
    insert_matched_data();
	print "<div class='highlight'>".__("Successful Import", $text_domain).". 
	        $view_link</div>";
	
//First step in importing address - mapping old table fields to the new table fields
} elseif ( empty($_POST[finish_import]) ) {

    if (!eregi("^select", trim($_POST[query]))) {
        print "<div class='highlight' style='border:solid red 1px; background-color:salmon'>".
            __("You May Only Use 'Select' Queries", $text_domain).
            ". | <a href='$_SERVER[REQUEST_URI]'>".
            __("Try Again", $text_domain).
            "</a></div>";
        exit;
    }
    
    
    define("DB2_HOST","$_POST[host]");
    define("DB2_USER","$_POST[user]");
    define("DB2_PASS","$_POST[pass]");
    define("DB2_NAME","$_POST[db]");
    $query=$_POST[query];
    
    //Doesn't use wpdb functionality
    $link=mysql_connect(DB2_HOST,DB2_USER,DB2_PASS);
    mysql_select_db(DB2_NAME,$link);
    mysql_query("SET NAMES utf8");
    $sql=$query;
    $result=mysql_query($sql,$link);
    $rez=array();
    while($row=mysql_fetch_assoc($result))
        $rez[]=$row;
    mysql_free_result($result);
    mysql_close($link);
    
    match_imported_data($rez);
}
