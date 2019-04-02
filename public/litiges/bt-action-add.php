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
	$req=$pdoLitige->prepare("SELECT libelle, action.id_web_user, DATE_FORMAT(date_action, '%d-%m-%Y')as dateFr, concat(prenom, ' ', nom) as name FROM action LEFT JOIN btlec.btlec ON action.id_web_user=btlec.btlec.id_webuser WHERE action.id_dossier= :id ORDER BY date_action");
	$req->execute(array(
		':id'		=>$_GET['id']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$actionList=getAction($pdoLitige);

function addAction($pdoLitige)
{
	$action=strip_tags($_POST['action']);
	$action=nl2br($action);
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier,libelle,id_web_user,date_action) VALUES (:id_dossier,:libelle,:id_web_user,:date_action)");
	$req->execute(array(
		':id_dossier'		=>	$_GET['id'],
		':libelle'		=>	$action,
		':id_web_user'		=> $_SESSION['id_web_user'],
		':date_action'		=> date('Y-m-d H:i:s'),
	));
	return $req->rowCount();
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
if(isset($_POST['submit']))
{

// si une action préécrite est choisie, il faut vérifie si elle a des contraintes et si oui, faire l'action nécessaire

	if(!empty($_POST['pretxt']))
	{
		echo "non vide";
		$help=getHelpInfo($pdoLitige);
	// si l'action pré-écrite à une contrainte, on l'execute
		if($help['id_contrainte'] ==NULL)
		{
			$newAction=addAction($pdoLitige);

			if($newAction>0)
			{
				header('Location:bt-action-add.php?id='.$_GET['id']);

			}
			else
			{
				$errors[]="Une erreur est survenue, impossible d'enregistrer votre action";
			}
		}
		else
		{
			$newAction=addAction($pdoLitige);

			if($newAction>0)
			{
				header('Location:contrainte.php?contrainte='.$help['id_contrainte'].'&id='.$_GET['id']);

			}
			else
			{
				$errors[]="Une erreur est survenue, impossible d'enregistrer votre action";
			}

		}


	}
	else
	{
		$newAction=addAction($pdoLitige);

		if($newAction>0)
		{
			header('Location:bt-action-add.php?id='.$_GET['id']);

		}
		else
		{
			$errors[]="Une erreur est survenue, impossible d'enregistrer votre action";
		}
	}

/*

traitement simple sans contrainte

*/
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
	<h1 class="text-main-blue py-5 ">Dossier N° <?= $fLitige['dossier']?></h1>
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
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($actionList) && count($actionList)>0)
					{
						foreach ($actionList as $action)
						{

							echo '<tr>';
							echo'<td>'.$action['dateFr'].'</td>';
							echo'<td>'.$action['name'].'</td>';

							echo'<td>'.$action['libelle'].'</td>';
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
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >
						<div class="row align-items-end p-3">
							<div class="col">
								<p class="heavy">Action existante :</p>
								<div class="form-group">
									<select name="pretxt" id="pretxt" class="form-control">
										<option value="">Sélectionnez une réponse préparée</option>
										<?php
										foreach($listPretxt as $pretxt)
										{
											echo '<option value="'.$pretxt['id'].'">'.$pretxt['nom'].' ('. $pretxt['pretxt'].'</option>';

										}


										?>
									</select>

								</div>




								<div class="form-group">
									<label for="action">Description de l'action :</label>
									<textarea type="text" class="form-control" row="10" name="action" id="msg"></textarea>
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
			<p class="text-center"><a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a></p>


		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>


</div>

<script type="text/javascript">

	$(document).ready(function (){

		$('#pretxt').on('change',function(){
			var txt=$('#pretxt option:selected').text();
			// var pretxt=txt.split(' (');
			var pretxt=txt.split(' (');
			// var bjr="Bonjour,\n\n";
			// var cdlt="\n\n"+"Cordialement,\n";

 						// console.log(name);
 						$('#msg').val(pretxt[1]);
 					});




	});

</script>





<?php

require '../view/_footer-bt.php';

?>