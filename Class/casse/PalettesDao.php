<?php

class PalettesDao
{

	private $pdo;

	public function __construct($pdo)
	{
		$this->setPdo($pdo);
	}
	public function setPdo($pdo)
	{
		$this->pdo = $pdo;
		return $pdo;
	}


	public function getPaletteByFilter($param)
	{

		$query = "SELECT palettes.*, sum(valo) as valopalette, galec, NumeroPalette, id_palette, btlec, exp, mt_fac
		FROM palettes
		LEFT JOIN casses ON palettes.id=casses.id_palette
		LEFT JOIN exps ON palettes.id_exp = exps.id
		left JOIN qlik.palettes4919 ON palettes.palette=NumeroPalette
		WHERE $param GROUP BY palettes.id ORDER BY palettes.date_crea DESC";

		$req = $this->pdo->query($query);
		return $req->fetchAll();
	}

	public function insertPalette($palette, $destruction, $statut, $idAffectation)
	{
		$req = $this->pdo->prepare("INSERT INTO palettes (palette, date_crea, statut, destruction, id_affectation) VALUES (:palette, :date_crea, :statut, :destruction, :id_affectation) ");
		$req->execute([
			':palette'	=> strtoupper($palette),
			':destruction'		=> $destruction,
			':date_crea' => date('Y-m-d H:i:s'),
			':id_affectation' => $idAffectation,
			':statut' => $statut
		]);
		return $this->pdo->lastInsertId();
	}
	public function getPalette($id)
	{
		$req = $this->pdo->prepare("SELECT * FROM palettes WHERE id= :id");
		$req->execute([
			':id'	=> $id
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function getStockPalette()
	{
		$req = $this->pdo->query("SELECT *, palettes.id as paletteid FROM palettes INNER JOIN qlik.palettes4919 ON palettes.palette = qlik.palettes4919.NumeroPalette");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	// public function getNewStockPalette()
	// {
	// 	$req = $this->pdo->query("SELECT *, palettes.id as paletteid FROM palettes INNER JOIN qlik.palettes4919 ON palettes.palette = qlik.palettes4919.NumeroPalette WHERE palettes.statut<=1 ");
	// 	return $req->fetchAll(PDO::FETCH_ASSOC);
	// }

	public function updatePalette($id, $lastExp, $contremarque,  $idAffectation)
	{
		$req = $this->pdo->prepare("UPDATE palettes SET id_exp= :id_exp, contremarque= :contremarque, id_affectation= :id_affectation WHERE id= :id");
		$req->execute([
			':id_exp'		=> $lastExp,
			':id'			=> $id,
			':contremarque'	=> $contremarque == '' ? null : $contremarque,
			':id_affectation' => $idAffectation

		]);
		return $req->rowCount();
	}

	public function getEnStockDispo()
	{
		$base = VERSION . 'qlik';
		$req = $this->pdo->query("SELECT palette, palettes.*, article, ean, designation, nb_colis, pcb, uvc, valo FROM palettes
			LEFT JOIN casses ON palettes.id = casses.id_palette
			LEFT JOIN {$base}.palettes4919 on palettes.palette=palettes4919.NumeroPalette
			WHERE destruction=0 AND statut= 1 and (id_affectation is null or id_affectation =2 or id_affectation =0) and NumeroPalette is not null");

		return $req->fetchAll(PDO::FETCH_GROUP);
	}



	public function updatePaletteExp($id, $lastExp, $idStatut, $idAffectation)
	{
		$req = $this->pdo->prepare("UPDATE palettes SET id_exp= :id_exp, statut= :id_statut, id_affectation = :id_affectation WHERE id= :id");
		$req->execute([
			':id'			=> $id,
			':id_exp'		=> $lastExp,
			':id_affectation'		=> $idAffectation,
			':id_statut'	=> $idStatut,

		]);
		// return $req->errorInfo();
		return $req->rowCount();
	}
	public function copyPaletteToDeleted($id)
	{
		$req = $this->pdo->prepare("INSERT INTO palettes_deleted (id, palette, statut, id_affectation, destruction, contremarque, date_crea, date_dd_pilote, date_retour_pilote, id_pilote, cmt_pilote, date_info_mag, date_delivery, date_clos, certificat, id_exp) SELECT * FROM palettes where id= :id");
		$req->execute([
			':id'			=> $id,

		]);
		return $req->rowCount();
	}
}
