<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
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
require '../../config/db-connect.php';

require 'casse-getters.fn.php';
require '../../Class/Uploader.php';
unset($_SESSION['goto']);

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$idExp=$_GET['id'];

$contremarque=getContremarqueList($pdoCasse, $idExp);
$contremarque=implode(', ',$contremarque);
$expInfo=getExpAndPalette($pdoCasse,$idExp);
$btlec=$expInfo[0]['btlec'];


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function closeCasse($pdoCasse){
	$req=$pdoCasse->prepare("UPDATE casses LEFT JOIN palettes ON casses.id_palette=palettes.id LEFT JOIN exps ON palettes.id_exp= exps.id SET etat=1, casses.date_clos= :date_clos WHERE exps.id= :id");
	$req->execute([
		':id'	=>$_GET['id'],
		':date_clos'=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();
	// return $req->errorInfo();
}



function closePalette($pdoCasse, $filename){
	$req=$pdoCasse->prepare("UPDATE palettes SET date_clos= :date_clos, certificat=:certificat, statut=2 WHERE id_exp= :id");
	$req->execute([
		':id'	=>$_GET['id'],
		':date_clos'=>date('Y-m-d H:i:s'),
		':certificat'	=>$filename
	]);
	return $req->rowCount();
}

function getLdSav($pdoSav, $sav, $module){
	$req=$pdoSav->prepare("SELECT email FROM mail_sav LEFT JOIN sav_users ON mail_sav.id_user_sav=sav_users.id WHERE mail_sav.sav= :sav AND module= :module");
	$req->execute([
		':sav'			=>$sav,
		':module'		=>$module
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}








if(isset($_POST['submit'])){

	$dirUpload=DIR_UPLOAD.'casse\\';

	$uploader=new Uploader();
	$uploader->setDir($dirUpload);
	$uploader->allowAllFormats();
	$uploader->setMaxSize(0.5);                          //set max file size to be allowed in MB//

	if($uploader->uploadFile('incfile')){   //txtFile is the filebrowse element name //
		$filename=$uploader->getUploadName(); //get uploaded file name, renames on upload//
		$maj=closePalette($pdoCasse, $filename);
		$clos=closeCasse($pdoCasse);
		if($maj>=1 && $clos>=1){
			if(VERSION=='_'){
				$dest='valerie.montusclat@btlec.fr';
				$cc=['valerie.montusclat@btlec.fr'];
			}
			else{
				$sav='S'.substr($btlec,2,2);
				$mailSav=getLdSav($pdoSav, $sav, 'litige');
				foreach ($mailSav as $ld) {
					$cc[]=$ld['email'];
				}
				array_push($cc,'valerie.montusclat@btlec.fr');
				$dest='btlecest.portailweb.logistique@btlec.fr';
			}
			$htmlMail = file_get_contents('mail-certif-upload.php');
			$htmlMail=str_replace('{LIV}',$idExp,$htmlMail);
			$htmlMail=str_replace('{PALETTE}',$contremarque,$htmlMail);
			$subject='Portail SAV Leclerc - casse - certificat de destruction';

			$attachment = Swift_Attachment::fromPath($dirUpload.$filename);
			// ---------------------------------------
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')

			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail SAV Leclerc'))
			->setTo($dest)
			->setCc($cc)
			->attach($attachment);
			$delivered=$mailer->send($message);
			if($delivered !=0)
			{
				$success[]  = "le fichier "  .$filename ." a bien été uploadé et un mail envoyé au service logistique" ;
			}else{
				$errors[]="impossible de traiter la demande";
			}


		}
		else{
			$errors[]="la base de donnée n'a pas pu être mise à jour";

		}

	}
	else{//upload failed
    $errors[]=$uploader->getMessage(); //get upload error message
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
	<h1 class="text-main-blue py-5 ">Certificat de destruction</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col ">
					<p>Cette interface vous permet de faire parvenir le certificat de destruction à BTLec et de clôturer les dossiers casse qui lui sont joints.<br>Votre document sera sauvegardé dans nos bases et envoyé par mail au service logistique pour les informer de votre dépôt. Vous serez en copie de ce mail.</p>
				</div>
			</div>
			<div class="row ">
				<div class="col-md-1"></div>
				<div class="col bg-yellow text-yellow-darker bg-alert">
					<i class="fas fa-bomb pr-3"></i>Merci de vous assurer que le certificat de destruction est rempli et qu'il correspond bien au(x) numéro(s) de palette suivant(s) :<br>
					<div class="text-center"><?=$contremarque?></div>
				</div>
				<div class="col-md-1"></div>

			</div>
			<!-- form -->

			<div class="row my-5 pb-5">
				<div class="col-md-2"></div>
				<div class="col">
					<form method="post" enctype="multipart/form-data" class="border p-5">

						<div class="row ">
							<div class="col-auto  pt-2">Sélectionner le certificat : </div>
							<div class="col-auto">
								<div class="input-file-container">
									<input type='file' class='input-file' id='incfile' name='incfile' >
									<label class="input-file-trigger" for='incfile'><i class="fas fa-cloud-upload-alt pr-3"></i>Parcourir</label>
								</div>
							</div>
							<div class="col-auto">
								<button class="btn btn-black" name="submit"><i class="fas fa-paper-plane pr-3"></i>Envoyer</button>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<p class="file-return"></p>

							</div>
						</div>

					</form>
				</div>
				<div class="col-md-2"></div>

			</div>

		</div>
	</div>

	<!-- ./container -->
</div>
<script type="text/javascript">

// ajout de la classe JS à HTML
// document.querySelector("html").classList.add('js');

// initialisation des variables
var fileInput  = document.querySelector( ".input-file" ),
button     = document.querySelector( ".input-file-trigger" ),
the_return = document.querySelector(".file-return");

// action lorsque la "barre d'espace" ou "Entrée" est pressée
button.addEventListener( "keydown", function( event ) {
	if ( event.keyCode == 13 || event.keyCode == 32 ) {
		fileInput.focus();
	}
});

// action lorsque le label est cliqué
button.addEventListener( "click", function( event ) {
	fileInput.focus();
	return false;
});

// affiche un retour visuel dès que input:file change
fileInput.addEventListener( "change", function( event ) {
	the_return.innerHTML=event.target.files[0].name;

});

</script>

<?php
require '../view/_footer-bt.php';
?>