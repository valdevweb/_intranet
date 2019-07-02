<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';
require 'casse-getters.fn.php';

function updatePalette($pdoCasse,$idExp){

	$req=$pdoCasse->prepare("UPDATE palettes SET date_info_mag = NOW() WHERE id_exp= :id_exp");
	$req->execute([
		':id_exp'	=> $idExp
	]);
	return $req->rowCount();

}

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$idExp=$_GET['id'];
$expInfo=getExpAndPalette($pdoCasse,$idExp);
$btlec=$expInfo[0]['btlec'];
$mailPole=false;

if($btlec!='2051' && $btlec!='2054' && $btlec!='2069'){
	echo 'non polee';
}
else{
	$mailPole=true;
}




if($mailPole){
	ob_start();
	include('pdf-certificat-destruction.php');
	$html=ob_get_contents();
	ob_end_clean();
	$filename='certificat de destruction.pdf';
}
else{
	ob_start();
	include('pdf-mag.php');
	$html=ob_get_contents();
	ob_end_clean();
	$filename='detail livraison palettes de casse.pdf';
}
$mpdf = new \Mpdf\Mpdf([
    'pagenumPrefix' => 'page ',
    'pagenumSuffix' => ' / ',
    'nbpgPrefix' => '',
    'nbpgSuffix' => ''
]);
$mpdf->setFooter('{PAGENO}{nbpg}');
$mpdf->WriteHTML($html);
$pdfContent = $mpdf->Output('', 'S');

		// --------------------------------------
		// destinataires


if(VERSION=='_'){
	$dest='valerie.montusclat@btlec.fr';
	$cc=['valerie.montusclat@btlec.fr'];
}
else{
	if($mailPole){
		$dest=$btlec.'-rbt@btlec.fr';
	}
	else{
		$dest=$btlec.'-rbt@btlec.fr';

	}
	$cc=['valerie.montusclat@btlec.fr', 'christelle.trousset@btlec.fr', 'nathalie.pazik@btlec.fr'];

}
if($mailPole){
	$link='<a href="'.SITE_ADDRESS.'/index.php?casse/certif-upload.php?id='.$_GET['id'].'"> en cliquant ici</a>';
	$htmlMail = file_get_contents('mail-pole-exp.php');
	$htmlMail=str_replace('{NB}',count($expInfo),$htmlMail);
	$htmlMail=str_replace('{LINK}',$link,$htmlMail);
}
else{
	$htmlMail = file_get_contents('mail-mag-exp.php');
	$htmlMail=str_replace('{NB}',count($expInfo),$htmlMail);
}
$subject='Portail BTLec Est - livraison palettes de casse';

// 		// ---------------------------------------
// 		// initialisation de swift
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->attach($attachmentPdf)
->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec EST'))
->setTo($dest)

->setCc($cc);


// echec => renvoie 0
$delivered=$mailer->send($message);
if($delivered !=0)
{
	$do=updatePalette($pdoCasse,$idExp);
	if($do>1){
		$loc='Location:bt-casse-dashboard.php?mailMag';
		header($loc);
	}
	else{
		$errors[]="impossible mettre à jour la base de donnée";
	}
}
else
{
	$errors[]="impossible d'envoyer le mail au magasin";
}







//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>



	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>