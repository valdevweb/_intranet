<?php

class EvoHelpers{

	public static function arrayOutilsRespName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, resp FROM outils ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayOutilsRespEmail($pdoEvo){
		$req=$pdoEvo->query("SELECT id, email FROM outils ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayOutilsRespId($pdoEvo){
		$req=$pdoEvo->query("SELECT id, id_resp FROM outils ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayOutilsPlateformeName($pdoEvo){
		$req=$pdoEvo->query("SELECT outils.id, plateforme FROM outils LEFT JOIN plateformes ON id_plateforme=plateformes.id ORDER BY outils.id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
}
