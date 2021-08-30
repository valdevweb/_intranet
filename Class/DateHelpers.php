<?php

class DateHelpers{


	private const JOURSHORT=[ '', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam','dim'];
	private const JOURLONG=[ '','lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi','dimanche',];
	private const DAYOFWEEK=['',' monday', 'tuesday' , 'wednesday' , 'thursday' , 'friday' , 'saturday'	,'sunday' ];
	// n
	private const MONTHLONG=['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
	private const MONTHSHORT=['', 'janv', 'fév', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc'];


	// convertDateToStringJour('2020/01/30' => mer)
	public static function convertDateToStringJour($date,$size=null){
		$date=new DateTime($date);
		if(isset($size)){
			return $jour=self::JOURLONG[$date->format('N')];
		}
		return $jour=self::JOURSHORT[$date->format('N')];

	}
	public static function convertDateTimeToStringJour($date,$size=null){

		if(isset($size)){
			return $jour=self::JOURLONG[$date->format('N')];
		}
		return $jour=self::JOURSHORT[$date->format('N')];

	}

	public static function convertDateToStringMois($date,$size=null){
		$date=new DateTime($date);
		if(isset($size)){
			return	$mois=self::MONTHLONG[$date->format('n')];
		}
		return	$mois=self::MONTHSHORT[$date->format('n')];
	}


	public static function convertDateTimeToStringMois($datetime,$size=null){
		if(isset($size)){
			return	$mois=self::MONTHLONG[$datetime->format('n')];
		}
		return	$mois=self::MONTHSHORT[$datetime->format('n')];
	}

	public static function concatJourMoisDateTime($date, $size=null){
		$jour=self::convertDateTimeToStringJour($date, $size);
		$mois=self::convertDateTimeToStringMois($date, $size);
		return $jour .' ' .$date->format('j').' ' .$mois;
	}
	public static function getDateJourDuMois($date){
		$date=new DateTime($date);
		return	$jourDate=$date->format('j');

	}

	public static function getDateYear($date){
		$date=new DateTime($date);
		return	$jourDate=$date->format('Y');

	}

	public static function getDateHour($date){
		$date=new DateTime($date);
		return $date->format("H:i");
	}

	public static function testDate($dateStartProsp, $dateEndProsp){
		$today=new DateTime();
		$today2=new DateTime();
		$todayStart=$today->modify('+ 15 day');
		$todayEnd=$today2->modify('- 7 day');
		if($dateStartProsp <=$todayStart && $dateEndProsp >= $todayEnd){
			echo 'la date de début de prospectus '.$dateStartProsp->format('d-m-Y') .' est inférieur à aujourd\'hui + 15 ('.$todayStart->format('d-m-Y') .')<br>';
			echo 'la date de fin de prospectus '.$dateEndProsp->format('d-m-Y') .' est inférieur à aujourd\'hui -7 ('.$todayEnd->format('d-m-Y') .')<br>';
			echo "on affhiche";
		}else{
			echo 'la date de début de prospectus '.$dateStartProsp->format('d-m-Y') .' n\'est pas inférieur à aujourd\'hui + 15 ('.$todayStart->format('d-m-Y') .')<br>';
			echo ' ou la date de fin de prospectus '.$dateEndProsp->format('d-m-Y') .' n\'est pas inférieur à aujourd\'hui -7 ('.$todayEnd->format('d-m-Y') .')<br>';

			echo "hors période";
		}

	}

	public static function frenchMonth($month, $size=null){
		if(isset($size)){
			return	$mois=self::MONTHLONG[$month];
		}
		return	$mois=self::MONTHSHORT[$month];
	}

}