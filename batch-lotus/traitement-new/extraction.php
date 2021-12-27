<?php

foreach ($extraction as $key => $row) {
	if(!empty($row['btlec'])){
		$param="WHERE name='".$row['ld_short']."' AND suffixe='".$row['suffixe']."'";
		$ldExist=$crudMag->getOneWhere("listdiffu", $param);
		if (empty($ldExist)) {
			$datas=['btlec'=>$row['btlec'], 'galec'=>$row['galec'], 'name'=>$row['ld_short'], 'suffixe'=>$row['suffixe'],'date_insert'=> date("Y-m-d"),'id_import'=> $newImport['id']];
			$idLd=$crudMag->insert("listdiffu", $datas);
		}else{
			$idLd=$ldExist['id'];
		}
		$crudMag->updateOneField("lotus_extraction", "id_listdiffu", $idLd, $row['id']);
	}

}

