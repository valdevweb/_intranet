<?php

class FormHelpers{

public static function checkChecked($value,$field){
    if(isset($_POST[$field])){
        if($_POST[$field]==$value){
            return "checked";
        }
    }

    return "";
}
// réffichage liste déroulante formulaire
public static function checkSelected($value,$field){
    if(isset($_POST[$field])){
        if($_POST[$field]==$value){
            return "selected";
        }
    }

    return "";
}

public static function checkCheckedArray($value,$array){
    if(isset($array)){
        if($array==$value){
            return "checked";
        }
    }

    return "";
}
// réffichage liste déroulante formulaire
public static function checkSelectedSession($value,$field){
    if(isset($_SESSION[$field])){
        if($_SESSION[$field]==$value){
            return "selected";
        }
    }

    return "";
}

}
