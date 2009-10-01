<?php
//debug class - optional

class debughandler {

    function debug($type,$string) {
        // nimmt fehler auf, leitet richtig weiter und gibt aus
        switch($type) {
            case 1: // normale fehler
            $error = '';
            break;
            case 2: // oop
            $error = '';
            break;
            case 3: // debugdurchleitung
            $error = '<br>::DEBUG:: '.$string.' ::DEBUG::<br>';
            break;
            default:
            die('Fatal Error - Unknown Errortype!');
            } 

        return $error;
        }

    }

class no_debughandler {
        
    function debug($type,$string){
        }

    }

?>
