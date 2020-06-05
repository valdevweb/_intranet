<?php


include('../../config/config.inc.php');


function getListModule($pdoEvo){
	$req=$pdoEvo->prepare("SELECT * FROM modules WHERE id_outil= :id_outil ORDER BY module");
	$req->execute([
		':id_outil'	=>$_POST['id_outil']
	]);

	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return "";
	}
	return $data;
}
$datas=getListModule($pdoEvo);
if(!empty($datas)){
	echo '<option value="">Sélectionner un outil</option>';
	foreach ($datas as $key => $data) {
		echo '<option value="'.$data['id'].'">'.$data['outil'].'</option>';

	}
}else{

	echo '<option value="0">Aucun module</option>';

}