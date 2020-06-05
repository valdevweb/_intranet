<?php


include('../../config/config.inc.php');


function getListOutils($pdoEvo){
	$req=$pdoEvo->prepare("SELECT * FROM outils WHERE id_plateforme= :id_plateforme ORDER BY outil");
	$req->execute([
		':id_plateforme'	=>$_POST['id_plateforme']
	]);

	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return "";
	}
	return $data;
}

if(isset($_POST["id_plateforme"]) && !empty($_POST["id_plateforme"])){

	$datas=getListOutils($pdoEvo);
	if(!empty($datas)){
		echo '<option value="">Sélectionner un outil</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$data['outil'].'</option>';

		}
	}

}

function getListModule($pdoEvo){
	$req=$pdoEvo->prepare("SELECT * FROM modules WHERE id_outils= :id_outils ORDER BY module");
	$req->execute([
		':id_outils'	=>$_POST['id_outil']
	]);

	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return "";
	}
	return $data;
}

if(isset($_POST["id_outil"]) && !empty($_POST["id_outil"])){

	$datas=getListModule($pdoEvo);
	if(!empty($datas)){
		echo '<option value="">Sélectionner un outil</option>';
		foreach ($datas as $key => $data) {
			echo '<option value="'.$data['id'].'">'.$data['module'].'</option>';

		}
	}else{

		echo '<option value="0">Aucun module</option>';

	}


}

