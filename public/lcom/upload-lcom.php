<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

//----------------------------------------------------------------
// require "../../functions/stats.fn.php";
// $descr="signaler la mise à dispo de nouveaux reversements";
// $page=basename(__file__);
// $action="consultation";
// $code=101;
// addRecord($pdoStat,$page,$action, $descr,$code);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=basename(__file__);
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//header et nav bar
include ('../view/_head-mig-bis.php');
include ('../view/_navbar.php');

//----------------------------------------------------------------
//			functions
//----------------------------------------------------------------
function addDoc($pdoBt, $filename)
{
	$webname=strip_tags($_POST['webname']);
	$req=$pdoBt->prepare("INSERT INTO doc_lcom(id_web_user, date_upload,filename,webname,id_doc_type,id_cat) VALUES(:id_web_user, :date_upload,:filename,:webname,:id_doc_type, :id_cat)");
	$req->execute(array(
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_upload'		=>date('Y-m-d'),
		':filename'			=>$filename,
		':webname'			=>$webname,
		':id_doc_type'		=>20,
		':id_cat'			=>$_POST['cat']

	));
	return $pdoBt->lastInsertId();
	// return $req->errorInfo();

}

function getCat($pdoBt)
{
	$req=$pdoBt->query("SELECT * FROM doc_lcom_cat ORDER BY nom");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$cats=getCat($pdoBt);


$errors=[];
$success=[];

//----------------------------------------------------------------
//			traitement formmulaire
//----------------------------------------------------------------

if(isset($_POST['submit']))
{
	if($_FILES['file']['error']===0)
	{
		$uploadDir= '..\..\..\upload\lcom\\';
		$filename=new SplFileInfo($_FILES['file']['name']);
		if(!move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$filename))
		{
			$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";
		}
	}
	if(count($errors)==0)
	{
		//ajout dans db
		$lastId=addDoc($pdoBt, $filename);
		if($lastId>0)
		{
			$success[]="le fichier ". $filename." a été uploadé avec succès  " ;
		}
		else
		{
			$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";
		}

	}
}

?>

<div class="container white-container shadow">
	<h1 class="blue-text text-darken-4">Espace Lcommerce</h1>
	<h4 class="blue-text text-darken-4" id="modalite-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Upload de documents</h4>

	<br><br>
	<div class="row">
		<div class="col-1"></div>
		<div class="col border shadow p-5">
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>"  enctype="multipart/form-data">

				<div class="row">
					<div class="col-7">
						<div class="form-group">
							<label for="cat">Catégorie de document</label>
							<select class="form-control" id="cat" name="cat">
								<option value="">Sélectionner</option>

								<?php
								foreach ($cats as $cat)
								{
									echo '<option value="'.$cat['id'].'">'.$cat['nom'].'</option>';

								}


								?>
							</select>
						</div>
					</div>
						<div class="col"></div>

					</div>

					<div class="row">
						<div class="col-7">
							<div class="form-group">
								<label for="webname">Nom à afficher pour le document</label>
								<input type="text" class="form-control" id="webname" name="webname" required="require">
							</div>
						</div>
						<div class="col"></div>
					</div>
					<div class="row">
						<div class="col">
							<div id="file-upload">
								<fieldset>
									<p class="pt-2">Document :</p>
									<div class="form-group">
										<p><input type="file" name="file" class='form-control-file'></p>
									</div>
								</fieldset>
							</div>
						</div>
						<!-- <div class="col"></div> -->
					</div>
					<div class="row pt-4">
						<div class="col">
							<p class="text-right">
								<button type="submit" id="submit" class="btn btn-primary" name="submit">Envoyer</button>
							</p>
						</div>
						<!-- <div class="col"></div> -->

					</div>
					<?php include('../view/_errors.php') ?>
				</form>
			</div>
			<div class="col-3"></div>

		</div>


	</div> <!-- ./container -->


	<?php





// footer avec les scripts et fin de html
	include('../view/_footer-mig-bis.php');
	?>