<?php

	require '../../config/config.inc.php';
	require '../../Class/MagDbHelper.php';
// https://www.webslesson.info/2016/06/ajax-autocomplete-textbox-using-jquery-php-and-mysql.html

if(isset($_POST["query"]))
{
	$response=explode("#",$_POST['query']);
	$page="fiche-mag.php";
	$searchTerm=$response[0];

	$req=$pdoMag->prepare("SELECT * FROM mag  LEFT JOIN sca3 ON mag.id=sca3.btlec_sca WHERE concat(mag.deno,mag.galec,mag.id,sca3.ville_sca,mag.adherent) LIKE :search");
	$req->execute([
		':search' =>'%'.$searchTerm .'%'
	]);
	$searchMags=$req->fetchAll(PDO::FETCH_ASSOC);
	$output = '';
	$output = '<ul class="results">';
	foreach ($searchMags as $searchMag){
		$output .='<li><a href="'.$page.'?id='.$searchMag['id'].'" class="result-item">'.$searchMag['id'].' - '.$searchMag['galec']. ' '.$searchMag['deno'] . ' - '.ucfirst(strtolower($searchMag['ville'])).' ('.$searchMag['adherent'].')</a></li>';
	}
	$output .= '</ul>';
	echo $output;
}

