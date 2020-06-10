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

}
