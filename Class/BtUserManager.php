<?php

class BtUserManager{

	public function getService($pdoBt,$idService){
		$req=$pdoBt->prepare('SELECT * FROM services WHERE id = :id');
		$req->execute(array(
			':id' =>$idService
		));
		return $row=$req->fetch(PDO::FETCH_ASSOC);
	}


	public function getListUserService($pdoBt,$idservice){
		$req=$pdoBt->prepare('SELECT *, CONCAT(web_users.intern_users.prenom," " , web_users.intern_users.nom) as fullname FROM contact_services LEFT JOIN web_users.intern_users ON  contact_services.id_web_user=web_users.intern_users.id_web_user WHERE contact_services.id_service= :id_service ORDER BY contact_services.resp DESC');
		$req ->execute(array(
			':id_service' =>$idservice
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);

	}
	public function listServicesContact($pdoBt){
		$req=$pdoBt->prepare("SELECT * FROM services WHERE slug <>'' ORDER BY service");
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
	public function getUserServiceContact($pdoBt,$idwebuser){
		$req=$pdoBt->prepare("SELECT id_service FROM contact_services WHERE id_web_user= :id_web_user ");
		$req->execute([
			':id_web_user' =>$idwebuser
		]);
		$datas=$req->fetchAll(PDO::FETCH_COLUMN);
		if(empty($datas)){
			return "";
		}
		return $datas;

	}

	public function getServiceById($pdoBt,$idService){
		$req=$pdoBt->prepare("SELECT * FROM services WHERE id = :id");
		$req->execute(array(
			':id'	=>$idService,
		));

		return $req->fetch(PDO::FETCH_ASSOC);

	}

}






?>