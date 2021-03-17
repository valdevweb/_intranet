<?php


$prosp=$prospDao->getProspectusByProspectus($_POST['code_op']);
if(!empty($prosp)){
	$idProsp=$prosp['id'];


}else{
	$prosp=$cataDao->getOpByCode($_POST['code_op']);
	$idProsp=$prospDao->addProspectus($prosp['date_start'], $prosp['date_end'],$prosp['code_op'],"");
}

foreach ($_POST['montant_gessica'] as $idArticle => $value) {
	if(!empty($_POST['montant_gessica'][$idArticle]) && !empty($_POST['montant_finance_gessica'][$idArticle]) && !empty($_POST['offre_gessica'][$idArticle]) ){
		if(!is_numeric($_POST['pvc_gessica'][$idArticle])){
			$errors[]="Veuillez saisir un prix de vente conseillé correct (ex : 150, 10.52, etc.)";
		}
		if(!is_numeric($_POST['montant_gessica'][$idArticle])){
			$errors[]="Veuillez saisir un montant correct (ex : 150, 10.52, etc.)";
		}
		if(!is_numeric($_POST['montant_finance_gessica'][$idArticle])){
			$errors[]="Veuillez saisir un montant financé correct (ex : 150, 10.52, etc.)";
		}
		$offreDao->addOffre($idProsp,$_POST['gt_gessica'][$idArticle], $_POST['marque_gessica'][$idArticle], $_POST['produit_gessica'][$idArticle], $_POST['reference_gessica'][$idArticle], $_POST['ean_gessica'][$idArticle],$_POST['ppi_gessica'][$idArticle], $_POST['offre_gessica'][$idArticle], $_POST['montant_gessica'][$idArticle], $_POST['montant_finance_gessica'][$idArticle], $_POST['euro_gessica'][$idArticle], $_POST['cmt_gessica'][$idArticle]);
	}
}
$successQ='?success=offres-add';
unset($_POST);
header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
