<?php

class CasseHelpers{


	public static function getPaletteActive($pdoCasse){

		$req=$pdoCasse->query("SELECT id, palette FROM palettes WHERE statut=0 ORDER BY palette");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getOperateur($pdoUser){
		$req=$pdoUser->query("SELECT CONCAT(prenom, ' ', nom) as operateur,id FROM intern_users WHERE mask_casse=0 ORDER BY prenom");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getCategorie($pdoCasse){
		$req=$pdoCasse->query("SELECT * FROM categories WHERE mask=0 ORDER BY categorie");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getOrigine($pdoCasse){
		$req=$pdoCasse->query("SELECT * FROM origines WHERE mask=0 ORDER BY origine");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getTypecasse($pdoCasse){
		$req=$pdoCasse->query("SELECT * FROM type_casse WHERE mask=0 ORDER BY type");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}

