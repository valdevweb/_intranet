<?php
foreach ($empty as $key => $extraction) {
	$data=['id_import'=>$newImport['id'], 'id_extraction'=>$extraction['id'], 'id_error'=>4];
	$crudMag->insert('listdiffu_errors',$data);
}