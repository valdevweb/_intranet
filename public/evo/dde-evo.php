<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

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

require "../../Class/EvoManager.php";
require "../../Class/EvoHelpers.php";
require "../../Class/UserHelpers.php";
require "../../functions/form.fn.php";
//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function insertEvo($pdoEvo, $resp){

	$req=$pdoEvo->prepare("INSERT INTO evos (id_from, id_resp, objet, evo, id_etat, date_dde, id_prio, id_plateforme, id_appli, id_module)
		VALUES (:id_from, :id_resp, :objet, :evo, :id_etat, :date_dde, :id_prio, :id_plateforme, :id_appli, :id_module)");
	$req->execute([
		':id_from'		=>$_SESSION['id_web_user'],
		':id_resp'		=>$resp,
		':objet'		=>$_POST['objet'],
		':evo'		=>$_POST['evo'],
		':id_etat'		=>1,
		':date_dde'		=>date('Y-m-d H:i:s'),
		':id_prio'		=>$_POST['prio'],
		':id_plateforme'		=>$_POST['pf'],
		':id_appli'		=>$_POST['appli'],
		':id_module'		=>empty($_POST['module'])? null: $_POST['module']

	]);
	$err=$req->errorInfo();
	if(empty($err[2])){
		return false;
	}else{
		return $err[2];
	}

}

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$evoMgr=new EvoManager($pdoEvo);
$listPF=$evoMgr->getListPlateforme();
$arrPf=EvoHelpers::arrayPlateformeName($pdoEvo);
$arrAppli=EvoHelpers::arrayAppliName($pdoEvo);
$arrModule=EvoHelpers::arrayModuleName($pdoEvo);
$arrDevMail=EvoHelpers::arrayAppliRespEmail($pdoEvo);

if(isset($_POST['submit'])){
	$arrAppliRespId=EvoHelpers::arrayAppliRespId($pdoEvo);
	$idResp=$arrAppliRespId[$_POST['appli']];
	$err=insertEvo($pdoEvo,$idResp);
	if(!$err){
		if(VERSION!="_"){
			$dest=['valerie.montusclat@btlec.fr'];
			$cc=[];
			$hidden=[];
		}else{
			$devMail=$arrDevMail[$idResp];
			$dest=[$devMail, 'luc.muller@btlec.fr', 'david.syllebranque@btlec.fr'];
			$dest=array_unique($dest);
			$cc=[];
			$hidden=['valerie.montusclat@btlec.fr'];
		}
		$htmlMail = file_get_contents('mail-new-dd.html');
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

// ---------------------------------------
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
		->setTo($dest)
		->setCc($cc)
		->setBcc($hidden);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
			$errors[]="erreur envoi mail";
		}else{
			$successQ='?success=cree';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}



	}else{

		$errors[]=$err;

	}
}

if(isset($_GET['success'])){
	$arrSuccess=[
		'cree'=>'Votre demande d\'évo a bien été envoyée',
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
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

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
								Définissez une priorité :
							</div>
							<div class="col">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="1" id="urgent" name="prio" required>
									<label class="form-check-label pr-5 text-red" for="urgent"><b>urgent</b></label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="2" id="normal" name="prio">
									<label class="form-check-label pr-5 text-main-blue" for="normal"><b>normal</b></label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="3" id="faible" name="prio">
									<label class="form-check-label pr-5 text-green" for="faible"><b>faible</b></label>
								</div>
							</div>
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

				<div class="row pb-5">
					<div class="col text-right">
						<button class="btn btn-black" name="submit">Valider</button>
					</div>
				</div>
			</form>
		</div>
	</div>


	<!-- ./container -->
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("input:radio[name='pf']").click(function () {
			var plateforme=$('input[name="pf"]:checked').val();
			$.ajax({
				type:'POST',
				url:'ajax-get-appli.php',
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
				url:'ajax-get-appli.php',
				data:{id_appli:appli},
				success: function(html){
					$("#module").html(html)
				}
			});
		});
	});


</script>
<?php
require '../view/_footer-bt.php';
?>