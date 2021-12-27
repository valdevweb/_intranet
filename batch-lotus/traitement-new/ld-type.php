	<?php

	foreach ($ld as $key => $extraction) {
		$codeErr=9;
		$data=['id_import'=>$newImport['id'], 'id_extraction'=>$extraction['id'], 'id_error'=>9];
		$crudMag->insert('listdiffu_errors',$data);

	}