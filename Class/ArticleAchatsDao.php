<?php


class ArticleAchatsDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function insertArticle($idOp, $article, $dossier, $libelle, $ean, $gt, $marque, $fournisseur, $cnuf, $deee, $ppi){
		$req=$this->pdo->prepare("INSERT INTO articles (id_op, article, dossier, libelle, ean, gt, marque, fournisseur, cnuf, deee, ppi) VALUES (:id_op, :article, :dossier, :libelle, :ean, :gt, :marque, :fournisseur, :cnuf, :deee, :ppi)");
		$req->execute([
			':id_op'	=>$idOp,
			 ':article'	=>$article,
			 ':dossier'	=>$dossier,
			 ':libelle'	=>$libelle,
			 ':ean'	=>$ean,
			 ':gt'	=>$gt,
			 ':marque'	=>$marque,
			 ':fournisseur'	=>$fournisseur,
			 ':cnuf'	=>$cnuf,
			 ':deee'	=>$deee,
			 ':ppi'	=>$ppi,

		]);
		return $this->pdo->lastInsertId();

	}


}


