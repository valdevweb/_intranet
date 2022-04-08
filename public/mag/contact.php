<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//----------------------------------------------------------------
require '../../vendor/autoload.php';

require '../../Class/BtUserManager.php';


require "../../functions/stats.fn.php";




//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

function addMsg($db,$id_service,$inc_file){
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$db->prepare('INSERT INTO msg (objet, msg, id_mag, id_service, date_msg, etat,inc_file,who,email, id_galec,code_bt,centrale)
		VALUE(:objet, :msg, :id_mag, :id_service, :date_msg, :etat, :inc_file, :who, :email, :id_galec, :code_bt, :centrale)');
	$req->execute(array(
		':objet'		=> strip_tags($_POST['objet']),
		':msg'			=> $msg,
		':id_mag'		=> strip_tags($_SESSION['id']),
		':id_service'	=> $id_service,
		':date_msg'		=>date('Y-m-d H:i:s'),
		':etat'			=> "en attente de réponse",
		':inc_file'		=>$inc_file,
		':who'			=>strip_tags($_POST['name']),
		':email'		=>strip_tags($_POST['email']),
		':id_galec'		=>$_SESSION['id_galec'],
		':code_bt'		=>$_SESSION['code_bt'],
		':centrale'		=>$_SESSION['centrale']
	));

	return $db->lastInsertId();
}


if(!isset($_GET['id'])){
	echo "aucun message sélectionné";
	exit();
}

//----------------------------------------------------------------
//			affichage : infos du services
//----------------------------------------------------------------
$userManager=new BtUserManager();
$service=$userManager->getService($pdoUser,$_GET['id']);

$serviceMembers=$userManager->getListUserService($pdoUser,$_GET['id']);



$uploadDir= DIR_UPLOAD. 'mag\\';

$errors=[];
$success=[];
$fileList="";
//soumission du formulaire
if(isset($_POST['post-msg'])){
	if(empty($_POST['objet']) || empty($_POST['msg']) || empty($_POST['name']) || empty($_POST['email'])){
		$errors[]= "merci de remplir tous les champs";
	}

	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors[]='Veuillez indiquez une adresse email valide';
	}
	//formulaire conforme
	if(empty($errors)){
		for($i=0;$i<count($_FILES['files']['name']) ;$i++){
			if($_FILES['files']['name'][$i]!=""){
				$filename=$_FILES['files']['name'][$i];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$filenameNoExt = basename($filename, '.'.$ext);
				$filenameNoExt=str_replace(" ","_",$filenameNoExt);
				$filenameNoExt=str_replace(";","",$filenameNoExt);

				$filenameNew=$filenameNoExt.'-'.date('YmdHis').'.'.$ext;
				if($fileList==""){
					$fileList= $filenameNew;

				}else{
					$fileList= $fileList.'; '.$filenameNew;
				}
				$uploaded=move_uploaded_file($_FILES['files']['tmp_name'][$i],$uploadDir.$filenameNew );
				if($uploaded==false){
					$errors[]="Impossible d'ajouter la pièce jointe";
				}

			}
		}
	}
	if(empty($errors)){
		$lastId=addMsg($pdoBt,$_GET['id'], $fileList);
		if($lastId>0){

				//créa du lien pour le mail  BT
			$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$lastId."'>ici pour consulter le message</a>";
			$linkMag="Cliquez <a href='".SITE_ADDRESS."/index.php?mag/edit-msg.php?msg=".$lastId."'>ici pour revoir votre demande</a>";
			if(VERSION=="_"){
				$dest=[MYMAIL];
			}else{
				$dest=[$service['mailing']];
			}

			$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
			$mailer = new Swift_Mailer($transport);

			$htmlMail = file_get_contents('../mail/new_mag_msg.tpl.html');
			$htmlMail=str_replace('{DEMANDEUR}',$_POST['name'],$htmlMail);
			$htmlMail=str_replace('{MAGASIN}',$_SESSION['nom'],$htmlMail);
			$htmlMail=str_replace('{OBJET}',$_POST['objet'],$htmlMail);
			$htmlMail=str_replace('{MSG}',$_POST['msg'],$htmlMail);
			$htmlMail=str_replace('{LINK}',$link,$htmlMail);
			$subject="PORTAIL BTLec - nouvelle demande : " .$_SESSION['nom'] ." pour le service " . $service['service'];
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')
			->setFrom(EMAIL_NEPASREPONDRE)
			->setTo($dest);

			if (!$mailer->send($message, $failures)){
				$errors[]='impossible d\'envoyer le mail à BTlec';
				echo "erreur";

			}else{
				$success[]="mail envoyé avec succés";
			}

			$htmlMail = file_get_contents('../mail/ar_mag.tpl.html');
			$htmlMail=str_replace('{SERVICE}',$service['service'],$htmlMail);
			$htmlMail=str_replace('{LINK}',$linkMag,$htmlMail);
			$subject="PORTAIL BTLec - demande envoyée";
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')
			->setFrom(EMAIL_NEPASREPONDRE)
			->setTo(array($_POST['email']));

			if (!$mailer->send($message, $failures)){
				$errors[]='impossible d\'envoyer le mail au magasin';
			}else{
				$success[]="mail envoyé avec succés";
			}

			if(empty($errors)){
				unset($_POST);
				header('Location:'. ROOT_PATH. '/public/mag/histo-mag.php');
			}

		}
		else{
			$errors[]="Echec : votre demande n'a pas pu être enregistrée";
		}

	}
}



