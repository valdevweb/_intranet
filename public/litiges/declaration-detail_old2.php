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
// id litige passé par get['id']
//------------------------------------------------------
//			INFOS
//------------------------------------------------------
/*
On peut avoir plisuers articles dans une seule décalration de litige
On doit donc faire une boucle pour récupérer tous les articles
Les champs de formulaires sont donc répétés autant de fois qu'il y a d'article
Pour parcourir les postes, il suffit 'ajouter un index pour chq ""sous formulaire. on a des resultat du genre :
POST[champ]	=>[0]
			=>[1]
la plupart des champs post ne pouvant pas être vides, on peut les parcourir sans problème. Ce n'est
pas le cas avec la gloable file car, rien ne force l'utilisateur à mettre une pièce jointe
Problème : comment relier les fichiers uploadé au produit concerné ?
Solution : ajout d'un index dans le nom de la varaible file qui s'incrémente à chq boucle
On commence à 1 car le 0 n'est pas pris en compte dans le name
 */







//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getLitige($pdoLitige){
	$req=$pdoLitige->prepare("SELECT dossiers.id as id,details.id as detail_id,details.dossier,palette,facture, date_facture,DATE_FORMAT(date_facture,'%d-%m-%Y')as datefac,article, ean, dossier_gessica, descr,qte_cde,tarif,fournisseur FROM dossiers LEFT JOIN details ON dossiers.id=details.id_dossier WHERE dossiers.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getReclamation($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM reclamation WHERE mask=0 ORDER BY reclamation");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function addDial($pdoLitige,$filename)
{
	$com=strip_tags($_POST['form_com']);
	$com=nl2br($com);
	$req=$pdoLitige->prepare("INSERT INTO dial(id_dossier,date_saisie,msg,id_web_user,filename,mag) VALUES (:id_dossier,:date_saisie,:msg,:id_web_user,:filename,:mag)");
	$req->execute(array(
		':id_dossier' =>$_GET['id'],
		':date_saisie' =>date('Y-m-d H:i:s'),
		':msg' =>$com,
		':id_web_user' =>$_SESSION['id_web_user'],
		':filename' =>$filename,
		':mag' =>1,

	));
	return $req->rowCount();
}

function majDossier($pdoLitige, $inversion)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET id_reclamation= :id_reclamation, inversion=:inversion WHERE id= :id");
	$req->execute(array(
		':id_reclamation'		=>$_POST['form_motif'],
		':inversion'			=>$inversion,
		':id'					=>$_GET['id']
	));
	return $req->rowCount();

}


$fMotif=getReclamation($pdoLitige);

if(isset($_GET['id']))
{
	$fLitige=getLitige($pdoLitige);


}


$errors=[];
$success=[];
$newData=0;
$uploadDir= '..\..\..\upload\litiges\\';

