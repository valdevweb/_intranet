<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
include('../view/_head.php');
include('../view/_navbar.php');
include '../../functions/form.fn.php';
	$idMsg=$_GET['msg'];
	$idMag=$_SESSION['id'];
	$msg=showThisMsg($pdoBt, $idMag, $idMsg);
	$reply=showThisReply($pdoBt, $idMag, $idMsg);
	$iduser=$reply['reply_by'];
	$replyBy=whoReplied($pdoBt,$iduser);

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


echo $msg['inc_file'];

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
		 		<?php ob_start();?>

				<div class="card-action">
					<p class="orange-text text-darken-2 boldtxt"><?= $replyBy["prenom"] .' '. $replyBy["nom"]?> vous a répondu :</p>

				</div>
				<div class="card-content">
					<p><?=$reply['reply']?></p>
				</div>
				<?php
				$replyStack=ob_get_contents();
			 	ob_end_clean();
			 	if($reply['reply']){
			 		echo $replyStack;
			 	}
			 	else
			 	{
				echo "<div class='card-content'><p>en attente de réponse</p></div>";
			 	}

			 	?>
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







