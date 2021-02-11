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
		$req=$this->pdoOcc->prepare("SELECT palettes.id as idpalette, palettes.palette, palettes_articles.* ,date_import, import_cmt.cmt, import_cmt.date_end FROM palettes
			LEFT JOIN palettes_articles ON palettes.id=palettes_articles.id_palette
			LEFT JOIN import_excel ON palettes.import=import_excel.id
			LEFT JOIN import_cmt ON palettes.import=import_cmt.id_import
			WHERE statut=:statut ORDER BY palettes_articles.id_import, palette");
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
	public function getListPaletteByStatut($statut){
		$req=$this->pdoOcc->prepare("SELECT palettes.*, import_excel.filename, import_excel.date_import FROM palettes
			LEFT JOIN import_excel ON palettes.import=import_excel.id
			WHERE statut=:statut ORDER BY date_import, palettes.id");
		$req->execute([
			':statut'		=>$statut
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getListArticleOccByArticlePalette($articlePalette){
		$req=$this->pdoOcc->prepare("SELECT id as id_article_occ, article_palette, designation as libelle, ean as gencod, quantite as qte, pa as tarif FROM palettes_articles WHERE article_palette=:article_palette");
		$req->execute([
			':article_palette'		=>$articlePalette
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
		$req=$this->pdoOcc->prepare("SELECT *, cdes_numero.id as id FROM cdes_numero LEFT JOIN cdes_detail ON cdes_numero.id=cdes_detail.id_cde WHERE statut= :statut GROUP BY id_cde");
		$req->execute([
			':statut'	=>$statut
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListCommandeByMag($idwebuser){
		$req=$this->pdoOcc->prepare("SELECT * FROM cdes_numero LEFT JOIN cdes_detail ON cdes_numero.id=cdes_detail.id_cde WHERE id_web_user = :id_web_user GROUP BY id_cde");
		$req->execute([
			':id_web_user'	=>$idwebuser
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

	public function getActiveListPaletteCmt(){
		$req=$this->pdoOcc->prepare("SELECT * FROM import_cmt LEFT JOIN import_excel ON id_import= import_excel.id WHERE date_end >= :date_end");
		$req->execute([
			':date_end'		=>date('Y-m-d H:i:s')
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);


	}

	public function getListImport(){
		$req=$this->pdoOcc->query("SELECT * FROM import_excel WHERE mask=0 order BY date_import");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListCmtImport(){
		$req=$this->pdoOcc->query("SELECT * FROM import_cmt LEFT JOIN import_excel ON id_import=import_excel.id WHERE mask=0 order BY id_import");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getNbPaletteDispo($idImport){
		$req=$this->pdoOcc->prepare("SELECT count(id) as nb FROM palettes	WHERE statut=:statut AND import= :import ");
		$req->execute([
			':statut'		=>1,
			':import'		=>$idImport

		]);
		$data=$req->fetch();
		return $data['nb'];
	}

	public function getPaletteStatut($pdoOcc,$id){
		$req=$this->pdoOcc->prepare("SELECT * FROM palettes WHERE id= :id ");
		$req->execute([
			':id'	=>$id

		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getMaxPaletteImport($idPalette){
		$req=$this->pdoOcc->prepare("SELECT * FROM palettes LEFT JOIN import_excel ON palettes.import=import_excel.id WHERE palettes.id= :id_palette");
		$req->execute([
			':id_palette'	=>$idPalette

		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	

}


