<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

$errors=[];
$success=[];


function getContactHelper($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM dial_help ORDER BY nom,pretxt");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$contactHelper=getContactHelper($pdoLitige);

function addHContact($pdoLitige)
{
	$msg=strip_tags($_POST['h-contact-form']);
	$msg=nl2br($msg);
	$req=$pdoLitige->prepare("INSERT INTO dial_help (pretxt, nom) VALUES(:pretxt, :nom) ");
	$req->execute(array(
		':pretxt'	=>$msg,
		':nom'	=>$_POST['name-form']
	));
	return $req->rowCount();
}

function getActionHelper($pdoLitige){
	$req=$pdoLitige->prepare("SELECT action_help.id as id,pretxt,id_contrainte,nom, contrainte_libelle FROM action_help LEFT JOIN action_contrainte ON action_help.id_contrainte= action_contrainte.id ORDER BY pretxt");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$actionHelper=getActionHelper($pdoLitige);

function getContrainte($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM action_contrainte ORDER BY contrainte_libelle");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$contrainteList=getContrainte($pdoLitige);

function addHAction($pdoLitige,$idcontrainte=NULL)
{

	if(!empty($_POST['contrainte-form']))
	{
		// si select est égal à new, on ne chnage pas la valeur d'idcontrainteététant donnée qu'on l'a passé en argument de la fonction via le last id de la requete précédente
		if($_POST['contrainte-form'] !='new')
		{
			$idcontrainte=$_POST['contrainte-form'];
		}
		$contrainte=1;
	}
	else
	{
		$contrainte=0;
		$idcontrainte=NULL;
	}
	$req=$pdoLitige->prepare("INSERT INTO action_help (nom,pretxt,contrainte,id_contrainte) VALUES(:nom,:pretxt,:contrainte,:id_contrainte)");
	$req->execute(array(
		':pretxt'			=>$_POST['h-action-form'],
		':nom'			=>$_POST['name-action'],
		':contrainte'			=>$contrainte,
		':id_contrainte'			=>$idcontrainte,
	));
	return $req->rowCount();
	// return $req->errorinfo();

}


function addnewContrainte($pdoLitige)
{
	$req=$pdoLitige->prepare("INSERT INTO action_contrainte(contrainte_libelle) VALUES (:contrainte_libelle)");
	$req->execute(array(
		':contrainte_libelle'	=>$_POST['contrainte-new-form']
	));
	return $pdoLitige->lastInsertId();
	// return $req->errorInfo();

}



if(isset($_POST['submit-contact']))
{
	$row=addHContact($pdoLitige);
	if($row==1)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
	else
	{
		$errors[]="Une erreur est survenue. Impossible d'enregistrer dans la base de donnée";
	}
}


if(isset($_POST['submit-action']))
{
//  3 cas
//  - action simple
//  - action + contrainte existante
//  - action + contrainte à créer
	if(!isset($_POST['contrainte-new-form']))
	{
		//  - action simple
		//  - action + contrainte existante
		$row=addHAction($pdoLitige);
		if($row==1)
		{
			$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
			header($loc);
		}
		else
		{
			$errors[]="Une erreur est survenue. Impossible d'enregistrer dans la base de donnée 1";


		}

	}
	else
	{
		$lastInsertId=addnewContrainte($pdoLitige);

		if($lastInsertId>0)
		{
			$row=addHAction($pdoLitige,$lastInsertId);
			if($row==1)
			{
				$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
				header($loc);
			}
			else
			{
				$errors[]="Une erreur est survenue. Impossible d'enregistrer dans la base de donnée 2";
			}
		}
		else
		{
				$errors[]="Une erreur est survenue. Impossible d'enregistrer dans la base de donnée 3";

		}
	}



}





if(isset($_GET['success']))
{
	$success[]="Votre texte a bien été ajouté à la base de donnée";
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
	<h1 class="text-main-blue py-5 ">Exploitation - textes préparés</h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>


	<h3 class="text-main-blue text-center pb-3" id="statut">Module contact</h3>
	<div class="row">
		<div class="col">
			<p class="text-blue heavy bigger">Textes existants : </p>

		</div>
	</div>

	<div class="row">
		<div class="col-1"></div>
		<div class="col">
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>Nom</th>
						<th>Texte</th>
						<th class="text-center">Supprimer</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($contactHelper as $contact)
					{
						echo '<tr>';
						echo'<td>'.$contact['nom'].'</td>';
						echo'<td>'.$contact['pretxt'].'</td>';
						echo'<td class="text-center"><a href="ex-delete.php?table=dial_help&id='.$contact['id'].'"><i class="fas fa-trash-alt"></i></a></td>';
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="col-1"></div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<p class="text-blue heavy bigger">Ajouter un texte : </p>
			<div class="row">
				<div class="col-1"></div>

				<div class="col">

					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
						<div class="form-group">
							<label>Nom : </label>
							<input type="text" class="form-control" name="name-form" required>
						</div>

						<div class="form-group">
							<label>Texte :</label>
							<textarea class="form-control" name="h-contact-form"></textarea>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="submit-contact"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>

				<div class="col-1"></div>
			</div>
		</div>
	</div>

	<!-- module action -->

	<h3 class="text-main-blue text-center pb-3" id="statut">Module action</h3>
	<div class="row">
		<div class="col">
			<p class="text-blue heavy bigger">Actions existantes : </p>

		</div>
	</div>

	<div class="row">
		<div class="col-1"></div>
		<div class="col">
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>Nom</th>
						<th>Actions</th>
						<th>Contrainte</th>
						<th class="text-center">Supprimer</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($actionHelper as $action)
					{
						echo '<tr>';
						echo'<td>'.$action['nom'].'</td>';
						echo'<td>'.$action['pretxt'].'</td>';
						echo'<td>'.$action['contrainte_libelle'].'</td>';
						echo'<td class="text-center"><a href="ex-delete.php?table=action_help&id='.$action['id'].'"><i class="fas fa-trash-alt"></i></a></td>';
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="col-1"></div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<p class="text-blue heavy bigger">Ajouter une action : </p>
			<div class="row">
				<div class="col-1"></div>
				<div class="col">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
						<div class="form-group">
							<label>Nom : </label>
							<input type="text" class="form-control" name="name-action" required>
						</div>
						<div class="form-group">
							<label>Action :</label>
							<input type="text" name="h-action-form" class="form-control">
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Choisir une tâche qui découle de cette action si besoin</label>
									<select class="form-control" id="new_contrainte" name="contrainte-form">
										<option value="">Sans tâche conjointe</option>
										<option value="new">Nouvelle tâche conjointe</option>
										<?php
										foreach ($contrainteList as $contrainte)
										{
											echo '<option value="'.$contrainte['id'].'">'.$contrainte['contrainte_libelle'].'</option>'	;
										}


										?>
									</select>
								</div>
							</div>
							<div class="col" id="new_input">


							</div>

						</div>



						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="submit-action"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>

				<div class="col-1"></div>
			</div>
		</div>
	</div>







	<div class="row">
					<p class="text-center"><a href="exploit-ltg.php" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-left pr-3"></i>Retour</a></p>

	</div>
</div>



<script type="text/javascript">
	$(document).ready(function(){
		var newInput='<div class="form-group"><label>Tâche conjointe à créer :</label><input type="text" name="contrainte-new-form" class="form-control"></div>';
		$('#new_contrainte').click(function() {
			var value = $("#new_contrainte option:selected").val();
			if(value=="new")
			{
				$('#new_input').append(newInput);
			}
			else
			{
				$('#new_input').empty();
			}
		});
	});
</script>


<?php

require '../view/_footer-bt.php';

?>