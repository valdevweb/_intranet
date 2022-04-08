<?php

class BtUserManager{

	public function getService($pdoUser,$idService){
		$req=$pdoUser->prepare('SELECT * FROM services WHERE id = :id');
		$req->execute(array(
			':id' =>$idService
		));
		return $row=$req->fetch(PDO::FETCH_ASSOC);
	}


	public function getListUserService($pdoUser,$idservice){
		$req=$pdoUser->prepare('SELECT *, CONCAT(prenom," " , nom) as fullname FROM intern_users 
		 WHERE id_service= :id_service ORDER BY resp DESC');
		$req ->execute(array(
			':id_service' =>$idservice
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);

	}
	public function listServicesContact($pdoUser){
		$req=$pdoUser->prepare("SELECT * FROM services WHERE slug <>'' ORDER BY service");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
		public function listServicesContactStrict($pdoUser){
		$req=$pdoUser->prepare("SELECT * FROM services WHERE mask_contact=0 ORDER BY service");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUserByid($pdoUser, $id){
		$req=$pdoUser->prepare("SELECT * FROM intern_users WHERE id_web_user= :id_web_user");
		$req->execute([
			':id_web_user'=>$id
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
	}
	public function getServiceById($pdoUser,$idService){
		$req=$pdoUser->prepare("SELECT * FROM services WHERE id = :id");
		$req->execute(array(
			':id'	=>$idService,
		));

		return $req->fetch(PDO::FETCH_ASSOC);

	}

	public function getUserAttribution($pdoUser,$idwebuser){

		$req=$pdoUser->prepare("SELECT * FROM attributions WHERE id_user= :id_user ORDER BY id");
		$req->execute([
			':id_user' =>$idwebuser
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

}






?>