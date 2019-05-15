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



