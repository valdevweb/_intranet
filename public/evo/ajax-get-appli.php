<?php


include('../../config/autoload.php');
include('../../Class/EvoManager.php');
require "../../functions/form.fn.php";
require "../../Class/BtUserManager.php";

$evoMgr=new EvoManager($pdoEvo);
$btUserMgr=new BtUserManager();
$userAccess=$btUserMgr->getUserAttribution($pdoUser,$_SESSION['id_web_user']);

function getListModuleByRights($pdoEvo, $idAppli, $userAccess){
	$listModule=[];
	$i=0;

	foreach ($userAccess as $key => $access) {
		echo $access['id_droit'];
		$req=$pdoEvo->prepare("SELECT * FROM modules WHERE id_appli= :id_appli AND id_droit= :id_droit ORDER BY module");
		$req->execute([
			':id_appli'	=>$idAppli,
			':id_droit'	=>$access['id_droit']
		]);
		while ($row = $req->fetch(PDO::FETCH_ASSOC)){
			$listModule[$i]['id']=$row['id'];
			$listModule[$i]['module']=$row['module'];
			$i++;
		}

	}

	return $listModule;
}
function sortFunction( $a, $b ) {
	return ($a['module'] < $b['module']) ? -1 : 1;
}



if(isset($_POST["id_plateforme"]) && !empty($_POST["id_plateforme"])){

	$datas=$evoMgr->getListAppli($_POST['id_plateforme']);
	if(!empty($datas)){
		echo '<option value="">Sélectionner une appli</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'"'.checkSelected($data['id'],'appli').'>'.$data['appli'].'</option>';

		}
	}

}




if(isset($_POST["id_appli"]) && !empty($_POST["id_appli"])){
	$datas=getListModuleByRights($pdoEvo,$_POST["id_appli"], $userAccess);
	$sortedData=usort($datas, "sortFunction");

	if(!empty($datas)){
		echo '<option value="">Sélectionner un module</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'"'.checkSelected($data['id'],'module').'>'.$data['module'].'</option>';
		}
	}else{
		echo '<option value="0">Aucun module</option>';
	}

}

