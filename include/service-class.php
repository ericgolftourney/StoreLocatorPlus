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