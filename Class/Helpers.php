<?php

class Helpers{


	private $page;

	private $color;

	public static function returnBtn($page,$color=null)
	{
		if(!$color){
			return '<div class="row py-3"><div class="col"><p class="text-right"><a href="'.$page.'" class="btn btn-primary">Retour</a></p></div></div>';
		}else{
			return '<div class="row py-3"><div class="col"><p class="text-right"><a href="'.$page.'" class="btn '.$color.'">Retour</a></p></div></div>';

		}
	}


	public static function arrayFlatten($array,$indexName,$valueName){
		for ($i=0; $i <count($array) ; $i++) {
			$newArray[$array[$i][$indexName]]=$array[$i][$valueName];
		}
		return $newArray;
	}

	public static function sanitize($str){
//convertit les caractères éligible en entités html
		$str = htmlentities($str);
//supprime anti-slash
		$str= stripslashes($str);
//Supprime les balises HTML et PHP d'une chaîne
		$str = strip_tags($str);
//supprime espace deb et fin chaine
		$str= trim($str);
		return $str;
	}



	public static function withZero($chiffre){
		if($chiffre < 10){
			return '0' . $chiffre;
		}else{
			return $chiffre;
		}
	}



	public static function separateurAuto($sep,$array,$lig){
		if(count($array)==$lig +1){
			return '';
		}else{
			return $sep;
		}
	}



}
