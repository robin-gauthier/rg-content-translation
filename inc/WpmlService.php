<?php

//namespace inc;

class WpmlService extends TranslationService
{
    public function __contruct() 
    {
        
    }

    public function getName() 
    {
        return 'wpml';
    }

    public function getInfo() 
    {
        return 'Using WPML as a plugin';
    }

    public function getLangList()
    {  
        return array_keys(icl_get_languages());
        //return implode(',',array_keys(icl_get_languages()));   
    }

    public function getDefaultLang()
    {  
        global $sitepress;
        return $sitepress->get_default_language();
    }


    public function getCurrentLang()
    {  
        return apply_filters( 'wpml_current_language', NULL );
    }
}
