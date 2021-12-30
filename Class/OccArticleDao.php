<?php


class OccArticleDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function insertArticlesOcc($idqlik, $article_qlik, $dossier_qlik, $panf_qlik, $deee_qlik, $sorecop, $design_qlik, $pcb_qlik, $fournisseur_qlik, $ean_qlik, $qte_qlik, $ppiQlik,
		$marqueQlik, $donotDelete){
		$req=$this->pdo->prepare("INSERT INTO articles_qlik(idqlik, article_qlik, dossier_qlik, panf_qlik, deee_qlik, sorecop, design_qlik, pcb_qlik, fournisseur_qlik, ean_qlik, qte_qlik,date_insert, ppi_qlik, marque_qlik, donotdelete) VALUES (:idqlik, :article_qlik, :dossier_qlik, :panf_qlik, :deee_qlik, :sorecop, :design_qlik, :pcb_qlik, :fournisseur_qlik, :ean_qlik, :qte_qlik, :date_insert, :ppi_qlik, :marque_qlik, :donotdelete)");
		$req->execute([
			':idqlik'		=>$idqlik,
			':article_qlik'	=>$article_qlik,
			':dossier_qlik'	=>$dossier_qlik,
			':panf_qlik'		=>$panf_qlik,
			':deee_qlik'		=>$deee_qlik,
			':sorecop'			=>$sorecop,
			':design_qlik'		=>$design_qlik,
			':pcb_qlik'		=>$pcb_qlik,
			':fournisseur_qlik'	=>$fournisseur_qlik,
			':ean_qlik'			=>$ean_qlik,
			':qte_qlik'			=>$qte_qlik,
			':date_insert'			=> date('Y-m-d H:i:s'),
			':ppi_qlik'			=>$ppiQlik,
			':marque_qlik'		=>$marqueQlik,
			':donotdelete'		=>$donotDelete,

		]);
		return $req->errorInfo();
	}

	public function deleteArticle($id){
		$req=$this->pdo->prepare("DELETE FROM articles_qlik WHERE id=:id");
		$req->execute([
			':id'			=>$id
		]);
		return $req->errorInfo();
	}
}
