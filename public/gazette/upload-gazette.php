<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
require '../../functions/upload.simple.fn.php';
require "../../functions/stats.fn.php";

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";



//construction du lien pour visualiser la gazette uploadée
$link="http://172.30.92.53/".$version."upload/gazette/";

//soumission formulaire gazette
if (isset($_POST['submit-gazette']))
{

	if (!empty($_FILES['gazette-upload']) && !empty($_POST['dateGazette']))
	{
		$category="gazette";
		extract($_POST);
		gazetteExist($pdoBt,$category,$dateGazette);
		$uploadDir= '..\..\..\upload\gazette\\';
		$upload=$_FILES['gazette-upload'];
		$msgGaz=checkUpload($upload, $uploadDir, $category, $dateGazette, $pdoBt);
		//-------------------------------------<
		//	ajout enreg dans stat
		//--------------------------------------
		$descr="fichier : " .$upload['name'] ;
		$page=basename(__file__);
		$action="upload gazette";
		addRecord($pdoStat,$page,$action, $descr);
		//------------------------------------->
	}
	else
	{
		unset($_FILES, $_POST);
		header('location:upload-gazette.php?empty-gazette');
		die;
	}
}


if (isset($_POST['submit-appros']))
{
	if (!empty($_FILES['appros-upload']) && !empty($_POST['dateAppros']))
	{
		$category="gazette appros";
		extract($_POST);
		gazetteExist($pdoBt,$category,$dateAppros);
		$uploadDir= '..\..\..\upload\gazette\\';
		$upload=$_FILES['appros-upload'];
		$msgApp=checkUpload($upload, $uploadDir,  $category,$dateAppros, $pdoBt);
		//-------------------------------------<
		//	ajout enreg dans stat
		//--------------------------------------
		$descr="fichier : " .$upload['name'] ;
		$page=basename(__file__);
		$action="upload gazette appros";
		addRecord($pdoStat,$page,$action, $descr);
		//------------------------------------->
	}
	else
	{
		unset($_FILES, $_POST);
		header('location:upload-gazette.php?empty-appros');
		die;
	}
}

//vérifie si déjà gazette à la date selectionnée => si oui erreur et stop
function gazetteExist($pdoBt,$category,$date)
{
	$req=$pdoBt->prepare("SELECT * FROM gazette WHERE date=:date AND category= :category");
	$req->execute(array(
		':date' 	=> $date,
		':category'	=>$category
	));
	// si on a des gazettes à la date spécifiée
	if($data=$req->fetch())
	{
		unset($_FILES, $_POST);
		if ($category=="gazette")
		{
			header('location:upload-gazette.php?errgaz');
		}
		else
		{
			header('location:upload-gazette.php?errapp');
		}
		die;
	}
}


//suppression de gazette
// si clic sur lien supprimer, on envoi l'id en paramètre de $_GET['sup']

if(isset($_GET['sup'])){
	echo 'id a sup ' .$_GET['sup'];
	deleteGaz($pdoBt,$_GET['sup']);
	header('location:upload-gazette.php');

}


include('../view/_head.php');
include('../view/_navbar.php');

?>



<div class="container">

<h1 class="light-blue-text text-darken-2">Envoi des gazettes</h1>
    <div class="row">
      <div class="col l4"></div>

      <div class="col l3">
      	<div class="mini-nav">
      		<p>Télécharger : </p>
       	 <p><a href="#gazette-frm" ><i class="fa fa-hand-o-right" aria-hidden="true"></i>la gazette hebdomadaire</a></p>
        	<p><a href="#gazette-appro-frm" ><i class="fa fa-hand-o-right" aria-hidden="true"></i>la gazette appros</a></p>
        </div>
      </div>
    </div>

<hr>



	<h4 id="gazette-frm"><i class="fa fa-hand-o-right" aria-hidden="true"></i>La gazette hebdomadaire</h4>
	<div class="row">
		<form method="post" action="upload-gazette.php" enctype="multipart/form-data" >
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<label class="w3-text-grey" for="dateGazette">Selectionnez la date de la gazette à uploader</label>
					<input type="date" class="w3-input w3-border no-spin" name="dateGazette" id="dateGazette" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
				</div>
				<div class="col l6"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<div class="upload">
						<label for="gazette-upload">&nbsp;&nbsp;Sélectionnez le fichier gazette</label>
					</div>
					<input type="file" name="gazette-upload" id="gazette-upload" >
				</div>
				<div class="col l4 align-right">
					<button class="btn waves-effect waves-light orange darken-3" type="submit" name="submit-gazette" >Envoyer</button>
				</div>
				<div class="col l2"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4"><p id="file-name-gazette"><?=isset($_FILES['name'])? $_FILES['name']: false?></p></div>
				<div class="col l6"></div>
			</div>
		</form>
	</div>
