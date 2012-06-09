<?php

/***********************************************************************
* Class: slp_service_class
*
* The slplus service creation object
*
* This handles the creation of the mobile listener service
*
************************************************************************/

if (! class_exists('slp_service_class')) {
    class slp_service_class {
        
        
            /*************************************
             * The Constructor
             */
            function __construct($params) {
                foreach ($params as $name => $value) {            
                    $this->$name = $value;
                }
            }

            function Sync_tags() {
                global $wpdb;
                
                // Initialize the table definitions
                //
                $table = $wpdb->prefix."store_locator";
                $tag_table = $wpdb->prefix."slp_tags";
                $link_table = $wpdb->prefix."slp_loc_tags";

                //gets all the tags
                //
                $query = "SELECT sl_tags, sl_id
                            FROM $table
                            WHERE sl_tags <> ''";
                $results = $wpdb->get_results($query);

                //If there are tags, continue
                //
                if ($results) {

                    $tags = array();
                    $new_tags = array();
                    $parts = null;
                    $post_to_tag = array();

                    //get all the tags from the result
                    //escape them, and add them to our tags list
                    //
                    foreach ($results as $tag)
                    {
                        $expr = "/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/";
                        $parts = preg_split($expr, trim(html_entity_decode($tag->sl_tags, ENT_QUOTES | ENT_HTML5)));
                        $parts = preg_replace("/^\"(.*)\"$/","$1",$parts);
                        $post_to_tag = array();
                        foreach ($parts as $part) {
                            
                            if (trim($part) != '') {
                                $tags[] = trim($part);
                                $post_to_tag[] = trim($part);
                            }
                        }

                        //insert all the tags into the tag table
                        //
                        foreach ($post_to_tag as $tagr) {
                            $col = array(
                                'tag_name' => $tagr
                            );

                            // determine the id of an already existing tag
                            $query = "select tag_id from $tag_table where tag_name = '$tagr';";
                            $success = $wpdb->get_results($query);

                            if(count($success) != 0) {
                                $new_tags[$tagr] = $success[0]->tag_id;
                            }
                            // it isn't created yet, so create the tag
                            else {
                                $success = $wpdb->insert($tag_table, $col);
                                if (!isset($new_tags[$tagr])) {
                                    $new_tags[$tagr] = $wpdb->insert_id;
                                }
                            }

                            $col = array(
                                'tag_id' => $new_tags[$tagr],
                                'sl_id' => $tag->sl_id
                            );
                            $success = $wpdb->insert($link_table, $col);                            
                        }
                        $query = "select * from $link_table where sl_id = $tag->sl_id and (tag_id != 0 ";

                        foreach ($post_to_tag as $existing_tag) {
                            $query .= "and tag_id != $new_tags[$existing_tag] ";
                        }

                        $query .= ");";
                        $success = $wpdb->get_results($query);

                        // Delete anything that shouldn't be in the table
                        foreach ($success as $to_delete) {
                            $query = "delete link.* from wp_slp_loc_tags link where tag_id = $to_delete->tag_id and $to_delete->sl_id;";
                            $wpdb->query($query);
                        }
                    }
                }
            }

            function SearchTags($tag)
            {
                global $wpdb;

                $query = "select 
                                link.sl_id
                            from
                                wp_slp_tags tags
                                left join wp_slp_loc_tags link on (tags.tag_id = link.tag_id)
                            where
                                tags.tag_name like $tag;";
                
                $results = $wpdb->get_results($query);
                //do this right
                return $results[0]->sl_id;
            }

            function GetAllTags() {
                global $wpdb;
                $query = "select 
                                *
                            from
                                wp_slp_tags";
                
                $results = $wpdb->get_results($query);
                //do this right
                return $results;
            }

            function GetTags($sl_id)
            {
                global $wpdb;
                $query = "select 
                                slp.sl_id,
                                group_concat(distinct tags.tag_name separator ',') as sl_tags
                            from
                                wp_store_locator slp
                                left join wp_slp_loc_tags link on (slp.sl_id = link.sl_id)
                                left join wp_slp_tags tags on (link.tag_id = tags.tag_id)
                            where slp.sl_id = $sl_id
                            group by
                                slp.sl_id
                            ";

                $results = $wpdb->get_results($query);

                return $results[0]->sl_tags;
            }
    }
}