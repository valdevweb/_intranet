<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../Class/Db.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';

require "../../Class/evo/EvoDao.php";
require "../../Class/evo/PlateformeDao.php";
require "../../Class/evo/EvoDocDao.php";
require "../../Class/evo/AffectationDao.php";
require "../../Class/evo/EvoHelpers.php";
require "../../Class/UserHelpers.php";
require "../../Class/Helpers.php";
require "../../Class/UserDao.php";
require "../../functions/form.fn.php";
//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

define("UPLOAD_DIR_EVO",DIR_UPLOAD.'evo-doc/' );
define("UPLOAD_URL_EVO",URL_UPLOAD.'evo-doc\\' );

$db= new Db();
$pdoEvo= $db->getPdo('evo');
$pdoUser= $db->getPdo('web_users');


$evoDao=new EvoDao($pdoEvo);
$plateformeDao=new PlateformeDao($pdoEvo);
$userDao=new UserDao($pdoUser);
$affectationDao=new AffectationDao($pdoEvo);
$docDao= new EvoDocDao($pdoEvo);



$listPF=$plateformeDao->getListPlateforme();
$arrPf=EvoHelpers::arrayPlateformeName($pdoEvo);
$arrAppli=EvoHelpers::arrayAppliName($pdoEvo);
$arrModule=EvoHelpers::arrayModuleName($pdoEvo);
$arrDevMail=EvoHelpers::arrayAppliRespEmail($pdoEvo);
$listLevel=EvoHelpers::arrayLevels($pdoEvo);


$listUsers=$userDao->getBtlecUserEvo();
$listServices=$userDao->getServicesMailing();
$affectionEmail=[];


if(isset($_POST['submit'])){

	$arrAppliRespId=EvoHelpers::arrayAppliRespId($pdoEvo);

	$idResp=$arrAppliRespId[$_POST['appli']];
	$idEvo=$evoDao->insertEvo($idResp);
	if(!empty($_POST['users'])){
		for ($i=0; $i < count($_POST['users']); $i++) {
			$user=$userDao->getUserById($_POST['users'][$i]);
			$affectionEmail[$i]['email']=$user['email'];
			$affectionEmail[$i]['id_web_user']=$user['id_web_user'];
			$affectionEmail[$i]['id_service']=$user['id_service'];
		}
	}


	if(!empty($_POST['services'])){
		$affectationSize=count($affectionEmail);
		for ($i=0; $i < count($_POST['services']); $i++) {
			$users=$userDao->getUsersByServiceById($_POST['services'][$i], true);
			foreach ($users as $key => $user) {
				$affectionEmail[$affectationSize]['email']=$user['email'];
				$affectionEmail[$affectationSize]['id_service']=$user['id_service'];
				$affectionEmail[$affectationSize]['id_web_user']=$user['id_web_user'];
				$affectationSize++;
			}

		}
	}

	if(!empty($affectionEmail)){
		$affectionEmail=Helpers::arrayUniqueMultiCol($affectionEmail,'email');
		foreach ($affectionEmail as $key => $email) {
			$affectationDao->insertAffectation($idEvo, $email['id_web_user'], $email['id_service'], $email['email']);
			$cc[]=$email['email'];
		}
	}


	if(isset($_FILES['files_doc']['tmp_name'][0]) &&  !empty($_FILES['files_doc']['tmp_name'][0])){
		for ($i=0; $i <count($_FILES['files_doc']['tmp_name']) ; $i++) {
			$orginalFilename=$_FILES['files_doc']['name'][$i];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);
			$filenameNoExt = basename($orginalFilename, '.'.$ext);
			$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;
			$uploaded=move_uploaded_file($_FILES['files_doc']['tmp_name'][$i],UPLOAD_DIR_EVO.$filename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée 2";
			}else{
				$listFilename[]=$filename;
			}
		}
	}


	if(!empty($listFilename)){
		for ($i=0; $i < count($listFilename); $i++) {
			$docDao->insertDoc($idEvo, $listFilename[$i], $_POST['filename'][$i], "");
		}
	}

	$cc=[];


	if(VERSION=="_"){
		$dest=[MYMAIL];
		$cc=[];

	}else{

		$devMail=$arrDevMail[$idResp];
		$dest=[$devMail, 'luc.muller@btlecest.leclerc', 'david.syllebranque@btlecest.leclerc'];
		$dest=array_unique($dest);

	}

	$htmlMail = file_get_contents('mail/mail-new-dd.html');
	$htmlMail=str_replace('{OBJET}',$_POST['objet'],$htmlMail);
	if(isset($_POST['module']) && !empty($_POST['module'])){
		$module=' - '.$arrModule[$_POST['module']];
	}else{
		$module="";
	}


	$demandeur=UserHelpers::getFullname($pdoUser, $_SESSION['id_web_user']);
	$htmlMail=str_replace('{WHAT}',$arrPf[$_POST['pf']]. ' - ' .$arrAppli[$_POST['appli']].$module,$htmlMail);
	$htmlMail=str_replace('{EVO}',$_POST['evo'],$htmlMail);
	$htmlMail=str_replace('{OBJET}',$_POST['objet'],$htmlMail);
	$htmlMail=str_replace('{DDEUR}',$demandeur,$htmlMail);
	$subject="Portail BTLec Est - Demandes d'évo - nouvelle demande" ;

	$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->setFrom(EMAIL_NEPASREPONDRE)
	->setTo($dest)
	->setCc($cc);

	if (!$mailer->send($message, $failures)){
		print_r($failures);
		$errors[]="erreur envoi mail";
	}else{
		$successQ='?success=cree&id='.$idEvo;
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}

