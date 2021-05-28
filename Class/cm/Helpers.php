<?php

class Helpers{

	/**
	 * lien btn retour
	 * @var string
	 */
	private $page;
	/**
	 * couleur btn retour optionnel
	 * @var string
	 */
	private $color;
	private $title;
	private $headers;
	private $str;
	private $id;



	public static function returnBtn($page,$color=null)
	{
		if($color==null){
			return '<div class="row py-3"><div class="col"><p class="text-right"><a href="'.$page.'" class="btn btn-primary">Retour</a></p></div></div>';
		}
		return '<div class="row py-3"><div class="col"><p class="text-right"><a href="'.$page.'" class="btn '.$color.'">Retour</a></p></div></div>';

	}
	public static function returnBtnJs($color=null)
	{
		if($color==null){
			return '<div class="row py-3"><div class="col"><p class="text-right"><a href="javascript:history.go(-1)" class="btn btn-primary">Retour</a></p></div></div>';
		}
		return '<div class="row py-3"><div class="col"><p class="text-right"><a href="javascript:history.go(-1)" class="btn '.$color.'">Retour</a></p></div></div>';

	}


	public static function withZero($chiffre){
		if($chiffre < 10){
			return '0' . $chiffre;
		}else{
			return $chiffre;
		}
	}

	public static function renderTitle($title){
		$firstLetter=substr($title,0,1);
		$otherLetters=substr($title,1,(strlen($title)-1));
		return '<h1 class="text-grey-d9 line-under varela pb-5"><span class="first-letter-two">'.$firstLetter.'</span>'.$otherLetters.'</h1>';

	}

	public static function tableHeader($headers){
		$table='<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr>';
		for ($i=0; $i < count($headers); $i++) {
			$table.='<th>'.$headers[$i].'</th>';
		}
		$table.='</thead><tbody>';
		return $table;

	}

	public static function separateurAuto($sep,$array,$lig){

		if(count($array)==$lig +1 ){
			return '';
		}else{
			return $sep;
		}
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





	public static function unsanitize($str){
		return nl2br(html_entity_decode($str));
	}

	public static function cmName($id){
		$cm=[1273=>'Sébastien',1274=>'Julien', 1275=>'Cyrille'];
		return $cm[$id];
	}
	public static function cmFullName($id){
		$cm=[1273=>'Sébastien Fournier',1274=>'Julien Guegan', 1275=>'Cyrille Canavatte'];
		return $cm[$id];
	}
}
