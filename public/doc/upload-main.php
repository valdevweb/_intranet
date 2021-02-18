<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
require '../../config/db-connect.php';

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";


require '../../functions/global.fn.php';

function deleteGaz($pdoBt,$id){
	$where = ['id' => $id];
	$req=$pdoBt->prepare("SELECT file FROM gazette WHERE id=:id");
	$req->execute($where);
	$name=$req->fetch(PDO::FETCH_ASSOC);
	$pdoBt->prepare("DELETE FROM gazette WHERE id=:id")->execute($where);
	return $name;
}

// liste des type de documents pour profil admin => droit admin et comm
function getAllTypeNames($pdoBt){
	$req=$pdoBt->query("SELECT * FROM doc_type WHERE category ='communication' ORDER BY name");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
//liste des types de documents pour comm
function getNonAdminTypeNames($pdoBt){
	$req=$pdoBt->query("SELECT * FROM doc_type WHERE droits ='comm' AND category ='communication' ORDER BY name");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

//converti code en libellé
function getTypeName($pdoBt, $id){
	$req=$pdoBt->prepare("SELECT * FROM doc_type WHERE id= :id");
	$req->execute(array(
		":id"	=>$id
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

//ajout documents type assortiment
function insertIntoDbDoc($pdoBt,$type,$file,$descr, $docDate){
	// $reply=strip_tags($_POST['reply']);
	$insert=$pdoBt->prepare('INSERT INTO documents (type,date,file,code,date_modif, name, id_doc_type) VALUES (:type,:date,:file,:code,:date_modif, :name, :id_doc_type)');
	$result=$insert->execute(array(
		':type'=>$type,
		':date'=>$docDate,
		':file'=>$file,
		':code'=>"0",
		':date_modif'	=>date('Y-m-d H:i:s'),
		':name'=>$descr,
		':id_doc_type'=>$_SESSION['id_doc_type'],

	));
	// print_r($insert->errorInfo());
	// print_r($result);

	return $result;
}


function insertIntoDbGazette($pdoBt,$file,$dateDeb,$dateFin, $category,$title)
{
	// $reply=strip_tags($_POST['reply']);
	if($dateFin=="")
	{

	}
	$insert=$pdoBt->prepare('INSERT INTO gazette (file,date,date_fin,category,code,title,date_modif, id_doc_type) VALUES (:file,:date,:date_fin,:category,:code,:title,:date_modif, :id_doc_type)');
	$result=$insert->execute(array(
		':file'=>$file,
		':date'=>$dateDeb,
		':date_fin' => $dateFin,
		':category' =>$category,
		':code'=>"0",
		':title'=>$title,
		':date_modif'	=>date('Y-m-d H:i:s'),
		':id_doc_type'=>$_SESSION['id_doc_type'],


	));
	// print_r($insert->errorInfo());
	return $result;
}


// on supprime la ligne dans la db qui contient le doc actuel  puisqu'on ne garde par d'historique pour les documents
function deleteDoc($pdoBt){
	$delete=$pdoBt->prepare('DELETE FROM documents WHERE id_doc_type= :id_doc_type');
	$result=$delete->execute(array(
		':id_doc_type' =>$_SESSION['id_doc_type'],

	));
	return $result;
}

function isUserInGroupBis($pdoBt,$idWebuser,$groupName){
	$req=$pdoBt->prepare("SELECT * FROM groups WHERE id_webuser= :idWebuser AND group_name= :groupName");
	$req->execute(array(
		":idWebuser" =>$idWebuser,
		":groupName" =>$groupName
	));
	return $req->rowCount();
}


function insertIntoDbDocCm($pdoBt,$filename,$title){
	$req=$pdoBt->prepare("INSERT INTO doc_cm (id_web_user,date_upload,file,info,id_doc_type) VALUES (:id_web_user,:date_upload,:file,:info,:id_doc_type)");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_upload'		=>date('Y-m-d H:i:s'),
		':file'				=>$filename,
		':info'				=>$title,
		':id_doc_type'			=>$_SESSION['id_doc_type']

	]);
	return $req->rowCount();

}

//------------------------------------------------------
//			DATA
//------------------------------------------------------
/* principe
on affiche les document tagé communication dans la liste déroulante du 1er formulaire
on recherche les infos du type de document sélectionné pour afficher les bons champ de formulaire
ex date_deb à 1 cad à oui, on met la var $dateDeb à oui pour l'afficher



*/

$errors=[];
$success=[];

$dateDeb=$dateFin=$libelle=$descr=false;

if(isset($_GET['sup'])){
	// echo 'id a sup ' .$_GET['sup'];
	$suppressed=deleteGaz($pdoBt,$_GET['sup']);
	$suppressed=$suppressed['file'];
	// header('location:deleted.php?id='.$suppressed);

}


$types=getNonAdminTypeNames($pdoBt);
$idUser=$_SESSION['id'];
// fonction de la _navbar
if (isUserInGroupBis($pdoBt,$idUser,"communication"))
{
	// recup nom des doc non admin
	$types=getNonAdminTypeNames($pdoBt);
}
elseif (isUserInGroupBis($pdoBt,$idUser,"admin"))
{
	// recup nom doc admin
	$types=getAllTypeNames($pdoBt);
}
else
{
	$types=[];
}

if(isset($_POST['type'])){
	$_SESSION['id_doc_type']=$_POST['type'];

}
if(isset($_SESSION['id_doc_type'])){
	$docType=getTypeName($pdoBt, $_SESSION['id_doc_type']);
	$dateDeb=($docType['date_deb']==1) ? true:false;
	$dateFin=($docType['date_fin']==1) ? true:false;
	$libelle=($docType['libelle']==1) ? true:false;
	$descr=($docType['descr']==1) ? true:false;
}

if(isset($_POST['send']) )
{
	// libelle suivant le type selectionné dans liste déroulante
	$category=getTypeName($pdoBt,$_SESSION['id_doc_type']);
	//traitement différent suivant type de fichier => insertion dans db différente
	//si champ du formulaire inexistant, on met la variable à "" sinon on lui affecte la valeur du post

	if($_FILES['file']['error']===0)
	{
		if($_SESSION['id_doc_type']==3 || $_SESSION['id_doc_type']==4 || $_SESSION['id_doc_type']==5 || $_SESSION['id_doc_type']==6 || $_SESSION['id_doc_type']==7 || $_SESSION['id_doc_type']==11 || $_SESSION['id_doc_type']==9 || $_SESSION['id_doc_type']==23){
			$uploadDir= DIR_UPLOAD. 'documents\\';
		}
		elseif($_SESSION['id_doc_type']==1 || $_SESSION['id_doc_type']==8 || $_SESSION['id_doc_type']==2){
			$uploadDir= DIR_UPLOAD. 'gazette\\';
		}
		$filename=new SplFileInfo($_FILES['file']['name']);
		if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$filename)){
			$success[]="le fichier ". $filename." a été uploadé avec succès  " ;

		}
		else{
			$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";
		}
	}
	if(empty($errors)){
		if(isset($_POST['libelle'])){
			$title=$_POST['libelle'];
		}
		elseif(isset($_POST['descriptif'])){
			$title=strip_tags($_POST['descriptif']);
			$title=nl2br($title);
		}
		else{
			$title="";
		}
		// $docDate=isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
		$dateDeb=isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
		$dateFin=isset($_POST['dateFin']) ?  $_POST['dateFin'] : NULL;
		if($_SESSION['id_doc_type']==3 || $_SESSION['id_doc_type']==4 || $_SESSION['id_doc_type']==5 || $_SESSION['id_doc_type']==6 || $_SESSION['id_doc_type']==7 || $_SESSION['id_doc_type']==11 || $_SESSION['id_doc_type']==9){
			// on supprime les doc précédents
			if(deleteDoc($pdoBt))
			{
			}
			else
			{
				$errors[]="erreur de suppression du fichier précédant";
			}
			if(insertIntoDbDoc($pdoBt,$category['name'], $filename,$title, $dateDeb)){
				$success[]= $category['name'] . " mis à jour  ";
				unset($_SESSION['id_doc_type']);
			}
			else{
				$errors[]="le fichier n'a pas pu être ajouté à la base de donnée";
			}
		}
		elseif($_SESSION['id_doc_type']==1 || $_SESSION['id_doc_type']==8 || $_SESSION['id_doc_type']==2){
			if(insertIntoDbGazette($pdoBt,$filename,$dateDeb,$dateFin,$category['name'],$title)){
				$success[]="base de donnée mise à jour";
				unset($_SESSION['id_doc_type']);
			}
			else{
				$errors[]="le fichier n'a pas pu être ajoutée. Nom du fichier : " .$filename;
			}
		}
		elseif($_SESSION['id_doc_type']==23){
			if(insertIntoDbDocCm($pdoBt,$filename,$title)){
				$success[]="base de donnée mise à jour";
				unset($_SESSION['id_doc_type']);
			}
			else{
				$errors[]="le fichier n'a pas pu être ajoutée. Nom du fichier : " .$filename;
			}
		}

	}
}
//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');

?>

<div class="container box-bd px-5 pb-5 shadow">
	<h1 class="text-main-blue py-5">Upload de documents</h1>

	<!-- choix du type de doc -->
	<div class="row">
		<div class="col-4 text-center p-3 border">
			<img src="../img/icons/upload.png" class="">
		</div>
		<div class="col px-5 py-4">
			<div class="row">
				<div class="col">
					<?php
					include('../view/_errors.php');
					?>
				</div>
			</div>

			<div class="row">
				<div class="col">
					<form method="post" action="">
						<div class="form-row">
							<div class="col">
								<label for="type">Sélectionnez le type de document à uploader</label>
								<!-- listes des types de documents existants -->
								<select class="form-control" name="type" id="type" onchange='this.form.submit()'>
									<option value="">type de fichier</option>
									<?php foreach($types as $type):  ?>
										<?php
										$selected='';
										if(isset($_POST['type'])){
											$selected=($type['id']==$_POST['type'])? 'selected' :'' ;
										}
										?>

										<option value="<?= $type['id']?>" <?= $selected?>><?= $type['name']?></option>
									<?php endforeach ?>

								</select>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<form method="post" action="<?= $_SERVER['PHP_SELF']?>"  enctype="multipart/form-data" >
						<?php if ($dateDeb): ?>
							<div class='row pt-3'>
								<div class='col-4'>
									<div class="form-group">
										<label for='date'>du : </label>
										<input type='date' class='form-control' id='date'  name='date' required>
									</div>
								</div>
							</div>
						<?php endif ?>
						<?php if ($dateFin): ?>
							<div class="row pt-3">
								<div class="col-4">
									<div class="form-group">
										<label for='date-fin'>au : </label>
										<input type='date' class='form-control' id='date-fin'  name='dateFin' required>
									</div>
								</div>
							</div>
						<?php endif ?>
						<?php if ($libelle): ?>
							<div class="row pt-3">
								<div class="col">
									<div class="form-group">
										<label for='libelle'>libellé : </label>
										<input type='text' class='form-control' id='libelle'  name='libelle' required>
									</div>
								</div>
							</div>
						<?php endif ?>
						<?php if ($descr): ?>
							<div class="row pt-3">
								<div class="col">
									<div class="form-group">
										<label for='descriptif'>Descriptif :</label>

										<textarea class='form-control' id='descriptif' name='descriptif' rows='10'></textarea>
									</div>
								</div>
							</div>
						<?php endif ?>
						<br>
						<div class="form-row">
							<div class="col">
								<label for="file">Joindre le fichier :</label>
								<p><input type="file" name="file" class='input-file' id="file"></p>
							</div>
						</div>
						<br>
						<!-- affichage des erreurs -->
						<?= isset($errorsDisplayOdr) ? $errorsDisplayOdr : ""; ?>
						<div class="form-row">
							<div class="col text-right">
								<button type="submit" class="btn btn-primary" name="send" id="send">Envoyer</button>
							</div>
						</div>
					</form>

					<!-- <p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p> -->
				</div> <!-- ./col -->

			</div><!-- ./row -->
		</div>
	</div>
	<!-- ./form -->
	<div class="row mt-5">
		<div class="col">
			<!-- listing pour les fichiers gazette uniquement -->
			<div id="listing">
			</div>
		</div>
	</div>
</div> <!-- ./container -->
<script type="text/javascript">

	//fonction de suppression de gazette - tableau des gazettes construit parr la page gazette.ajax
	function deleteEl(id){
		//id de la gazette à supprimer
		deleteid=id;
		// # du td cliqué => comme on ne peut pas récupérer l'évenement clic, on passe le numéro d'id à la fonction
		var el = $('#'+id);
		$.ajax({
			url: 'delete.ajax.php',
			type: 'POST',
			data: { id:deleteid },
			success: function(response){

			    // Removing row from HTML Table
			    $(el).closest('tr').css('background','tomato');
			    $(el).closest('tr').fadeOut(800, function(){
			    	$(this).remove();
			    });

			}
		});
	}
	$(document).ready(function(){

		$("#send").click(function(e) { // bCheck is a input type button
			var fileName = $("#file").val();

		    if(!fileName) { // no file was selected
		    	alert("Veuillez sélectionner un fichier");
		    	e.preventDefault();

		    }
		});
		// gazette, on affiche la liste des dernières gazette
			//on réinitialise la liste sinon elles s'ajoutent
			var id_doc_type = $('#type').find(":selected").val();

			$('#listing').html("");
			if(id_doc_type==1 || id_doc_type== 2 || id_doc_type == 8)		{
				$.ajax({
					type: 'POST',
					url:'gazette.ajax.php',
					data:'id_doc_type='+id_doc_type,
					success:function(html){
						$('#listing').append(html);
					}
				});
			}
		});


	</script>




	<?php
	include('../view/_footer-bt.php');