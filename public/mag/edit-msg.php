<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
include('../view/_head.php');
include('../view/_navbar.php');
include '../../functions/form.fn.php';
include "../../functions/stats.fn.php";
$descr="détail message côté magasin";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);

	$idMsg=$_GET['msg'];
	$idMag=$_SESSION['id'];
	$msg=showThisMsg($pdoBt, $idMag, $idMsg);
	$replies=showReplies($pdoBt, $idMsg);
	// on supprime la var de session qui permet la redirection suite à l'ouverture du mail
	unset($_SESSION['goto']);


function isAttached($dbData)
{
	global $version;
	$href="";
	if(!empty($dbData))
	{
		$ico="<i class='fa fa-paperclip fa-lg' aria-hidden='true'></i>";
		$href= "Pièce jointe : &nbsp; &nbsp; &nbsp; &nbsp; <a href='http://172.30.92.53/".$version ."upload/mag/" . $dbData . "'>" .$ico ."&nbsp; &nbsp; ouvrir</a>";
	}
	return $href;
}

// echo $msg['inc_file'];

?>


<div class="container">
	<p><a href= "<?= ROOT_PATH?>/public/mag/histo.php" class="orange-text text-darken-2"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>
	<div class="row">
		<div class="col s12">
			<h1 class="light-blue-text text-darken-2">Détail de votre demande</h1>
		</div>
	</div>


	<div class="down"></div>
	<!--historique-->
	<div class="row">
		<div class="card horizontal">
			<div class="card-image">
				<img class="" src="../img/contact/question.png">
			</div>
			<div class="card-stacked">
				<div class="card-action">
					<p class="orange-text text-darken-2 boldtxt">VOTRE DEMANDE  du <?= date('d-m-Y', strtotime($msg['date_msg']))?> : <?=$msg['objet']?></p>

				</div>
				<div class="card-content">
					<p><?=$msg['msg']?></p>
					<p><?=isAttached($msg['inc_file'])?></p>

				</div>

			</div>
		</div>

<div class="down"></div>
		<div class="card horizontal">

			<div class="card-stacked">
		 		<?php foreach($replies as $reply): ?>


				<div class="card-action">
					<p class="orange-text text-darken-2 boldtxt">Réponse du : <?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
					<p class="text-darken-2 boldtxt">Par : <?= repliedByIntoName($pdoBt,$reply['replied_by'])?></p>


				</div>
				<div class="card-content">
					<p><?= $reply['reply'] ?></p>

				</div>
				<?php endforeach ?>

			</div>
			<div class="card-image">
				<img class="edit" src="../img/contact/reponse.png">
			</div>
		</div>
	</div>
</div>

<div class="down"></div>




<?php


include('../view/_footer.php');
 ?>

</body>
</html>







