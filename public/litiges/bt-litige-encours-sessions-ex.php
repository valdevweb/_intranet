<?php
// 2- traitement des variables de session
// on compose la requete en fonction des var de session existantes
// 3 grand groupes :
// form-data pour le formulaire 1
// form-data-deux pour le formulaire 2
// filter data pour les filtres
// on ajoute tous les critères de recherche au tableau paramList


if(isset($_SESSION['form-data'])){
	$paramList=[];
	// unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);
	if(isset($_SESSION['form-data']['date_start']) && !empty($_SESSION['form-data']['date_start'])){
		$dateStart=$_SESSION['form-data']['date_start'];
	}else{
		$dateStart="2019-01-01 00:00:00";
	}
	if(isset($_SESSION['form-data']['date_end']) && !empty($_SESSION['form-data']['date_end'])){
		$dateEnd=$_SESSION['form-data']['date_end'];
	}else{
		$dateEnd=date('Y-m-d') ." 00:00:00";
	}
	$paramDate="date_crea BETWEEN '".$dateStart ."'  AND '".$dateEnd."'";
	$paramList[]=$paramDate;


	if(isset($_SESSION['form-data']['etat'])){
		$paramEtat=join(' OR ', array_map(function($value){return 'id_etat='.$value;},$_SESSION['form-data']['etat']));
	}else{
		$paramEtat='';
	}
	$paramList[]=$paramEtat;
	// $listLitige=getListLitige($pdoLitige);
}
if (isset($_SESSION['form-data-deux'])) {
	if(isset($_SESSION['form-data-deux']['search_strg']) && !isset($_SESSION['form-data-deux']['article'])){
		$paramStrg= "concat(dossiers.dossier,magasin.mag.deno,dossiers.galec,magasin.mag.id) LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['article'])){
		$paramStrg= "details.article LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}else{
		$paramStrg="";
	}
	$paramList[]=$paramStrg;
}
if(isset($_SESSION['filter-data'])){
	if(isset($_SESSION['filter-data']['vingtquatre']) ){
		$paramVingtQuatre= ' vingtquatre = 1 OR esp = 1';
	}else{
		$paramVingtQuatre= '';
	}
	$paramList[]=$paramVingtQuatre;

	if (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']==1) {
		$paramCommission= " commission=1 ";
	}elseif (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']==0){
		$paramCommission= " commission=0 ";
	}else{
		$paramCommission= "";
	}

	$paramList[]=$paramCommission;
}