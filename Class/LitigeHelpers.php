<?php


class LitigeHelpers{

	public static function listReclamation($pdoLitige){
		$req=$pdoLitige->query("SELECT id, reclamation FROM reclamation WHERE mask=0 ORDER BY reclamation");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}


}