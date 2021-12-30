<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require '../../Class/evo/EvoDao.php';
require '../../Class/evo/EvoHelpers.php';
require '../../Class/UserHelpers.php';
require '../../Class/evo/AffectationDao.php';
require '../../Class/evo/EvoDocDao.php';
require '../../Class/evo/TempsDao.php';
require '../../Class/evo/NotifDao.php';
require '../../Class/evo/PlanningDao.php';
require '../../Class/evo/ChainageDao.php';
require '../../Class/evo/NoteDao.php';
require "../../Class/UserDao.php";
require "../../Class/Helpers.php";
require '../../Class/evo/ChgLogDao.php';

require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');
define("UPLOAD_DIR_EVO",DIR_UPLOAD.'evo-doc/' );
define("UPLOAD_URL_EVO",URL_UPLOAD.'evo-doc\\' );
$warning=false;
$tempsTotal=0;
$affectionEmail=[];


if(!isset($_GET['id'])){
	echo "une erreur c'est produite";
	exit;
}
$evoDao=new EvoDao($pdoEvo);
$docDao=new EvoDocDao($pdoEvo);
$tempsDao=new TempsDao($pdoEvo);
$planningDao=new planningDao($pdoEvo);
$notifDao=new notifDao($pdoEvo);
$chainageDao=new chainageDao($pdoEvo);
$affectationDao= new AffectationDao($pdoEvo);
$noteDao=new NoteDao($pdoEvo);
$userDao=new UserDao($pdoUser);
$chgDao=new ChgLogDao($pdoEvo);


$evo=$evoDao->getThisEvo($_GET['id']);


$docs=$docDao->getDocsEvo($_GET['id']);
$tempsPasses=$tempsDao->getTemps($_GET['id']);
$affectation=$affectationDao->getAffectation($_GET['id']);
$plannings=$planningDao->getPlanningEvo($_GET['id']);
$notifs=$notifDao->getNotifsByEvo($_GET['id']);
$parents=$chainageDao->isParent($_GET['id']);
$enfants=$chainageDao->isEnfant($_GET['id']);
$notes=$noteDao->getNotes($_GET['id']);
$droitExploit=$userDao->isUserAllowed([87]);

$arrDevMail=EvoHelpers::arrayAppliRespEmail($pdoEvo);
$listUsers=$userDao->getBtlecUserEvo();
$listServices=$userDao->getServicesMailing();
$listEtat=EvoHelpers::arrayEtat($pdoEvo);
$listLevel=EvoHelpers::arrayLevels($pdoEvo);
require_once('exploit-commun/00-init-var.php');

$listChg=$chgDao->getChgLogsByFields($idName, $_GET['id']);
$listDocChg=$chgDao->getChgLogsDocByField($idName, $_GET['id']);



if(isset($_POST['add_doc'])){
	include 'exploit-commun/01-add-doc.php';
}

if(isset($_POST['add_changelog'])){
	include 'exploit-commun/02-add-chg.php';
}

if(isset($_POST['update_changelog'])){
	include 'exploit-commun/03-update-chg.php';
}

