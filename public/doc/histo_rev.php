<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//----------------------------------------------------------------
require "../../functions/utilities.fn.php";
//----------------------------------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),"consultation", "historique des reversements",101);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
// $page=basename(__file__);
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//header et nav bar
include ('../view/_head-bt.php');
include ('../view/_navbar.php');

//----------------------------------------------------------------
//			functions
//----------------------------------------------------------------

$info=[];

// alimentation du select (filtre tout l'affichage)
function getRevType($pdoBt)
{
	$req=$pdoBt->query("SELECT name, id FROM doc_type WHERE category='reversement' ORDER BY ordre");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$revList=getRevType($pdoBt);


function getRev($pdoBt)
{
	$req=$pdoBt->prepare("SELECT id_type,date_rev,YEAR(date_rev) as year,MONTH(date_rev) as month, DATE_FORMAT(date_rev, '%d/%m/%Y') as fulldate, divers, name FROM reversements LEFT JOIN doc_type ON reversements.id_type= doc_type.id ORDER BY date_rev DESC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getRevFiltred($pdoBt)
{
	$req=$pdoBt->prepare("SELECT id_type,date_rev,YEAR(date_rev) as year,MONTH(date_rev) as month, DATE_FORMAT(date_rev, '%d/%m/%Y') as fulldate, divers, name FROM reversements LEFT JOIN doc_type ON reversements.id_type= doc_type.id WHERE id_type= :id_type ORDER BY date_rev DESC");
	$req->execute(array(
		':id_type'	=>$_POST['doc_type']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


	// echo "<pre>";
	// var_dump($_POST);
	// echo '</pre>';


$refYear="";
if(isset($_POST['submit']) && isset($_POST['doc_type'])  && !empty($_POST['doc_type']))
{
	$fullRevList=getRevFiltred($pdoBt);
	// echo "form submit";
}
elseif(empty($_POST['doc_type']))
{
	$fullRevList=getRev($pdoBt);
	// echo "true";
}
else
{
	$fullRevList=getRev($pdoBt);
}

if(empty($fullRevList))
{
	$info[]="Pas de résultat pour le type de document sélectionné";
}
// echo "<pre>";
// 	var_dump($fullRevList);
// 	echo '</pre>';

?>
<?php

//contenu
include('histo_rev.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer-bt.php');
?>