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

}