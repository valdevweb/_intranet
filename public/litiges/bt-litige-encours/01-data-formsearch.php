<?php

// 1- traitement des formulaires de recherche
// si un des 2 formulaires est validé, on stock les post en var de sessions
// ce sont elle qui sont vérifiée pour le réaffichage des formulaires
// on reset les var de session du formulaire non validé pour le cas où il aurait été
// utilisé précédemment. Cela permet de ne pas cumuler les critères de recherches
// en revanche les critères de filtres eux sont cumulés aux formulaires
// pour retirer les filtres, l'utilisateur doit cliquer sur le bouton adéquat
// on recharge la page après traitement des posts pour que les var de session soient utilisées par la requete

if(isset($_POST['search_one'])){
	unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);
	// date début
	if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {
		$_SESSION['form-data']['date_start']=$_POST['date_start'];
	}else{
		if(isset($_SESSION['form-data']['date_start'])){
			unset($_SESSION['form-data']['date_start']);
		}
	}

	// date fin
	if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {
		$_SESSION['form-data']['date_end']=$_POST['date_end'];
	}else{
		if(isset($_SESSION['form-data']['date_end'])){
			unset($_SESSION['form-data']['date_end']);
		}
	}

	// etat
	if (isset($_POST['etat']) && !empty($_POST['etat'])) {
		$_SESSION['form-data']['etat']=$_POST['etat'];
	}else{
		if(isset($_SESSION['form-data']['etat'])){
			unset($_SESSION['form-data']['etat']);
		}
	}

	if (isset($_POST['typo']) && !empty($_POST['typo'])) {
		$_SESSION['form-data']['typo']=$_POST['typo'];
	}else{
		if(isset($_SESSION['form-data']['typo'])){
			unset($_SESSION['form-data']['typo']);
		}
	}

	if (isset($_POST['centrale']) && !empty($_POST['centrale'])) {
		$_SESSION['form-data']['centrale']=$_POST['centrale'];
	}else{
		if(isset($_SESSION['form-data']['centrale'])){
			unset($_SESSION['form-data']['centrale']);
		}
	}

	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}

if(isset($_POST['search_two'])){
	unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);

	// article
	// si article est coché, on fait une requete avec une jointure sur la table détail
	if(isset($_POST['article'])){
		$_SESSION['form-data-deux']['article']=true;
	}
	if(isset($_POST['btlec'])){
		$_SESSION['form-data-deux']['btlec']=true;
	}
	if(isset($_POST['galec'])){
		$_SESSION['form-data-deux']['galec']=true;
	}

	// search_strg
	if(isset($_POST['search_strg']) && !empty($_POST['search_strg'])){
		$_SESSION['form-data-deux']['search_strg']=$_POST['search_strg'];
	}else{
		unset($_SESSION['form-data-deux']['search_strg']);
	}
	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}
if(isset($_POST['clear_form'])){
	unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);
	unset($_SESSION['filter-data']);
	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}