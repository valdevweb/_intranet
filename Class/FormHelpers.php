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
    // valeur
    public static function restoreValue($value){
        if(isset($value)){
            return $value;
        }
        return "";
    }

    public static function restoreChecked($value,$dbValue){
        if(isset($dbValue)){
            if($dbValue==$value){
                return "checked";
            }
        }
        return "";
    }
    public static function restoreSelected($value,$dbValue){
        if(isset($dbValue)){
            if($dbValue==$value){
                return "selected";
            }
        }
        return "";
    }

        public static function restoreSelectedArray($value,$array){
            if(in_array($value,$array)){
                return "selected";
            }
            return "";

    }

           public static function restoreCheckedArray($value,$array){
            if(in_array($value,$array)){
                return "checked";
            }
            return "";

    }

    public static function checkSelectedArray($value,$array){
        if(isset($array)){
            for ($i=0; $i < count($array); $i++) {
                if($array[$i]==$value)
                    return "selected";
            }
        }

    }

 // case à cocher, btn radio
    public static function checkCheckedArray($value,$postName){
        if(isset($_POST[$postName])){
            for ($i=0; $i < count($_POST[$postName]); $i++) {
                if($_POST[$postName][$i]==$value)
                    return "checked";
            }
        }

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
