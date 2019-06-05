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
	$req=$pdoLitige->prepare("SELECT dossiers.id as id,details.id as detail_id,details.dossier,palette,facture, date_facture,DATE_FORMAT(date_facture,'%d-%m-%Y')as datefac,article, ean, dossier_gessica, descr,qte_cde,tarif, fournisseur,details.box_tete, details.box_art FROM dossiers LEFT JOIN details ON dossiers.id=details.id_dossier WHERE dossiers.id= :id");
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
// pour les vols, il faut ajouter les GESSICA.PoidsBrutUV et GESSICA.PoidsBrutUL
function updateDetail($pdoLitige,$reclamation,$qteLitige,$id, $pj,$ean, $valoLig){

	$req=$pdoLitige->prepare("UPDATE details SET id_reclamation = :reclamation, qte_litige= :qte_litige, pj= :pj, inversion= :ean, valo_line= :valo_line WHERE id= :id");
	$req->execute(array(
		':reclamation' =>$reclamation,
		':qte_litige'	=>$qteLitige,
		':id'			=>$id,
		':pj'			=>$pj,
		':ean'			=>$ean,
		':valo_line'			=>$valoLig,
	));
	return $req->rowCount();
	// return $req->errorInfo();
}
function updateDetailInversion($pdoLitige,$reclamation,$qteLitige,$id, $pj,$ean,$invArticle,$invDescr,$tarifUv,$invFournisseur, $invQte, $valoLig)
{
	$req=$pdoLitige->prepare("UPDATE details SET id_reclamation = :reclamation, qte_litige= :qte_litige, pj= :pj, inversion= :ean, inv_article= :inv_article, inv_descr=:inv_descr,inv_tarif=:inv_tarif, inv_fournisseur=:inv_fournisseur, inv_qte=:inv_qte, valo_line= :valo_line   WHERE id= :id");
	$req->execute(array(
		':reclamation' =>$reclamation,
		':qte_litige'	=>$qteLitige,
		':id'			=>$id,
		':pj'			=>$pj,
		':ean'			=>$ean,
		':inv_article'		=>$invArticle,
		':inv_descr'		=>$invDescr,
		':inv_tarif'		=>$tarifUv,
		':inv_fournisseur'		=>$invFournisseur,
		':inv_qte'		=>$invQte,
		':valo_line'			=>$valoLig,

	));
	return $req->rowCount();
	// return $req->errorInfo();
}

// 3 pour 1er commentaire
function addDial($pdoLitige)
{
	$com=strip_tags($_POST['form_com']);
	$com=nl2br($com);
	$req=$pdoLitige->prepare("INSERT INTO dial(id_dossier,date_saisie,msg,id_web_user,mag) VALUES (:id_dossier,:date_saisie,:msg,:id_web_user,:mag)");
	$req->execute(array(
		':id_dossier' =>$_GET['id'],
		':date_saisie' =>date('Y-m-d H:i:s'),
		':msg' =>$com,
		':id_web_user' =>$_SESSION['id_web_user'],
		':mag' =>3,

	));
	return $req->rowCount();
}

$fMotif=getReclamation($pdoLitige);

