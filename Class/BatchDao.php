<?php


class BatchDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getBatchDirAppli($idAppli){

		$req=$this->pdo->prepare("SELECT * FROM batch_dir WHERE id_appli= :id_appli ORDER BY dir");
		$req->execute([
			':id_appli'		=>$idAppli
		]);
		return $req->fetchAll();
	}
	public function getBatchAppli($idAppli){

		$req=$this->pdo->prepare("SELECT * FROM batch LEFT JOIN batch_dir ON id_dir = batch_dir.id  WHERE id_appli= :id_appli  ORDER BY dir, page");
		$req->execute([
			':id_appli'		=>$idAppli
		]);
		return $req->fetchAll();
	}
	public function pageIsInBatch($page, $idAppli){
		$req=$this->pdo->prepare("SELECT * FROM batch LEFT JOIN batch_dir ON id_dir = batch_dir.id WHERE id_appli= :id_appli AND page= :page");
		$req->execute([
			':id_appli'		=>$idAppli,
			':page'		=>$page,
		]);
		return $req->fetchAll();
	}

	public function getIdDir($dir){

		$req=$this->pdo->prepare("SELECT * FROM batch_dir WHERE dir= :dir");
		$req->execute([
			':dir'		=>$dir
		]);
		return $req->fetch();
	}
	public function addBatch($idDir,$url,$page,$descr ){
		$req=$this->pdo->prepare("INSERT INTO batch (id_dir, url, page, descr) VALUES (:id_dir, :url, :page, :descr)");
		$req->execute([
			':id_dir'		=>$idDir,
			':url'		=>$url,
			':page'		=>$page,
			':descr'		=>$descr,

		]);
		return $req->rowCount();
	}
}


