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
Pour parcourir les posts, il suffit 'ajouter un index pour chq ""sous formulaire. on a des resultat du genre :
POST[champ]	=>[0]
			=>[1]
la plupart des champs post ne pouvant pas être vides, on peut les parcourir sans problème. Ce n'est
pas le cas avec la gloable file car, rien ne force l'utilisateur à mettre une pièce jointe
Problème : comment relier les fichiers uploadé au produit concerné ?
Solution : ajout d'un index dans le nom de la variable file qui s'incrémente à chq boucle
On commence à 1 car le 0 n'est pas pris en compte dans le name
 */


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getLitigeTemp($pdoLitige){
	$req=$pdoLitige->prepare("SELECT dossiers_temp.id as id,details_temp.id as detail_id,details_temp.dossier,palette,facture, date_facture,DATE_FORMAT(date_facture,'%d-%m-%Y')as datefac,article, ean, dossier_gessica, descr,qte_cde,tarif, fournisseur,details_temp.box_tete, details_temp.box_art FROM dossiers_temp LEFT JOIN details_temp ON dossiers_temp.id=details_temp.id_dossier WHERE dossiers_temp.id= :id");
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

	$req=$pdoLitige->prepare("UPDATE details_temp SET id_reclamation = :reclamation, qte_litige= :qte_litige, pj= :pj, inversion= :ean, valo_line= :valo_line WHERE id= :id");
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
	$req=$pdoLitige->prepare("UPDATE details_temp SET id_reclamation = :reclamation, qte_litige= :qte_litige, pj= :pj, inversion= :ean, inv_article= :inv_article, inv_descr=:inv_descr,inv_tarif=:inv_tarif, inv_fournisseur=:inv_fournisseur, inv_qte=:inv_qte, valo_line= :valo_line   WHERE id= :id");
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
	$req=$pdoLitige->prepare("INSERT INTO dial_temp(id_dossier,date_saisie,msg,id_web_user,mag) VALUES (:id_dossier,:date_saisie,:msg,:id_web_user,:mag)");
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
	$req=$pdoQlik->prepare("SELECT id,`GESSICA.CodeArticle` as article,`GESSICA.CodeDossier` as dossier,`GESSICA.LibelleArticle` as libelle,`GESSICA.PFNP` AS pfnp,`GESSICA.PCB` as pcb,`GESSICA.NomFournisseur` as fournisseur FROM basearticles WHERE `GESSICA.Gencod` LIKE :inversion ORDER BY `GESSICA.CodeDossier`");
	$req->execute(array(
		':inversion'	=>'%'.$ean.'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


if(isset($_GET['id']))
{
	$fLitige=getLitigeTemp($pdoLitige);
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
		if(count($errors)==0){
			// cas d'une inversion de réf
			if($_POST['form_motif'][$i]==5 || ($_POST['form_motif'][$i]==8 && (!empty($_POST['form_autre_qte'][$i]) && !empty($_POST['form_autre'][$i]))) )
			{
				// si formulaire rempli
				if(!empty($_POST['form_autre_qte'][$i]) && !empty($_POST['form_autre'][$i]))
				{
					// cas de l'inversion de référence (5)
					$ean=$_POST['form_autre'][$i];
					$invQte=$_POST['form_autre_qte'][$i];
				// recup info du produit reçu à la place => base statsventelitige !!! or ça devrait être la base article
					$listProdInv=getProdInversion($pdoQlik,$ean);

				// si on trouve la réf qui a été livrée à la place
					if(count($listProdInv)>=1)
					{
					// on cherche en 1er dans le 1000
						foreach ($listProdInv as $prodInv)
						{
							if($prodInv['dossier']==1000)
							{
								$invArticle=$prodInv['article'];
								$invDescr=$prodInv['libelle'];
								$invTarif=$prodInv['pfnp'];
								$invFournisseur=$prodInv['fournisseur'];
								$pcb=$prodInv['pcb'];
								$invDossier=$prodInv['dossier'];
								$tarifUv=$invTarif;
								break;
							}
						// si trouvé en dehors du 1000
							elseif(!empty($prodInv['article']))
							{
								$invArticle=$prodInv['article'];
								$invDescr=$prodInv['libelle'];
								$invTarif=$prodInv['pfnp'];
								$invFournisseur=$prodInv['fournisseur'];
								$pcb=$prodInv['pcb'];
								$invDossier=$prodInv['dossier'];
								$tarifUv=$invTarif;
								break;
							}
						// si pas trouvé
							else
							{
								$invArticle=$invDescr=$invTarif=$invFournisseur=$pcb=$invDossier=$tarifUv=NULL;

							}
						}
						$valoLig=($fLitige[$i]['tarif']/$fLitige[$i]['qte_cde']*$_POST['form_qte'][$i]) - ($tarifUv*$invQte);

					}
					else
					{
					// ean non trouvé :
						$invArticle=$invDescr=$invTarif=$invFournisseur=$pcb=$invDossier=$tarifUv=$valoLig=NULL;
					}

					//maj du 03/12/2019 : mag ne font pas de déclaration d'inversion de réf mais déclarent des manquants et
					//ajoutent en commentaire la réf en excédent
					//on ajoute donc les zones de saisies inv de réf pour les manquants
					//si c'est vide, ça reste un manquant, sinon
					//on les traite comme des inversion de ref
					//on force donc l'id motif à inversion de référence
					$idmotif=5;
				// article trouvé ou non , on met à jour la db avec des champ null si rien
					$do=updateDetailInversion($pdoLitige,$idmotif, $_POST['form_qte'][$i],$_POST['form_id'][$i],$allfilename,$ean,$invArticle,$invDescr,$tarifUv,$invFournisseur, $invQte, $valoLig);
				// echo '1 do '.$do;
				}
				else
				{
					$errors[]='merci de renseigner l\'EAN reçu et la quantité';
				}

			}
			else{


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
			header('Location:declaration-validation.php?id='.$_GET['id']);
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
	<h1 class="text-main-blue py-5 ">Déclaration de litige 2/2</h1>
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
						?>
						<?php foreach ($fLitige as $litige): ?>
							<?php if($litige['box_tete']!=1):?>
								<!-- info produit -->
								<div class="row yellow-box">
									<div class="col">
										<h5 class="khand heavy spacy  pt-3 ">Produit : <?=$litige['descr'].' - Art. : '.$litige['article']?></h5>
										<div class="row no-gutters">
											<div class="col ">
												<span class="libelle">Fournisseur : </span>
												<span><?=$litige['fournisseur']?></span>
												<span class="libelle pl-5"> EAN : </span>
												<span><?=$litige['ean']?></span>
												<span class="libelle pl-5"> Dossier : </span>
												<span><?=$litige['dossier_gessica']?></span>
											</div>
										</div>
										<!-- // fin de ligne 2 -->
										<div class="row pb-3">
											<div class="col">
												<span class="libelle">Quantité : </span>
												<span><?=$litige['qte_cde']?></span>

												<span class="libelle pl-5">Palette : </span>
												<span><?=$litige['palette']?></span>
												<span class="libelle pl-5">Facture : </span>
												<span><?=$litige['facture']?></span>
												<span class="libelle pl-5">Date facture : </span>
												<span><?=$litige['datefac']?></span>
											</div>
										</div>
										<!-- // fin info article, fin container : -->
									</div>
								</div>
								<!-- fin infoi prod dans jaune-->
								<!-- //ajout row pour border -->
								<div class="row border pt-3 mb-5">
									<div class="col">
										<div class="row">
											<div class="col-4">
												<p class="khand heavy">Motif de la réclamation :</p>
												<div class="form-group">
													<select class="form-control" name="form_motif[]"  id="motif<?=$litige['detail_id']?>"required>
														<option value="">Sélectionnez</option>
														<?php foreach ($fMotif as $motif){
															echo '<option value="'.$motif['id'].'">'.$motif['reclamation'].'</option>';
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-3">
												<p class="khand heavy">Quantité concernée en UV :
												</p>
												<div class="form-group">
													<input type="text" class="form-control" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" name="form_qte[]" required>
													<input type="hidden" value="<?=$litige['detail_id']?>" name="form_id[]">
												</div>
											</div>
										</div>
										<!-- hidden fields showed only if manquant => 8 -->
										<div class="hidden" id="toggleMissing<?=$litige['detail_id']?>">
											<div class="row">
												<div class="col-12  pl-3">
													<p class="text-reddish">Avez vous reçu un produit non commandé à la place des produits manquants ?</p>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" value="1" id="radio-inv-oui-<?=$litige['detail_id']?>" name="radio-inv-<?=$litige['detail_id']?>">
														<label class="form-check-label" for="radio-inv">Oui</label>
														<input class="form-check-input ml-3" type="radio" value="1" id="radio-inv-non-<?=$litige['detail_id']?>" name="radio-inv-<?=$litige['detail_id']?>">
														<label class="form-check-label" for="radio-inv-non">Non</label>
													</div>
												</div>
											</div>
										</div>
										<div class="hidden" id="toggleEan<?=$litige['detail_id']?>">
											<div class="row">
												<div class="col-4">
													<p class="khand heavy">Ean article reçu non commandé :
													</p>
													<div class="form-group">
														<input type="text" class="form-control" name="form_autre[]" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" id="ean-received">
													</div>
												</div>
												<div class="col-3">
													<p class="khand heavy">Quantité UV reçue :
													</p>
													<div class="form-group">
														<input type="text" class="form-control" name="form_autre_qte[]">
													</div>
												</div>
												<div class="col"></div>
											</div>
										</div>
										<!-- fin hidden fields -->
										<div class="row mt-5">
											<div class="col">
												<p><span class="khand heavy">Photos /vidéos :</span><br>
													<span class="circle-icon"><i class="fas fa-lightbulb"></i></span><span class="text-reddish pl-3 heavy tighter">Maintenez la touche CTRL enfoncée pour sélectionner plusieurs fichiers</span>
												</p>
											</div>
										</div>
										<div class="row">
											<div class="col-auto">
												<div class="form-group">
													<label class="btn btn-upload btn-file text-center"><input name="form_file<?=$subForm?>[]" id="form_file<?=$litige['detail_id']?>" type="file" multiple="" class="form-control-file"><i class="fas fa-file-image pr-3"></i>Sélectionner</label>
												</div>
											</div>
											<!-- // upload filename -->
											<div class="col" id="<?=$litige['detail_id']?>"></div>
										</div>
									</div>
								</div>
							<?php endif ?>
							<?php $subForm++; ?>
						<?php endforeach ?>
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
        	var toShowMissing="#toggleMissing"+selectId;
				// var centrale = ;
				if($(this).val()==8)
				{
					$(toShowMissing).attr('class','show');
				}
				else
				{
					$(toShowMissing).attr('class','hidden');
				}
			});

		$('.form-check-input').click(function() {
			var radioId=this.id;
			console.log(radioId)
			splitId=radioId.split("-");
			var toShowEan="#toggleEan"+splitId[3];

			if(splitId[2]=="oui"){
				$(toShowEan).attr('class','show');
			}else{
				$(toShowEan).attr('class','hidden');

			}

		// $('input:radio[name="+radioName+"]').change(function() {
		// 	if (this.value == 'allot') {
		// 		alert("Allot Thai Gayo Bhai");
		// 	}
		// 	else if (this.value == 'transfer') {
		// 		alert("Transfer Thai Gayo");
		// 	}
		// });
		});

		$(function(){

			$('#ean-received').keyup(function(e) {
				if(this.value!='-')
					while(isNaN(this.value))
						this.value = this.value.split('').reverse().join('').replace(/[\D]/i,'')
					.split('').reverse().join('');
				})
			.on("cut copy paste",function(e){
				e.preventDefault();
			});

		});

	});



</script>


<?php

require '../view/_footer-bt.php';

?>