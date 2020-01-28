<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require('../../Class/Uploader.php');


unset($_SESSION['goto']);
$errors=[];
$success=[];
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dossiers WHERE id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

$fLitige=getLitige($pdoLitige);


function getAction($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT libelle, action.id_web_user, DATE_FORMAT(date_action, '%d-%m-%Y')as dateFr, concat(prenom, ' ', nom) as name, pj FROM action LEFT JOIN btlec.btlec ON action.id_web_user=btlec.btlec.id_webuser WHERE action.id_dossier= :id ORDER BY date_action");
	$req->execute(array(
		':id'		=>$_GET['id']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$actionList=getAction($pdoLitige);

function addAction($pdoLitige, $fileList, $contrainte)
{
	$action=strip_tags($_POST['action']);
	$action=nl2br($action);
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier,libelle,id_contrainte,id_web_user,pj,date_action) VALUES (:id_dossier,:libelle,:id_contrainte,:id_web_user,:pj,:date_action)");
	$req->execute(array(
		':id_dossier'		=>	$_GET['id'],
		':libelle'		=>	$action,
		':id_contrainte'	=>$contrainte,
		':id_web_user'		=> $_SESSION['id_web_user'],
		':pj'				=>$fileList,
		':date_action'		=> date('Y-m-d H:i:s'),
	));
	return $pdoLitige->lastInsertId();
	// return $req->errorInfo();
}

function getPretxt($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT action_help.id,nom, pretxt FROM action_help LEFT JOIN action_contrainte ON id_contrainte=action_contrainte.id ORDER BY nom");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$listPretxt=getPretxt($pdoLitige);


function getHelpInfo($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT action_help.id,action_help.contrainte, nom, pretxt,contrainte_libelle, id_contrainte FROM action_help LEFT JOIN action_contrainte ON id_contrainte=action_contrainte.id WHERE action_help.id=:id");
	$req->execute(array(
		':id'		=>$_POST['pretxt']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// recupère le pale sav du mag pour action 4 : demande intervention sav
function getMagSav($pdoSav,$galec){
	$req=$pdoSav->prepare("SELECT sav FROM mag WHERE galec = :galec");
	$req->execute([
		':galec'		=>$galec
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}


if(isset($_POST['submit']))
{

// si une action préécrite est choisie, il faut vérifie si elle a des contraintes et si oui, faire l'action nécessaire

	if(isset($_FILES['incfile']['name'][0]) && empty($_FILES['incfile']['name'][0])){
		$allfilename="";
	}
	else
	{
		$uploadDir='..\..\..\upload\litiges\\';
		$uploaded=false;
		$allfilename="";
		$nbFiles=count($_FILES['incfile']['name']);
		for ($f=0; $f <$nbFiles ; $f++)
		{
			$filename=$_FILES['incfile']['name'][$f];
			$maxFileSize = 5 * 1024 * 1024; //5MB

			if($_FILES['incfile']['size'][$f] > $maxFileSize)
			{
				$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
			}
			else
			{
				// cryptage nom fichier
		 		// Get the fileextension
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				  // Get filename without extesion
				$filename_without_ext = basename($filename, '.'.$ext);
					// Generate new filename => ajout d'un timestamp au nom du fichier
				$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
				$uploaded=move_uploaded_file($_FILES['incfile']['tmp_name'][$f],$uploadDir.$filename );

			}
			if($uploaded==false)
			{
				$errors[]="impossible de télécharger le fichier";
			}
			else
			{

				$allfilename.=$filename .';';
			}
		}
	}
	// menu déroulante avec les actions préexistante et qui peuvant avoir des contrainte et/ou un texte préécrit
	if(!empty($_POST['pretxt']))
	{
		$help=getHelpInfo($pdoLitige);
	// si l'action pré-écrite à une contrainte, on l'execute
		if($help['id_contrainte'] ==NULL){
			$newAction=addAction($pdoLitige, $allfilename,$contrainte=null);
			if($newAction>0){
				header('Location:bt-action-add.php?id='.$_GET['id']);
			}
			else{
				$errors[]="Une erreur est survenue, impossible d'enregistrer votre action";
			}
		}

	// si contrainte
	// => pour la contrainte 4 demande d'inter sav, on a besoin de vérifier que le mag a bien un pôle SAV, si ce n'est pas le cas, on bloque le traitement
	// si pas de bloquage, on ajoute l'action avec son numéro de contrainte et on redirige vers la page contrainte qui fait le traitement approprié
		else{
			if($help['id_contrainte'] ==4){
				$galec=$fLitige['galec'];
				$sav=getMagSav($pdoSav,$galec);
			}
			if(isset($sav) && empty($sav)){
				$errors[]="Vous ne pouvez pas ajouter cette action, aucun pôle SAV n'a été renseigné pour ce magasin";
			}
			else
			{
				$newAction=addAction($pdoLitige, $allfilename, $help['id_contrainte']);
				if($newAction>0){
					header('Location:contrainte.php?contrainte='.$help['id_contrainte'].'&id='.$_GET['id'].'&action='.$newAction) ;

				}
				else
				{
					$errors[]="Une erreur est survenue, impossible d'enregistrer votre action";
				}

			}
		}
	}
	else
	{
		$newAction=addAction($pdoLitige, $allfilename);

		if($newAction>0)
		{
			header('Location:bt-action-add.php?id='.$_GET['id']);


		}
		else
		{
			$errors[]="Une erreur est survenue, impossible d'enregistrer votre action";
		}
	}

}





if(isset($_GET['success']))
{
	$success[]='action effectuée avec succès';
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
	<div class="row py-3">
		<div class="col">
			<p class="text-right"><a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a></p>
		</div>
	</div>
	<h1 class="text-main-blue pb-5 ">Dossier N° <?= $fLitige['dossier']?></h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col">
			<h2 class="khand">Dernières actions menées</h2>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table">
				<thead class="bg-kaki">
					<tr>
						<th>date</th>
						<th>Par</th>
						<th>Action</th>
						<th>Pièces jointes</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($actionList) && count($actionList)>0)
					{

						foreach ($actionList as $action)
						{
							if($action['pj']!='')
							{
								$pj=createFileLink($action['pj']);
							}
							else
							{
								$pj='';
							}

							echo '<tr>';
							echo'<td>'.$action['dateFr'].'</td>';
							echo'<td>'.$action['name'].'</td>';

							echo'<td>'.$action['libelle'].'</td>';
							echo'<td>'.$pj.'</td>';
							echo '</tr>';
						}

					}
					else
					{
						echo '<tr><td colspan="3">Aucune Action</td></tr>';
					}

					?>

				</tbody>
			</table>
		</div>

	</div>






	<div class="row mt-4">
		<div class="col">
			<h2 class="khand">Ajouter une action</h2>
		</div>
	</div>



	<div class="row">
		<div class="col">
			<!-- inside transporteurs -->
			<div class="row">
				<div class="col bg-kaki-light">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data" >
						<div class="row align-items-end p-3">
							<div class="col">
								<p class="heavy">Action existante :</p>
								<div class="form-group">
									<select name="pretxt" id="pretxt" class="form-control">
										<option value="">Sélectionnez une réponse préparée</option>
										<?php
										foreach($listPretxt as $pretxt)
										{
											if($pretxt['pretxt']==''){
												echo '<option value="'.$pretxt['id'].'">'.$pretxt['nom'].'</option>';

											}
											else{
												echo '<option value="'.$pretxt['id'].'">'.$pretxt['nom'].' ('. $pretxt['pretxt'].')</option>';
											}

										}


										?>
									</select>

								</div>




								<div class="form-group">
									<label for="action">Description de l'action :</label>
									<textarea type="text" class="form-control" row="10" name="action" id="msg"></textarea>
								</div>
								<div id="upload-zone">
									<label for='incfile'>Ajouter une pièce jointe : </label>
									<input type='file' class='form-control-file' id='incfile' name='incfile[]' multiple="" >
									<p id="p-add-more"><a id="add_more" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Envoyer d'autres fichiers</a></p>
									<div id="filelist"></div>
								</div>
							</div>
							<div class="col-auto">
								<button type="submit" id="submit_t" class="btn btn-kaki" name="submit"><i class="fas fa-plus-square pr-3"></i>Enregistrer</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<!-- RETOUR -->
	<div class="row my-5">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>


</div>

<script type="text/javascript">
	$(document).ready(function (){
		$('#pretxt').on('change',function(){
			var txt=$('#pretxt option:selected').text();
			splittxt=txt.split(' (');
			console.log();
			if(splittxt.length>1){
					var pretxt=splittxt[1].split(')');
				$('#msg').val(pretxt[0]);
			}else{
				$('#msg').val('');

			}



		});
		var fileName='';
		var fileList='';
    	var fileSizeMo=0;

		var totalFileSize=0;
		$('input[type="file"]').change(function(e){
			var nbFiles=e.target.files.length;
			for (var i = 0; i < nbFiles; i++)
			{
    		    // var fileName = e.target.files[0].name;
    		    fileName=e.target.files[i].name;
    		    fileSize=e.target.files[i].size;
    		    totalFileSize=totalFileSize+ fileSize;
    		    fileSizeMo=Math.round(fileSize/1000000);
    		    // 5120
    		    if(fileSize>10000000){
    		    	fileList += '<div class="text-red">Attention le fichier "' + fileName + '" est trop lourd (' +fileSizeMo + 'Mo au lieu du 10Mo maximum)</div>';

    		    }
    		    else
    		    {
    		  	  fileList += fileName + ' - ';
    		    }

    		}
 		   // console.log(fileList);
 		   titre='<p><span class="heavy">Fichier(s) : </span>'
 		   end='</p>';
 		   if(totalFileSize>10000000)
 		   {
 		   	totalFileSizeMo=Math.round(totalFileSize/1000000);
 		   		warning='<div class="text-red">Attention la taille totale des fichiers dépasse la taille autorisée de 10Mo (Poids total de vos fichiers : ' +totalFileSizeMo + 'Mo)<br></div>';
 		   }else{
 		   	warning=''
 		   }
 		   all=titre+warning+fileList+end;
 		   $('#filelist').append(all);
 		   fileList="";
 		});





	});
</script>





<?php

require '../view/_footer-bt.php';

?>