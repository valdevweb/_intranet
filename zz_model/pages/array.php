<?php

// SAVOIR SI TABLEAU MULTIDIMENTIONNEL EST VIDE
// vide le tableau si il est vide (multidimentional array => jamais vide même si pas de donnée ppuis que contient au moins un tableau vide ou non)
// input type="text" name=serial[]
$emptiedSerial = array_filter($_POST['serial']);
if(!empty($emptiedSerial))
{

	for($i=0;$i<count($_POST['serial']);$i++)
	{
		if(!empty($_POST['serial'][$i]))
		{
			$add=addSerials($pdoCasse,$lastInsertId,$_POST['serial'][$i]);
			if($add!=1){
				$errors[]="impossible d'ajouter le numéro de serie";
			}
		}
	}

}


//in_array multidimentionnel

$centraleRight=
Array
(
    [0] => 1
    [1] => 2
    [2] => 37
    [3] => 28
    [4] => 60
    [5] => 72
)


function in_array_r($item , $array){
    return preg_match('/"'.preg_quote($item, '/').'"/i' , json_encode($array));
}


		if(in_array_r($right['id'],$centraleRight))
		{
			echo $right['id'] .'<br>';
		}

$url_in_array = in_array('urlof5465', array_column($userdb, 'url'));


ou

$userdb = Array
(
    (0) => Array
        (
            ('uid') => '100',
            ('name') => 'Sandra Shush',
            ('url') => 'urlof100'
        ),

    (1) => Array
        (
            ('uid') => '5465',
            ('name') => 'Stefanie Mcmohn',
            ('url') => 'urlof5465'
        ),

    (2) => Array
        (
            ('uid') => '40489',
            ('name') => 'Michael',
            ('url') => 'urlof40489'
        )
);



$url_in_array = in_array('urlof5465', array_column($userdb, 'url'));


// in_array('needle', call_user_func_array('array_merge', $arr))