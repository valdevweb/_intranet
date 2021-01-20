<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}



require '../../functions/mail.fn.php';
require '../../Class/BtUserManager.php';
require "../../Class/MsgManager.php";


require "../../functions/stats.fn.php";
$descr="détail message côté magasin";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
// $page=basename(__file__);
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


function addReply($pdoBt,$file){

	$req=$pdoBt->prepare('INSERT INTO replies (id_msg, reply, replied_by, date_reply,inc_file) VALUES (:id_msg, :reply, :replied_by, :date_reply, :inc_file)');
	$req->execute(array(
		':reply'		=> $_POST['reply'],
		':date_reply'	=> date('Y-m-d H:i:s'),
		':id_msg'		=> $_GET['msg'],
		':replied_by'	=>$_SESSION['id_web_user'],
		':inc_file'		=> $file
	));
	return $req->rowCount();

}


function formatPJ($incFileStrg){
	$href="";
	if(!empty($incFileStrg)){
		// on transforme la chaine de carctère avec tous les liens (séparateur : ; ) en tableau
		$incFileStrg=explode( '; ', $incFileStrg );
		for ($i=0;$i<count($incFileStrg);$i++){
		$ico="<i class='fa fa-paperclip fa-lg pl-5 pr-3 hvr-pop' aria-hidden='true'  ></i>";
		$href.= "<a class='pj' href='http://172.30.92.53/".VERSION ."upload/mag/" . $incFileStrg[$i] . "' target='blank'>" .$ico ."ouvrir</a>";
		}
		$href="<p>".$href."</p>";

	}

	return $href;
}

$errors=[];
$uploadDir= '..\..\..\upload\mag\\';
$fileList="";

$msgManager=new MsgManager();
$msg=$msgManager->getDemande($pdoBt,$_GET['msg']);
$replies=$msgManager->getListReplies($pdoBt, $_GET['msg']);
$btUserManager=new BtUserManager();
$infoService=$btUserManager->getService($pdoUser,$msg['id_service']);

// on supprime la var de session qui permet la redirection suite à l'ouverture du mail
unset($_SESSION['goto']);



if(isset($_POST['post-reply'])){
	if((empty($_POST['reply']))){
		$errors[]= "merci de remplir tous les champs";
	}
	if(empty($errors)){
		extract($_POST);
		for($i=0;$i<count($_FILES['file']['name']) ;$i++){
			if($_FILES['file']['name'][$i]!=""){
				$filename=$_FILES['file']['name'][$i];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$filenameNoExt = basename($filename, '.'.$ext);
				$filenameNoExt=str_replace(" ","_",$filenameNoExt);
				$filenameNoExt=str_replace(";","",$filenameNoExt);
				$filenameNoExt=str_replace("'","",$filenameNoExt);

				$filenameNew=$filenameNoExt.'-'.date('YmdHis').'.'.$ext;
				if($fileList==""){
					$fileList= $filenameNew;

				}else{
					$fileList= $fileList.'; '.$filenameNew;
				}
				$uploaded=move_uploaded_file($_FILES['file']['tmp_name'][$i],$uploadDir.$filenameNew );
				if($uploaded==false){
					$errors[]="Impossible d'ajouter la pièce jointe";
				}

			}
		}
		$addrep=addReply($pdoBt, $fileList);
		if($addrep!=1){
			$errors[]= "votre réponse n'a pas pu être enregistrée";
		}else{
			if(VERSION=="_"){
				$to="valerie.montusclat@btlec.fr";
			}else{
				$to=$infoService['mailing'];
			}
			$objet="PORTAIL BTLec - ajout d'un commentaire sur la demande du magasin " .$_SESSION['nom'];
			$tplForBtlec="../mail/new_mag_msg.tpl.html";

			$contentOne=$msg['who'];
			$contentTwo=$_SESSION['nom'];
			$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$_GET['msg']."'>ici pour consulter le message</a>";
			// $link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter le message</a>";
			if(sendMail($to,$objet,$tplForBtlec,$contentOne,$contentTwo,$link)){
				$successQ='?msg='.$_GET['msg'].'&success';
				unset($_POST);
				header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
			}
			else{
				$errors[]="Echec d'envoi de l'email";

			}

		}
	}
	if(!empty($errors)){
		$descr="err : " . implode(" ",$errors);
	}
	else{
		$descr="succès envoi message mag";
	}
	$page=basename(__file__);
	$action="ajout message mag à une demande existante";
	addRecord($pdoStat,$page,$action, $descr);
}

