<?php


class MsgManager{


	public function getDemande($pdoBt,$id){

		$req=$pdoBt->prepare("SELECT *, msg.id as idMsg FROM msg LEFT JOIN web_users.services ON msg.id_service=services.id LEFT JOIN magasin.mag ON id_galec=magasin.mag.galec WHERE  msg.id= :id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getListDemandeByGalec($pdoBt,$galec){

		$req=$pdoBt->prepare("SELECT *, msg.id as idMsg FROM msg LEFT JOIN web_users.services ON msg.id_service=services.id LEFT JOIN magasin.mag ON id_galec=magasin.mag.galec WHERE  msg.id_galec= :galec ORDER BY msg.id DESC ");
		$req->execute([
			':galec'		=>$galec
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListReplies($pdoBt,$idMsg){
		$req=$pdoBt->prepare("SELECT *,CONCAT(web_users.intern_users.prenom,' ' , web_users.intern_users.nom) as fullname 
		FROM replies 
		LEFT JOIN web_users.intern_users 
		ON replies.replied_by=web_users.intern_users.id_web_user WHERE id_msg= :id_msg ORDER BY date_reply ASC");
		$req->execute(array(
			':id_msg'	=>$idMsg
		));

		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListDdeEncours($pdoBt){
		$req=$pdoBt->prepare("SELECT *, msg.id as idMsg FROM msg
			LEFT JOIN web_users.services ON msg.id_service=web_users.services.id  WHERE etat <> :clos ORDER BY id_service, date_msg DESC");
		$req->execute(array(
			':clos' =>'clos'
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getListDdeEncoursService($pdoBt,$idservice){
		$req=$pdoBt->prepare("SELECT  *, msg.id as idMsg
			FROM msg
			LEFT JOIN web_users.services ON msg.id_service=web_users.services.id  WHERE etat <> :clos AND id_service= :id_service ORDER BY id_service, date_msg DESC");
		$req->execute(array(
			':clos' =>'clos',
			':id_service' =>$idservice
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function affectation($pdoBt,$idMsg,$service){
		$update=$pdoBt->prepare('UPDATE msg SET id_service= :service  WHERE id= :id');
		$result=$update->execute(array(
			':service'		=> $service,
			':id'		=>$idMsg
		));
		return $result;
	}



	public function  getListDdeClos($pdoBt)
	{
		$req=$pdoBt->prepare("SELECT * FROM msg LEFT JOIN web_users.services ON msg.id_service=services.id  WHERE etat= :clos ORDER BY id_service, date_msg DESC");
		$req->execute(array(
			':clos' =>'clos'
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListDdeClosService($pdoBt,$idservice){
		$req=$pdoBt->prepare("SELECT  *,msg.id as idMsg FROM msg LEFT JOIN web_users.services ON msg.id_service=services.id  WHERE etat = :clos AND id_service= :id_service ORDER BY id_service, date_msg DESC");
		$req->execute(array(
			':clos' =>'clos',
			':id_service' =>$idservice
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


}



?>