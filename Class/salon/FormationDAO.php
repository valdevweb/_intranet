<?php

class FormationDAO{

	private $pdoBt;



	public function __construct($pdoBt){
		$this->setPdo($pdoBt);
	}

	public function setPdo($pdoBt){
		$this->pdoBt=$pdoBt;
		return $pdoBt;
	}

	public function getCreneaux($pdoBt){
		$req=$this->pdoBt->query("SELECT id, DATE_FORMAT(start, '%Hh%i') as start,  DATE_FORMAT(end, '%Hh%i') as end FROM salon_creneaux");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getIncriptionByFormation($pdoBt, $idFormation){
		$req=$this->pdoBt->prepare("SELECT * FROM salon_formations LEFT JOIN magasin.mag ON btlec=magasin.mag.id WHERE id_formation= :id_formation ORDER BY id_web_user");
		$req->execute([
			':id_formation'		=>$idFormation
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addFormation($pdoBt, $idFormation){

		$req=$this->pdoBt->prepare("INSERT INTO salon_formations (id_formation, id_web_user, btlec, mardi, mercredi, id_creneau_1, id_creneau_2, nb, email)VALUES(:id_formation, :id_web_user, :btlec, :mardi, :mercredi, :id_creneau_1, :id_creneau_2, :nb, :email)");
		$req->execute([
			':id_formation'	=>$idFormation,
			 ':id_web_user'	=>$_SESSION['id_web_user'],
			 ':btlec'		=>$_SESSION['code_bt'],
			 ':mardi'	=> ($_POST['jour']=="mardi")? 1:0,
			 ':mercredi'	=>($_POST['jour']=="mercredi")? 1:0,
			 ':id_creneau_1'	=>$_POST['first-choice'],
			 ':id_creneau_2'	=>$_POST['second-choice'],
			 ':nb'	=>$_POST['nb'],
			 ':email' =>$_POST['email']
		]);

		return $req->rowCount();
	}



}