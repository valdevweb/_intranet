<?php

class AffectationDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function insertAffectation($idEvo, $idwebuser, $idService, $email){
		$req=$this->pdo->prepare("INSERT INTO affectations (id_evo, id_web_user, id_service, email) VALUES (:id_evo, :id_web_user, :id_service, :email)");
		$req->execute([
			':id_evo'	=>$idEvo,
			':id_web_user'	=>$idwebuser,
			':id_service'	=>$idService,
			':email'	=>$email,

		]);
		return $req->rowCount();
	}

	public function getAffectation($idEvo){

		$req=$this->pdo->prepare("SELECT affectations.*, web_users.intern_users.id_web_user, web_users.intern_users.fullname FROM affectations LEFT JOIN web_users.intern_users ON affectations.id_web_user=web_users.intern_users.id_web_user WHERE id_evo= :id_evo ORDER BY fullname");
		$req->execute([
			':id_evo'	=>$idEvo,

		]);
		return $req->fetchAll();
	}

	public function deleteAffectation($id){
		$req=$this->pdo->prepare("DELETE FROM affectations WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
		return $req->errorInfo();
	}

	public function getAffectationByEvo($periodeStart, $periodeEnd, $idwebuser){

		$req=$this->pdo->prepare("SELECT evos.id, affectations.* FROM evos
			LEFT JOIN affectations on evos.id=affectations.id_evo
			LEFT JOIN planning on evos.id=planning.id_evo
			WHERE planning.date_start between :periode_start and :periode_end and affectations.id is not null");
		$req->execute([
			':periode_start'	=>$periodeStart,
			':periode_end'		=>$periodeEnd
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
}


