<?php

class TempsDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getTemps($idEvo){

		$req=$this->pdo->prepare("SELECT * FROM temps WHERE id_evo= :id_evo");
		$req->execute([
			':id_evo'	=>$idEvo
		]);
		return $req->fetchAll();
	}
	public function insertTemps($idEvo, $minutes, $dateExec){
		$req=$this->pdo->prepare("INSERT INTO temps (id_evo, minutes, date_exec) VALUES (:id_evo, :minutes, :date_exec)");
		$req->execute([
			':id_evo'		=>$idEvo,
			':minutes'		=>$minutes,
			':date_exec'		=>$dateExec,
		]);
		return $req->errorInfo();
	}

	public function deleteTemps($id){
		$req=$this->pdo->prepare("DELETE FROM temps WHERE id =:id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->errorInfo();
	}

}