function getProdInversion($pdoQlik,$ean)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE gencod= :inversion ORDER BY date_mvt DESC");
	$req->execute(array(
		':inversion'	=>$ean
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


if(isset($_GET['id']))
{
	$fLitige=getLitige($pdoLitige);
}





$foreachErrors=[];
$foreachSuccess=[];

$errors=[];
$success=[];
$newData=0;
$uploadDir= '..\..\..\upload\litiges\\';
$valoTotal=0;

if(isset($_POST['submit']))
{
// on récupère le nombre d'article pour pouvoir boucler sur tableau de champ de formulaire
	$nbArticle=count($_POST['form_id']);
	for ($i=0; $i <$nbArticle ; $i++)
	{
		//------------------------------------------------------
		//			traitement de chq "sous formulaire
		//------------------------------------------------------
		//nom de la clé de la global FILES
		$fileKey=$i+1;
		$fileKey="form_file" . $fileKey;
		// 1- verif si fichier joint (tableau complet pour 1 article)
		if(isset($_FILES[$fileKey]['name'][0]) && empty($_FILES[$fileKey]['name'][0]))
		{
			$allfilename="";
		}
		else
		{
			// fichier => on boucle
			$allfilename="";
			$nbFiles=count($_FILES[$fileKey]['name']);
			for ($f=0; $f <$nbFiles ; $f++)
			{
				$filename=$_FILES[$fileKey]['name'][$f];
				$maxFileSize = 5 * 1024 * 1024; //5MB

				if($_FILES[$fileKey]['size'][$f] > $maxFileSize)
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
					$uploaded=move_uploaded_file($_FILES[$fileKey]['tmp_name'][$f],$uploadDir.$filename );
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
			// cas d'une inversion de réf
			if($_POST['form_motif'][$i]==5)
			{
				// si formulaire rempli
				if(!empty($_POST['form_autre_qte'][$i]) && !empty($_POST['form_autre'][$i]))
				{
					// cas de l'inversion de référence (5)
					$ean=$_POST['form_autre'][$i];
					$invQte=$_POST['form_autre_qte'][$i];
				// recup info du produit reçu à la place
					$listProdInv=getProdInversion($pdoQlik,$ean);
				// si on trouve la réf qui a été livrée à la place
					if(count($listProdInv)>1)
					{
					// on cherche en 1er dans le 1000
						foreach ($listProdInv as $prodInv)
						{
							if($prodInv['dossier']==1000)
							{
								$invArticle=$prodInv['article'];
								$invDescr=$prodInv['libelle'];
								$invTarif=$prodInv['tarif'];
								$invFournisseur=$prodInv['fournisseur'];
								$qt_qlik=$prodInv['qte'];
								$invDossier=$prodInv['dossier'];
								$tarifUv=$invTarif/$qt_qlik;
								break;
							}
						// si trouvé en dehors du 1000
							elseif(!empty($prodInv['article']) && !empty($prodInv['libelle']) && !empty($prodInv['tarif']) && !empty($prodInv['fournisseur']) && !empty($prodInv['qte']))
							{
								$invArticle=$prodInv['article'];
								$invDescr=$prodInv['libelle'];
								$invTarif=$prodInv['tarif'];
								$invFournisseur=$prodInv['fournisseur'];
								$qt_qlik=$prodInv['qte'];
								$invDossier=$prodInv['dossier'];
								$tarifUv=$invTarif/$qt_qlik;
								break;
							}
						// si pas trouvé
							else
							{
								$invArticle=$invDescr=$invTarif=$invFournisseur=$qt_qlik=$invDossier=$tarifUv=NULL;

							}
						}
						$valoLig=($fLitige[$i]['tarif']/$fLitige[$i]['qte_cde']*$_POST['form_qte'][$i]) - ($tarifUv*$invQte);

					}
					else
					{
					// ean non trouvé :
						$invArticle=$invDescr=$invTarif=$invFournisseur=$qt_qlik=$invDossier=$tarifUv=$valoLig=NULL;
					}
				// article trouvé ou non , on met à jour la db avec des champ null si rien
					$do=updateDetailInversion($pdoLitige,$_POST['form_motif'][$i], $_POST['form_qte'][$i],$_POST['form_id'][$i],$allfilename,$ean,$invArticle,$invDescr,$tarifUv,$invFournisseur, $invQte, $valoLig);
				// echo '1 do '.$do;
				}
				else
				{
					$errors[]='merci de renseigner l\'EAN reçu et la quantité';
				}

			}
			else
			{


				// cas général (pas d'inversion de produit)
				// exéédent :
				if($_POST['form_motif'][$i]==6)
				{

					$valoLig= -(($fLitige[$i]['tarif']/$fLitige[$i]['qte_cde'])*$_POST['form_qte'][$i]);
				}
				else{
					$valoLig=($fLitige[$i]['tarif']/$fLitige[$i]['qte_cde'])*$_POST['form_qte'][$i];

				}
				$ean="";
				$do=updateDetail($pdoLitige,$_POST['form_motif'][$i], $_POST['form_qte'][$i],$_POST['form_id'][$i],$allfilename,$ean, $valoLig);


			}

		}



		if(isset($do) && $do>0)
		{
			$foreachSuccess[]="success";

		}
		else
		{
			$foreachErrors[]="erreurs";

		}
	}

	if(count($foreachErrors)==0)
	{
		$addCom=addDial($pdoLitige);
		if($addCom>0)
		{
			header('Location:recap-declaration.php?id='.$_GET['id']);
		}
		else
		{
			$errors[]="une erreur est survenue pendant l'enregistrement";

		}
	}
	else
	{


		$errors[]="une erreur est survenue pendant l'enregistrement";
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
						<!-- ./row -->
						<?php
						$subForm=1;
						foreach ($fLitige as $litige)
						{
							if($litige['box_tete']==1)
							{
								// on nb'affiche pas les tête de box
							}
							else
							{


							// info produit
							echo '<div class="row yellow-box">';
							echo '<div class="col">';
							echo '<h5 class="khand heavy spacy  pt-3 ">Produit : '.$litige['descr'].' - Art. : '.$litige['article'].'</h5>';
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
							echo '<span class="libelle">Quantité : </span>';
							echo '<span>'.$litige['qte_cde'].'</span>';

							echo '<span class="libelle pl-5">Palette : </span>';
							echo '<span>'.$litige['palette'].'</span>';
							echo '<span class="libelle pl-5">Facture : </span>';
							echo '<span>'.$litige['facture'].'</span>';
							echo '<span class="libelle pl-5">Date facture : </span>';
							echo '<span>'.$litige['datefac'].'</span>';
							echo '</div>';
							echo '</div>';
							// fin info article, fin container :
							echo '</div></div>';
							// debut form
							//ajout row pour border
							echo '<div class="row border pt-3 mb-5">';
							echo '<div class="col">';

							echo '<div class="row">';
							echo '<div class="col-4">';
							echo '<p class="khand heavy">Motif de la réclamation :</p>';
							echo '<div class="form-group">';
							echo '<select class="form-control" name="form_motif[]"  id="motif'.$litige['detail_id'].'"required>';
							echo '<option value="">Sélectionnez</option>';
							foreach ($fMotif as $motif)
							{
								echo '<option value="'.$motif['id'].'">'.$motif['reclamation'].'</option>';

							}
							echo '</select>';
							echo '</div>';
							echo '</div>';
							echo '<div class="col-3">';
							echo '<p class="khand heavy">Quantité concernée en UV : ';
							echo '</p>';
							echo '<div class="form-group">';
							echo '<input type="text" class="form-control" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" name="form_qte[]" required>';
							echo '<input type="hidden" value="'.$litige['detail_id'].'" name="form_id[]">';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '<div class="hidden" id="toggle'.$litige['detail_id'].'">';

							echo '<div class="row">';
							echo '<div class="col-4">';
							echo '<p class="khand heavy">Ean article reçu : ';
							echo '</p>';
							echo '<div class="form-group">';
							echo '<input type="text" class="form-control" name="form_autre[]" >';
							echo '</div>';
							echo '</div>';
							echo '<div class="col-3">';
							echo '<p class="khand heavy">Quantité UV reçue : ';
							echo '</p>';
							echo '<div class="form-group">';
							echo '<input type="text" class="form-control" name="form_autre_qte[]">';
							echo '</div>';
							echo '</div>';
							echo '<div class="col"></div>';
							echo'</div>';
							echo '</div>';

							echo '<p><span class="khand heavy">Photos /vidéos :</span><br>';
							echo '<span class="circle-icon"><i class="fas fa-lightbulb"></i></span><span class="text-reddish pl-3 heavy tighter">Maintenez la touche CTRL enfoncée pour sélectionner plusieurs fichiers</span></p>';
							echo '<div class="row">';
							echo '<div class="col-auto">';
							echo '<div class="form-group">';
							echo '<label class="btn btn-upload btn-file text-center"><input name="form_file'.$subForm.'[]" id="form_file'.$litige['detail_id'].'" type="file" multiple="" class="form-control-file"><i class="fas fa-file-image pr-3"></i>Sélectionner</label>';
							echo '</div>';
							echo '</div>';
														// fileanme
							echo '<div class="col" id="'.$litige['detail_id'].'">';

							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';

								}
						//fin du if  box




							// fin de row et col avec le border
							// echo '</div>';
							// echo '</div>';
							$subForm++;
						}
						?>
						<p class="khand heavy bigger">Commentaires : </p>

						<div class="form-group">
							<textarea class="form-control" name="form_com"></textarea>
						</div>
					</div>
				</div>


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
		var fileName='';
		var fileList='';
		$('input[type="file"]').change(function(e){
			var nbFiles=e.target.files.length;
			var inputFileId=e.target.id;
			lastStrg=inputFileId.length;
			inputFileId=inputFileId.substring(9,lastStrg);
			inputFileId="#"+inputFileId;
			console.log(inputFileId);
			for (var i = 0; i < nbFiles; i++)
			{
            // var fileName = e.target.files[0].name;
            fileName=e.target.files[i].name;
            console.log(fileName);

            fileList += fileName + ' - ';
        }
        // console.log(fileList);
        titre='<p><span class="text-reddish heavy">Fichier(s) : </span>'
        end='</p>';
        all=titre+fileList+end;
        $(inputFileId).append(all);
        fileList="";
    });

		$('select').on('change',function(e){
        // $("select#motif7").change(function(){
        	var selectId=e.target.id;
        	sizeStrg=selectId.length;
        	selectId=selectId.substring(5,sizeStrg);
        	console.log( selectId );
        	var toShow="#toggle"+selectId;
				// var centrale = ;
				if($(this).val()==5)
				{
					$(toShow).attr('class','show');
				}
				else
				{
					$(toShow).attr('class','hidden');

				}

  	// 		var toShow='mot'
  			// $("#td_id").toggleClass('change_me newClass');
  		});




	});



</script>


<?php

require '../view/_footer-bt.php';

?>