<?php


class DailymailDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function newGazetteToday(){

		$req=$this->pdo->prepare("SELECT * FROM gazette WHERE DATE_FORMAT(date_insert,'%Y-%m-%d')=:date_insert");
		$req->execute([
			':date_insert'		=>date('Y-m-d')
		]);
		return $req->fetchAll();

	}

	public function newGesapToday(){

		$req=$this->pdo->prepare("SELECT * FROM gesap WHERE DATE_FORMAT(date_insert,'%Y-%m-%d')=:date_insert");
		$req->execute([
			':date_insert'		=>date('Y-m-d')
		]);
		return $req->fetchAll();

	}

	public function newInfoLivToday(){

		$req=$this->pdo->prepare("SELECT * FROM infos_livraison WHERE DATE_FORMAT(date_insert,'%Y-%m-%d')=:date_insert");
		$req->execute([
			':date_insert'		=>date('Y-m-d')
		]);
		return $req->fetchAll();

	}


	public function newOdrToday(){

		$req=$this->pdo->prepare("SELECT * FROM odr WHERE DATE_FORMAT(date_insert,'%Y-%m-%d')=:date_insert");
		$req->execute([
			':date_insert'		=>date('Y-m-d')
		]);
		return $req->fetchAll();

	}

	public function newTelBriiToday(){

		$req=$this->pdo->prepare("SELECT * FROM prospectus_offres WHERE DATE_FORMAT(date_insert,'%Y-%m-%d')=:date_insert");
		$req->execute([
			':date_insert'		=>date('Y-m-d')
		]);
		return $req->fetchAll();

	}
}


