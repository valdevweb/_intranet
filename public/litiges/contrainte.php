<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require_once '../../vendor/autoload.php';



//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



$errors=[];
$success=[];
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dossiers WHERE id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

$fLitige=getLitige($pdoLitige);

function updateCtrl($pdoLitige)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET ctrl_ok=:ctrl_ok WHERE id=:id");
	$req->execute(array(
		':ctrl_ok'	=>1,
		':id'		=>$_GET['id']
	));
	return $req->rowCount();
}


//------------------------------------------------------
//			CONTRAINTE ACTUELLES
//------------------------------------------------------
/*

1 = envoi mail de demande de contrôle de stock
3= mettre le contrôle de stock à  oui
 */
if($_GET['contrainte']==2)
{
	// 1 récup info litige pour envoyer demande de contrôle aux pilotes
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
// content
	$htmlMail = file_get_contents('mail-dde-ctrl-stock.php');
	// $htmlMail=str_replace('{PROD}',$prod,$htmlMail);
// sujet
	$subject='Portail BTLec - Litiges - Contrôle de stock - ';

	$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
		->setTo(['valerie.montusclat@btlec.fr'])
		->addBcc('valerie.montusclat@btlec.fr');
$delivered = $mailer->send($message);
if($delivered !=0)
{
		header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
}
else
{
		$errors[]="impossible d'envoyer le mail";

}


}
elseif($_GET['contrainte']==1)
{
	$row=updateCtrl($pdoLitige);
	if($row==1)
	{
		header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
	}
	else
	{
		$errors[]="impossible de mettre à jour la base de donnée";

	}
}




echo $_GET['contrainte'];
echo '<br>';
echo $_GET['id'];

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
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>

		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

</div>







<?php

require '../view/_footer-bt.php';

?>