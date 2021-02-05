<?php

class UserDao{

	// la db est pdoLitige
	private $pdo;


	public function __construct($pdo){
		$this->setPdo($pdo);
	}

	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getNbPwd(){
		$req=$this->pdo->prepare("SELECT count(id) as recup FROM  users WHERE date_maj_nohash IS NOT NULL");
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getNbCompte(){
		$req=$this->pdo->prepare("SELECT count(id) as compte FROM  users");
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}
}