<?php
$param="";
	$paramList=[];
	if(isset($_SESSION['encours_filter']['gt']) && !empty($_SESSION['encours_filter']['gt'])){
		$paramGt=join(' OR ',array_map(
			function($value){return "gt=".$value;},
			$_SESSION['encours_filter']['gt']));
		$paramList[]='('.$paramGt.')';

	}
	if(!empty($_SESSION['encours_filter']['fou'])){
		$paramFou='fournisseur="'.htmlspecialchars($_SESSION['encours_filter']['fou'], ENT_QUOTES).'"';
		$paramList[]=$paramFou;
	}



	if(!empty($_SESSION['encours_filter']['marque'])){
		$paramMarque='marque="'.htmlspecialchars($_SESSION['encours_filter']['marque'], ENT_QUOTES).'"';
		$paramList[]=$paramMarque;

	}
	if(isset($_SESSION['encours_filter']['op']) && !empty($_SESSION['encours_filter']['op'])){
		$paramOp=join(' OR ',array_map(
			function($value){return "libelle_op='".$value."'";},
			$_SESSION['encours_filter']['op']));
		$paramList[]=$paramOp;

	}
	if(isset($_SESSION['encours_filter']['other_num_cde']) && !empty($_SESSION['encours_filter']['other_num_cde'])){
		if(isset($_SESSION['encours_filter']['num_cde'])){
			unset($_SESSION['encours_filter']['num_cde']);
			$_SESSION['encours_filter']['num_cde']=$_SESSION['encours_filter']['other_num_cde'];
			unset($_SESSION['encours_filter']['other_num_cde']);
		}

	}
	if(isset($_SESSION['encours_filter']['num_cde']) && !empty($_SESSION['encours_filter']['num_cde'])){
		$paramNumCde=join(' OR ',array_map(
			function($value){return "id_cde=".$value;},
			$_SESSION['encours_filter']['num_cde']));
		$paramList[]=$paramNumCde;

	}
	if(isset($_SESSION['encours_filter']['dossier'])){
		if($_SESSION['encours_filter']['dossier']==1000){
			$paramDossier= 'dossier=1000';
			$paramList[]=$paramDossier;

		}
		if($_SESSION['encours_filter']['dossier']==1){
			$paramDossier= 'dossier!=1000';
			$paramList[]=$paramDossier;
		}

	}
	if(!empty($_SESSION['encours_filter']['date_start']) && !empty($_SESSION['encours_filter']['date_end']) && isset($_SESSION['encours_filter']['date_type']) && !empty($_SESSION['encours_filter']['date_type'])){
		if($_SESSION['encours_filter']['date_type']=='date_op'){
			$paramDate="date_start ";
		}
		if($_SESSION['encours_filter']['date_type']=='date_cde'){
			$paramDate="date_cde ";

		}
		if($_SESSION['encours_filter']['date_type']=='date_liv'){
			$paramDate="date_liv ";

		}
		$paramDate.= 'BETWEEN "'. $_SESSION['encours_filter']['date_start']. '" AND "'. $_SESSION['encours_filter']['date_end'].'"';
		$paramList[]=$paramDate;
	}

	if(!empty($_SESSION['encours_filter']['search_strg'])){
		$paramSearch=$_SESSION['encours_filter']['type_of_strg']. " LIKE '%".htmlspecialchars($_SESSION['encours_filter']['search_strg'], ENT_QUOTES). "%'";
		$paramList[]=$paramSearch;
	}
    if(!empty($_SESSION['encours_filter']['ref'])){
        $paramRef="ref LIKE '%".htmlspecialchars($_SESSION['encours_filter']['ref'], ENT_QUOTES). "%'";
		$paramList[]=$paramRef;

    }
    if(isset($_SESSION['encours_filter']['codelec']) && !empty($_SESSION['encours_filter']['codelec'])){
        $codelec=str_replace(' ','',htmlspecialchars($_SESSION['encours_filter']['codelec'],ENT_QUOTES));
        $paramCodelec="codelec =".$codelec;
		$paramList[]=$paramCodelec;

    }

	$param='AND ' .join(' AND ',$paramList);
	// echo $param;
    /** @var CdesDao $cdesDao */
	$listCdes=$cdesDao->getCdes($param);
	if(isset($_SESSION['encours_filter']['fou'])){
		if(isset($paramGt)){
			$paramGt= ' AND (' . $paramGt .')';
			$listCdesByFou=$cdesDao->getCdesByFou($_SESSION['encours_filter']['fou'], $paramGt);
		}

	}
// echo $_SESSION['encours_filter']['fou'];
	$nbArt=count($listCdes);
	$listInfos=$cdesAchatDao->getInfos($param);