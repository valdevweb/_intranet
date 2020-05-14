<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}


if($_SESSION['type']=='btlec'){
	unset($_SESSION['id_galec']);
}
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
if(isset($_SESSION['dd_ouv'])){
	$idOuv=$_SESSION['dd_ouv'];
	unset($_SESSION['dd_ouv']);

}
else{
	$idOuv=false;
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

function updateTempDetail($pdoLitige,$numDossier){

	$req=$pdoLitige->prepare("UPDATE details_temp SET dossier =:dossier WHERE id_dossier= :id");
	$req->execute([
		':id'		=>$_GET['id'],
		':dossier'	=>$numDossier,
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

function deleteTempInvPalette($pdoLitige,$idDossier){
	$req=$pdoLitige->prepare("DELETE FROM palette_inv_temp  WHERE id_dossier= :id_dossier");
	$req->execute([
		':idDossier'	=>$idDossier,

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

function deleteTempDial($pdoLitige,$idDossier){
	$req=$pdoLitige->prepare("DELETE FROM dial_temp  WHERE id_dossier= :id_dossier");
	$req->execute([
		':id_dossier'	=>$idDossier,
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

function copyDetail($pdoLitige,$numDossier){
	$req=$pdoLitige->prepare("INSERT INTO details( id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, puv, pul, fournisseur, cnuf, qte_litige, box_tete, box_art, id_reclamation, inv_palette, inversion, inv_article, inv_qte, inv_descr, inv_tarif, valo_line, inv_fournisseur, etat_detail, pj, ctrl_ko, ecart, mvt, date_ctrl) SELECT  id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, puv, pul, fournisseur, cnuf, qte_litige, box_tete, box_art, id_reclamation, inv_palette, inversion, inv_article, inv_qte, inv_descr, inv_tarif, valo_line, inv_fournisseur, etat_detail, pj, ctrl_ko, ecart, mvt,date_ctrl FROM details_temp WHERE details_temp.dossier= :dossier");
	$req->execute([
		':dossier'	=>$numDossier
	]);
	return $req->rowCount();
	// return $req->errorInfo();

}

function updateDetail($pdoLitige,$numDossier,$idDossier){

	$req=$pdoLitige->prepare("UPDATE details SET id_dossier =:id_dossier WHERE dossier= :dossier");
	$req->execute([
		':id_dossier'		=>$idDossier,
		':dossier'	=>$numDossier,
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


function updateOuv($pdoLitige,$lastInsertId,$numdossier, $idOuv)
{

	$req=$pdoLitige->prepare("UPDATE ouv SET id_litige= :id_litige, dossier= :dossier WHERE id= :id");
	$req->execute(array(
		':id_litige'		=>$lastInsertId,
		':dossier'		=>$numdossier,
		'id'		=>$idOuv
	));
	return $req->rowCount();

}
function getOuvRep($pdoLitige,$idOuv){
	$req=$pdoLitige->prepare("SELECT * FROM ouv_rep WHERE id_ouv= :id_ouv");
	$req->execute([
		':id_ouv'	=>$idOuv
	]);
	$result=$req->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($result)){
		return $result;
	}
	else{
		return false;
	}
}


function insertToDial($pdoLitige,$idDossier,$numDossier, $msg, $idwebuser, $date, $pj, $mag){
	$req=$pdoLitige->prepare("INSERT INTO dial ( id_dossier, date_saisie, msg, id_web_user, filename, mag) VALUES ( :id_dossier, :date_saisie, :msg, :id_web_user, :filename, :mag) ");
	$req->execute([
		':id_dossier'		=>$idDossier,
		 ':date_saisie'		=>$date,
		 ':msg'				=>$msg,
		 ':id_web_user'		=>$idwebuser,
		 ':filename'		=>$pj,
		 ':mag'				=>$mag
	]);
	return $req->rowCount();
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
// // si on a imposé un numéro de dossier sur la page déclaration basic, on l'a récupéré dans la var de session et affecté à numDossier au moment de lunset des var de session en haut de la page
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
// on récupère le vrai id de notre dossier
	$idDossier=copyDossier($pdoLitige);


// 4- update details temp avec id dossier et numDossier
	$upDetail=updateTempDetail($pdoLitige,$numDossier);

// 5-copie details_temp vers prod
	$copyDetail=copyDetail($pdoLitige,$numDossier);
	// maj prod detail avec bon id dossier
	updateDetail($pdoLitige,$numDossier,$idDossier);

// 		echo "<pre>";
// 		print_r($copyDetail);
// 		echo '</pre>';

// exit();

// si dial cad un commentaire saisi sur la page declaration-detail
	$isDial=isDial($pdoLitige);
	if($idOuv){
	// on ajoute le numéro de dossier et son id pour pouvoir l'afficher dans le tableau des demandes d'ouvertures
	// mais on ne se sert pas de cette table pour afficher le 1er commentaire magasin
	// on le recupère tj de la table dial (pour rappel, sur la page delcaration-detail, on affiche dans le textarea
	// le message d'origine du mag de  cette table ouv pour pouvoir le récupérer dans dial_temp)
		updateOuv($pdoLitige,$idDossier,$numDossier, $idOuv);
		// on copie les autres echanges (table ouv_rep dans la table dial non temporaire)
		// donc recup dans ouv_rep, les id_ouv
		// le pousse dans dial avec nouveau num dossier
		$echangeOuv=getOuvRep($pdoLitige,$idOuv);

		if($echangeOuv){
			foreach ($echangeOuv as $key => $value) {
				insertToDial($pdoLitige,$idDossier,$numDossier, $value['msg'], $value['id_web_user'], $value['date_saisie'], $value['pj'], $value['mag']);
			}
		}

	}
	if($isDial){

		$upTempDial=updateTempDial($pdoLitige,$idDossier);
		$copyDial=copyDial($pdoLitige, $idDossier);
		// delte temp dial
		deleteTempDial($pdoLitige,$idDossier);
	}


	$invPalette=isPaletteInv($pdoLitige);
	if($invPalette){
// 6-update table palette_inv_temp avec id dossier
		$upInvPalette=updateTempInvPalette($pdoLitige,$idDossier);
// 7- copie palette_inv_temp
		copyInvPalette($pdoLitige,$idDossier);
		// delete palette_inv_temp
		deleteTempInvPalette($pdoLitige, $idDossier);
		header('Location:declaration-recap.php?id='.$idDossier);

	}
	else{

		header('Location:declaration-recap.php?id='.$idDossier);
	}




}

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






