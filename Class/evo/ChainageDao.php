<?php

class ChainageDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}
	public function isParent($idEvo){

		$req=$this->pdo->prepare("SELECT * FROM evo_chainage WHERE parent= :parent");
		$req->execute([
			':parent'		=>$idEvo
		]);
		return $req->fetchAll();
	}
	public function isEnfant($idEvo){
		$req=$this->pdo->prepare("SELECT * FROM evo_chainage WHERE enfant= :enfant");
		$req->execute([
			':enfant'		=>$idEvo
		]);
		return $req->fetchAll();
	}

	public function insertChainage($parent, $enfant){
		$req=$this->pdo->prepare("INSERT INTO evo_chainage (parent, enfant) VALUES ( :parent, :enfant)");
		$req->execute([
			 ':parent'	=>$parent,
			 ':enfant'	=>$enfant,

		]);
		return $req->rowCount();
	}

}


