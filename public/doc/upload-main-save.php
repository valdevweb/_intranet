<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

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
function getTypeName($pdoBt){
	$req=$pdoBt->prepare("SELECT name FROM doc_type WHERE id= :id");
	$req->execute(array(
		":id"	=>$_POST['type']
	));
	return $req->fetch();

}

//ajout documents type assortiment
function insertIntoDbDoc($pdoBt,$type,$file,$descr, $docDate){
	// $reply=strip_tags($_POST['reply']);
	$insert=$pdoBt->prepare('INSERT INTO documents (type,date,file,code,date_modif, name, id_doc_type) VALUE (:type,:date,:file,:code,:date_modif, :name, :id_doc_type)');
	$result=$insert->execute(array(
		':type'=>$type,
		':date'=>$docDate,
		':file'=>$file,
		':code'=>"0",
		':date_modif'	=>date('Y-m-d H:i:s'),
		':name'=>$descr,
		':id_doc_type'=>$_POST['type'],

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
	$insert=$pdoBt->prepare('INSERT INTO gazette (file,date,date_fin,category,code,title,date_modif, id_doc_type) VALUE (:file,:date,:date_fin,:category,:code,:title,:date_modif, :id_doc_type)');
	$result=$insert->execute(array(
		':file'=>$file,
		':date'=>$dateDeb,
		':date_fin' => $dateFin,
		':category' =>$category,
		':code'=>"0",
		':title'=>$title,
		':date_modif'	=>date('Y-m-d H:i:s'),
		':id_doc_type'=>$_POST['type'],


	));
	// print_r($insert->errorInfo());
	return $result;
}


// on supprime la ligne dans la db qui contient le doc actuel  puisqu'on ne garde par d'historique pour les documents
function deleteDoc($pdoBt){
	$delete=$pdoBt->prepare('DELETE FROM documents WHERE id_doc_type= :id_doc_type');
	$result=$delete->execute(array(
		':id_doc_type' =>$_POST['type']
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

//------------------------------------------------------
//			DATA
//------------------------------------------------------




$errors=[];
$success=[];



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

if(isset($_POST['send']) )
{
	extract($_POST);
	// libelle suivant le type selectionné dans liste déroulante
	$category=getTypeName($pdoBt);
	// echo $category['name'];
	//traitement différent suivant type de fichier => insertion dans db différente
	//si champ du formulaire inexistant, on met la variable à "" sinon on lui affecte la valeur du post

	//catégorie document communication hors gazette
	if($type==3 || $type==4 || $type==5 || $type==6 || $type==7 || $type==11 || $type==9)
	{
		if($_FILES['file']['error']===0)
		{
			$uploadDir= '..\..\..\upload\documents\\';
			$filename=new SplFileInfo($_FILES['file']['name']);
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$filename))
			{
				$success[]="le fichier ". $filename." a été uploadé avec succès  " ;

			}
			else
			{
				$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";

			}

		}
		if(count($errors)==0)
		{
			// si type 11  on récupère le libelle
			if(isset($_POST['libelle']))
			{
				$title=$_POST['libelle'];
			}
			else
			{
				$title="";
			}

			if(isset($_POST['date']))
			{
				$docDate=$_POST['date'];
			}
			else
			{
				$docDate=date('Y-m-d');
			}

			if(deleteDoc($pdoBt))
			{
			}
			else
			{
				$errors[]="erreur de suppression du fichier précédant";
			}

			if(insertIntoDbDoc($pdoBt,$category['name'], $filename,$title, $docDate))
			{

				$success[]= $category['name'] . " mis à jour  ";
				$_POST=array();
				$_FILES=array();
				// unset($_POST);
				// unset($_FILES);
			// unset($success);
			}
			else
			{

				$errors[]="le fichier n'a pas pu être ajouté à la base de donnée";
				// unset($errors);
			}
		}
// include('listing-doc.php');

	}
// gazette quotidienne
	elseif($type==1 || $type==8 || $type==2)
	{
		if($_FILES['file']['error']===0)
		{
			$uploadDir= '..\..\..\upload\gazette\\';
			$filename=new SplFileInfo($_FILES['file']['name']);
			if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$filename))
			{
				// $success[]="le fichier ". $filename." a été enregistré avec succès  " ;

			}
			else
			{
				$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";

			}

		}
		if(count($errors)==0)
		{

			//vérifie l'existance des champs de formulaire :
			// $title=isset($_POST['libelle']) || ? $_POST['libelle'] :"";
			if(isset($_POST['libelle']))
			{
				$title=$_POST['libelle'];
			}
			elseif(isset($_POST['descriptif']))
			{
				$title=nl2br($_POST['descriptif']);
			}
			else
			{
				$title="";
			}
			// echo $_POST['dateDeb'];
			$dateDeb=isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
			$dateFin=isset($_POST['dateFin']) ?  $_POST['dateFin'] : NULL;

			if(insertIntoDbGazette($pdoBt,$filename,$dateDeb,$dateFin,$category['name'],$title))
			{
				$success[]=" le fichier ". $category['name'] ." - " .$filename . " a été enregistré avec succès  ";
				unset($_POST);
				unset($_FILES);
			// unset($success);
			}
			else
			{
				$errors[]="le fichier n'a pas pu être ajoutée. Nom du fichier : " .$filename;
				// unset($errors);
			}
		}

	}

	//on n'enregistre dans la base de donnée que si on a détecté aucune erreur

	ob_start();
	include ('../view/_errors.php');
	$errorsDisplayOdr=ob_get_clean();



}

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');

?>

<div class="container box-bd px-5 pb-5 shadow">
	<h1 class="blue-text text-darken-4">Upload des documents</h1>

	<!-- formulaire -->
	<div class="row">
		<div class="col">
			<form method="post" action="">
				<div class="form-row">
					<div class="col">
						<label for="type">Selectionnez le type de document à uploader</label>
						<!-- listes des types de documents existants -->
						<select class="form-control" name="type" id="type" onchange='this.form.submit()'>
							<option value="">type de fichier</option>
							<?php foreach($types as $type):  ?>
								<option value="<?= $type['id']?>"><?= $type['name']?></option>
							<?php endforeach ?>

						</select>
					</div>
				</div>
			</form>
			<form method="post" action="<?= $_SERVER['PHP_SELF']?>"  enctype="multipart/form-data" >

				<!-- champs de formulaire spécifique au type de document -->
				<div id="specific-fields">
					<div class='row'>
						<div class='col'>
							<div class="form-group">
								<label for='date'>date : </label>
								<input type='date' class='form-control' id='date'  name='date' required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for='date-fin'>date de fin : </label>
								<input type='date' class='form-control' id='date-fin'  name='dateFin' required>
							</div>
						</div>
					</div>



					<div class="row">
						<div class="col">
							<div class="form-group">
								<input type='text' class='form-control' id='libelle'  name='libelle' required>
								<label for='libelle'>libellé : </label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<div class="form-group">
								<textarea class='form-control' id='descriptif' name='descriptif' rows='10'></textarea>
								<label for='descriptif'>Descriptif</label>
							</div>
						</div>
					</div>









				</div>
				<br>
				<div class="form-row">
					<div class="col">
						<label for="file">Joindre les fichiers :</label>
						<p><input type="file" name="file" class='input-file' id="file"></p>
					</div>
				</div>
				<br>
				<!-- affichage des erreurs -->
				<?= isset($errorsDisplayOdr) ? $errorsDisplayOdr : ""; ?>
				<div class="form-row">
					<div class="col-sm12 col-md-10">
						<button type="submit" class="btn btn-primary" name="send" id="send">Envoyer</button>
					</div>
				</div>
			</form>

			<!-- <p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p> -->
		</div> <!-- ./col -->
		<div class="col"></div>
	</div><!-- ./row -->
	<!-- ./form -->
	<div class="row">
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
// if( document.getElementById("videoUploadFile").files.length == 0 ){
//     console.log("no files selected");
// }
		$("#send").click(function(e) { // bCheck is a input type button
			var fileName = $("#file").val();

		    if(!fileName) { // no file was selected
		    	alert("Veuillez sélectionner un fichier");
		    	e.preventDefault();

		    }
		});

		$('#type').on('change',function(){

			//code html des divers balises utilisée
			var startFormRow="<div class='form-row'>";
			var col="<div class='col'>";
			var dateUniqueLabel="<label for='date'>date : </label>";
			var inputDateUnique="<input type='date' class='browser-default form-control' id='date'  name='date' required>";
			var dateDebutLabel="<label for='date'>date de début : </label>";
			var dateFinLabel="<label for='date-fin'>date de fin : </label>";
			var inputDateDebut="<input type='date' class='browser-default form-control' id='date'  name='date' required>";
			var inputDateFin="<input type='date' class='browser-default form-control' id='date-fin'  name='dateFin' required>";
			var finDiv="</div>";
			var inputText="<input type='text' class='browser-default form-control' id='libelle'  name='libelle' required>";
			var inputTextLabel="<label for='libelle'>libellé : </label>";
			var textareaZone ="<textarea class='form-control' id='descriptif' name='descriptif' rows='10'></textarea>";
			var textareaLabel ="<label for='descriptif'>Descriptif</label>";

			//assemblage des balises
			var dateUnique = startFormRow + col + dateUniqueLabel + inputDateUnique +  finDiv + col + finDiv + finDiv;
			var libelle= startFormRow + col + inputTextLabel + inputText + finDiv +finDiv;
			var dateDebut = startFormRow + col + dateDebutLabel + inputDateDebut +  finDiv + col + finDiv +finDiv;
			var dateFin = startFormRow + col + dateFinLabel + inputDateFin +  finDiv + col + finDiv +finDiv;
			var descriptif= startFormRow + col + textareaLabel + textareaZone + finDiv+finDiv;

			//affichage du formulaire suivant le type de document sélectionné
			//cas général = dateunique
			//8 = mdd - 9 = kit affiche
			var id_doc_type =$(this).val();
			console.log(id_doc_type);
			if (id_doc_type==9 || id_doc_type ==8)
			{
				$('#specific-fields').html(libelle);
			}
			//2 = gazette appro
			else if(id_doc_type==2)
			{
				$('#specific-fields').html(dateDebut + dateFin + descriptif);
			}
			else if(id_doc_type==6 || id_doc_type==7 || id_doc_type==11)
			{
				$('#specific-fields').html();

			}
			else
			{
				$('#specific-fields').html(dateUnique);
			}
			//si gazette, on affiche la liste des dernières gazette
			//on réinitialise la liste sinon elles s'ajoutent
			$('#listing').html("");
			if(id_doc_type==1 || id_doc_type== 2 || id_doc_type == 8)

			// if(code==1 || code== 2 || code == 8)
		{
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
	});

</script>




<?php
include('../view/_footer-bt.php');