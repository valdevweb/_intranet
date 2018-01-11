<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}



include '../../functions/form.bt.fn.php';
//affichage de l'historique des réponses
include '../../functions/form.fn.php';
include '../../functions/mail.fn.php';
require "../../functions/stats.fn.php";

//------------------------------
//	ajout enreg dans stat
//------------------------------<

// $descr="detail d'une demande mag côté BT";
// $page=basename(__file__);
// $action="consultation";
// addRecord($pdoStat,$page,$action, $descr);

//------------------------------>

include '../view/_head.php';
include '../view/_navbar.php';

//------------------------------------------------------------
//				affiche lien vers piec jointe si existe
//------------------------------------------------------------
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

// pour affichage contenu msg
$idMsg=$_GET['msg'];
$oneMsg=showOneMsg($pdoBt,$idMsg);

//contenu histo des reponses
$replies=showReplies($pdoBt, $idMsg);
?>
<div class="container">
	<div class="row">
		<div class="col l12">
			<p><a href="histo.php" class="orange-text text-darken-2"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>
		</div>
	</div>
		<h1 class="blue-text text-darken-2">Dossier clos</h1>

	<h5 class="light-blue-text text-darken-2">La demande :</h5>
	<div class="row box-border">
		<div class="col l12 ">
					<p><span class="boldtxt">Objet : </span><?=$oneMsg['objet'] ?></p>
					<p><span class="boldtxt">Message : </span><?=$oneMsg['msg'] ?></p>
					<p><span class="boldtxt"></span><?=isAttached($oneMsg['inc_file']) ?></p>
				</div>
	</div>
	<p>&nbsp;</p>
	<h5 class="light-blue-text text-darken-2">Historique des réponses :</h5>

			<!-- exemple de if -->
			<?php
			if (!$replies)
			{
				echo "<div class='row box-border'><div class='col l12'><p>vous n'avez pas encore apporté de réponse au magasin</p></div></div>";
			}
			?>

	<!-- histo des réponses	 -->

	<?php foreach($replies as $reply): ?>
	<div class="row box-border">
		<div class="col l6">
			<p class="orange-text text-darken-2 boldtxt">Réponse du : <?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
		</div>
		<div class="col l6">
			<p class="orange-text text-darken-2 boldtxt">Par : <?= repliedByIntoName($pdoBt,$reply['replied_by'])?></p>
		</div>
		<div class="col l12">
			<p><?= $reply['reply'] ?></p>
		</div>
	</div>
	<?php endforeach ?>
	<p>&nbsp;</p>



</div>  <!--container


<?php



include('../view/_footer.php');
 ?>








