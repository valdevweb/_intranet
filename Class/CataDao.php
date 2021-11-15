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
		$req=$this->pdo->prepare("SELECT * FROM cata_op WHERE (date_start BETWEEN :monday AND :sunday) ORDER BY origine, code_op");
		$req->execute([
			':monday'	=>$monday,
			':sunday'	=>$sunday

		]);
		return $req->fetchAll();
	}

	public function getArticleByCodeOp($codeOp){
		$req=$this->pdo->prepare("SELECT cata_dossiers.dossier,cata_dossiers.code_op,cata_op.date_start, ba.* FROM ba
			LEFT JOIN cata_dossiers ON ba.dossier=cata_dossiers.dossier
			LEFT JOIN cata_op ON cata_dossiers.code_op=cata_op.code_op
			 WHERE cata_dossiers.code_op LIKE :code_op order by gt, marque, article ");
		$req->execute([
			':code_op'	=>$codeOp,

		]);
		return $req->fetchAll();
	}

	public function getOpByCode($codeOp){

		$req=$this->pdo->prepare("SELECT cata_op.*, cata_dossiers.dossier, cata_dossiers.cata FROM cata_op LEFT JOIN cata_dossiers ON cata_op.code_op=cata_dossiers.code_op WHERE cata_op.code_op= :code_op GROUP BY cata_op.code_op");
		$req->execute([
			':code_op'		=>$codeOp
		]);
		return $req->fetch();
	}

}


