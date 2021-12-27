<?php

class TrtDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getTrtHistoByExp(){

		$req=$this->pdo->prepare("SELECT exps.id as id_exp, trt_histo.* FROM trt_histo left JOIN exps ON trt_histo.id_exp=exps.id WHERE exps.exp=0 order by trt_histo.id_exp, trt_histo.insert_on");
		$req->execute([

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function insertTrtHisto($idExp, $idTrt){
		$req=$this->pdo->prepare("INSERT INTO trt_histo (id_exp, id_trt, insert_on, insert_by) VALUES (:id_exp, :id_trt, :insert_on, :insert_by)");
		$req->execute([
			':id_exp'	=>$idExp,
			 ':id_trt'	=>$idTrt,
			 ':insert_on'	=>date('Y-m-d H:i:s'),
			 ':insert_by' 	=>$_SESSION['id_web_user']
		]);
		return $req->rowCount();
	}




}