<?php 
/*
* Function to use in template to call the translation service
*/
function rgct($key) 
{
    global $wpdb;
    global $rgct_table_name;
    global $translationTool;

    $result = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix.$rgct_table_name." 
                               WHERE translation_key = '".$key."' 
                                AND lang = '".$translationTool->getCurrentLang()."' 
                                AND value != ''" );
    
    if($result) {
        return $result->value;
    }
    
    return $key;
}




/*
* Form actions
*/
if(isset($_REQUEST['action'])) {
    switch($_REQUEST['action']) {
        case 'generate_db' :
            $translationTool->generateDbEntries(sanitize_key($_GET['lang']));
        break;
        case 'clean_db' : 
            $translationTool->cleanDbEntries(sanitize_key($_GET['lang']));
        break;
        case 'search_db' : 
        $translations = $translationTool->searchDbEntries(sanitize_text_field($_GET['search']), sanitize_key($_GET['lang']));
        break;
        default:
    }
} else {
    $translations = $translationTool->getFields($currentPluginLanguage);
}

