<?php
session_start();


require('../../../config/config.inc.php');
require '../../../config/db-connect.php';


include('../../../Class/evo/EvoDao.php');
include('../../../Class/evo/ModuleDao.php');
include('../../../Class/evo/AppliDao.php');
require "../../../functions/form.fn.php";
require "../../../Class/BtUserManager.php";

$evoMgr=new EvoDao($pdoEvo);
$appliDao=new AppliDao($pdoEvo);
$moduleDao=new ModuleDao($pdoEvo);
$btUserMgr=new BtUserManager();
$userAccess=$btUserMgr->getUserAttribution($pdoUser,$_SESSION['id_web_user']);
function getListModule($pdoEvo, $idAppli){
	$listModule=[];
	$i=0;

	$req=$pdoEvo->prepare("SELECT * FROM modules WHERE id_appli= :id_appli  ORDER BY module");
	$req->execute([
		':id_appli'	=>$idAppli,
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

// function sortFunction( $a, $b ) {
// 	return ($a['module'] < $b['module']) ? -1 : 1;
// }



if(isset($_POST["id_plateforme"]) && !empty($_POST["id_plateforme"])){

	$datas=$appliDao->getApplisByPlateforme($_POST['id_plateforme']);
	if(!empty($datas)){
		echo '<option value="">Sélectionner une appli</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'"'.checkSelected($data['id'],'appli').'>'.$data['appli'].'</option>';

		}
	}

}




if(isset($_POST["id_appli"]) && !empty($_POST["id_appli"])){
	$datas=$moduleDao->getListModule($_POST["id_appli"]);

	if(!empty($datas)){
		echo '<option value="">Sélectionner un module</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'"'.checkSelected($data['id'],'module').'>'.$data['module'].'</option>';
		}
	}else{
		echo '<option value="0">Aucun module</option>';
	}

}

