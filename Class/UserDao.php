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


	public function isUserAllowed($params){
		$session=$_SESSION['id'];
		$placeholders=implode(',', array_fill(0, count($params), '?'));
		$req=$this->pdo->prepare("SELECT id_user FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session" );
		$req->execute($params);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($datas)){
			return false;
		}
		return true;

	}

	public function getUserAttributionsByService($idService){

		$req=$this->pdo->prepare("SELECT * FROM intern_users
			LEFT JOIN attributions ON intern_users.id_web_user = attributions.id_user
			LEFT JOIN droits ON attributions.id_droit= droits.id
			WHERE id_service= :id_service ORDER BY intern_users.id_web_user, id_droit");
		$req->execute([
			':id_service'	=>$idService

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

}