if(isset($_POST['submit']))
{
		// 1- verif si fichier joint (tableau complet pour 1 article)
		if(empty($_FILES['form_file']['name'][0]))
		{
			$allfilename="";
		}
		else
		{
			// fichier => on boucle
			$allfilename="";
			$nbFiles=count($_FILES['form_file']['name']);
			for ($f=0; $f <$nbFiles ; $f++)
			{
				$filename=$_FILES['form_file']['name'][$f];
				$maxFileSize = 5 * 1024 * 1024; //5MB

				if($_FILES['form_file']['size'][$f] > $maxFileSize)
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
					$uploaded=move_uploaded_file($_FILES['form_file']['tmp_name'][$f],$uploadDir.$filename );
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

		if(count($errors)==0)
		{
			if(isset($_POST['form_autre']) && !empty($_POST['form_autre']))
			{
				$inversion=$_POST['form_autre'];
			}
			else
			{
				$inversion="";
			}

			$updateDossier=majDossier($pdoLitige, $inversion);


			if($updateDossier>0)
			{
				$doaddDial=addDial($pdoLitige,$allfilename);
			}
			if($doaddDial>0)
			{
			header('Location:recap-declaration.php?id='.$_GET['id']);

			}
			else
			{
				$errors[]="une erreur est survenue pendant l'enregistrement";
			}

		}

}

// on va utiliser l'id pour enregistrer les produits sélectionnés sachant qu'à chaque import de la base, il changera

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
	<h1 class="text-main-blue py-5 ">Dossier litige n° <?=$fLitige[0]['dossier']?></h1>
	<!-- start row -->
	<div class="row no-gutters">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data" class="light-shadow border-top-big">
				<div class="row">
					<div class="col p-5">
						<h5 class="khand text-center heavy text-red pb-3">Détail des produits : </h5>


						<!-- ./row -->

						<?php
						$subForm=1;
						foreach ($fLitige as $litige)
						{
							// info produit titre
							echo '<div class="row yellow-box">';
							echo '<div class="col">';
							echo'<h5 class="khand heavy spacy  pt-3 ">'.$litige['descr'].' - Art. : '.$litige['article'].'</h5>';
							echo '</div></div>';


							echo '<div class="row border">';
							echo '<div class="col ">';
							// interieur détail
							echo '<div class="row no-gutters">';
							echo '<div class="col ">';
							echo '<span class="libelle">Fournisseur : </span>';
							echo '<span> '.$litige['fournisseur'].'</span>';
							echo '<span class="libelle pl-5"> EAN : </span>';
							echo '<span>'.$litige['ean'].'</span>';
							echo '<span class="libelle pl-5"> Dossier : </span>';
							echo '<span>'.$litige['dossier_gessica'].'</span>';
							echo '</div>';
							echo '</div>';
							// fin de ligne 2
							echo '<div class="row pb-3">';
							echo '<div class="col">';
							echo '<span class="libelle ">Palette : </span>';
							echo '<span>'.$litige['palette'].'</span>';
							echo '<span class="libelle pl-5">Facture : </span>';
							echo '<span>'.$litige['facture'].'</span>';
							echo '<span class="libelle pl-5">Date facture : </span>';
							echo '<span>'.$litige['datefac'].'</span>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							// fin info article, fin yellow :


							$subForm++;
						}
						?>
						<!-- debut form -->
						<!-- ajout row pour border -->
						<div class="row border pt-3 mb-5 mt-3">
							<div class="col">
								<h5 class="khand text-center heavy pb-3 text-red">Informations sur le litige</h5>

								<div class="row">
									<div class="col-4">

										<p class="khand heavy">Motif de la réclamation :</p>
										<div class="form-group">
											<select class="form-control" name="form_motif"  id="motif" required>
												<option value="">Sélectionnez</option>
												<?php
												foreach ($fMotif as $motif)
												{
													echo '<option value="'.$motif['id'].'">'.$motif['reclamation'].'</option>';

												}
												?>
											</select>
										</div>
									</div>
							<!-- 		<div class="col-3">
										<p class="khand heavy">Quantité concernée :
										</p>
										<div class="form-group">
											<input type="text" class="form-control" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" name="form_qte" required>
										</div>
									</div> -->
									<div class="col">
										<div class="hidden" id="toggle">
											<p class="khand heavy">Ean article reçu :
											</p>
											<div class="form-group">
												<input type="text" class="form-control" name="form_autre">
											</div>
										</div>
									</div>
									<div class="col-3"></div>
								</div>
								<p class="khand heavy">Commentaires : </p>
								<div class="form-group">
									<textarea class="form-control" name="form_com" required></textarea>
								</div>
								<p><span class="khand heavy">Photos /vidéos :</span><br>
									<span class="circle-icon"><i class="fas fa-lightbulb"></i></span><span class="text-reddish pl-3 heavy tighter">Maintenez la touche CTRL enfoncée pour sélectionner plusieurs fichiers</span></p>
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<label class="btn btn-upload btn-file text-center"><input name="form_file[]" id="form_file" type="file" multiple="" class="form-control-file"><i class="fas fa-file-image pr-3"></i>Sélectionner</label>
											</div>
										</div>
										<!-- file anme -->
										<div class="col" id="filelist">

										</div>
									</div>
									<!-- fin de row et col avec le border -->
								</div>
							</div>

							<p class="pt-5 text-right upper"><button class="btn btn-primary" type="submit" name="submit">Envoyer</button></p>

						</div>
					</div>


				</form>
			</div>
			<div class="col-lg-1 col-xxl-2"></div>
		</div>
		<!-- ./row -->

	</div>
	<script type="text/javascript">
	$(document).ready(function(){
			// ----------------------------------
			// upload
			// ----------------------------------
			var fileName='';
			var fileList='';
			$('input[type="file"]').change(function(e){
				var nbFiles=e.target.files.length;
				for (var i = 0; i < nbFiles; i++)
				{
					fileName=e.target.files[i].name;
					fileList += fileName + ' - ';
				}
				titre='<p><span class="heavy">Fichier(s) : </span>';
				end='</p>';
				all=titre+fileList+end;
				$('#filelist').append(all);
				fileList="";
	});
			// ----------------------------------
			// select => montre input hidden
			// ----------------------------------

			$('select').on('change',function(e){
        	// var selectId=e.target.id;
        	// sizeStrg=selectId.length;
        	// selectId=selectId.substring(5,sizeStrg);

        	var toShow="#toggle";
				// var centrale = ;
				if($(this).val()==5)
				{
					$(toShow).attr('class','show');
				}
				else
				{
					$(toShow).attr('class','hidden');
				}
  		});

	});



		</script>


		<?php

		require '../view/_footer-bt.php';

		?>