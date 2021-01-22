<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require '../../Class/Uploader.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];







//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getThisPres($pdoBt){
	$req=$pdoBt->prepare("SELECT *, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, pres_files.id as iddoc FROM pres lEFT JOIN pres_files ON pres.id=pres_files.id_pres WHERE pres.id= :id ORDER BY ordre");
	$req->execute([
		':id'		=>$_GET['id']
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function addDoc($pdoBt, $ofile, $pdfFile){
	$req=$pdoBt->prepare("INSERT INTO pres_files (id_pres, pdf, ofile, ordre, created_on, created_by) VALUES (:id_pres, :pdf, :ofile, :ordre, :created_on, :created_by)");
	$req->execute([
		':id_pres'		=>$_GET['id'],
		':pdf'		=>$pdfFile,
		':ofile'		=>$ofile,
		':ordre'		=>$_POST['order'],
		':created_on'		=>date('Y-m-d H:i:s'),
		':created_by'	=>$_SESSION['id_web_user']

	]);
	return $req->rowCount();
}

$pres=getThisPres($pdoBt);



if(isset($_POST['add-doc'])){
	$ofile="";
	$pdfFile="";

	if(isset($_FILES['orgfile']['name']) && !empty($_FILES['orgfile']['name']))
	{
		$uploader   =   new Uploader();
		$uploader->setDir('..\..\..\upload\pres\\');
		$uploader->allowAllFormats();
		$uploader->setMaxSize(5);

		if($uploader->uploadFile('orgfile')){
			$ofile=$uploader->getUploadName();
		}
		else{
			$errors[]=$uploader->getMessage();
		}
	}


	if(isset($_FILES['pdffile']['name']) && !empty($_FILES['pdffile']['name']))
	{
		$uploaderPdf   =   new Uploader();
		$uploaderPdf->setDir('..\..\..\upload\pres\\');
		 // $uploaderPdf->setExtensions('pdf');
		$uploaderPdf->allowAllFormats();
		$uploaderPdf->setMaxSize(5);

		if($uploaderPdf->uploadFile('pdffile')){
			$pdfFile=$uploaderPdf->getUploadName();
		}
		else{
			$errors[]=$uploaderPdf->getMessage();
		}
	}



	$newFiles=addDoc($pdoBt, $ofile, $pdfFile);
	if($newFiles==1){
		$successQ='?id='.$_GET['id'].'&success=1';
		unset($_POST);
		unset($_FILES);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}else{
		$errors[]="une erreur s'est produite";
	}



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

<div class="row">
	<div class="col">
		<h1 class="text-main-blue pt-5 text-center">Gestion de la présentation : </h1>
		<h5 class="mali sub text-center"><?= isset($pres[0])? $pres[0]['name']:''?></h5>
	</div>
	<div class="col-2 text-right pt-2">
		<button class="btn btn-primary "><a href="home-pres.php" class="text-white"> Retour</a></button>
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


	<div class="bg-separation"></div>
	<div class="row my-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<h5>Listes des documents de la présentation</h5>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<div class="row pb-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<!-- start table -->
			<table class="table table-sm table-striped">
				<thead class="thead-dark">
					<tr>
						<th>PDF de présentation</th>
						<th>Document Original</th>
						<th class="text-center">Suppression</th>
						<th class="text-right">Ordre</th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($pres) && !empty($pres)): ?>
					<?php foreach ($pres as $key => $p): ?>

						<tr>
							<td><a href="<?= UPLOAD_DIR.'\\pres\\'.$p['pdf'] ?>" target="blank"><?=$p['pdf']?></a></td>
							<td><a href="<?= UPLOAD_DIR.'\\pres\\'.$p['ofile'] ?>" target="blank"><?=$p['ofile']?></a></td>

							<td class="text-center"><a href="delete-doc-pres.php?idpres=<?=$_GET['id'].'&iddoc='.$p['iddoc']?>"><i class="fas fa-trash-alt"></i></a></td>
							<td class="text-right order" contenteditable="true" id="<?=$p['iddoc']?>"><?=$p['ordre']?></td>
						</tr>
					<?php endforeach ?>

				<?php endif ?>

			</tbody>
		</table>
		<!-- ./table -->
		<button class="btn btn-red" id="save">Sauvegarder l'ordre</button>
	</div>
	<div class="col-lg-1"></div>
</div>

<div class="bg-separation"></div>

<div class="row my-5">
	<div class="col-lg-1"></div>
	<div class="col"><h5>Ajouter des documents à la présentation :</h5></div>
	<div class="col-lg-1"></div>
</div>


<div class="row pb-5">
	<div class="col-lg-1"></div>
	<div class="col">

		<form action="<?=$_SERVER['PHP_SELF'].'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col bg-verylight-orange p-3">
					<label for="pdffile">Fichier pdf : </label> <span class="float-right"><i class="far fa-file-pdf fa-lg pr-3 text-red"></i></span>
					<input type="file" class="form-control-file" id="pdffile" name="pdffile"><br>
				</div>
				<div class="col bg-verylight-blue p-3">
					<label for="orgfile">Fichier original (facultatif) </label><span class="float-right"><i class="fas fa-file-excel fa-lg pr-2 text-green"></i><i class="fas fa-file-word fa-lg pr-2 text-blue"></i><i class="fas fa-file-image fa-lg text-grey pr-3"></i></span>
					<input type="file" class="form-control-file" id="orgfile" name="orgfile">
				</div>
			</div>
			<div class="row mt-3">
				<div class="col">
					<div class="form-group">
						<label>Position du document dans la présentation :</label>
						<input type="text" class="form-control" name="order" pattern="[0-9]+" title="Seuls les chiffres sont autorisés" required>
					</div>
				</div>
				<div class="col mt-4 pt-2">
					<button class="btn btn-black" name="add-doc">Ajouter</button>
				</div>
			</div>





		</form>
	</div>

	<div class="col-lg-1"></div>
</div>
<div class="bg-separation"></div>

<div class="row my-5">
	<div class="col-lg-1"></div>
	<div class="col"><h5>Regarder la présentation :</h5></div>
	<div class="col-lg-1"></div>
</div>
<div class="row pb-5">
	<div class="col text-center">
		<a href="display-pres.php?id_pres=<?=$_GET['id']?>&iddoc=0"><i class="fas fa-video fa-2x"></i></a>
	</div>
</div>


<!-- ./container -->
</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('#save').click(function(){
			var ligne=$('tr').length -1;
			var data=[];
			$("td.order").each(function(){
			// $("td[contenteditable=true]").each(function(){
				var row=this.parentElement.rowIndex -1; // - le header
				while(row >= data.length){
					//l id correspond à l'id du doc et le text l'ordre saisi ou non par l'ut
					data.push($(this).attr("id")+ "_" + $(this).text());
				}
			});

			$.post('ajax-order.php', {table:data},function(msg){
			});
			window.location.href = window.location.href;
			// ne marche pas à tous les coupslocation.reload();

		});


	});

</script>




<?php
require '../view/_footer-bt.php';
?>