<?php

class BaDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}
	public function getArtDossier($art, $dossier){

		$req=$this->pdo->prepare("SELECT * FROM ba WHERE article= :article AND dossier = :dossier");
		$req->execute([
			':article'	=>$art,
			':dossier'	=>$dossier
		]);
		return $req->fetch();
	}
	public function getArtByEan($ean){

		$req=$this->pdo->prepare("SELECT * FROM ba WHERE ean LIKE :ean");
		$req->execute([
			':ean'	=>'%'.$ean.'%',
		]);
		return $req->fetchAll();
	}
	public function getArtById($id){

		$req=$this->pdo->prepare("SELECT * FROM ba WHERE id= :id");
		$req->execute([
			':id'	=>$id,
		]);
		return $req->fetch();
	}
		public function getArtByIdBa($idBa){

		$req=$this->pdo->prepare("SELECT * FROM ba WHERE id_ba= :id_ba");
		$req->execute([
			':id_ba'	=>$idBa,
		]);
		return $req->fetch();
	}


	public function getArtByArt($article){

		$req=$this->pdo->prepare("SELECT * FROM ba WHERE article= :article");
		$req->execute([
			':article'		=>$article
		]);
		return $req->fetchAll();
	}
	// public function getArticle($idArticle){
	// 	$req=$pdoQlik->prepare("SELECT `GESSICA.CodeDossier` as dossier, `GESSICA.CodeArticle` as article, `GESSICA.GT` as gt, `GESSICA.LibelleArticle` as libelle, `GESSICA.PCB` as pcb, `GESSICA.PANF` as panf, `GESSICA.CodeFournisseur` as cnuf, `GESSICA.NomFournisseur` as fournisseur,`GESSICA.PFNP` as pfnp,`GESSICA.D3E` as deee, `GESSICA.SORECOP` as sacem,`GESSICA.CodifD3E` as deee_codif,	id FROM basearticles WHERE id = :id");
	// 	$req->execute(array(
	// 		':id'	=>$idArticle
	// 	));
	// 	return $req->fetch(PDO::FETCH_ASSOC);
	// }

	// function searchArticle($pdoQlik){
	// // $req=$pdoQlik->prepare("SELECT id,`GESSICA.CodeDossier` as dossier FROM basearticles WHERE `GESSICA.CodeArticle`= :article");
	// 	$req=$pdoQlik->prepare("SELECT
	// 		id,
	// 		`GESSICA.CodeDossier` as dossier,
	// 		`GESSICA.GT` as gt,
	// 		`GESSICA.LibelleArticle` as libelle,
	// 		`GESSICA.PCB` as pcb,
	// 		`GESSICA.PANF` as valo,
	// 		`GESSICA.CodeFournisseur` as cnuf,
	// 		`GESSICA.NomFournisseur` as fournisseur,
	// 		`CTBT.StkEnt` as stock
	// 		FROM basearticles WHERE `GESSICA.CodeArticle`= :article ORDER BY `GESSICA.CodeDossier`");
	// 	$req->execute(array(
	// 		':article'	=>$_POST['article']
	// 	));
	// 	return $req->fetchAll(PDO::FETCH_ASSOC);
	// // return $req->errorInfo();
	// }


}


