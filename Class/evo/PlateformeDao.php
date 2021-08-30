<?php

class PlateformeDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getListPlateforme(){
		$req=$this->pdo->query("SELECT * FROM plateformes ORDER BY plateforme");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListPlateformeResp($idResp){
		$req=$this->pdo->prepare("SELECT * FROM plateformes where id_resp= :id_resp ORDER BY plateforme");
			$req->execute([
			':id_resp'	=>$idResp
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function plateFormeInUse($idPlateforme){

		$req=$this->pdo->prepare("SELECT * FROM appli WHERE id_plateforme= :id_plateforme");
		$req->execute([
			':id_plateforme'	=>$idPlateforme
		]);
		$datas=$req->fetchAll();
		if(!empty($datas)){
			return true;
		}

		$req=$this->pdo->prepare("SELECT * FROM evos WHERE id_plateforme= :id_plateforme");
		$req->execute([
			':id_plateforme'	=>$idPlateforme
		]);
		$datas=$req->fetchAll();
		if(!empty($datas)){
			return true;
		}
		return false;
	}

	public function deletePlateforme($idPlateforme){
		$req=$this->pdo->prepare("DELETE FROM plateformes WHERE id= :id");
		$req->execute([
			':id'		=>$idPlateforme
		]);
		return $req->errorInfo();
	}

}


