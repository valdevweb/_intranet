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
	public static function listReclamationEdit($pdoLitige){
		$req=$pdoLitige->query("SELECT id, reclamation FROM reclamation WHERE noedit=0 ORDER BY reclamation");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listTypo($pdoLitige){
		$req=$pdoLitige->query("SELECT id, typo FROM typo WHERE mask=0 ORDER BY typo");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listReclamationContrainte($pdoLitige){
		$req=$pdoLitige->query("SELECT id, reclamation_contrainte FROM reclamation_contrainte ORDER BY reclamation_contrainte");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function listReclamationPhoto($pdoLitige){
		$req=$pdoLitige->query("SELECT id FROM reclamation WHERE id_contrainte=1");
		return $req->fetchAll(PDO::FETCH_COLUMN);
	}

	public static function listTypoAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, typo FROM typo ORDER BY typo");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function listAffreteAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, affrete FROM affrete ORDER BY affrete");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function listAnalyseAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, analyse FROM analyse ORDER BY analyse");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function listConclusionAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, conclusion FROM conclusion ORDER BY conclusion");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listEquipeAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, CONCAT (nom, ' ', prenom) FROM equipe ORDER BY nom");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listEtatAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, etat FROM etat ORDER BY etat");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function listImputationAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, imputation FROM imputation ORDER BY imputation");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listTransitAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, transit FROM transit ORDER BY transit");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listTransporteurAll($pdoLitige){
		$req=$pdoLitige->query("SELECT id, transporteur FROM transporteur ORDER BY transporteur");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function listActions($pdoLitige){
		$req=$pdoLitige->query("SELECT id, action FROM actions ORDER BY action");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
}