// btn nav
if($msg['etat']!='clos'){
	$btnAnswer='<a href= "#mag-msg" ><i class="fa fa-pencil-square-o prefix fa-lg pr-2" aria-hidden="true"></i>Répondre</a>';
	$btnReopen="";
}
else
{
	$btnAnswer="";
	$btnReopen='<a href="unlock.php?id_msg='.$_GET['msg']. '"><i class="fa fa-unlock-alt prefix fa-lg pr-3" aria-hidden="true"></i> Ré-ouvrir</a>';
}

include('../view/_head-mig.php');
include('../view/_navbar.php');
?>
<!-- <div class="container-fluid"> -->
	<div class="floating-menu-vm">
		<h3>Actions</h3>
		<a href= "histo-mag.php"><i class="fa fa-chevron-circle-left fa-lg pr-3" aria-hidden="true"></i>Retour</a>
		<?= $btnAnswer ?>
		<?= $btnReopen ?>

		<!-- </div> -->
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1"></div>
		</div>

		<!-- titre  -->
		<div class="row">
			<div class="col-12">

				<h1 class="blue-text text-darken-4 no-margin">Votre demande au service <?= $infoService['service']?> : <br><span class='sub-h1'>n° <?= $_GET['msg']?> - <?=$msg['objet']?> </span></h1>
			</div>
		</div>
		<!-- message 1  -->
		<div class="row">
			<div class="col-12">
				<div class="card-panel mag mb-5">
					<p class="text-right date"><?= date('d-m-Y', strtotime($msg['date_msg']))?></p>
					<p><?=$msg['msg']?></p>
					<?php
					if(!empty($msg['inc_file']))
					{
						echo "<p><span class='labelFor'>Pièce jointe : </span></p>";
						echo "<p>".formatPJ($msg['inc_file'])."</p>";
					}
					?>
				</div>
				<div class="center-text">
					<hr class="line">
				</div>
			</div>
		</div>
		<!-- reponses -->
		<?php foreach($replies as $reply): ?>
			<?php
	//nom de la personne qui a répondu si bt mise en forme différente suivant réponse BT ou mag
	// on sait que c'est réponse mag si $by est vide car la fonction va rechercher le nom de la personne qui a répondu dans la table BT
			if(is_null($reply['fullname']))
			{
				$by="";
				$side='mag';
				$logo="../img/logos/leclerc-rond-50.jpg";

			}
			else
			{
				$color="orange-text";
				$by="<p class='nom'>" .$reply['fullname'] ."</p>";
				$side='bt';
				$logo="../img/logos/bt-rond-50.jpg";
			}
			?>
			<?= $by ?>
			<div class="row">
				<div class="col-12">
					<div class="card-panel <?= $side ?>">
						<img class="w3-circle" src="<?=$logo ?>">

						<p class="text-right date"><?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
						<p><?= $reply['reply'] ?></p>
						<?php
						if(!empty($reply['inc_file']))
						{
							echo "<p><span class='labelFor'>Pièce(s) jointe(s) :</p>";
							echo  "<p>".formatPJ($reply['inc_file'])."</p>";
						}
						?>
					</div>
				</div>
			</div>
		<?php endforeach ?>
		<?php
		ob_start();
		?>
		<br><br>




		<div class="row">
			<div class="col">
				<div class="bg-white border px-5 py-3">
					<h4 class="blue-text text-darken-4"><i class="fa fa-pencil-square-o prefix pl-1 pr-3 fa-lg" aria-hidden="true"></i><strong>Ajouter un message :</strong></h4>
					<form action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?msg='.$_GET['msg'] ?>"  method="post" enctype="multipart/form-data" id="mag-msg">
						<div class="form-group">
							<label for="reply"></label>
							<textarea class="form-control" placeholder="Votre message" name="reply" id="reply" ></textarea>
						</div>
						<div class="pt-5 pb-2" id="file-upload">
							<p class="blue-text text-darken-4 pb-2"><i class="fa fa-download pr-3 fa-lg" aria-hidden="true"></i>Envoyer des pièces jointes</p>
							<p><input type="file" name="file[]"  class='form-control-file' ></p>
							<p class="pr-1 pt-2 blue-text text-darken-4" id="p-add-more"><a id="addmore" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Ajouter un fichier supplémentaire</a></p>
						</div>
						<div class="input-field text-right">
							<button class="btn" type="submit" name="post-reply">Envoyer</button>
						</div>

					</form>
				</div>
			</div>
		</div>
		<?php
	// si le message n'est pas clos, on affiche le formulaire pour ajouter une réponse
		$newResponseForm=ob_get_clean();
		if($msg['etat']!='clos')
		{
			echo $newResponseForm;
		}




		?>


	</div>
	<script type="text/javascript">
		$(document).ready(function (){
			$('#addmore').click(function(){
				$('#p-add-more').prepend('<p><input type="file" name="file[]"></p>');
				$('input[type="file"]').val();
			});
		});
	</script>



	<?php


	include('../view/_footer.php');
	?>

</body>
</html>







