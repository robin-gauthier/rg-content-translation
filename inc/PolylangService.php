<?php

//namespace inc;

class PolylangService extends TranslationService
{
    public function __contruct() 
    {
        
    }

    public function getName() 
    {
        return 'polylang';
    }

    public function getInfo() 
    {
        return 'Using Polylang as a plugin';
    }

    public function getLangList()
    {  
        //return implode(',',pll_languages_list());
        return pll_languages_list();
    }

    public function getDefaultLang()
    {  
        return pll_default_language();
    }

    public function getCurrentLang()
    {  
        return pll_current_language();
    }
}
