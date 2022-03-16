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
	public function magExpAlreadyExist($btlec){

		$req=$this->pdo->prepare("SELECT * FROM exps WHERE exp=0 AND btlec = :btlec");
		$req->execute([
			':btlec'		=>$btlec
		]);
		return $req->fetch(PDO::FETCH_ASSOC);

	}
	public function getActiveExp(){
		$req=$this->pdo->query("SELECT * FROM exps WHERE exp=0");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getExpByAffectation($idAffectation){
		$req=$this->pdo->prepare("SELECT exps.id, exps.*, palettes.id as id_palette, palettes.statut, palettes.palette, palettes.contremarque FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp WHERE exp=0 AND id_affectation= :id_affectation");
		$req->execute([
			':id_affectation'			=>$idAffectation
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
	public function getExpDetails(){
		$req=$this->pdo->query("SELECT exps.id, exps.*, palettes.id as id_palette, palettes.statut, palettes.palette, palettes.contremarque
			FROM exps
			LEFT JOIN palettes ON exps.id=palettes.id_exp WHERE exp=0");
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function insertExp($btlec, $galec, $idAffectation){
		$req=$this->pdo->prepare("INSERT INTO exps (btlec, galec, date_crea, id_affectation) VALUES (:btlec, :galec, :date_crea, :id_affectation)");
		$req->execute([
			':btlec'		=>$btlec,
			':galec'		=>$galec,
			':id_affectation'		=>$idAffectation,
			':date_crea'	=>date('Y-m-d H:i:s')
		]);
		return $this->pdo->lastInsertId();
	}

	public function getExpPaletteCasse($idExp){
		$req=$this->pdo->prepare("SELECT casses.*, palettes.palette, palettes.contremarque
			FROM exps LEFT JOIN palettes ON exps.id=palettes.id_exp
			LEFT JOIN casses ON palettes.id= casses.id_palette
			WHERE exps.id= :id_exp ORDER BY palettes.id, article");
		$req->execute([
			':id_exp'			=>$idExp
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

}