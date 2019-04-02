<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
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
function getDoc($pdoBt)
{
	$req=$pdoBt->prepare("SELECT doc_lcom.id as id_doc,filename,webname,id_cat,DATE_FORMAT(date_upload,'%d/%m/%Y') as date_upload, doc_lcom_cat.nom FROM doc_lcom LEFT JOIN doc_lcom_cat ON id_cat=doc_lcom_cat.id WHERE doc_lcom.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));

	return $req->fetch(PDO::FETCH_ASSOC);
}

$doc=getDoc($pdoBt);

function getCat($pdoBt)
{
	$req=$pdoBt->query("SELECT * FROM doc_lcom_cat ");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$cats=getCat($pdoBt);

function updateCat($pdoBt)
{
	$req=$pdoBt->prepare("UPDATE doc_lcom SET id_cat= :id_cat WHERE doc_lcom.id= :id");
	$req->execute(array(
		':id_cat'		=>$_POST['cat'],
		'id'			=>$_GET['id']

	));
	$row=$req->rowCount();
	 // return $req->errorInfo();

	return	$row;
}




$errors=[];
$success=[];

//----------------------------------------------------------------
//			traitement formmulaire
//----------------------------------------------------------------
if(isset($_POST['submit']))
{
$row=updateCat($pdoBt);


	if($row>0)
	{
		$success[]="catégorie modifiée. <a href='move-lcom.php'>Cliquez ici pour retourner sur la page précédente</a>";



	}
	else
	{
		$errors[]="une erreur s'est produite";


	}

}

?>

<div class="container white-container shadow">
	<h1 class="blue-text text-darken-4">Espace Lcommerce</h1>
	<br><br>
	<div class="row">
		<div class="col-1"></div>
		<div class="col-10 border p-5">
			<h3 class="text-center text-orange pb-1">Changer le document de catégorie</h3>
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>?id=<?=$_GET['id']?>">

				<div class="row">
					<div class="col-7">
						<p class="font-weight-bold mt-5">Document : <?= $doc['webname'] ?> du <?= $doc['date_upload']?></p>
						<p class="font-weight-bold mb-5">Catégorie actuelle : <?= $doc['nom'] ?> </p>
						<div class="form-group">
							<label for="cat">Nouvelle catégorie</label>
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
						<p class="text-right">
								<button type="submit" id="submit" class="btn btn-primary mt-3" name="submit">Modifier</button>
							</p>
					</div>

						<div class="col"></div>

					</div>


			<?php include('../view/_errors.php')  ?>

		</div>
		<div class="col-1"></div>

	</div>


</div> <!-- ./container -->


<?php





// footer avec les scripts et fin de html
include('../view/_footer-mig-bis.php');
?>