if(isset($_GET['success'])){
	$arrSuccess=[
		'cree'=>'Votre demande d\'évo a bien été envoyée. Vous pouvez la <a href="evo-detail.php?id='.$_GET['id'].'">consulter ici</a>'
	];
	$success[]=$arrSuccess[$_GET['success']];
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
		<h1 class="text-main-blue py-5 ">Demande d'évo</h1>

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
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post"  enctype="multipart/form-data">

					<div class="row">
						<div class="col-auto">
							<img src="../img/evo/code-ico.jpg" alt="code" class="polaroid">
						</div>
						<div class="col">
							<div class="row">
								<div class="col-4 text-main-blue">
									Sélectionnez une plateforme :
								</div>
								<div class="col">
									<?php foreach ($listPF as $key => $pf): ?>

										<div class="form-check form-check-inline">
											<input class="form-check-input" required type="radio" value="<?=$pf['id']?>" <?=checkChecked($pf['id'],'pf')?> id="pf" name="pf">
											<label class="form-check-label pr-5" for="pf"><?=$pf['plateforme']?></label>
										</div>

									<?php endforeach ?>
								</div>
							</div>
							<div class="row ">
								<div class="col-md-4 mt-3 pt-2 text-main-blue">
									Sélectionnez une application :
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="appli"></label>
										<select class="form-control" name="appli" id="appli" required>
											<option value="">Sélectionner</option>
											<option value="">commencez par choisir une plateforme</option>
										</select>
									</div>

								</div>
							</div>
							<div class="row">
								<div class="col-md-4 mt-3 pt-2 text-main-blue">
									Sélectionnez un module :
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="module"></label>
										<select class="form-control" name="module" id="module">
											<option value="">Sélectionner</option>
										</select>
									</div>

								</div>
							</div>

							<div class="row mb-3">
								<div class="col-4 text-main-blue">
									Chronophagie :
								</div>
								<div class="col">
									<?php foreach ($listLevel as $keyLevel => $value): ?>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" value="<?=$keyLevel?>"  <?= ($keyLevel==2)?"checked" :""?> name="chrono" required id="<?=$listLevel[$keyLevel]['class']?>">
											<label class="form-check-label pr-5 text-<?=$listLevel[$keyLevel]['class']?>" ><b><?=$listLevel[$keyLevel]['chrono']?></b></label>
										</div>
									<?php endforeach ?>



								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							Affecter la demande à des utilisateurs et/ou à des services
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="users">A des personnes :</label>
								<select class="form-control" name="users[]" id="users" multiple>
									<option value="">Sélectionner</option>
									<?php foreach ($listUsers as $key => $user): ?>
										<option value="<?=$user['id']?>"><?=$user['fullname']?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="services">A des services : </label>
								<select class="form-control" name="services[]" id="services" multiple>
									<option value="">Sélectionner</option>
									<?php foreach ($listServices as $key => $service): ?>
										<option value="<?=$service['id']?>"><?=$service['service']?></option>
									<?php endforeach ?>
								</select>
							</div>

						</div>
					</div>

					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="objet" class="text-main-blue">Objet de votre demande</label>
								<input type="text" class="form-control" name="objet" id="objet" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="evo" class="text-main-blue">Votre demande :</label>
								<textarea name="evo" id="" cols="30" rows="5" class="form-control" required></textarea>
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
											<input type="file" name="files_doc[]" class='form-control-file' multiple id="files-doc">
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
					<div class="row py-5">
						<div class="col text-right">
							<button class="btn btn-black" name="submit">Valider</button>
						</div>
					</div>
				</form>
			</div>
		</div>


		<!-- ./container -->
	</div>
	<script src="../../public/js/upload-helpers.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("input:radio[name='pf']").click(function () {
				var plateforme=$('input[name="pf"]:checked').val();
				$.ajax({
					type:'POST',
					url:'dde-evo/ajax-get-appli.php',
					data:{id_plateforme:plateforme},
					success: function(html){
						$("#appli").html(html)
					}
				});
			});
			$('#appli').on("change",function(){
				var appli=$('#appli').val();
				console.log("appli" + appli);
				$.ajax({
					type:'POST',
					url:'dde-evo/ajax-get-appli.php',
					data:{id_appli:appli},
					success: function(html){
						$("#module").html(html)
					}
				});
			});

			$('#files-doc').change(function(){
				multipleWithName('files-doc','warning-zone', 'form-zone')
			});
		});


	</script>
	<?php
	require '../view/_footer-bt.php';
?>