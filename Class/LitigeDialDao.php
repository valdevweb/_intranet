<?php

class LitigeDialDao{

	// la db est pdoLitige
	private $pdo;


	public function __construct($pdo){
		$this->setPdo($pdo);
	}

	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getUnreadDossier(){
		$req=$this->pdo->query("SELECT dial.*,dossiers.dossier, count(dial.id) as nb  FROM dial LEFT JOIN dossiers ON id_dossier= dossiers.id WHERE mag=1 AND read_dial=0 GROUP BY id_dossier ORDER BY dossiers.id DESC");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getUnreadDossierColumn(){
		$req=$this->pdo->query("SELECT dial.id_dossier  FROM dial LEFT JOIN dossiers ON id_dossier= dossiers.id WHERE mag=1 AND read_dial=0 GROUP BY id_dossier ORDER BY dossiers.id DESC");
		return $req->fetchAll(PDO::FETCH_COLUMN);
	}

	public function getUnreadActionSav(){
		$req=$this->pdo->query("SELECT dial.*,dossiers.dossier, count(dial.id) as nb  FROM dial LEFT JOIN dossiers ON id_dossier= dossiers.id WHERE mag=1 AND read_dial=0 GROUP BY id_dossier ORDER BY dossiers.id DESC");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getUnreadActionSavColumn(){
		$req=$this->pdo->query("SELECT dial.id_dossier  FROM dial LEFT JOIN dossiers ON id_dossier= dossiers.id WHERE mag=1 AND read_dial=0 GROUP BY id_dossier ORDER BY dossiers.id DESC");
		return $req->fetchAll(PDO::FETCH_COLUMN);
	}

	public function updateRead($idDial,$read){
		$req=$this->pdo->prepare("UPDATE dial SET read_dial= :read_dial WHERE id= :id");
		$req->execute([
			':read_dial'	=>$read,
			':id'			=>$idDial
		]);

	}
}