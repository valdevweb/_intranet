<?php


class LitigeHelpers{

	public static function listReclamationIncludingMasked($pdoLitige){
		// !!!!! attention, on a masqué les inversions de référence mais on converti les manquant /excédent en inversion de référence
		// donc on a parfois besoin des relcamation non masquée
		$req=$pdoLitige->query("SELECT id, reclamation FROM reclamation ORDER BY reclamation");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function listReclamation($pdoLitige){
		// !!!!! attention, on a masqué les inversions de référence mais on converti les manquant /excédent en inversion de référence
		// donc on a parfois besoin des relcamation non masquée
		$req=$pdoLitige->query("SELECT id, reclamation FROM reclamation WHERE mask=0 ORDER BY reclamation");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listTypo($pdoLitige){
		$req=$pdoLitige->query("SELECT id, typo FROM typo WHERE mask=0 ORDER BY typo");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

}