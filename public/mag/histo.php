<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require_once '../../functions/form.fn.php';

// $allMagMsg=showAllMsg($pdoBt);


// 	echo "<pre>";
// 	// var_dump(listIdMsg($pdoBt));

// 	echo '</pre>';

// $allMsg=listAllMsg($pdoBt);

// 	echo "<pre>";
// 	var_dump($allMsg);
// 	echo '</pre>';

// die;

// {
//  	$result=allMsg($pdoBt,$value);
// 	echo "<pre>";
// 	var_dump($result);
// 	echo '</pre>';

// }


	// echo "<pre>";
	// var_dump($allMagMsg);
	// echo '</pre>';

// traitement des état des demandes
// si nouveau  : en attente de réponse
// si en cours => prendre la dernière réponse en date et afficher la date et le nom de l'utilisateur qui à répondu + lein pour consulter (même que consulter)







//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('histo.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');