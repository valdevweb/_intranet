<?php

class OccPaletteMgr{


	private $pdoBt;

	public function __construct($pdoBt){
		$this->setPdo($pdoBt);
	}
	public function setPdo($pdoBt){
		$this->pdoBt=$pdoBt;
		return $pdoBt;
	}

	public function getListPaletteDetailByStatut($statut){
		$req=$this->pdoBt->prepare("SELECT occ_palettes.id as idpalette, occ_palettes.palette, occ_articles.*  FROM occ_palettes LEFT JOIN occ_articles ON occ_palettes.id=occ_articles.id_palette WHERE statut=:statut");
		$req->execute([
			':statut'		=>$statut
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}


	public function getListPaletteByCde($statut){
		$req=$this->pdoBt->prepare("SELECT occ_palettes.*, occ_cdes.*,occ_cdes.id as id_cde, DATE_FORMAT(date_insert,'%d-%m-%Y') as date_cde FROM occ_palettes LEFT JOIN occ_cdes ON occ_palettes.id=occ_cdes.id_palette WHERE statut=:statut");
		$req->execute([
			':statut'		=>$statut
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function updatePaletteStatut($pdoBt,$idPalette,$statut){
		$req=$pdoBt->prepare("UPDATE occ_palettes SET statut= :statut WHERE id= :id");
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

}
