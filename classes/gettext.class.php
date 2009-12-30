<?php
//
//      Quassel Backlog Search - classes
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

//gettext class - optional (requiers php with gettext support)


//return default-string, if no gettext is installed.
if(!function_exists('gettext')){
    function gettext($string){
        return $string;
        }
    }else{
    require('config.php');
    
    // set default language
    if(!isset($language) OR empty($language) OR !is_dir('i18n/'.$language)){
        $language = 'en_US';
        }
// set gettext infos
setlocale(LC_MESSAGES,$language);
// ./i18n/$language/LC_MESSAGES/qbs.mo
bindtextdomain("qbs", "./i18n");
bind_textdomain_codeset("qbs", "UTF-8");
textdomain("qbs");
}

// set _() alias, if not installed.
if(!function_exists('_')){
    function _($string){
        return gettext($string);
        }
    }

?>