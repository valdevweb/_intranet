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



}
