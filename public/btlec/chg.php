<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

include '../../config/db-connect.php';
require_once  '../../vendor/autoload.php';



//affichage de l'historique des réponses
require "../../functions/stats.fn.php";
require('../../Class/BtUserManager.php');
require('../../Class/MsgManager.php');
require('../../Class/UserHelpers.php');


//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";


//-----------------------------------------------
//				affichage
//-----------------------------------------------
//liste des services qui s'affichent dans les checkbox
$userManager=new BtUserManager();
$msgManager=new MsgManager();
$listServicesContact=$userManager->listServicesContactStrict($pdoUser);


$idMsg=$_GET['msg'];
$oneMsg=$msgManager->getDemande($pdoBt,$_GET['msg']);



if ($_SESSION['id_service']!= 5 && $_SESSION['id_service']!= 16 && $_SESSION['id_service'] != 6 &&  $oneMsg['id_service'] != $_SESSION['id_service'] ){
	if($_SESSION['id_service']==4 && $oneMsg['id_service']==14 ){
	}else{
		echo "vous ne pouvez pas réaffecter cette demande, elle n'est plus destinée à votre service";
		exit();
	}
}


//compte des ligne pour afficher les checkbox en colonnes
$nbServices=count($listServicesContact);
$nbServicesLine=round($nbServices /4);
$lig=0;


// info msg






$success=[];
$errors=[];

if(isset($_POST['affect']))
{
	if(!isset($_POST['service'])){
		$errors[]="veuillez sélectionner un service";

	}else{

		$newServiceInfo=$userManager-> getServiceById($pdoUser,$_POST['service'][0]);
		$msgManager->affectation($pdoBt,$idMsg,$_POST['service'][0]);
		$username=UserHelpers::getFullname($pdoUser,$_SESSION['id_web_user']);



		if (VERSION =="_"){
			$dest[]=MYMAIL;
		}else{
			$dest[]=$newServiceInfo['mailing'];
		}


		$link="Cliquez <a href='".SITE_ADDRESS."/index.php?".$idMsg."'>ici pour consulter la demande magasin</a>";



		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);

		$htmlMail = file_get_contents("../mail/reaffectation.html");
		$htmlMail=str_replace('{LINK}',$link,$htmlMail);
		$htmlMail=str_replace('{CONTENT1}',$username,$htmlMail);
		$htmlMail=str_replace('{CONTENT2}',$oneMsg['deno'],$htmlMail);
		$subject="PORTAIL BTLec - demande magasin réaffectée au service " . $newServiceInfo['service'];
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest);
		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$success[] ="demande réaffectée au service " . $newServiceInfo['mailing'];

		}
	}
}


include '../view/_head-bt.php';
include '../view/_navbar.php';

?>
<div class="container">
	<div class="row shadow-sm bg-white rounded border p-2">
		<div class="col">

			<h1>Réaffectation d'une demande</h1>


			<h5><i class="far fa-hand-point-right pr-3"></i>Demande n °<?= $oneMsg['idMsg'] .' : '.$oneMsg['objet']?></h5>
			<hr>
			<br><br>
			<form action="chg.php?msg=<?=$idMsg ?>" method="post" id="chg">
				<div class="row">
					<div class="col-3">
						<?php foreach ($listServicesContact as $key => $service): ?>
							<?php if ($lig < $nbServicesLine): ?>

								<div class="form-group form-check">
									<input type='checkbox' class='form-check-input' id='<?=$service['id']?>' name='service[]' value= '<?=$service['id']?>' />
									<label class='form-check-label' for='<?=$service['id']?>'><?=$service['service']?></label>
								</div>
							<?php else: ?>
								<?php $lig=0;?>
							</div><div class="col-3">
								<div class="form-group form-check">
									<input type='checkbox' class='form-check-input' id='<?=$service['id']?>' name='service[]' value= '<?=$service['id']?>' />
									<label class='form-check-label' for='<?=$service['id']?>'><?=$service['service']?></label>
								</div>
							<?php endif ?>
							<?php $lig++; ?>
						<?php endforeach ?>


					</div>
				</div>
				<div class="row">
					<div class="col">
						<p class="text-right">
							<button class="btn btn-primary" type="submit" name="affect">Affecter</button>
						</p>
					</div>
				</div>

			</form>
			<?php include('../view/_errors.php') ?>


			<p class="back"><a href="dashboard.php"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>

		</div>
	</div>
</div>

</div>  <!--container


<?php



include('../view/_footer-bt.php');
 ?>








