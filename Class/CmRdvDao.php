<?php

class CmRdvDao{


	private $pdo;

	// la db utilisée est celle des chargés de mission (cm)
	public function __construct($pdo){
		$this->setPdo($pdo);
	}

	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getLastPendingRdv(){
		$req=$this->pdo->prepare("SELECT * FROM rdv WHERE galec= :galec AND accepted=0 ORDER BY id desc LIMIT 1 ");
		$req->execute([
			':galec'	=>$_SESSION['id_galec']
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);
		if(!empty($data)){
			return $data;
		}
		return false;
	}
}