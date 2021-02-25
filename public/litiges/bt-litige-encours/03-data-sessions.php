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
		$dateEnd=date('Y-m-d H:i:s');
	}
	$paramDate="date_crea BETWEEN '".$dateStart ."'  AND '".$dateEnd."'";
	$paramList[]=$paramDate;


	if(isset($_SESSION['form-data']['etat'])){
		$paramEtat=join(' OR ', array_map(function($value){return 'id_etat='.$value;},$_SESSION['form-data']['etat']));
	}else{
		$paramEtat='';
	}
	$paramList[]=$paramEtat;

	if(isset($_SESSION['form-data']['typo'])){
		$paramTypo=join(' OR ', array_map(function($value){return 'id_typo='.$value;},$_SESSION['form-data']['typo']));
	}else{
		$paramTypo='';
	}
	$paramList[]=$paramTypo;


	if(isset($_SESSION['form-data']['centrale'])){
		$paramCentrale=join(' OR ', array_map(function($value){return 'centrale='.$value;},$_SESSION['form-data']['centrale']));
	}else{
		$paramCentrale='';
	}
	$paramList[]=$paramCentrale;
	// $listLitige=getListLitige($pdoLitige);
}
if (isset($_SESSION['form-data-deux'])) {
	if(isset($_SESSION['form-data-deux']['search_strg']) && !isset($_SESSION['form-data-deux']['article']) && !isset($_SESSION['form-data-deux']['btlec']) && !isset($_SESSION['form-data-deux']['galec'])){
		$paramStrg= "concat(dossiers.dossier,magasin.mag.deno) LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['article'])){
		$paramStrg= "details.article LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['btlec'])){
		$paramStrg= "magasin.mag.id =".$_SESSION['form-data-deux']['search_strg'];
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['galec'])){
		$paramStrg= "magasin.mag.galec=".$_SESSION['form-data-deux']['search_strg'] ;
	}else{
		$paramStrg="";
	}
	$paramList[]=$paramStrg;
}
if(isset($_SESSION['filter-data'])){
	if(isset($_SESSION['filter-data']['vingtquatre']) && $_SESSION['filter-data']['vingtquatre']==1){
		$paramVingtQuatre= ' vingtquatre = 1 OR esp = 1';
	}elseif (isset($_SESSION['filter-data']['vingtquatre']) && $_SESSION['filter-data']['vingtquatre']==0){
		$paramVingtQuatre= " vingtquatre = 0 AND esp = 0";
	}else{
		$paramVingtQuatre= '';
	}
	$paramList[]=$paramVingtQuatre;

	if (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']==1) {
		$paramCommission= " commission=1 ";
	}elseif (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']=="pending"){
		$paramCommission= " commission=0 ";
	}else{
		$paramCommission= "";
	}

	$paramList[]=$paramCommission;

	if (isset($_SESSION['filter-data']['occasion']) && $_SESSION['filter-data']['occasion']==1) {
		$paramOccasion= " occasion=1 ";
	}elseif (isset($_SESSION['filter-data']['occasion']) && $_SESSION['filter-data']['occasion']==0){
		$paramOccasion= " occasion=0 ";
	}else{
		$paramOccasion= "";
	}
	$paramList[]=$paramOccasion;

}