//header et nav bar
include ('../view/_head-bt.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu

?>

<div id="container" class="container">

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row my-5 pb-5">
		<div class="col-5">
			<div class="card text-white mb-3">
				<div class="card-header bg-primary">
					<div class="row">
						<div class="col-auto">
							<img src="../img/contact/img_avatar100.png" alt="Avatar" class="w3-circle">
						</div>
						<div class="col">
							<h5><?= $service['service'] ?></h5>
						</div>
					</div>
				</div>
				<div class="card-body bg-light text-dark">
					<h5 class="card-title">Description : <?= $service['description'] ?></h5>
					<p class="card-text">
						<strong>Vos interlocteurs :</strong><br>
						<?php
						$count=0;
						foreach ($serviceMembers as $key => $n) {
							$size=count($serviceMembers);
							if($n['resp']){
								echo $n['fullname']. ' <br> ';
							}else{
								if ($key==$size-1) {
									echo $n['fullname'];

								}else{
									echo $n['fullname'].' - ';

								}
							}
						}
						?>
					</p>
				</div>
			</div>
		</div>
		<div class="col p-3 border">
			<h1 class="text-main-blue">Votre demande</h1>
			<form class='down' id="msg-form" action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'] ?>" method="post" enctype="multipart/form-data">

				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="objet">Objet :</label>
							<input type="text" class="form-control" name="objet" id="objet" required value="<?=isset($_POST['objet'])? $_POST['objet']: ""?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="msg">Message :</label>
							<textarea class="form-control" name="msg" id="msg" row="3" style="height: 250px;" required><?=isset($_POST['msg'])? $_POST['msg']: ""?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="name">Votre nom :</label>
							<input type="text" class="form-control" name="name" id="name" title="seules les lettres sont autorisées" type="text" required="require" pattern="[a-zA-Z ]+" value="<?=isset($_POST['name'])? $_POST['name']: ""?>">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="email">Votre email :</label>
							<input type="text" class="form-control" name="email" id="email" value="<?=isset($_POST['email'])? $_POST['email']: ""?>" required>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col mb-3 text-main-blue text-center sub-title font-weight-bold ">
								Fichiers  :
							</div>
						</div>
						<div class="row">
							<div class="col  bg-blue-input rounded pt-2">
								<div class="form-group text-right">
									<label class="btn btn-upload-primary btn-file text-center">
										<input type="file" name="files[]" class='form-control-file' multiple id="files">
										Sélectionner
									</label>
								</div>
								<div class="row mt-3">
									<div class="col" id="form-zone"></div>
								</div>
								<div class="row mt-3">
									<div class="col" id="warning-zone"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-2">
					<div class="col text-right">
						<button class="btn btn-primary" type="submit" name="post-msg" id="post-msg">Envoyer</button>
					</div>
				</div>

			</form>

		</div>
	</div>
	<div class="row pb-5">
		<div class="col">

		</div>
	</div>

	<!-- contenu -->
</div>
<script src="../../public/js/upload-helpers.js"></script>
<script type="text/javascript">
	$(document).ready(function (){


		$('#addmore').click(function(){
			$('#p-add-more').prepend('<p><input type="file" name="file[]"></p>');
			$('input[type="file"]').val();
		});
		$('#files').change(function(){
			noRename('files','warning-zone', 'form-zone')
		});
		$("#msg-form").submit(function(e){
			if($("#email").val()!="" && $("#objet").val()!="" && $("#msg").val()!="" && $("#nom").val()!=""){
				if ($("#email").val()) {

					var email = $("#email").val();
					var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
					if (filter.test(email)) {
						$('input[name="post-msg"]').hide();
						$('#wait').text("Merci de patienter...");
					}
				}
			}
		});
	});
</script>



<?php



// footer avec les scripts et fin de html
include('../view/_footer-bt.php');

