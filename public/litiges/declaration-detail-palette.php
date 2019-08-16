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

// même fonction que ce sont inversion de palette ou non ($inv_palette mis à nul si non)
function updateDetail($pdoLitige,$id,$qteLitige,$pj, $inv_palette, $valoLig){
	$req=$pdoLitige->prepare("UPDATE details_temp SET id_reclamation = :reclamation, qte_litige= :qte_litige, pj= :pj, inv_palette= :inv_palette, valo_line= :valo_line WHERE id= :id");
	$req->execute(array(
		':reclamation' =>$_POST['form_motif'],
		':qte_litige'	=>$qteLitige,
		':id'			=>$id,
		':pj'			=>$pj,
		':inv_palette'	=>$inv_palette,
		':valo_line'			=>$valoLig,

	));
	return $req->rowCount();
}

// 3 ^pour 1er commentaire
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

function getPaletteInversion($pdoQlik)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE palette LIKE :palette ORDER BY date_mvt DESC");
	$req->execute(array(
		':palette'	=>'%'.$_POST['form_autre'].'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


if(isset($_GET['id']))
{
	$fLitige=getLitigeTemp($pdoLitige);
}
// $paletteFound['palette'],$paletteFound['facture'],$paletteFound['article'],$paletteFound['gencod'],$paletteFound['dossier'],$paletteFound['libelle'],$paletteFound['qte'],$paletteFound['tarif'],$paletteFound['fournisseur'],$paletteFound['cnuf']
// qd inversion de palette on enregistre dans la table palette inv le contenu de la palette qui a été reçue
function addPaletteInv($pdoLitige,$palette,$facture,$date_facture,$article,$ean,$dossier_gessica,$descr,$qte_cde,$tarif,$fournisseur, $cnuf)
{
	$req=$pdoLitige->prepare("INSERT INTO palette_inv_temp (id_dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf, found)
		VALUES (:id_dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf, :found)");
	$req->execute(array(
		':id_dossier'		=>$_GET['id'],
		':palette'			=>$palette,
		':facture'			=>$facture,
		':date_facture'	=>$date_facture,
		':article'			=>$article,
		':ean'				=>$ean,
		':dossier_gessica'	=>$dossier_gessica,
		':descr'			=>$descr,
		':qte_cde'			=>$qte_cde,
		':tarif'			=>$tarif,
		':fournisseur'		=>$fournisseur,
		':cnuf'			=>$cnuf,
		':found'			=>1,

	));
	return $req->rowCount();
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
	if(isset($_FILES['form_file']['name'][0]) && empty($_FILES['form_file']['name'][0]))
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

		// cas de l'inversion de palette
		if(isset($_POST['form_autre']) && !empty($_POST['form_autre']))
		{
				// cas de l'inversion de palette
			$inv_palette=$_POST['form_autre'];
				// recup info du produit reçu à la place
			$paletteInv=getPaletteInversion($pdoQlik,$inv_palette);
				// si on trouve la réf qui a été livrée à la place
			if(count($paletteInv)>=1)
			{
					// palette trouvée
				foreach ($paletteInv as $pal)
				{
					$paletteFound=addPaletteInv($pdoLitige,$pal['palette'],$pal['facture'], $pal['date_mvt'],$pal['article'],$pal['gencod'],$pal['dossier'],$pal['libelle'],$pal['qte'],$pal['tarif'],$pal['fournisseur'],$pal['cnuf']);
					if($paletteFound!=1)
					{
						$errors[]="Problème d'enregistrement lors de l'ajout de la palette reçue";
					}

				}
			}
		}
		else
		{
			// on met une valeur par défaut pour pouvoir faire la même requete quelque soit le cas
			$inv_palette=NULL;
		}


// on récupère le nombre d'article pour pouvoir boucler sur tableau de champ de formulaire
//
		if(count($errors)==0)
		{
			foreach ($fLitige as $litige)
			{
				$valoLig=$litige['tarif'];
				$do=updateDetail($pdoLitige,$litige['detail_id'], $litige['qte_cde'], $allfilename, $inv_palette, $valoLig);
				if($do>0)
				{
					$foreachSuccess[]="success";

				}
				else
				{
					$foreachErrors[]="erreurs" .$do;


				}
			}
		}
		else
		{
				// echo '2 do '.$do;
		}
			// $do=updateDetail($pdoLitige,$_POST['form_motif'][$i], $_POST['form_qte'][$i],$_POST['form_id'][$i],$allfilename,$ean);
				// echo '3 do '.$do;
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
	<h1 class="text-main-blue pt-5 pb-3">Votre dossier litige</h1>
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

	<div class="row border pt-3 mb-5 light-shadow">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col">
						<p>Veuillez renseigner le formulaire ci-dessous pour finaliser l'ouverture de votre dossier de litige et recevoir le numéro qui lui sera attribué.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<p class="khand heavy">Motif de la réclamation :</p>
						<div class="form-group">
							<select class="form-control" name="form_motif"  id="motif"required>
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
					<div class="col-4">
						<div class="hidden" id="toggle">
							<p class="khand heavy">Palette reçue :
							</p>
							<div class="form-group">
								<input type="text" class="form-control" name="form_autre">
							</div>
						</div>
					</div>
					<div class="col"></div>
				</div>
				<!-- fin row 1 -->
				<div class="row">
					<div class="col">
						<p><span class="khand heavy">Photos /vidéos :</span><br>
							<span class="circle-icon"><i class="fas fa-lightbulb"></i></span><span class="text-reddish pl-3 heavy tighter">Maintenez la touche CTRL enfoncée pour sélectionner plusieurs fichiers</span>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-auto">
						<div class="form-group">
							<label class="btn btn-upload btn-file text-center"><input name="form_file[]" id="form_file" type="file" multiple="" class="form-control-file"><i class="fas fa-file-image pr-3"></i>Sélectionner</label>
						</div>
					</div>
					<div class="col" id="filelist">

					</div>
				</div>
				<div class="row">
					<div class="col">
						<p class="khand heavy bigger">Commentaires : </p>
						<div class="form-group">
							<textarea class="form-control" name="form_com"></textarea>
						</div>
					</div>
				</div>
				<p class="pt-5 text-right upper"><button class="btn btn-primary" type="submit" name="submit">Envoyer</button></p>
			</form>
		</div>

	</div>

	<div class="row">
		<div class="col">
			Détail de la palette :
			<table class="table">
				<thead class="bg-yellow">
					<tr>
						<th>EAN</th>
						<th>Article</th>
						<th>Désign</th>
						<th>Fournisseur</th>
						<th>Quantité</th>
						<th>Palette</th>
						<th>Facture</th>
					</tr>
				</thead>
				<tbody>
					<?php

					foreach ($fLitige as $litige)
					{
						echo '<tr>';
						echo '<td>'.$litige['ean'].'</td>';
						echo '<td>'.$litige['article'].'</td>';
						echo '<td>'.$litige['descr'].'</td>';
						echo '<td>'.$litige['fournisseur'].'</td>';
						echo '<td>'.$litige['qte_cde'].'</td>';
						echo '<td>'.$litige['palette'].'</td>';
						echo '<td>'.$litige['facture'].'</td>';
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>


	<!-- ./row -->
</div>
<script type="text/javascript">
	$(document).ready(function(){
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

		$('select').on('change',function(e){
        // $("select#motif7").change(function(){

        	var toShow="#toggle";
				// var centrale = ;
				if($(this).val()==7)
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