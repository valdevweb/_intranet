<?php

class DroitDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getAttributionUsersByType($idType){
		$req=$this->pdo->prepare("SELECT id_user, web_users.users.login, id_droit, attributions.id as id, fonction, description FROM attributions
			LEFT JOIN users ON attributions.id_user=users.id
			LEFT JOIN droits ON attributions.id_droit=droits.id
			where id_type= :id_type ORDER BY web_users.users.login, id_droit");
		$req->execute([
			':id_type'	=>$idType
		]);
		return $req->fetchAll();

	}
		public function getAttributionByType($idType){
		$req=$this->pdo->prepare("SELECT id_user, web_users.users.login, id_droit, attributions.id as id, fonction, description FROM attributions
			LEFT JOIN users ON attributions.id_user=users.id
			LEFT JOIN droits ON attributions.id_droit=droits.id
			where id_type= :id_type GROUP BY id_droit ORDER BY  id_droit");
		$req->execute([
			':id_type'	=>$idType
		]);
		return $req->fetchAll();

	}


	public function listDroits(){
		$req=$this->pdo->query("SELECT * FROM droits ORDER BY id_appli, fonction");
		return $req->fetchAll();

	}
}

