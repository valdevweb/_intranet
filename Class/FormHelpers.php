<?php

class FormHelpers{

/*---------------------------------------------------------
    Réaffichage de données après soumission d'un formulaire
    ---------------------------------------------------------*/

 // case à cocher, btn radio
    public static function checkChecked($value,$field){
        if(isset($_POST[$field])){
            if($_POST[$field]==$value){
                return "checked";
            }
        }
        return "";
    }
// liste déroulante
    public static function checkSelected($value,$field){
        if(isset($_POST[$field])){
            if($_POST[$field]==$value){
                return "selected";
            }
        }
        return "";
    }

// input ou textareo
    public static function restorePost($field){
        if(isset($_POST[$field])){
            return $_POST[$field];
        }
        return "";
    }

 // case à cocher, btn radio
    public static function checkCheckedArray($value,$array){
        if(isset($array)){
            if($array==$value){
                return "checked";
            }
        }

        return "";
    }
//  liste déroulante
    public static function checkSelectedSession($value,$field){
        if(isset($_SESSION[$field])){
            if($_SESSION[$field]==$value){
                return "selected";
            }
        }
        return "";
    }

}
