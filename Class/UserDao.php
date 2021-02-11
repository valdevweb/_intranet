<?php

class UserDao{


	private $pdo;


	public function __construct($pdo){
		$this->setPdo($pdo);
	}

	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getNbPwd(){
		$req=$this->pdo->prepare("SELECT count(id) as recup FROM  users WHERE date_maj_nohash IS NOT NULL");
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getNbCompte(){
		$req=$this->pdo->prepare("SELECT count(id) as compte FROM  users");
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function userHasThisRight($idwebuser,$right){
		$req=$this->pdo->prepare("SELECT * FROM attributions WHERE id_user= :id_user AND id_droit= :id_droit");
		$req->execute([
			':id_user'	=>$idwebuser,
			':id_droit'=>$right
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function addRight($idwebuser, $right){
		$req=$this->pdo->prepare("INSERT INTO attributions (id_user, id_droit) VALUES (:id_user, :id_droit)");
		$req->execute([
			':id_user'	=>$idwebuser,
			':id_droit'=>$right
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}


		public function removeRight($idwebuser, $right){
		$req=$this->pdo->prepare("DELETE FROM attributions WHERE id_user= :id_user AND id_droit= :id_droit");
		$req->execute([
			':id_user'	=>$idwebuser,
			':id_droit'=>$right
		]);
		// return $req->errorInfo();
	}
}