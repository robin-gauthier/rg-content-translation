<?php

//namespace inc;

class TranslationService
{
    private $maxPerPages = 2;

    public function getFields($lang)
    {  
        global $rgct_table_name;
        global $wpdb;
        
        $results = $wpdb->get_results(
                    "SELECT * FROM " . $wpdb->prefix . $rgct_table_name . " 
                    WHERE lang = '" . $lang . "'"
                    );

        return $results;
    }

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

    public function searchDbEntries($search, $lang)
    {   
        global $rgct_table_name;
        global $wpdb;
        
        $results = $wpdb->get_results( 
                                "SELECT * FROM " . $wpdb->prefix . $rgct_table_name . " 
                                WHERE lang = '" . $lang . "' AND  
                                (
                                    translation_key like '%" . $search . "%'
                                    OR
                                    value like '%" . $search . "%'
                                )
                                "
        );

        return $results;
        
    }

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

    private function getListFromFiles()
    {   
        $values = array();

        $accepted_files = ['php', 'blade', 'twig'];
        $dir = get_template_directory();
    
        $di = new RecursiveDirectoryIterator($dir);
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            if(in_array($file->getExtension(), $accepted_files)) {
                $contents = file_get_contents($file);
                $pattern = "/rgct\(['\"\s](.)*['\"\s]\)/";
                
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
