<?php

session_start();
include('../../config/config.inc.php');
include('../../Class/EvoManager.php');
include('../../Class/EvoHelpers.php');
$evoMgr=new EvoManager($pdoEvo);
$arrayPlateformeOutils=EvoHelpers::arrayOutilsPlateformeName($pdoEvo);


if(isset($_POST["id_resp"]) && !empty($_POST["id_resp"])){

	$datas=$evoMgr->getListOutilsResp($_POST['id_resp']);
	if(!empty($datas)){
		echo '<option value="">Sélectionner un outil</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$arrayPlateformeOutils[$data['id']].' - '.$data['outils'].'</option>';

		}
	}

}



if(isset($_POST["id_outils"]) && !empty($_POST["id_outils"])){

	$datas=$evoMgr->getListModule($_POST["id_outils"]);
	if(!empty($datas)){
		echo '<option value="">Sélectionner un module</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$data['module'].'</option>';

		}
	}else{

		echo '<option value="0">Aucun module</option>';

	}


}

