<?php

class OccPaletteMgr{


	private $pdoOcc;

	public function __construct($pdoOcc){
		$this->setPdo($pdoOcc);
	}
	public function setPdo($pdoOcc){
		$this->pdoOcc=$pdoOcc;
		return $pdoOcc;
	}

	public function getListPaletteDetailByStatut($statut){
		$req=$this->pdoOcc->prepare("SELECT palettes.id as idpalette, palettes.palette, palettes_articles.*  FROM palettes LEFT JOIN palettes_articles ON palettes.id=palettes_articles.id_palette WHERE statut=:statut ORDER BY palette");
		$req->execute([
			':statut'		=>$statut
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}


	public function getListPaletteByCde($statut){
		$req=$this->pdoOcc->prepare("SELECT palettes.*, cdes_detail.*,cdes_detail.id as id_cde, DATE_FORMAT(date_insert,'%d-%m-%Y') as date_cde FROM palettes LEFT JOIN cdes_detail ON palettes.id=cdes_detail.id_palette WHERE statut=:statut");
		$req->execute([
			':statut'		=>$statut
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function updatePaletteStatut($pdoOcc,$idPalette,$statut){
		$req=$pdoOcc->prepare("UPDATE palettes SET statut= :statut WHERE id= :id");
		$req->execute([
			':id'		=>$idPalette,
			':statut'	=>$statut
		]);
		$err=$req->errorInfo();
		if(!empty($err[2])){
			return false;
		}
		// return $err;
		return true;
	}

	public function updatePaletteCdeStatut($pdoOcc,$idCde,$statut){
		$req=$pdoOcc->prepare("UPDATE palettes LEFT JOIN cdes_detail ON palettes.id=cdes_detail.id_palette SET statut= :statut WHERE id_cde= :id_cde");
		$req->execute([
			':id_cde'		=>$idCde,
			':statut'	=>$statut
		]);
		return $req->rowCount();
	}

	public function getListCommandeByStatut($statut){
		$req=$this->pdoOcc->prepare("SELECT * FROM cdes_numero LEFT JOIN cdes_detail ON cdes_numero.id=cdes_detail.id_cde WHERE statut= :statut GROUP BY id_cde");
		$req->execute([
			':statut'	=>$statut
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);

	}
	public function getCdeByIdCde($idCde){
	$req=$this->pdoOcc->prepare("SELECT cdes_detail.*,palettes_articles.*, cdes_detail.date_insert as date_cde  FROM cdes_detail LEFT JOIN palettes_articles ON cdes_detail.id_palette = palettes_articles.id_palette WHERE  id_cde= :id_cde ORDER BY cdes_detail.id_palette");
	$req->execute([
		':id_cde'	=>$idCde

	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

}


