<?php

	$boxSelected=false;
	$i=1;

	// si on arrive sur cette page suite à une déclaration de vol, on connait déjà le ou les  numéros de palette qui postent pb
	// on les récupère dans la varaible de session $_SESSION['palette']
	if(!isset($_SESSION['palette'])){
		$searchStr=$_POST['search_strg'];
		$dataSearch=$litigeDao->searchPaletteOrFacture($pdoQlik, $searchStr, $_SESSION['id_galec']);
	}else{
		$dataSearch=$litigeDao->getPaletteForRobbery($pdoQlik, $_SESSION['palette']);
	}

	// echo "<pre>";
	// print_r($dataSearch);
	// echo '</pre>';
	// on récupère les tete de box et contenu de box dans ces tableaux pour plus tard dans l'insertion de donnée pourvoir préciser si box
	$boxTete=[];
	$boxDetail=[];
	
	foreach ($dataSearch as $key =>$data){
		if($magInfo['occasion']==1){
			$listProdOcc=$occDao->getListArticleOccByArticlePalette($data['article']);
			if(!empty($listProdOcc)){
				$dataSearch[$key]['occasion']=1;
				$dataSearch[$key]['occasion_detail']=$listProdOcc;
			}

		}
		$dataSearch[$key]['box-tete']='';
		$dataSearch[$key]['box-detail']='';

		if($data['tarif']==0){
			$teteboxFound=$litigeDao->getBoxHead($pdoQlik, $data['dossier'], $data['article']);
				if(!empty($teteboxFound)){
				$dataSearch[$key]['box-tete']=$i;

				// pour faciliter le tri on assigne le code  article -1 à la tête de box
				$dataSearch[$key]['box-detail']= $data['article']-1;
				$boxTete[]=$data['article'];
				$i++;
			}
		}
		// on verifie chaque couple code article et dossier, si il est dans la table assortiment => si oui article= detail box
		$boxContent=$litigeDao->getBoxDetail($pdoQlik, $data['dossier'], $data['article']);
		echo "<pre>";

		// echo $data['dossier'];
		// echo $data['article'];
		print_r($boxContent);
		echo '</pre>';
		if(!empty($boxContent)){
			$boxSelected=true;
			$dataSearch[$key]['box-detail']=$boxContent['tete'];
		}

	}
echo "<pre>";
print_r($dataSearch);
echo '</pre>';
	if($boxSelected){
		function nameSort($a, $b){
			return strcmp($a['box-detail'], $b['box-detail']);
		}
		usort($dataSearch, 'nameSort');
	}



echo "<pre>";
print_r($dataSearch);
echo '</pre>';


