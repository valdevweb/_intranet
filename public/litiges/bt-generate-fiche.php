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
require_once '../../Class/LitigeDao.php';


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------



function getFinance($pdoQlik, $btlec, $year){
	$req=$pdoQlik->prepare("SELECT CA_Annuel FROM statsventesadh WHERE CodeBtlec= :btlec AND AnneeCA= :year");
	$req->execute(array(
		':btlec' =>$btlec,
		':year'	=>$year
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


$yearN=date('Y');
$yearNUn= date("Y",strtotime("-1 year"));
$yearNDeux= date("Y",strtotime("-2 year"));






$litigeDao=new LitigeDao($pdoLitige);
$fLitige=$litigeDao->getLitigeDossierDetailReclamMagEtatById($_GET['id']);
// $firstDial=getFirstDial($pdoLitige);
$firstDial=$litigeDao->getFirstDial($_GET['id']);
$infos=$litigeDao->getInfos($_GET['id']);
$analyse=$litigeDao->getAnalyse($_GET['id']);
$actionList=$litigeDao->getAction($_GET['id']);



$coutTotal=$infos['mt_transp']+$infos['mt_assur']+$infos['mt_fourn']+$infos['mt_mag'];
if($infos['ctrl_ok']==0){
	$ctrl="non contrôlé";
}else{
	$ctrl="fait";
}

if($coutTotal!=0){
	$coutTotal=number_format((float)$coutTotal,2,'.','');
}


if($fLitige[0]['vingtquatre']==1){
	$vingtquatre='<img src="../img/litiges/2448_40.png">';

}
else{
	$vingtquatre="";
}

if($fLitige[0]['flag_valo']==1){
	$valoMag=$fLitige[0]['valo'] . '&euro;';
}
elseif($fLitige[0]['flag_valo']==2){
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
$footer='<table class="padding-table">';
$footer.='<tr><td>TRAITEMENT</td></tr>';
$footer.='<tr><td class="spacing-l"></td></tr>';
$footer.='<tr><td>Date et validation</td></tr>';
$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
$path='http://172.30.92.53/'.VERSION.'upload/litiges/'.$html;

$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLFooter($footer);
$mpdf->AddPage('', // L - landscape, P - portrait
        '', '', '', '',
       '', // margin_left
        '', // margin right
       '', // margin top
       40, // margin bottom
        0, // margin header
        10); // margin footer
$mpdf->WriteHTML($path);
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