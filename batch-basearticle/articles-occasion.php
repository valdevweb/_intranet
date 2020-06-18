<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';





function getArticlesOcc($pdoQlik){
	$version=VERSION;
	$req=$pdoQlik->query("
		SELECT basearticles.id as idqlik, `GESSICA.CodeArticle`as article_qlik,`GESSICA.CodeDossier`as dossier_qlik,`GESSICA.PANF` as panf_qlik,`GESSICA.D3E`as deee_qlik,`GESSICA.SORECOP` as sorecop,`GESSICA.LibelleArticle`as design_qlik, `GESSICA.PCB`as pcb_qlik, `GESSICA.NomFournisseur` as fournisseur_qlik, `GESSICA.Gencod` as ean_qlik, `CTBT.StkEnt` as qte_qlik

		FROM `basearticles`

		WHERE `GESSICA.GT` LIKE '13' AND  `GESSICA.LibelleArticle` LIKE 'OKAZ%' ORDER BY article_qlik
		");
	return $req->fetchAll();
}

function insertArticlesOcc($pdoBt, $idqlik, $article_qlik, $dossier_qlik, $panf_qlik, $deee_qlik, $sorecop, $design_qlik, $pcb_qlik, $fournisseur_qlik, $ean_qlik, $qte_qlik){
	$req=$pdoBt->prepare("INSERT INTO occ_article_qlik(idqlik, article_qlik, dossier_qlik, panf_qlik, deee_qlik, sorecop, design_qlik, pcb_qlik, fournisseur_qlik, ean_qlik, qte_qlik,date_insert) VALUES (:idqlik, :article_qlik, :dossier_qlik, :panf_qlik, :deee_qlik, :sorecop, :design_qlik, :pcb_qlik, :fournisseur_qlik, :ean_qlik, :qte_qlik, :date_insert)");
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
		':date_insert'			=> date('Y-m-d H:i:s')

	]);
	return $req->errorInfo();


}

$req=$pdoBt->query("DELETE FROM occ_article_qlik");

$articleOcc=getArticlesOcc($pdoQlik);


foreach($articleOcc as $art){

$added=insertArticlesOcc($pdoBt, $art['idqlik'], $art['article_qlik'], $art['dossier_qlik'], $art['panf_qlik'], $art['deee_qlik'], $art['sorecop'], $art['design_qlik'], $art['pcb_qlik'], $art['fournisseur_qlik'], $art['ean_qlik'], $art['qte_qlik']);

	echo "<pre>";
	print_r($added);
	echo '</pre>';


}

