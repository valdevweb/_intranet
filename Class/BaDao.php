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
}


