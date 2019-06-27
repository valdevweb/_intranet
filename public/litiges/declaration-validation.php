<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}


if($_SESSION['type']=='btlec'){
	unset($_SESSION['id_galec']);
	if(isset($_SESSION['palette']))
	{
		unset($_SESSION['palette']);
		unset($_SESSION['vol-id']);

	}
	// dossier_litige= si num dossier saisi manuellement par nat ou christelle
	if(isset($_SESSION['dossier_litige'])){
		$numDossier=$_SESSION['dossier_litige'];
		unset($_SESSION['dossier_litige']);

	}
}


$errors=[];
$success=[];
//------------------------------------------------------
//			procedure
//------------------------------------------------------
/*
1-recup le der numDossier de dossier
2-update dossier_temp avec vrai numDossier
3-copy dossier-temp en dossier prod
4- update details temp avec id dossier et numDossier
5-copie details_temp vers prod
6-update table palette_inv_temp avec id dossier
7- copie palette_inv_temp
 */



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

// le numéro de dossier du litige et non l'id du litige
function getLastNumDossier($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT dossier FROM dossiers ORDER BY dossier DESC LIMIT 1");
	$req->execute();
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

function copyDossier($pdoLitige){
	$req=$pdoLitige->prepare("INSERT INTO dossiers(dossier,date_crea, user_crea, nom, galec, id_web_user, vingtquatre, etat_dossier, id_robbery, id_transp, id_affrete, transitok, id_transit, id_prepa, id_ctrl, id_chg, id_ctrl_stock, ctrl_ok, id_user_ctrl_stock, id_gt, id_imputation, id_typo, id_etat, id_analyse, id_conclusion, date_prepa, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, flag_valo, valo, date_cloture, commission, date_commission)
		SELECT dossier, date_crea, user_crea, nom, galec, id_web_user, vingtquatre, etat_dossier, id_robbery, id_transp, id_affrete, transitok, id_transit, id_prepa, id_ctrl, id_chg, id_ctrl_stock, ctrl_ok, id_user_ctrl_stock, id_gt, id_imputation, id_typo, id_etat, id_analyse, id_conclusion, date_prepa, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, flag_valo, valo, date_cloture, commission, date_commission FROM dossiers_temp WHERE dossiers_temp.id= :id");
	$req->execute([
		':id'		=>$_GET['id']
	]);
	return $pdoLitige->lastInsertId();
}


function updateTempDossier($pdoLitige,$numDossier){
	$req=$pdoLitige->prepare("UPDATE dossiers_temp SET dossier= :dossier WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['id'],
		':dossier'	=>$numDossier
	]);

	return $req->rowCount();

}

function updateTempDetail($pdoLitige,$idDossier,$numDossier){

	$req=$pdoLitige->prepare("UPDATE details_temp SET id_dossier= :id_dossier, dossier =:dossier WHERE id_dossier= :id");
	$req->execute([
		':id'		=>$_GET['id'],
		':dossier'	=>$numDossier,
		':id_dossier'	=>$idDossier
	]);
	return $req->rowCount();
	// return $req->errorInfo();

}

function updateTempInvPalette($pdoLitige,$idDossier){
	$req=$pdoLitige->prepare("UPDATE palette_inv_temp SET id_dossier= :idDossier WHERE id_dossier= :id");
	$req->execute([
		':idDossier'	=>$idDossier,
		':id'			=>$_GET['id'],
	]);
	return $req->rowCount();

}

function updateTempDial($pdoLitige,$idDossier){
	$req=$pdoLitige->prepare("UPDATE dial_temp SET id_dossier= :idDossier WHERE id_dossier= :id");
	$req->execute([
		':idDossier'	=>$idDossier,
		':id'			=>$_GET['id'],
	]);
	return $req->rowCount();
	// return $req->errorInfo();

}



function copyInvPalette($pdoLitige,$idDossier){
	$req=$pdoLitige->prepare("INSERT INTO palette_inv (id_dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf, found) SELECT id_dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf, found FROM palette_inv_temp WHERE id_dossier= :id_dossier");
	$req->execute([
		':id_dossier'	=>$idDossier
	]);
	// return $req->rowCount();
	return $req->errorInfo();
}

function copyDetail($pdoLitige,$idDossier){
	$req=$pdoLitige->prepare("INSERT INTO details( id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, puv, pul, fournisseur, cnuf, qte_litige, box_tete, box_art, id_reclamation, inv_palette, inversion, inv_article, inv_qte, inv_descr, inv_tarif, valo_line, inv_fournisseur, etat_detail, pj, ctrl_ko, ecart, mvt) SELECT  id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, puv, pul, fournisseur, cnuf, qte_litige, box_tete, box_art, id_reclamation, inv_palette, inversion, inv_article, inv_qte, inv_descr, inv_tarif, valo_line, inv_fournisseur, etat_detail, pj, ctrl_ko, ecart, mvt FROM details_temp WHERE details_temp.id_dossier= :id_dossier");
	$req->execute([
		':id_dossier'	=>$idDossier
	]);
	return $req->rowCount();
	// return $req->errorInfo();

}


function copyDial($pdoLitige, $idDossier){
	$req=$pdoLitige->prepare("INSERT INTO dial( id_dossier, date_saisie, msg, id_web_user, filename, mag) SELECT id_dossier, date_saisie, msg, id_web_user, filename, mag FROM dial_temp WHERE dial_temp.id_dossier= :id_dossier");
	$req->execute([
		':id_dossier'	=>$idDossier
	]);
	return $req->rowCount();
}

function isDial($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM dial_temp WHERE id_dossier= :id_dossier");
	$req->execute([
		':id_dossier'	=>$_GET['id']
	]);
	$result=$req->fetch(PDO::FETCH_ASSOC);
	if(isset($result['id'])){
		return $result;
	}
	else{
		return false;
	}
}




function isPaletteInv($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM palette_inv_temp WHERE id_dossier= :id_dossier");
	$req->execute([
		':id_dossier'	=>$_GET['id']
	]);
	$result=$req->fetch(PDO::FETCH_ASSOC);
	if(isset($result['id'])){
		return $result;
	}
	else{
		return false;
	}
}


// la déclaration est normalement finalisée, donc on peut recopier de la table temp à la table de prod
if(isset($_GET['id']))
{
//------------------------------------------------------
//			Recopie de la base temp vers la base
//------------------------------------------------------
// 1-recup le der numDossier de dossier
// // si on a imposé un numéro de dossier sur la page déclaration basic, on l'a récupéré dans la var de session et affecté à numDossier
	if(!isset($numDossier)){
		$numDossier=getLastNumDossier($pdoLitige);
		$numDossier=$numDossier['dossier'];
			// il faut vérifier que l'on a pas changé d'année
			// prend les 2 1er caractère du numdossier pour les comparer à l'année actuelle
			// si différent de l'anneé actuelle, on a changé d'année par rapport au der dossier
			// il faut donc créer le 1er numdossier
		$yearDossier=substr($numDossier,0,2);
		if($yearDossier==date('y'))
		{
				// pas de chg d'année, on prend le der num dossier, oon ajoute 1
			$numDossier=$numDossier +1;
		}
		else
		{
			$numDossier=date('y').'001';

		}
	}


// 2-update dossier_temp avec vrai numDossier
	updateTempDossier($pdoLitige,$numDossier);
// 3-copy dossier-temp en dossier prod
	$idDossier=copyDossier($pdoLitige);
// 4- update details temp avec id dossier et numDossier
	$upDetail=updateTempDetail($pdoLitige,$idDossier,$numDossier);

// 5-copie details_temp vers prod
	$copyDetail=copyDetail($pdoLitige,$idDossier);
// si dial
	$isDial=isDial($pdoLitige);
	if($isDial){
		$upTempDial=updateTempDial($pdoLitige,$idDossier);
		$copyDial=copyDial($pdoLitige, $idDossier);
	}

	$invPalette=isPaletteInv($pdoLitige);
	if($invPalette){
// 6-update table palette_inv_temp avec id dossier
		$upInvPalette=updateTempInvPalette($pdoLitige,$idDossier);
// 7- copie palette_inv_temp
		copyInvPalette($pdoLitige,$idDossier);
		header('Location:declaration-recap.php?id='.$idDossier);

	}
	else{

		header('Location:declaration-recap.php?id='.$idDossier);
	}




}

	// on profite du recap pour calculer la valo totale d'un litige
// 2 cas
// normal : somme,
// inversion de palette = somme palette cammandé - sommme palette reçue
// inversion de palette =7
// 	$sumLitige=getSumLitige($pdoLitige);

// 	if($sumLitige['id_reclamation']==7)
// 	{
// 		$sumRecu=getSumPaletteRecu($pdoLitige);
// 		$sumCde=$sumLitige['sumValo'];
// 		$sumRecu=$sumRecu['sumValo'];
// 		$sumValo=$sumCde -$sumRecu;
// 		$update=updateValoDossier($pdoLitige,$sumValo);

// 	}
// 	else{
// 		$sumValo=$sumLitige['sumValo'];
// 		$update=updateValoDossier($pdoLitige,$sumValo);
// 	}

// }









//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
</div>
<?php
require '../view/_footer-bt.php';
?>






