<?php
/*
* Ajax function to save current page translation form
*/

add_action( 'wp_ajax_rgct_save', 'rgct_save' );
function rgct_save() {
    global $rgct_table_name;
    global $wpdb;

    $lang = sanitize_key($_POST['lang']);
        
    foreach($_POST['form'] as $input=>$value) {
        $wpdb->update(
            $wpdb->prefix.$rgct_table_name,
            array('value'=>stripslashes($value)),
            array('id'=>$input)
        );
    }
    
    wp_die();
}