if(isset($_POST['add_temps'])){
	if(empty($_POST['minutes']) || empty($_POST['date_exec'])){
		$_errors[]="Merci de remplir tout les champs";
	}
	if(empty($errors)){
		$done=$tempsDao->insertTemps($_GET['id'], $_POST['minutes'], $_POST['date_exec']);
	}
	$successQ='?success=temps&id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if(isset($_POST['add_affectation'])){
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
			$users=$userDao->getUserByServiceById($_POST['services'][$i], true);
			foreach ($users as $key => $user) {
				$affectionEmail[$affectationSize]['email']=$user['email'];
				$affectionEmail[$affectationSize]['id_service']=$user['id_web_user'];
				$affectionEmail[$affectationSize]['id_web_user']=$user['id_web_user'];
				$affectationSize++;
			}

		}
	}
	if(!empty($affectionEmail)){
		$affectionEmail=Helpers::arrayUniqueMultiCol($affectionEmail,'email');
		foreach ($affectionEmail as $key => $email) {
			$affectationDao->insertAffectation($_GET['id'], $email['id_web_user'], $email['id_service'], $email['email']);
			$cc[]=$email['email'];
		}
	}
	$successQ='?id='.$_GET['id'].'#title-affectation';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

}
if(isset($_POST['add_notif'])){
	if(empty($_POST['title']) || empty($_POST['date_notif'])){
		$errors[]="Vous devez au moins saisir une date et un objet pour la notification";
	}
	if(empty($errors)){

		$notifDao->insertNotif($_GET['id'], $_POST['title'], $_POST['date_notif'], $_POST['notif']);
		$successQ='?id='.$_GET['id'].'#title-notif';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}
if(isset($_POST['add_chainage'])){
	if(empty($_POST['parent']) || empty($_POST['enfant'])){
		$errors[]="Vous devez saisir les numéros des évolutions";
	}
	if(empty($errors)){

		$chainageDao->insertChainage($_POST['parent'], $_POST['enfant']);
		$successQ='?id='.$_GET['id'].'#title-chainage';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}

if(isset($_POST['add_note'])){
	if (empty($_POST['note'])) {
		$errors[]="Vous devez saisir une note";
	}
	if(empty($errors)){
		$noteDao->insertNote($_GET['id'], $_POST['note']);
		$successQ='?id='.$_GET['id'].'#title-note';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}
if(isset($_POST['update_note'])){
	if (empty($_POST['note'])) {
		$errors[]="Vous devez saisir une note";
	}
	if(empty($errors)){
		$noteDao->updateNote($_GET['id_note_update'], $_POST['note']);
		$successQ='?id='.$_GET['id'].'#title-note';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}

if(isset($_POST['modif_evo'])){
	$evoDao->updateEvo($_GET['id'], $_POST['evo'], $_POST['cmt_dd'], $_POST['chrono']);
	$successQ='?id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}


if(isset($_POST['add_planning'])){
	if(empty($_POST['date_start']) ||empty($_POST['date_end'])){
		$errors[]="Merci de saisir une date de fin et de début";
	}

	if(empty($errors)){
		$planningDao->insertPlanning($_GET['id'], $evo['id_resp'], $_POST['date_start'], $_POST['date_end']);
		if(isset($_POST['envoi']) && $_POST['envoi']==1){
			if(!empty($affectation)){
				foreach ($affectation as $key => $affect) {
					$dest[]=$affect['email'];
				}
			}

			$cc[]=$arrDevMail[$evo['id_resp']];


			if(VERSION=="_"){
				$dest=['valerie.montusclat@btlec.fr'];
				$cc=[];
			}

			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);

			$htmlMail = file_get_contents('mail/plannification.html');
			$htmlMail=str_replace('{OBJET}',$evo['objet'],$htmlMail);
			$htmlMail=str_replace('{EVO}',$evo['evo'],$htmlMail);
			$htmlMail=str_replace('{DATE_START}',date('d-m-Y', strtotime($_POST['date_start'])),$htmlMail);
			$htmlMail=str_replace('{DATE_END}',date('d-m-Y', strtotime($_POST['date_end'])),$htmlMail);
			$subject='Portail BTLec - demande d\'évo - mise au planning';
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')
			->setFrom(EXPEDITEUR_MAIL)
			->setTo($dest)
			->setCc($cc);

			if (!$mailer->send($message, $failures)){
				print_r($failures);
			}else{
				$success[]="mail envoyé avec succés";
			}
		}

		$successQ='?success=planning&id='.$_GET['id'].'#add-planning';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}
if(isset($_GET['del_affectation'])){
	$affectationDao->deleteAffectation($_GET['del_affectation']);
	$successQ='?id='.$_GET['id'].'#title-affectation';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

}

if(isset($_GET['del_planning'])){
	$planningDao->deletePlanning($_GET['del_planning']);
	$successQ='?id='.$_GET['id'].'#title-planning';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

}

if(isset($_GET['del_document'])){
	$do=$docDao->deleteDoc($_GET['del_document']);
	$successQ='?id='.$_GET['id'].'#title-document';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

}
if(isset($_GET['del_temps'])){
	$tempsDao->deleteTemps($_GET['del_temps']);
	$successQ='?id='.$_GET['id'].'#title-temps';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

}


if(isset($_GET['id_note_update'])){
	$thisNote=$noteDao->getNote($_GET['id_note_update']);
}


if(isset($_POST['update_note'])){
	$thisNote=$noteDao->getNote($_GET['id_note_update']);
	$successQ='?id='.$_GET['id'].'#title-note';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if(isset($_GET['id_note_del'])){
	$thisNote=$noteDao->maskNote($_GET['id_note_del']);
	$successQ='?id='.$_GET['id'].'#title-note';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}
if(isset($_GET['update_chg'])){
	$chgToUpdate=$chgDao->getChg($_GET['update_chg']);

}
if(isset($_GET['del_chg_doc'])){
	$chgDao->deleteChgDoc($_GET['del_chg_doc']);
	$successQ='?id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}
if(isset($_GET['del_chg'])){
	$chgDao->deleteChglog($_GET['del_chg']);
	$successQ='?id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}



if(isset($_POST['statuer'])){
	include('dashboard-evo\01-post-statuer.php');
}

if(isset($_POST['cloturer'])){
	include('dashboard-evo\02-post-cloturer.php');
}


if(isset($_GET['success'])){
	$arrSuccess=[
		'decision'=>'Envoi de la décision au demandeur et au développeur fait avec succès',
		'over'=>'Demande clôturée',
		'doc'=>'Document ajouté',
		'temps'=>'Temps ajouté',
		'planning'=>'Evo plannifiée',
		'chg-add'	=>'Changelog ajouté'
	];
	$success[]=$arrSuccess[$_GET['success']];
}




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row pt-5 pb-3">
		<div class="col">
			<h1 class="text-orange">Demande d'évo #<?=$_GET['id']?></h1>
		</div>
		<div class="col text-right">
			<?php if ($droitExploit): ?>

				<a href="planning-evo.php" class="btn btn-dark mr-3">Retour Planning</a>
				<a href="dashboard-evo.php" class="btn btn-dark">Retour Supervision</a>
			<?php else: ?>
				<a href="vosdemandes-evo.php" class="btn btn-dark">Retour</a>
			<?php endif ?>

		</div>

	</div>
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
			<?php include 'evo-detail/00-main-evo.php' ?>
		</div>
	</div>
	<?php if ($droitExploit): ?>

		<div class="row pb-3">
			<div class="col text-right align-self-end">
				<div id="modif-evo" class="btn btn-dark">Modifier l'évo</div>
			</div>
		</div>
		<div class="row">
			<div class="col"></div>
			<div class="col-lg-2 align-self-end ">
				<?php if ($evo['id_etat']==1): ?>
					<div class="text-center pb-3">
						<a href="#modal-statuer" data-toggle="modal" data-prio="<?=$evo['id_prio']?>" data-id="<?=$evo['id']?>">
							<button class="btn btn-dark">Statuer</button>
						</a>
					</div>

				<?php elseif($evo['id_etat']==2):?>
					<div class="text-center  pb-3">
						<a href="#modal-cloturer" data-toggle="modal" data-prio="<?=$evo['id_prio']?>" data-id="<?=$evo['id']?>">
							<button class="btn btn-dark">Cloturer</button>
						</a>
					</div>
				<?php endif ?>
			</div>
		</div>
		<div class="row" >
			<div class="col hidden border py-3 px-5" id="modif-evo-form">
				<?php include 'evo-detail/00-form-modif.php' ?>
			</div>
		</div>

		<div class="bg-separation-thin"></div>
		<div class="row mt-3">
			<div class="col text-center">
				<h3 class="text-orange">Exploitation</h3>
			</div>
		</div>

		<div class="row  mt-3" id="title-note">
			<div class="col">
				<h5 class="text-orange">Notes</h5>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col">
				<?php if (!empty($notes)): ?>
					<?php include 'evo-detail/13-table-notes.php' ?>
				<?php endif ?>
			</div>

		</div>
		<div class="row">
			<div class="col text-right  align-self-end">
				<div id="add-note" class="btn btn-dark">Ajouter une note</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col">
				<div class="hidden  border py-3 px-5"  id="add-note-form">
					<?php include 'evo-detail/13-form-notes.php' ?>
				</div>
			</div>
		</div>
		<?php if (isset($_GET['id_note_update'])): ?>
			<?php include 'evo-detail/13-form-notes-update.php' ?>
		<?php endif ?>

		<div class="bg-separation-thin"></div>

		<div class="row mt-3 mb-4">
			<div class="col">
				<h5 class="text-orange">Documents</h5>
				<?php include 'exploit-commun/10-list-document.php' ?>
			</div>

		</div>
		<div class="row mb-3">
			<div class="col text-right align-self-end">
				<div id="add-doc" class="btn btn-dark">Ajouter des documents</div>
			</div>
		</div>
		<div class="row" >
			<div class="col hidden" id="add-doc-form">
				<?php include 'exploit-commun/10-form-doc.php' ?>
			</div>
		</div>
		<div class="bg-separation-thin"></div>

		<div class="row">
			<div class="col">
				<h5 class="text-orange">ChangeLog</h5>
			</div>
		</div>
		<?php if (!empty($listChg)): ?>
			<?php include 'exploit-commun/12-table-chg.php' ?>
		<?php endif ?>
		<div class="row mb-3">
			<div class="col text-right">
				<div class="btn btn-dark" id="add-chglog">Ajouter un changelog</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="hidden" id="add-chglog-form">
					<?php include 'exploit-commun/13-form-chg.php' ?>
				</div>
			</div>
		</div>
		<?php if (isset($_GET['update_chg'])): ?>
			<?php include 'exploit-commun/14-form-chg-update.php' ?>
		<?php endif ?>
		<div class="bg-separation-thin"></div>


		<div class="row  mt-3" id="title-planning">
			<div class="col">
				<h5 class="text-orange">Planifier</h5>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col">
				<?php if (!empty($plannings)): ?>
					<?php include 'evo-detail/06-table-planning.php' ?>
				<?php endif ?>
			</div>
			<div class="col text-right  align-self-end">
				<div id="add-planning" class="btn btn-dark">Planifier</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col"></div>
			<div class="col-9">
				<div class="hidden  border py-3 px-5"  id="add-planning-form">
					<?php include 'evo-detail/05-form-planning.php' ?>
				</div>
			</div>
			<div class="col"></div>
		</div>
		<div class="bg-separation-thin"></div>
		<div class="row  mt-3" id="title-temps">
			<div class="col">
				<h5 class="text-orange">Temps passé</h5>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col">
				<?php if (!empty($tempsPasses)): ?>
					<?php include 'evo-detail/03-table-temps.php' ?>
				<?php endif ?>
			</div>
			<div class="col text-right align-self-end">
				<div id="add-time" class="btn btn-dark">Ajout de temps passé</div>
			</div>
		</div>
		<div class="row">
			<div class="col hidden border py-3 px-5"  id="add-time-form">
				<?php include 'evo-detail/04-form-temps.php' ?>
			</div>
		</div>
		<div class="bg-separation-thin"></div>
		<div class="row  mt-3" id="title-affectation">
			<div class="col">
				<h5 class="text-orange">Affectation</h5>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col">
				<?php if (!empty($affectation)): ?>
					<?php include 'evo-detail/06-table-affectation.php' ?>
				<?php endif ?>
			</div>
			<div class="col text-right align-self-end">
				<div id="add-affectation" class="btn btn-dark">Affecter</div>
			</div>
		</div>

		<div class="row">
			<div class="col hidden border py-3 px-5"  id="add-affectation-form">
				<?php include 'evo-detail/07-form-affectation.php' ?>
			</div>
		</div>

		<div class="bg-separation-thin"></div>

		<div class="row mt-3" id="title-notif">
			<div class="col">
				<h5 class="text-orange">Notifications</h5>
			</div>
		</div>


		<div class="row mb-4">
			<div class="col">
				<?php if (!empty($notifs)): ?>
					<?php include 'evo-detail/09-table-notif.php' ?>
				<?php endif ?>
			</div>
			<div class="col text-right align-self-end">
				<div id="add-notif" class="btn btn-dark">Créer une notification</div>
			</div>
		</div>

		<div class="row">
			<div class="col hidden border py-3 px-5"  id="add-notif-form">
				<?php include 'evo-detail/10-form-notif.php' ?>
			</div>
		</div>
		<div class="bg-separation-thin"></div>
		<div class="row mt-3" id="title-chainage">
			<div class="col">
				<h5 class="text-orange">Chainer à une autre demande</h5>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col">
				<?php if (!empty($parents) ||!empty($enfants)): ?>
				<?php include 'evo-detail/11-table-chainage.php' ?>
			<?php endif ?>
		</div>
		<div class="col text-right align-self-end">
			<div id="add-chainage" class="btn btn-dark">Créer un chainage</div>
		</div>
	</div>

	<div class="row">
		<div class="col hidden py-3 px-5"  id="add-chainage-form">
			<?php include 'evo-detail/12-form-chainage.php' ?>
		</div>
	</div>
<?php endif ?>
<?php
include('dashboard-evo\10-modal-statuer.php');
include('dashboard-evo\11-modal-cloturer.php');
?>

</div>

<script src="../../public/js/upload-helpers.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$('#modal-statuer').on('show.bs.modal', function (e) {
			var idevo = $(e.relatedTarget).data('id');
			var prio = $(e.relatedTarget).data('prio');
			$("input[name=prio][value=" + prio + "]").attr('checked', 'checked');
			var hiddenidevo=$('#id_evo');
			hiddenidevo.val(idevo)
			$.ajax({
				type:'POST',
				url:'ajax-getthis-evo.php',
				data:{id_evo:idevo},
				success: function(html){
					$("#objet").html(html)
				}
			});
		});


		$('#modal-cloturer').on('show.bs.modal', function (e) {
			var idevo = $(e.relatedTarget).data('id');
			var hiddenidevo=$('#id_evo_cloture');

			hiddenidevo.val(idevo)
			$.ajax({
				type:'POST',
				url:'ajax-getthis-evo.php',
				data:{id_evo:idevo},
				success: function(html){
					$("#objet_cloture").html(html)
				}
			});
		});

		$('#files-doc').change(function(){
			multipleWithName('files-doc','warning-zone', 'form-zone');
		});

		$("#url").on("click", function() {
			var files=$('#files-doc').get(0).files.length;
			$('input[name^="filename"]').each(function() {
				var filename=$(this).val();
				console.log(filename);
				$('#urlname').val(filename);
			});
		});

		$("#urlchg").on("click", function() {
			console.log("ddd");
			var files=$('#files-chglog-new').get(0).files.length;
			$('input[name^="filename"]').each(function() {
				var filename=$(this).val();
				$('#urlnamechg').val(filename);

			});
		});
		$("#urlchg-update").on("click", function() {
			console.log("dzdzd");
			var files=$('#files-chglog-update').get(0).files.length;
			$('input[name^="filename"]').each(function() {
				var filename=$(this).val();
				$('#urlnamechgupdate').val(filename);

			});
		});

		$("#add-doc").on("click", function() {
			$("#add-doc-form").toggleClass("hidden shown");

			if($("#add-doc-form").is(':visible')){
				$("#add-doc").text("Fermer");
			}else{
				$("#add-doc").text("Ajouter des documents");
			}

		});


		$("#add-chglog").on("click", function() {
			$("#add-chglog-form").toggleClass("hidden shown");

			if($("#add-chglog-form").is(':visible')){
				$("#add-chglog").text("Fermer");
			}else{
				$("#add-chglog").text("Ajouter un changelog");
			}

		});

		$('#files-chglog').change(function(){
			multipleWithName('files-chglog','warning-chg-zone', 'form-chg-zone')
		});
		$('#files-chglog-new').change(function(){
			multipleWithName('files-chglog-new','warning-chg-zone-new', 'form-chg-zone-new')
		});

		$('#files-chglog-update').change(function(){
			multipleWithName('files-chglog-update','warning-chg-zone-update', 'form-chg-zone-update')
		});
		$("#add-time").on("click", function() {
			$("#add-time-form").toggleClass("hidden shown");
			if($("#add-time-form").is(':visible')){
				$("#add-time").text("Fermer");
			}else{
				$("#add-time").text("Ajout de temps passé");
			}
		});
		$("#add-planning").on("click", function() {
			$("#add-planning-form").toggleClass("hidden shown");
			if($("#add-planning-form").is(':visible')){
				$("#add-planning").text("Fermer");
			}else{
				$("#add-planning").text("Planifier");
			}
		});

		$("#add-affectation").on("click", function() {
			$("#add-affectation-form").toggleClass("hidden shown");
			if($("#add-affectation-form").is(':visible')){
				$("#add-affectation").text("Fermer");
			}else{
				$("#add-affectation").text("affecter");
			}
		});

		$("#add-notif").on("click", function() {
			$("#add-notif-form").toggleClass("hidden shown");
			if($("#add-notif-form").is(':visible')){
				$("#add-notif").text("Fermer");
			}else{
				$("#add-notif").text("Créer une notification");
			}
		});

		$("#add-chainage").on("click", function() {
			$("#add-chainage-form").toggleClass("hidden shown");
			if($("#add-chainage-form").is(':visible')){
				$("#add-chainage").text("Fermer");
			}else{
				$("#add-chainage").text("Créer un chainage");
			}
		});
		$("#add-note").on("click", function() {
			$("#add-note-form").toggleClass("hidden shown");
			if($("#add-note-form").is(':visible')){
				$("#add-note").text("Fermer");
			}else{
				$("#add-note").text("Créer une note");
			}
		});

		$("#modif-evo").on("click", function() {
			$("#modif-evo-form").toggleClass("hidden shown");
			if($("#modif-evo-form").is(':visible')){
				$("#modif-evo").text("Fermer");
			}else{
				$("#modif-evo").text("Modifier l'évo");
			}
		});
		$("#more").addClass('hidden');
		$("#open").on("click", function() {
			var myClass = $("#more").attr("class");

			$("#more").toggleClass("hidden shown");

			if (myClass=="shown") {
				console.log("show");
				$("#open").text("plus...");
			}else if(myClass=="hidden"){
				console.log("hide");
				$("#open").text("fermer");

			}
		});

		$('.select-p').on("click", function(){
			var nbCol=$(this).attr('data-nb-col');
			var value=$(this).val();
			console.log("hello"+nbCol +value);
			if(value=="text"){
				$('#text-'+nbCol).show();
				$('#image-'+nbCol).hide();
			}
			if(value=="image"){
				$('#text-'+nbCol).hide();

				$('#image-'+nbCol).show();
			}
		});

	});

</script>
<?php
require '../view/_footer-bt.php';
?>