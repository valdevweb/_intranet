<?php


include('../../config/config.inc.php');
include('../../Class/EvoManager.php');
$evoMgr=new EvoManager($pdoEvo);



if(isset($_POST["id_plateforme"]) && !empty($_POST["id_plateforme"])){

	$datas=$evoMgr->getListOutils($_POST['id_plateforme']);
	if(!empty($datas)){
		echo '<option value="">Sélectionner un outil</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$data['outils'].'</option>';

		}
	}

}



if(isset($_POST["id_outils"]) && !empty($_POST["id_outils"])){

	$datas=$evoMgr->getListModule($_POST["id_outils"]);
	if(!empty($datas)){
		echo '<option value="">Sélectionner un outil</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$data['module'].'</option>';

		}
	}else{

		echo '<option value="0">Aucun module</option>';

	}


}
