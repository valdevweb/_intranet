<?php


class CdesRelancesDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	public function insertRelance($cnuf, $cmt){
		$req=$this->pdo->prepare("INSERT INTO cdes_relances (cnuf, cmt, date_declenche, by_envoi) VALUES (:cnuf, :cmt, :date_declenche, :by_envoi)");
		$req->execute([
			':cnuf'			=>$cnuf,
			':cmt'			=>$cmt,
			':date_declenche'	=>date('Y-m-d H:i:s'),
			':by_envoi'		=>$_SESSION['id_web_user']
		]);
		return $this->pdo->lastInsertId();
	}

	public function insertRelanceDetail($idR, $idEncours, $qteRestante){
		$req=$this->pdo->prepare("INSERT INTO cdes_relances_details (id_relance, id_encours, qte_restante) VALUES (:id_relance, :id_encours,  :qte_restante)");
		$req->execute([
			':id_relance'		=>$idR,
			':id_encours'		=>$idEncours,

			':qte_restante'		=>$qteRestante,

		]);
		return $req->rowCount();
	}

	public function insertEmail($idR, $email, $idContact){
		$req=$this->pdo->prepare("INSERT INTO cdes_relances_email (id_relance, email, id_contact) VALUES (:id_relance, :email, :id_contact)");
		$req->execute([
			':id_relance'		=>$idR,
			':email'			=>$email,
			':id_contact'		=>$idContact,
		]);
		return $req->rowCount();
	}

	public function getRelancesToSend(){

		$req=$this->pdo->prepare("SELECT * FROM cdes_relances WHERE statut=0 AND  by_envoi= :by_envoi AND  DATE_FORMAT(date_declenche, '%Y-%m-%d')=:date_declenche");
		$req->execute([
			':date_declenche'	=>date('Y-m-d'),
			':by_envoi'		=>$_SESSION['id_web_user']
		]);
		return $req->fetchAll();
	}

	public function getRDetailToSend($idR){

		$req=$this->pdo->prepare("SELECT * FROM cdes_relances_details WHERE id_relance= :id_relance");
		$req->execute([
			':id_relance'	=>$idR

		]);
		return $req->fetchAll();
	}
	public function getRMailToSend($idR){

		$req=$this->pdo->prepare("SELECT * FROM cdes_relances_email WHERE id_relance= :id_relance");
		$req->execute([
			':id_relance'	=>$idR
		]);
		return $req->fetchAll();
	}

	public function updateRelance($idR){
		$req=$this->pdo->prepare("UPDATE cdes_relances SET date_envoi= :date_envoi, statut=1 WHERE id= :id");
		$req->execute([
			':id'	=>$idR,
			':date_envoi'		=>date('Y-m-d H:i:s')

		]);
		return $req->rowCount();
	}


	public function getRelancesSince($date, $param=null){
		if($param==null){
			$param="";
		}
		$q="SELECT * FROM cdes_relances
			LEFT JOIN cdes_relances_details ON cdes_relances.id= cdes_relances_details.id_relance
			LEFT JOIN qlik.cdes_encours ON cdes_relances_details.id_encours=qlik.cdes_encours.id WHERE date_envoi>= :date_envoi $param ORDER BY date_envoi DESC, fournisseur";
		$req=$this->pdo->prepare($q);
		$req->execute([
			':date_envoi'		=>$date

		]);
		return $req->fetchAll();

	}
}