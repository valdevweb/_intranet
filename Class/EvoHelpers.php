<?php

class EvoHelpers{

	public static function arrayAppliRespName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, resp FROM appli ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayAppliRespEmail($pdoEvo){
		$req=$pdoEvo->query("SELECT id, email FROM appli ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayAppliRespId($pdoEvo){
		$req=$pdoEvo->query("SELECT id, id_resp FROM appli ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayAppliPlateformeName($pdoEvo){
		$req=$pdoEvo->query("SELECT appli.id, plateforme FROM appli LEFT JOIN plateformes ON id_plateforme=plateformes.id ORDER BY appli.id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
}
