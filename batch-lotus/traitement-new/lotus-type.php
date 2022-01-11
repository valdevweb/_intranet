<?php



foreach ($lotus as $key => $extraction) {
	$name=explode('/',$extraction['contenu']);
	$name=trim($name[0]);

	$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$name."*)",$justThese);
	$data = ldap_get_entries($lotusCon, $result);
	if($data['count']==0){
			// pas trouvé
		$codeErr=2;
		$email=$extraction['contenu'];
	}elseif($data['count']==1){



			// trouvé unique
		if(!strpos($data[0]['mail'][0],'/')){
			$email=$data[0]['mail'][0];
			$codeErr=0;
		}else{
			if(isset($data[0]['mailaddress'])){
				if(str_contains($data[0]['mailaddress'][0],'.leclerc')){
					$email=$data[0]['mailaddress'][0];
					$codeErr=0;
				}
			}else{
				$codeErr=5;
				$email="";
			}


		}
	}else{
			// plusieurs correspondances trouvées
		for($i=0;$i<count($data)-1;$i++){
			if($extraction['contenu']==$data[$i]['displayname'][0]){
					// on reverifie si on n'a pas à nouveau une adresse lotus : pour l'instant cas unqiue avec laurice lionel.
					// Il a 2 fiches dans lotus dont une où aucune adresse mail n'est saisie si bien que ça renvoie à nouveau l'adresse lotus
				if(!strpos($data[$i]['mail'][0],'/')){
					$email=$data[$i]['mail'][0];
					$codeErr=0;
				}
			}else{
				if(isset($data[0]['mailaddress'])){
					if(str_contains($data[0]['mailaddress'][0],'.leclerc')){
				// $found=strpos($data[0]['mailaddress'][0],".leclerc");
						echo "OUI pour ".$extraction['contenu'].' : ' .$data[0]['mailaddress'][0];
						echo "<br>";
						$email=$data[0]['mailaddress'][0];
						$codeErr=0;

					}
				}

			}
		}
		if(empty($email)){
			$codeErr=2;
			$email=$extraction['contenu'];
		}

	}

	if($codeErr==0){
		// ajout mail
		// 1 vérif si mail dans emails, si oui récup id sinon ajoute et récup id
		$idEmail=$crudMag->getOneByField("emails", "email", $email);
		if(empty($idEmail)){
			$idEmail=$crudMag->insertOne("emails", "email", $email);
		}else{
			$idEmail=$idEmail['id'];
		}

		$datas=['id_listdiffu'=>$extraction['id_listdiffu'], 'id_email'=>$idEmail];
		$crudMag->insert("listdiffu_email", $datas);



	}else{
		// insertion erreur
		$data=['id_import'=>$newImport['id'], 'id_extraction'=>$extraction['id'], 'id_error'=>$codeErr];
		$crudMag->insert('listdiffu_errors',$data);
	}

}

// SELECT * FROM `listdiffu` LEFT JOIN listdiffu_email ON listdiffu.id=listdiffu_email.id_listdiffu LEFT JOIN emails on listdiffu_email.id_email=emails.id WHERE btlec=4040