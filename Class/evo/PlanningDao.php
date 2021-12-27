<?php

class PlanningDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getPlanningEvo($idEvo){

		$req=$this->pdo->prepare("SELECT * FROM planning  WHERE id_evo= :id_evo order by date_start");
		$req->execute([
			':id_evo'		=>$idEvo
		]);
		return $req->fetchAll();
	}

	public function insertPlanning($idEvo, $idResp, $dateStart,$dateEnd){
		$req=$this->pdo->prepare("INSERT INTO planning (id_evo, id_resp, date_start, date_end) VALUES (:id_evo, :id_resp, :date_start, :date_end)");
		$req->execute([
			':id_evo'	=>$idEvo,
			 ':id_resp'	=>$idResp,
			 ':date_start'	=>$dateStart,
			 ':date_end'	=>$dateEnd,

		]);
		return $req->rowCount();
	}

	public function deletePlanning($id){
		$req=$this->pdo->prepare("DELETE FROM planning WHERE id= :id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->errorInfo();
	}

	public function getPlanningEvoUserByEvo($idwebuser){

		$req=$this->pdo->prepare("SELECT planning.id_evo, planning.* FROM planning
			LEFT JOIN evos ON planning.id_evo = evos.id
			LEFT JOIN affectations ON affectations.id_evo= evos.id WHERE id_from= :id_user OR affectations.id_web_user= :id_user GROUP BY planning.id order by date_start");
		$req->execute([
			':id_user'		=>$idwebuser
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getPlanningEvoDev($idwebuser, $periodeStart, $periodeEnd){

		$req=$this->pdo->prepare("SELECT evos.id, evos.id_etat, evos.id_chrono,  evos.objet, planning.date_start,planning.date_end, module, appli FROM planning
			LEFT JOIN evos ON planning.id_evo = evos.id
			LEFT JOIN modules ON id_module= modules.id
			LEFT JOIN appli ON evos.id_appli=appli.id
			LEFT JOIN responsables ON evos.id_resp= responsables.id
			LEFT JOIN affectations ON affectations.id_evo= evos.id WHERE idwebuser= :idwebuser AND planning.date_start between :periode_start and :periode_end GROUP BY planning.id order by planning.date_start");
		$req->execute([
			':idwebuser'		=>$idwebuser,
			':periode_start'	=>$periodeStart,
			':periode_end'		=>$periodeEnd

		]);
		return $req->fetchAll();
	}



	public function getPlanningByEvo($idEtat){

		$req=$this->pdo->prepare("SELECT id_evo, planning.* FROM evos RIGHT JOIN planning ON evos.id= planning.id_evo WHERE id_etat= :id_etat ORDER BY id_evo, date_start");
		$req->execute([
			':id_etat'		=>$idEtat
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
public function getEvoThisWeek($week,$year){

	$req=$this->pdo->prepare("SELECT evos.* FROM planning LEFT JOIN evos ON planning.id_evo = evos.id WHERE ");
	$req->execute([

	]);
	return $req->fetchAll();
}


}


