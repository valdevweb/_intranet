<?php


class CataDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getOneWeekOp(){
		$monday=date('Y-m-d',strtotime($_POST['week']));
		$sunday=(new DateTime($monday))->modify("+ 6 days")->format('Y-m-d');
		$req=$this->pdo->prepare("SELECT * FROM cata_op WHERE (date_start BETWEEN :monday AND :sunday) AND (origine='B' OR origine='G')");
		$req->execute([
			':monday'	=>$monday,
			':sunday'	=>$sunday

		])
		return $req->fetchAll();
	}

	public function getArticleByCodeOp($codeOp){
		$req=$this->pdo->prepare("SELECT * FROM ba LEFT JOIN cata_dossiers ON ba.dossier=cata_dossiers.dossier WHERE code_op=:code_op");
		$req->execute([
			':code_op'	=>$codeOp,

		])
		return $req->fetchAll();
	}

}


