<?php

session_start();
include('../../config/config.inc.php');
require '../../config/db-connect.php';


include('../../Class/evo/AppliDao.php');
include('../../Class/evo/ModuleDao.php');
include('../../Class/evo/EvoHelpers.php');

$appliDao=new AppliDao($pdoEvo);
$moduleDao= new ModuleDao($pdoEvo);


$arrayPlateformeAppli=EvoHelpers::arrayAppliPlateformeName($pdoEvo);


if(isset($_POST["id_resp"]) && !empty($_POST["id_resp"])){
	$datas=$appliDao->getListAppliResp($_POST['id_resp']);
	if(!empty($datas)){

		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$arrayPlateformeAppli[$data['id']].' - '.$data['appli'].'</option>';

		}
	}
}

if(isset($_POST["id_appli"]) && !empty($_POST["id_appli"])){

	$datas=$moduleDao->getListModule($_POST["id_appli"]);
	if(!empty($datas)){
		echo '<option value="">Sélectionner un module</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$data['module'].'</option>';

		}
	}else{
		echo '<option value="0">Aucun module</option>';
	}
}

