<?php

/*
* Create custom table in database on install
*/
function rgct_install () {
    
    global $wpdb;
    global $rgct_version;
    global $rgct_table_name;
        
    $table_name = $wpdb->prefix . $rgct_table_name; 
    $charset_collate = $wpdb->get_charset_collate();
    

	$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `entry_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                `last_modified` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                `translation_key` varchar(500) DEFAULT '' NOT NULL,
                `value` text DEFAULT '' NOT NULL,
                `lang` varchar(5) DEFAULT NULL,
                UNIQUE KEY id (id),
                INDEX {$table_name}_translation_key_IDX (translation_key),
                INDEX {$table_name}_lang_IDX (lang)
            ) ENGINE=InnoDB DEFAULT CHARSET=$charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );    
    
	dbDelta( $sql );

	add_option( 'rgct_version', $rgct_version );
   
}