<!-- affichage message erreur, réussite -->
	<div class="down"></div>
	<div class="row">
		<div class="col l12 center">
			<?php
			if (isset($_GET['errgaz']))
			{
				echo "<p>Une gazette existe déjà pour cette date</p>";
			}
			if (isset($_GET['empty-gazette']))
			{
				echo "<p>Merci de sélectionner un fichier et une date</p>";
			}
			if(isset($msgGaz['success']))
			{

				echo "<p><a href='".$link.$msgGaz['success'] ."'>voir la gazette uploadée</a></p>";
			}
			elseif (isset($msg['err']))
			{
				echo "<p>".$msg['err']."</p>";
			}
			elseif (isset($msg))
			{
				var_dump($msg);

			}
			?>
		</div>
	</div><!-- END affichage message erreur, réussite -->

	<h4><i class="fa fa-hand-o-right" aria-hidden="true"></i>les 10 dernières gazettes : </h4>
	<table class="bordered" cellpadding="2px">
		<thead><tr>
			<th>Date</th>
			<th>nom fichier</th>
			<th class="center">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$category="gazette";
		$results= histoGazetteUpload($pdoBt, $category);
		foreach ($results as  $gazette) {
			?>
			<tr>
				<td><?php echo date('d-m-Y', strtotime($gazette['date'])) ?></center></td>
				<td><?php echo $gazette['file'] ?></center></td>
				<td class="delete"><a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?sup='. $gazette['id'] ?>" class="delete" title="supprimer"><i class="fa fa-trash fa-lg"  aria-hidden="true"></i></a></td>
			</tr>

			<?php
		}

		?>
	</tbody>
</table>

<hr>

<h4 id="gazette-appro-frm"><i class="fa fa-hand-o-right" aria-hidden="true"></i>La gazette appros</h4>

	<div class="row">
		<form method="post" action="upload-gazette.php" enctype="multipart/form-data" >
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<label class="w3-text-grey" for="dateAppros">Selectionnez la date de la gazette à uploader</label>
					<input type="date" class="w3-input w3-border no-spin" name="dateAppros" id="dateAppros" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
				</div>
				<div class="col l6"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<div class="upload">
						<label for="appros-upload">&nbsp;&nbsp;Sélectionnez le fichier gazette</label>
					</div>
					<input type="file" name="appros-upload" id="appros-upload" >
				</div>
				<div class="col l4 align-right">
					<button class="btn waves-effect waves-light orange darken-3" type="submit" name="submit-appros" >Envoyer</button>
				</div>
				<div class="col l2"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4"><p id="file-name-appros"><?=isset($_FILES['name'])? $_FILES['name']: false?></p></div>
				<div class="col l6"></div>
			</div>
		</form>
	</div>
<div class="down"></div>
	<div class="row">
		<div class="col l12 center">
			<?php
			if (isset($_GET['errapp']))
			{
				echo "<p>Une gazette appro existe déjà pour cette date</p>";
			}
			if (isset($_GET['empty-appros']))
			{
				echo "<p>Merci de sélectionner un fichier et une date</p>";
			}
			if(isset($msgApp['success']))
			{

				echo "<p><a href='".$link.$msgApp['success'] ."'>voir la gazette uploadée</a></p>";
			}
			elseif (isset($msg['err']))
			{
				echo "<p>".$msg['err']."</p>";
			}
			elseif (isset($msg))
			{
				var_dump($msg);

			}
			?>
		</div>
	</div><!-- END affichage message erreur, réussite -->

<h4><i class="fa fa-hand-o-right" aria-hidden="true"></i>les 10 dernières gazettes appros: </h4>
	<table class="bordered" cellpadding="2px">
		<thead><tr>
			<th>Date</th>
			<th>nom fichier</th>
			<th class="center">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$category="gazette appros";
		$results= histoGazetteUpload($pdoBt, $category);
		foreach ($results as  $gazette) {
			?>
			<tr>
				<td><?php echo date('d-m-Y', strtotime($gazette['date'])) ?></center></td>
				<td><?php echo $gazette['file'] ?></center></td>
				<td class="delete"><a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?sup='. $gazette['id'] ?>" class="delete" title="supprimer"><i class="fa fa-trash fa-lg"  aria-hidden="true"></i></a></td>
			</tr>

			<?php
		}

		?>
	</tbody>
</table>


</div> <!--END container  -->

	<?php
			include('../view/_footer.php');



