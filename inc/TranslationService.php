<?php

//namespace inc;

class TranslationService
{
    /*
    * Maximum item per pages
    */
    private $maxPerPages = 100;

    /*
    * if this value is changed, you will need to overwrite the rgct function in your functions.php

    function nameOverwrite($key) {
        return rgct($key);
    }

    */
    private $templatePattern = 'rgct';

    /*
    *   Get all translation from current language
    *   Return an array of values
    *   totalItems = Number of results from the search
    *   totalPages = Maximum page numbers
    *   currentPage = Current page selected
    *   results = List of results from the database
    */
    public function getFields($lang)
    {  
        global $rgct_table_name;
        global $wpdb;

        $page = isset($_GET['paginate'])?$_GET['paginate']:1;
        $offset  = ($page - 1) * $this->maxPerPages;
        $totalItems = $this->getTotalItems($lang);

        if(!$totalItems) {
            return [
                    'totalItems' => 0,
                    'totalPages' => 0,
                    'currentPage' => $page,
                    'results' => []
                    ];
        }
        
        $results = $wpdb->get_results(
                    "SELECT * FROM " . $wpdb->prefix . $rgct_table_name . " 
                    WHERE lang = '" . $lang . "' LIMIT " . $offset . "," . $this->maxPerPages
                    );
        
        return [
            'totalItems' => $totalItems,
            'totalPages' => ceil($totalItems/$this->maxPerPages),
            'currentPage' => $page,
            'results' => $results
            ];
    }

    /*
    * Save all new translation keys from templates
    * Redirect back to the list
    */
    public function generateDbEntries($lang) 
    {
        global $rgct_table_name;
        global $wpdb;
        
        foreach($this->getListFromFiles() as $val) {
                
            $exist = $wpdb->query( 
                        $wpdb->prepare( 
                            "SELECT * FROM " . $wpdb->prefix . $rgct_table_name . " 
                             WHERE translation_key = %s AND lang = %s", $val, $lang
                        )
                    );
            if(!$exist) {
                $wpdb->insert( 
                    $wpdb->prefix . $rgct_table_name, 
                    array( 'entry_date'      => date('Y-m-d H:i:s'), 
                           'last_modified'   => date('Y-m-d H:i:s'),
                           'translation_key' => $val,
                           'value'           => '',
                           'lang'            => $lang,
                            )
                );
            }
        }

        header('location:/wp-admin/admin.php?page=rg-content-translation%2Flist.php&lang='. $lang);

    }

    /*
    *   Get all translation from current language and search term
    *   Return an array of values
    *   totalItems = Number of results from the search
    *   totalPages = Maximum page numbers
    *   currentPage = Current page selected
    *   results = List of results from the database
    */
    public function searchDbEntries($search, $lang)
    {   
        global $rgct_table_name;
        global $wpdb;
        
        $searchSql = " AND  
        (
            translation_key like '%" . $search . "%'
            OR
            value like '%" . $search . "%'
        )";

        $page = isset($_GET['paginate'])?$_GET['paginate']:1;
        $offset  = ($page - 1) * $this->maxPerPages;
        $totalItems = $this->getTotalItems($lang, $searchSql);

        if(!$totalItems) {
            return [
                    'totalItems' => 0,
                    'totalPages' => 0,
                    'currentPage' => $page,
                    'results' => []
                    ];
        }
        
        $results = $wpdb->get_results( 
                    "SELECT * FROM " . $wpdb->prefix . $rgct_table_name . " 
                    WHERE lang = '" . $lang . "' " . $searchSql . "
                    LIMIT " . $offset . "," . $this->maxPerPages
        );

        return [
            'totalItems' => $totalItems,
            'totalPages' => ceil($totalItems/$this->maxPerPages),
            'currentPage' => $page,
            'results' => $results
            ];

        return $results;
        
    }

    /*
    * Delete unused keys from the database
    * Redirect back to the list
    */
    public function cleanDbEntries($lang)
    {   
        global $rgct_table_name;
        global $wpdb;

        $toDelete = [];
        $keys = $this->getListFromFiles();        

        foreach($this->getListFromDb($lang) as $row) {
            if(!in_array($row->translation_key, $keys)) {
                array_push($toDelete, $row->id);
            }
        }

        if(count($toDelete)) {
            $ids = implode( ',', array_map( 'absint', $toDelete ) );
            $wpdb->query( "DELETE FROM " . $wpdb->prefix . $rgct_table_name . " WHERE ID IN($ids)" );
        }

        header('location:/wp-admin/admin.php?page=rg-content-translation%2Flist.php&lang='. $lang);
        
    }

    /*
    * Return count of results from the database
    */
    private function getTotalItems($lang, $search = '')
    {   
        global $rgct_table_name;
        global $wpdb;

        $rows = $wpdb->get_results(
                        "SELECT COUNT(*) as num_rows  FROM " . $wpdb->prefix . $rgct_table_name . " 
                        WHERE lang = '" . $lang . "' ". $search
                    ); 

        return $rows[0]->num_rows;
    }

    /*
    * Iterate over all files from current theme
    * Return an array of keys used in templates
    */
    private function getListFromFiles()
    {   
        $values = array();

        $accepted_files = ['php', 'blade', 'twig'];
        $dir = get_template_directory();
    
        $di = new RecursiveDirectoryIterator($dir);
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            if(in_array($file->getExtension(), $accepted_files)) {
                $contents = file_get_contents($file);
                $pattern = "/".$this->templatePattern."\(['\"\s](.)*['\"\s]\)/";
                
                if(preg_match_all($pattern, $contents, $matches)){            
                    foreach($matches[0] as $term) {                
                        $start = (  strpos($term, "'") == 5 && strpos($term, '"') != 6 || 
                                    strpos($term, "'") != 6 && strpos($term, '"') == 5
                                 )?5:6;
                        
                        $lastChars = substr($term, -2);
                        $last =(strpos($lastChars, "'") === false && strpos($lastChars, '"') === false)?-3:-2;                
                        
                        $val = substr($term, $start+1, $last);
                        
                        array_push($values, $val);
                    }
                }
            }
        }

        return $values;
    }

    /*
    * Return all entries associated with a language from the database
    */
    private function getListFromDb($lang)
    {   
        global $rgct_table_name;
        global $wpdb;
        
        $results = $wpdb->get_results( 
                                "SELECT * FROM " . $wpdb->prefix . $rgct_table_name . " 
                                WHERE lang = '" . $lang . "'"
        );

        return $results;
    }
}
