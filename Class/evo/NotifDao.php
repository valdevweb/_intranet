<?php

class NotifDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getNotifsByEvo($idEvo){

		$req=$this->pdo->prepare("SELECT * FROM notifs WHERE id_evo=:id_evo");
		$req->execute([
			':id_evo'		=>$idEvo
		]);
		return $req->fetchAll();
	}

	public function insertNotif($idEvo, $title, $dateNotif, $notif){
		$req=$this->pdo->prepare("INSERT INTO notifs (id_evo, title, notif, date_notif) VALUES (:id_evo, :title, :notif, :date_notif)");
		$req->execute([
			':id_evo'		=>$idEvo,
			':title'	=>$title,
			':notif'	=>$notif,
			':date_notif'	=>$dateNotif,

		]);
		return $req->rowCount();
	}


	public function getTodaysNotifs($dateNotif){
		$req=$this->pdo->prepare("SELECT notifs.*, evos.id_resp, evos.evo, evos.objet,  responsables.email FROM notifs
			LEFT JOIN evos ON notifs.id_evo= evos.id
			LEFT JOIN responsables ON evos.id_resp=responsables.id
			WHERE date_notif=:date_notif");
		$req->execute([
			':date_notif'		=>$dateNotif
		]);
		return $req->fetchAll();
	}
}


