
<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
require 'Class/Db.php';
require 'Class/OccArticleDao.php';

$db=new Db();
$pdoQlik=$db->getPdo('qlik');
$pdoOcc=$db->getPdo('occasion');

$occArtDao=new OccArticleDao($pdoOcc);
$donotDelete=0;

function getArticlesOcc($pdoQlik){
	$req=$pdoQlik->query("
		SELECT basearticles.id as idqlik, `GESSICA.CodeArticle`as article_qlik,`GESSICA.CodeDossier`as dossier_qlik,`GESSICA.PFNP` as panf_qlik,`GESSICA.D3E`as deee_qlik,`GESSICA.SORECOP` as sorecop,`GESSICA.LibelleArticle`as design_qlik, `GESSICA.PCB`as pcb_qlik, `GESSICA.NomFournisseur` as fournisseur_qlik, `GESSICA.Gencod` as ean_qlik, `CTBT.StkEnt` as qte_qlik, `GESSICA.PPI` as ppi_qlik, `GESSICA.Marque` as marque_qlik
		FROM `basearticles`
		WHERE `GESSICA.GT` LIKE '13' AND  `GESSICA.LibelleArticle` LIKE 'OKAZ%' AND `CTBT.StkEnt`!=0 ORDER BY article_qlik
		");
	return $req->fetchAll();
}



// GROUP BY article_occ

function getCommandesNonExpedie($pdoOcc){
	$req=$pdoOcc->query("SELECT  article_occ, sum(qte_cde) as qte_cde  FROM cdes_numero LEFT JOIN cdes_detail ON cdes_numero.id=cdes_detail.id_cde WHERE statut !=3 AND article_occ IS NOT NULL GROUP BY article_occ");
	return $datas=$req->fetchAll(PDO::FETCH_KEY_PAIR);

}

function deleteArticlesQlik($pdoOcc){
	$req=$pdoOcc->query("DELETE FROM articles_qlik WHERE donotdelete=0 OR (donotdelete=1 and qte_qlik=0)");
}


deleteArticlesQlik($pdoOcc);
$articleOcc=getArticlesOcc($pdoQlik);

	// echo "<pre>";
	// print_r($articleOcc);
	// echo '</pre>';


$cdesNonExp=getCommandesNonExpedie($pdoOcc);


foreach($articleOcc as $art){
	if(isset($cdesNonExp[$art['article_qlik']])){
		echo "article "	.$art['article_qlik']. "qte qlik" .$art['qte_qlik'] ."nonexp".$cdesNonExp[$art['article_qlik']];
		$stockReel=$art['qte_qlik']-$cdesNonExp[$art['article_qlik']];
	}else{
		$stockReel=$art['qte_qlik'];
	}
	if($stockReel>=0){
		$added=$occArtDao->insertArticlesOcc($art['idqlik'], $art['article_qlik'], $art['dossier_qlik'], $art['panf_qlik'], $art['deee_qlik'], $art['sorecop'], $art['design_qlik'], $art['pcb_qlik'], $art['fournisseur_qlik'], $art['ean_qlik'], $stockReel, $art['ppi_qlik'], $art['marque_qlik'],$donotDelete );
	}

}

