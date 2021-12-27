<?php


foreach ($emailDb as $key => $extraction) {


	$name=explode('@',$extraction['contenu']);
	$name=trim($name[0]);
	$result=ldap_search($lotusCon, $ldaptree, "(mail=".$name."*)",$justThese);
	$data = ldap_get_entries($lotusCon, $result);

		// correspondace trouvée
	if(count($data)>1){
		$email=$data[0]['mail'][0];
		$idEmail=$crudMag->getOneByField("emails", "email", $email);
		if(empty($idEmail)){
			$idEmail=$crudMag->insertOne("emails", "email", $email);
		}else{
			$idEmail=$idEmail['id'];
		}

		$datas=['id_listdiffu'=>$extraction['id_listdiffu'], 'id_email'=>$idEmail];
		$crudMag->insert("listdiffu_email", $datas);

	}else{
			// adresse mail non trouvée
		$codeErr=9;
		$data=['id_import'=>$newImport['id'], 'id_extraction'=>$extraction['id'], 'id_error'=>$codeErr];
		$crudMag->insert('listdiffu_errors',$data);
	}
}