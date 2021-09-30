<?php
class ExpDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}
	function magExpAlreadyExist($btlec){

		$req=$this->pdo->prepare("SELECT * FROM exps WHERE exp=0 AND btlec = :btlec");
		$req->execute([
			':btlec'		=>$btlec
		]);
		return $req->fetch(PDO::FETCH_ASSOC);

	}
	function getActiveExp(){
		$req=$this->pdo->query("SELECT * FROM exps WHERE exp=0");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	function insertExp($btlec, $galec){
		$req=$this->pdo->prepare("INSERT INTO exps (btlec, galec, date_crea) VALUES (:btlec, :galec, :date_crea)");
		$req->execute([
			':btlec'		=>$btlec,
			':galec'		=>$galec,
			':date_crea'	=>date('Y-m-d H:i:s')
		]);
		return $this->pdo->lastInsertId();
	}
}