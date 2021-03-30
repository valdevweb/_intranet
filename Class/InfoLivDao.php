<?php

class InfoLivDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}
	public function getOpByOp($codeOp){

		$req=$this->pdo->prepare("SELECT  * FROM operations	WHERE code_op LIKE :code_op");
		$req->execute([
			':code_op'		=>$codeOp
		]);
		return $req->fetch();
	}

	public function getInfoLivByOp($codeOp){

		$req=$this->pdo->prepare("SELECT articles.*, recu, infos_livraison.id as id_liv, recu, info_livraison, article_remplace, ean_remplace, recu_deux, info_livraison_deux, recu_remplace, info_livraison_remplace, recu_deux_remplace, info_livraison_deux_remplace,operations.code_op, operations.id as id_op, erratum FROM infos_livraison
			LEFT JOIN articles ON id_article=articles.id
			LEFT JOIN operations ON articles.id_op=operations.id
			WHERE operations.code_op LIKE :code_op ORDER BY gt, marque");
		$req->execute([
			':code_op'		=>$codeOp
		]);
		return $req->fetchAll();
	}

	public function getInfoLivByOpKeyArticle($codeOp){

		$req=$this->pdo->prepare("SELECT articles.article, articles.*, recu, infos_livraison.id as id_liv, recu, info_livraison, article_remplace, ean_remplace, recu_deux, info_livraison_deux, recu_remplace, info_livraison_remplace, recu_deux_remplace, info_livraison_deux_remplace, operations.code_op, operations.id as id_op, erratum FROM infos_livraison
			LEFT JOIN articles ON id_article=articles.id
			LEFT JOIN operations ON articles.id_op=operations.id
			WHERE operations.code_op LIKE :code_op ORDER BY gt, marque");
		$req->execute([
			':code_op'		=>$codeOp
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
	public function getInfoLivByIdOp($idOp){

		$req=$this->pdo->prepare("SELECT articles.*, recu, infos_livraison.id as id_liv, recu, info_livraison, article_remplace, ean_remplace, recu_deux, info_livraison_deux, recu_remplace, info_livraison_remplace, recu_deux_remplace, info_livraison_deux_remplace, operations.code_op, operations.id as id_op, erratum FROM infos_livraison
			LEFT JOIN articles ON id_article=articles.id
			LEFT JOIN operations ON articles.id_op=operations.id
			WHERE operations.id=:id ORDER BY gt, marque");
		$req->execute([
			':id'		=>$idOp
		]);
		return $req->fetchAll();
	}
	public function getInfoLivByIdOpKeyArticle($idOp){

		$req=$this->pdo->prepare("SELECT articles.article, articles.*, recu, infos_livraison.id as id_liv, recu, info_livraison, article_remplace, ean_remplace, recu_deux, info_livraison_deux, recu_remplace, info_livraison_remplace, recu_deux_remplace, info_livraison_deux_remplace, operations.code_op, operations.id as id_op, erratum FROM infos_livraison
			LEFT JOIN articles ON id_article=articles.id
			LEFT JOIN operations ON articles.id_op=operations.id
			WHERE operations.id=:id ORDER BY gt, marque");
		$req->execute([
			':id'		=>$idOp
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function insertOp($codeOp, $op, $codeCata,  $origine, $dateStart, $dateEnd){
		$req=$this->pdo->prepare("INSERT INTO operations (code_op, operation, code_cata, origine, date_start, date_end) VALUES (:code_op, :operation, :code_cata, :origine, :date_start, :date_end)");
		$req->execute([
			':code_op'	=>$codeOp,
			':operation'	=>$op,
			':code_cata'	=>$codeCata,
			':origine'	=>$origine,
			':date_start'	=>$dateStart,
			':date_end'	=>$dateEnd,

		]);
		return $this->pdo->lastInsertId();
	}

	public function insertInfoLiv($idArticle, $recu, $infoLiv, $articleRemplace, $eanRemplace, $recuDeux, $infoLivDeux, $recuRemplace, $infoLivRemplace, $recuDeuxRemplace, $infoLivDeuxRemplace, $erratum){
		$req=$this->pdo->prepare("INSERT INTO infos_livraison
			(id_article, recu, info_livraison, article_remplace, ean_remplace, recu_deux, info_livraison_deux, recu_remplace, info_livraison_remplace, recu_deux_remplace, info_livraison_deux_remplace, erratum, date_insert) VALUES
			(:id_article, :recu, :info_livraison, :article_remplace, :ean_remplace, :recu_deux, :info_livraison_deux, :recu_remplace, :info_livraison_remplace, :recu_deux_remplace, :info_livraison_deux_remplace,  :erratum, :date_insert)");
		$req->execute([
			':id_article'		=>$idArticle,
			':recu'				=>$recu,
			':info_livraison'	=>$infoLiv,
			':article_remplace'	=>$articleRemplace,
			':ean_remplace'		=>$eanRemplace,
			':recu_deux'				=>$recuDeux,
			':info_livraison_deux'		=>$infoLivDeux,
			':recu_remplace'	=>$recuRemplace,
			':info_livraison_remplace'	=>$infoLivRemplace,
			':recu_deux_remplace'	=> $recuDeuxRemplace,
			':info_livraison_deux_remplace'=>$infoLivDeuxRemplace,
			':erratum'				=>$erratum,
			':date_insert'		=>date('Y-m-d H:i:s')

		]);
		return $req->errorInfo();
	}

	public function updateInfoLiv($idArticle, $recu, $infoLiv, $articleRemplace, $eanRemplace, $recuDeux, $infoLivDeux, $recuRemplace, $infoLivRemplace, $recuDeuxRemplace, $infoLivDeuxRemplace ){
		$req=$this->pdo->prepare("UPDATE infos_livraison SET recu= :recu, info_livraison = :info_livraison, article_remplace= :article_remplace, ean_remplace= :ean_remplace, recu_deux= :recu_deux, info_livraison_deux = :info_livraison_deux, recu_remplace= :recu_remplace, info_livraison_remplace= :info_livraison_remplace, recu_deux_remplace= :recu_deux_remplace, info_livraison_deux_remplace= :info_livraison_deux_remplace, date_insert= :date_insert  WHERE id_article= :id_article");
		$req->execute([
			':id_article'	=>$idArticle,
			':recu'	=>$recu,
			':info_livraison'	=>$infoLiv,
			':article_remplace'	=>$articleRemplace,
			':ean_remplace'	=>$eanRemplace,
			':recu_deux'				=>$recuDeux,
			':info_livraison_deux'		=>$infoLivDeux,
			':recu_remplace'	=>$recuRemplace,
			':info_livraison_remplace'	=>$infoLivRemplace,
			':recu_deux_remplace'	=> $recuDeuxRemplace,
			':info_livraison_deux_remplace'=>$infoLivDeuxRemplace,
			':date_insert'		=>date('Y-m-d H:i:s')
		]);

	}
	public function updateInfoLivErratum($idArticle, $recu, $infoLiv, $articleRemplace, $eanRemplace, $recuDeux, $infoLivDeux, $recuRemplace, $infoLivRemplace, $recuDeuxRemplace, $infoLivDeuxRemplace, $erratum){
		$req=$this->pdo->prepare("UPDATE infos_livraison SET recu= :recu, info_livraison = :info_livraison, article_remplace= :article_remplace, ean_remplace= :ean_remplace, recu_deux= :recu_deux, info_livraison_deux = :info_livraison_deux, recu_remplace= :recu_remplace, info_livraison_remplace= :info_livraison_remplace, recu_deux_remplace= :recu_deux_remplace, info_livraison_deux_remplace= :info_livraison_deux_remplace, erratum=:erratum, date_insert= :date_insert  WHERE id_article= :id_article");
		$req->execute([
			':id_article'	=>$idArticle,
			':recu'	=>$recu,
			':info_livraison'	=>$infoLiv,
			':article_remplace'	=>$articleRemplace,
			':ean_remplace'	=>$eanRemplace,
			':recu_deux'				=>$recuDeux,
			':info_livraison_deux'		=>$infoLivDeux,
			':recu_remplace'	=>$recuRemplace,
			':info_livraison_remplace'	=>$infoLivRemplace,
			':recu_deux_remplace'	=> $recuDeuxRemplace,
			':info_livraison_deux_remplace'=>$infoLivDeuxRemplace,
			':erratum'				=>$erratum,
			':date_insert'		=>date('Y-m-d H:i:s')
		]);

	}
	public function getOpAVenir(){
		$today=new DateTime();
		if($today->format('w')==1){
			$today=$today->modify('- 7 day');
		}
		$req=$this->pdo->prepare("SELECT * FROM operations WHERE date_end>= :date_end ORDER BY date_start");
		$req->execute([
			':date_end'		=>$today->format('Y-m-d')
		]);
		return $req->fetchAll();
	}

	public function getOpByCode($param){

		$req=$this->pdo->query("SELECT * FROM operations WHERE $param");
		return $req->fetchAll();
	}



	public function updateErratum($idArticle,$erratum){
		$req=$this->pdo->prepare("UPDATE infos_livraison SET erratum= :erratum WHERE id_article= :id_article");
		$req->execute([
			':id_article'			=>$idArticle,
			':erratum'		=>$erratum
		]);
		return $req->rowCount();
	}
}


