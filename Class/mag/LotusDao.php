<?php

class LotusDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}
	public function getAllMailFromLd(){
		$req=$this->pdo->query("SELECT * FROM lotus_ld GROUP BY email ");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function updateRouting($id, $route){
		$req=$this->pdo->prepare("UPDATE lotus_ld SET routing= :routing WHERE id= :id");
		$req->execute([
			':id'		=>$id,
			':routing'	=>$route

		]);
		return $req->rowCount();
	}

	public function getRouting(){

	$req=$this->pdo->query("SELECT * FROM lotus_ld WHERE routing IS NOT NULL");
	return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}


