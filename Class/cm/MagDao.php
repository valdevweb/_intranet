<?php

class MagDao{


	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}
	function getEmailBySuffixe($suffixe,$galec){
		$req=$this->pdo->prepare("SELECT * FROM lotus_ld WHERE galec LIKE :galec and ld_suffixe= :suffixe");
		$req->execute([
			':galec'	=>$galec,
			':suffixe'	=>$suffixe
		]);
	// return $req->errorInfo();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	function getMagEmails($galec){
		$req=$this->pdo->prepare("SELECT * FROM lotus_ld WHERE galec LIKE :galec ORDER BY ld_suffixe");
		$req->execute([
			':galec'	=>$galec,

		]);
	// return $req->errorInfo();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}
