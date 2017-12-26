<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require_once '../../functions/form.fn.php';

$allMagMsg=showAllMsg($pdoBt);


// traitement des état des demandes
// si nouveau  : en attente de réponse
// si en cours => prendre la dernière réponse en date et afficher la date et le nom de l'utilisateur qui à répondu + lein pour consulter (même que consulter)

function etat($etat)
{
switch ($etat) {
	case 'nouveau':
		$value="en attente de réponse";
		break;
	case 'clos':
		$value="clôturé le " . $date ;
		break;
	case 'en cours':
		$value= $user . "vous a répondu le  " . $date ;
		break;
	default:
		$value="";
		break;
}
}





//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('histo.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');