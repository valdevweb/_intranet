<?php
$paramList=[];
$today=(new DateTime())->setTime(23,59);

if($today<new DateTime($_POST['date_end'])){
	$_POST['date_end']=$today->format('Y-m-d');
}

$dateParam=" (date_start BETWEEN '".$_POST['date_start'] ."' AND '". $_POST['date_end']."') ";


$paramList[]=$dateParam;
if(!empty($_POST['strg'])){
	$strParam="titre LIKE '%".$_POST['strg']."%'";
	$paramList[]=$strParam;

}
if(!empty($_POST['main_cat'])){
	$mainCatParam="main_cat=".$_POST['main_cat'];
	$paramList[]=$mainCatParam;

}
if(!empty($_POST['cat'])){
	$catParam="cat=".$_POST['cat'];
	$paramList[]=$catParam;
}
$params= join(' AND ', $paramList). " ORDER BY date_start DESC";

$results=$gazetteDao->getGazetteByParam($params);



if(!empty($results)){
	$resultsLink=$gazetteDao->getLinksByParam($params);
	$resultsFiles=$gazetteDao->getFilesByParam($params);
}