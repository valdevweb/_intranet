<?php


class UtilsDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	public function searchDocAppli($string, $idwebuser){

		$req=$this->pdo->prepare("SELECT appli_docs.* FROM appli_docs LEFT JOIN appli ON appli_docs.id_appli= appli.id WHERE (filename like :filename or cmt like :cmt or urlname like :urlname) and id_resp= :id_resp");
		$req->execute([
			':filename'		=>'%'.$string.'%',
			':cmt'		=>'%'.$string.'%',
			':urlname'		=>'%'.$string.'%',
			':id_resp'	=>$idwebuser
		]);
		return $req->fetchAll();
	}

	public function searchDocModule($string, $idwebuser){

		$req=$this->pdo->prepare("SELECT module_docs.*, modules.id_appli FROM module_docs
			LEFT JOIN modules ON module_docs.id_module= modules.id
			LEFT JOIN appli ON modules.id_appli= appli.id

			WHERE (filename like :filename or cmt like :cmt or urlname like :urlname) and id_resp= :id_resp");
		$req->execute([
			':filename'		=>'%'.$string.'%',
			':cmt'		=>'%'.$string.'%',
			':urlname'		=>'%'.$string.'%',
			':id_resp'	=>$idwebuser
		]);
		return $req->fetchAll();
	}
	public function searchDocEvo($string, $idwebuser){

		$req=$this->pdo->prepare("SELECT evo_docs.* , evos.id_appli, evos.id_module, evos.id as id_evo, objet FROM evo_docs
			LEFT JOIN evos ON evo_docs.id_evo= evos.id
			WHERE (filename like :filename or cmt like :cmt or urlname like :urlname) and id_resp= :id_resp");
		$req->execute([
			':filename'		=>'%'.$string.'%',
			':cmt'		=>'%'.$string.'%',
			':urlname'		=>'%'.$string.'%',

			':id_resp'	=>$idwebuser
		]);
		return $req->fetchAll();
	}

}