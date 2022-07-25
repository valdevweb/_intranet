<?php

class CasseHelpers
{


	public static function getPaletteActive($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT id, palette FROM palettes WHERE statut=0 ORDER BY palette");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getPaletteActiveDestruction($pdoCasse, $destruction)
	{
		$req = $pdoCasse->prepare("SELECT id, palette FROM palettes WHERE statut=0 and destruction= :destruction ORDER BY palette");
		$req->execute([
			':destruction'		=> $destruction
		]);
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getOperateur($pdoUser)
	{
		$req = $pdoUser->query("SELECT CONCAT(prenom, ' ', nom) as operateur,id FROM intern_users WHERE mask_casse=0 ORDER BY prenom");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getCategorie($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT * FROM categories WHERE mask=0 ORDER BY categorie");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getOrigine($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT * FROM origines WHERE mask=0 ORDER BY origine");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getTypecasse($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT * FROM type_casse WHERE mask=0 ORDER BY type");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getAffectation($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT id, affectation FROM affectation_palette ORDER BY affectation ");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getAffectationIco($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT id, ico FROM affectation_palette ORDER BY id ");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getListStatutPalette($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT id, statut FROM statuts_palette ORDER BY id ");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function getListStatutPaletteIco($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT id, ico FROM statuts_palette ORDER BY id ");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getStatutsPalette($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT * FROM statuts_palette ORDER BY id ");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getListStatutsExp($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT id, statut FROM statuts_expedition ORDER BY id ");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getListTraitements($pdoCasse, $field)
	{
		$req = $pdoCasse->query("SELECT id, traitement FROM traitements WHERE {$field}= 1 ORDER BY ordre ");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getTraitementsByType($pdoCasse, $field)
	{
		$req = $pdoCasse->query("SELECT * FROM traitements WHERE {$field}= 1 ORDER BY ordre ");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getTraitementsUrl($pdoCasse)
	{
		$req = $pdoCasse->query("SELECT id, url FROM traitements ORDER BY id ");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
}
