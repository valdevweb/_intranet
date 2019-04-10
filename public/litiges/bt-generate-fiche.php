<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require_once '../../vendor/autoload.php';


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

// info produit
function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT
		dossiers.id as id_main,	dossiers.dossier,dossiers.date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,dossiers.user_crea,dossiers.galec,dossiers.etat_dossier,vingtquatre, inversion,inv_article,inv_fournisseur,inv_tarif,inv_descr,nom, inv_qte, valo,flag_valo,
		details.id as id_detail,details.ean,details.id_dossier,	details.palette,details.article,details.tarif,details.qte_cde, details.qte_litige,details.dossier_gessica,details.descr,details.fournisseur,details.pj,
		reclamation.reclamation,
		btlec.sca3.mag, btlec.sca3.centrale, btlec.sca3.btlec,
		etat.etat
		FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN etat ON etat_dossier=etat.id
		WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}



function getFirstDial($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM `dial` WHERE id_dossier=:id ORDER BY id ASC LIMIT 1");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'" class="link-main-blue"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
	// return $filelist;
}

function getInfos($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT transporteur.transporteur, affrete.affrete, transit.transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, DATE_FORMAT(date_prepa,'%d-%m-%Y') as dateprepa, ctrl_ok FROM dossiers
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		WHERE  dossiers.id= :id ");

	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$infos=getInfos($pdoLitige);


function getAnalyse($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT gt, imputation, typo, etat, analyse, conclusion FROM dossiers
		LEFT JOIN gt ON id_gt=gt.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}
$analyse=getAnalyse($pdoLitige);

function getAction($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT libelle, action.id_web_user, DATE_FORMAT(date_action, '%d-%m-%Y')as dateFr, concat(prenom, ' ', nom) as name FROM action LEFT JOIN btlec.btlec ON action.id_web_user=btlec.btlec.id_webuser WHERE action.id_dossier= :id ORDER BY date_action");
	$req->execute(array(
		':id'		=>$_GET['id']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$actionList=getAction($pdoLitige);
function getDialog($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id_dossier,DATE_FORMAT(date_saisie, '%d-%m-%Y') as dateFr,msg,id_web_user,filename,mag FROM dial WHERE id_dossier= :id ORDER BY date_saisie");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
$dials=getDialog($pdoLitige);
function getBtName($pdoBt, $idwebuser)
{
	$req=$pdoBt->prepare("SELECT CONCAT (prenom, ' ', nom) as name FROM btlec WHERE id_webuser= :id_web_user");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getMagName($pdoUser, $idwebuser)
{
	$req=$pdoUser->prepare("SELECT btlec.sca3.mag FROM users LEFT JOIN btlec.sca3 ON users.galec=btlec.sca3.galec WHERE users.id= :id_web_user ");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}


$coutTotal=$infos['mt_transp']+$infos['mt_assur']+$infos['mt_fourn']+$infos['mt_mag'];
if($infos['ctrl_ok']==0)
{
	$ctrl="non contrôlé";
}
else{
	$ctrl="fait";
}

if($coutTotal!=0){
	$coutTotal=number_format((float)$coutTotal,2,'.','');
}


$fLitige=getLitige($pdoLitige);


$firstDial=getFirstDial($pdoLitige);

if($fLitige[0]['flag_valo']==1)
{
	$valoMag=$fLitige[0]['valo'] . '&euro;';
}
elseif($fLitige[0]['flag_valo']==2)
{
	$valoMag='impossible de calculer la valorisation sans le PU de la référence reçue';
}
else{
	$valoMag=0;
}


//----------------------------------------------
		//  		PDF
		//----------------------------------------------

		// récupération du contenu html du pdf
		ob_start();
		include('pdf-fiche-suivi.php');
		$html=ob_get_contents();
		ob_end_clean();
		$path='http://172.30.92.53/'.VERSION.'upload/litiges/'.$html;

		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML($path);
		// $pdfContent = $mpdf->Output('', 'S');
		$pdfContent = $mpdf->Output();

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->


<?php

require '../view/_footer-bt.php';

?>