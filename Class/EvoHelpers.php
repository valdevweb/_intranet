<?php

class EvoHelpers{

	public static function arrayAppliRespName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, resp FROM appli ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayAppliName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, appli FROM appli ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayPlateformeName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, plateforme FROM plateformes ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayModuleName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, module FROM modules ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayAppliRespEmail($pdoEvo){
		$req=$pdoEvo->query("SELECT id, email FROM responsables ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayAppliRespId($pdoEvo){
		$req=$pdoEvo->query("SELECT id, id_resp FROM appli ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayRespName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, resp FROM responsables ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayAppliPlateformeName($pdoEvo){
		$req=$pdoEvo->query("SELECT appli.id, plateforme FROM appli LEFT JOIN plateformes ON id_plateforme=plateformes.id ORDER BY appli.id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
}
