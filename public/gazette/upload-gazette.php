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
		$code=1;
		extract($_POST);
		gazetteExist($pdoBt,$category,$dateGazette);
		$uploadDir= '..\..\..\upload\gazette\\';
		$upload=$_FILES['gazette-upload'];
		//pas de date de fin pour la gazette normale donc on met la même date que la date d'upload
		$dateNa=$dateGazette;
		$noTitle="gazette";
		$msgGaz=checkUpload($upload, $uploadDir, $category, $code, $dateGazette, $dateNa, $noTitle, $pdoBt);
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


//soumission formulaire gazette
if (isset($_POST['submit-gazette-spe']))
{

	if (!empty($_FILES['gazette-spe-upload']) && !empty($_POST['dateGazetteSpe']) && !empty($_POST['title']))
	{
		$category="spéciale gazette";
		$code=8;
		extract($_POST);
		// gazetteExist($pdoBt,$category,$dateGazette);
		$uploadDir= '..\..\..\upload\gazette\\';
		$upload=$_FILES['gazette-spe-upload'];
		//pas de date de fin pour la gazette normale donc on met la même date que la date d'upload
		$dateNa=$dateGazetteSpe;
		$msgGaz=checkUpload($upload, $uploadDir, $category, $code, $dateGazetteSpe, $dateNa, $title, $pdoBt);
		//-------------------------------------<
		//	ajout enreg dans stat
		//--------------------------------------
		$descr="fichier : " .$upload['name'] ;
		$page=basename(__file__);
		$action="upload gazette speciale";
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
	if (!empty($_FILES['appros-upload']) && !empty($_POST['dateApprosDeb']) && !empty($_POST['dateApprosFin']))
	{
		$category="gazette appros";
		$code=2;
		$noTitle="gazette appros";
		extract($_POST);
		// gazetteExist($pdoBt,$category,$dateApprosDeb);
		$uploadDir= '..\..\..\upload\gazette\\';
		$upload=$_FILES['appros-upload'];
		$msgApp=checkUpload($upload, $uploadDir, $category,$code, $dateApprosDeb,$dateApprosFin, $noTitle, $pdoBt);
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
		header('location:upload-gazette.php?errgaz');
		die;
	}
}


//suppression de gazette
// si clic sur lien supprimer, on envoi l'id en paramètre de $_GET['sup']

if(isset($_GET['sup'])){
	echo 'id a sup ' .$_GET['sup'];
	deleteGaz($pdoBt,$_GET['sup']);
	header('location:upload-gazette.php#gazetteTable');
}
if(isset($_GET['supSpe'])){
	echo 'id a sup ' .$_GET['supSpe'];
	deleteGaz($pdoBt,$_GET['supSpe']);
	header('location:upload-gazette.php#gazetteSpeTable');
}


if(isset($_GET['supAppro'])){
	echo 'id a sup ' .$_GET['supAppro'];
	deleteGaz($pdoBt,$_GET['supAppro']);
	header('location:upload-gazette.php#approsTable');
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
				<p><a href="#gazette-frm" ><i class="fa fa-hand-o-right" aria-hidden="true"></i>la gazette du jour</a></p>
				<p><a href="#gazette-spe-frm" ><i class="fa fa-hand-o-right" aria-hidden="true"></i>Spéciale Gazette</a></p>
				<p><a href="#gazette-appro-frm" ><i class="fa fa-hand-o-right" aria-hidden="true"></i>la gazette appros</a></p>
			</div>
		</div>
	</div>

	<hr>

	<!-- affichage message erreur, réussite gazette normal -->
	<div class="down"></div>

	<div class="row">
		<div class="col l12 center">
			<?php
			if (isset($_GET['errgaz']))
			{
				echo "<div class='card-panel red'><p class='white-text'>Une gazette existe déjà pour cette date</p></div>";
			}
			if (isset($_GET['empty-gazette']))
			{
				echo "<div class='card-panel red'><p class='white-text'>Merci de sélectionner un fichier et une date</p></div>";
			}
			if(isset($msgGaz['success']))
			{

				echo "<div class='card-panel teal lighten-2'><p class='white-text'><a href='".$link.$msgGaz['success'] ."'>voir la gazette uploadée</a></p></div>";
			}
			elseif (isset($msg['err']))
			{
				echo "<div class='card-panel red'><p class='white-text'>".$msg['err']."</p></div>";
			}
			elseif (isset($msg))
			{
				var_dump($msg);

			}

			?>
		</div>
	</div><!-- END affichage message erreur, réussite -->





	<div class="row">
		<div class="col l12 center" id="approMsg">
			<?php
			if (isset($_GET['errapp']))
			{
				echo "<div class='card-panel red'><p class='white-text'>Une gazette appro existe déjà pour cette date</p></div>";
			}
			if (isset($_GET['empty-appros']))
			{
				echo "<div class='card-panel red'><p class='white-text'>Merci de sélectionner un fichier et une date</p></div>";
			}
			if(isset($msgApp['success']))
			{

				echo "<div class='card-panel teal lighten-2'><p class='white-text'><a href='".$link.$msgApp['success'] ."'>voir la gazette appro uploadée</a></p></div>";
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
		</div>

	<!-- END affichage message erreur, réussite -->



	<!-- GAZETTE DU JOUR DEBUT -->
	<!-- GAZETTE DU JOUR FORM -->
	<h4 id="gazette-frm"><i class="fa fa-hand-o-right" aria-hidden="true"></i>La gazette du jour</h4>
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
	<!-- GAZETTE DU JOUR FIN DE FORM -->
	<!-- GAZETTE DU JOUR HISTO -->
	<h4><i class="fa fa-hand-o-right" aria-hidden="true"></i>les 10 dernières gazettes : </h4>
	<table class="bordered" cellpadding="2px" id="gazetteTable">
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
	<!-- GAZETTE DU JOUR FIN -->

	<!-- GAZETTE SPE DEBUT -->
	<!-- GAZETTE SPE FORM -->
	<h4 id="gazette-spe-frm"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Gazette spéciale</h4>
	<div class="row">
		<form method="post" action="upload-gazette.php" enctype="multipart/form-data" >
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<label class="w3-text-grey" for="title">Titre de la spéciale gazette</label>
					<input type="text" class="w3-input w3-border no-spin" name="title" id="title" required>
				</div>
				<div class="col l6"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<label class="w3-text-grey" for="dateGazetteSpe">Selectionnez la date de la gazette à uploader</label>
					<input type="date" class="w3-input w3-border no-spin" name="dateGazetteSpe" id="dateGazetteSpe" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
				</div>
				<div class="col l6"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<div class="upload">
						<label for="gazette-spe-upload">&nbsp;&nbsp;Sélectionnez le fichier spéciale gazette</label>
					</div>
					<input type="file" name="gazette-spe-upload" id="gazette-spe-upload" >
				</div>
				<div class="col l4 align-right">
					<button class="btn waves-effect waves-light orange darken-3" type="submit" name="submit-gazette-spe" >Envoyer</button>
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
	<!-- GAZETTE DU SPE FIN DE FORM -->
	<!-- GAZETTE DU SPE HISTO -->
	<h4><i class="fa fa-hand-o-right" aria-hidden="true"></i>les 10 dernières spéciales gazettes : </h4>
	<table class="bordered" cellpadding="2px" id="gazetteSpeTable">
		<thead><tr>
			<th>Date</th>
			<th>nom fichier</th>
			<th class="center">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$category="spéciale gazette";
		$results= histoGazetteUpload($pdoBt, $category);
		foreach ($results as  $gazetteSpe) {
			?>
			<tr>
				<td><?php echo date('d-m-Y', strtotime($gazetteSpe['date'])) ?></center></td>
				<td><?php echo $gazetteSpe['file'] ?></center></td>
				<td class="delete"><a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?supSpe='. $gazetteSpe['id'] ?>" class="delete" title="supprimer"><i class="fa fa-trash fa-lg"  aria-hidden="true"></i></a></td>
			</tr>

			<?php
		}

		?>
	</tbody>
	</table>
	<!-- GAZETTE SPE FIN -->




	<hr>

	<h4 id="gazette-appro-frm"><i class="fa fa-hand-o-right" aria-hidden="true"></i>La gazette appros</h4>

	<div class="row">
		<form method="post" action="upload-gazette.php" enctype="multipart/form-data" >
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<label class="w3-text-grey" for="dateApprosDeb">Selectionnez la date de début de l'opération</label>
					<input type="date" class="w3-input w3-border no-spin" name="dateApprosDeb" id="dateApprosDeb" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
				</div>
				<div class="col l6"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<label class="w3-text-grey" for="dateApprosFin">Selectionnez la date de fin de l'opération</label>
					<input type="date" class="w3-input w3-border no-spin" name="dateApprosFin" id="dateApprosFin" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
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


	<h4><i class="fa fa-hand-o-right" aria-hidden="true"></i>les 10 dernières gazettes appros: </h4>
	<table class="bordered" cellpadding="2px" id="approsTable">
		<thead><tr>
			<th>Date de début</th>
			<th>Date de fin</th>
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
				<td><?php echo date('d-m-Y', strtotime($gazette['date_fin'])) ?></center></td>
				<td><?php echo $gazette['file'] ?></center></td>
				<td class="delete"><a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?supAppro='. $gazette['id'] ?>" class="delete" title="supprimer"><i class="fa fa-trash fa-lg"  aria-hidden="true"></i></a></td>
			</tr>

			<?php
		}

		?>
	</tbody>
	</table>


</div> <!--END container  -->

	<?php
			include('../view/_footer.php');



