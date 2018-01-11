<?php
//----------------------------------
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------
require('../../functions/form.bt.fn.php');
require('../../functions/form.fn.php');

require "../../functions/stats.fn.php";
// $page=basename(__file__);
// $descr="consultation des demandes magasins";
// $action="consultation";
// $data=addRecord($pdoStat,$page,$action, $descr);






//----------------------------------
//			html head
//----------------------------------

include('../view/_head.php');
include('../view/_navbar.php');

//----------------------------------
//			traitement
//----------------------------------

//bt retour
//$_SESSION['page_request']=$_SERVER['REQUEST_URI'];

//---------------------------------------
//		alim list deroulante services
//---------------------------------------
//contenu de la table service
$services=listServices($pdoBt);


//recherche du service du user connecté dans l'array services
$found_key = array_search($_SESSION['id_service'], array_column($services, 'id'));
//découpe le tableau à partir de la valeur recherchée jusqu'à la fin du tableau
$userService =array_slice($services,$found_key,1);
$one=array_slice($services,$found_key+1);
//découpe du début jusquà la valeur recherchée
$two=array_slice($services,0,$found_key);

function color($id,$services)
{
	$found_key = array_search($id, array_column($services, 'id'));
	$colorName= $services[$found_key]['color'];
	return $colorName;

}



//recup msg mag non clots
$msg=histoDdesMag($pdoBt);


include ('histo.ct.php');
include('../view/_footer.php');

