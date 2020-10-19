<?php
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

require_once  '../../vendor/autoload.php';
require('../../Class/UserHelpers.php');



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
$descr="achat formulaire de réponse litige" ;
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr, 101);

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$repColor=[
	0	=>'bg-alert-primary',
	1	=>'bg-alert-yellow'
];


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


UNSET($_SESSION['goto']);



function getAction($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id, libelle,id_dossier, action.id_web_user, DATE_FORMAT(date_action, '%d-%m-%Y')as dateFr, pj, achats FROM action WHERE id_dossier= :id_dossier AND (id_contrainte>=8 AND id_contrainte<=11) ORDER BY date_action");
	$req->execute(array(
		':id_dossier'		=>$_GET['id']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function getLitige($pdoLitige){
	$req=$pdoLitige->prepare("SELECT DATE_FORMAT(date_crea, '%d-%m-%Y') as dateFr, dossiers.id as mainid, dossiers.dossier, btlec.sca3.mag, btlec.sca3.centrale,btlec.sca3.btlec, etat_dossier, details.article,details.descr,details.qte_litige, details.valo_line, reclamation.reclamation, details.inversion,details.qte_cde,details.inv_tarif,details.inv_article,details.inv_descr FROM dossiers LEFT JOIN details ON dossiers.id=details.id_dossier LEFT JOIN reclamation ON details.id_reclamation = reclamation.id LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec WHERE dossiers.id= :id ");
	$req->execute(array(
		':id'		=>$_GET['id']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getListDossier($pdoLitige){
	$req=$pdoLitige->query("SELECT DISTINCT dossier,dossiers.id  FROM action LEFT JOIN dossiers ON action.id_dossier=dossiers.id WHERE id_contrainte>=8 AND id_contrainte<=11 ORDER BY dossier DESC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$listDossier=getListDossier($pdoLitige);


function addAction($pdoLitige, $filelist){

	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier,libelle,id_contrainte,id_web_user,achats,pj, date_action) VALUES (:id_dossier,:libelle,:id_contrainte,:id_web_user,:achats,:pj,:date_action)");
	$req->execute(array(
		':id_dossier'		=>	$_GET['id'],
		':libelle'		=>	$msg,
		':id_contrainte'	=>11,
		':id_web_user'		=> $_SESSION['id_web_user'],
		':achats'				=>1,
		':pj'				=>$filelist,
		':date_action'		=> date('Y-m-d H:i:s'),
	));
	return $req->rowCount();
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




if(isset($_POST['id_dossier'])){
	header('Location:intervention-achats.php?id='.$_POST['id_dossier']);

}

if(isset($_GET['id'])){
	$listAction=getAction($pdoLitige);
	$thisLitige=getlitige($pdoLitige);
}

if(isset($_POST['submit'])){
// vérifie si pièce jointes
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

	if(count($errors)==0)
	{
		$add=addAction($pdoLitige,$allfilename);
		if($add==1)
		{

			if(VERSION=='_'){
				$dest='valerie.montusclat@btlec.fr';

			}
			else{
				$dest='btlecest.portailweb.logistique@btlec.fr';

			}
		// envoi mail litigelivraison
			$htmlMail = file_get_contents('mail/mail_rep_achats.php');
			$htmlMail=str_replace('{MAG}',$thisLitige[0]['mag'],$htmlMail);
			$htmlMail=str_replace('{DOSSIER}',$thisLitige[0]['dossier'],$htmlMail);
			$htmlMail=str_replace('{MSG}',$_POST['msg'],$htmlMail);
			$subject='Portail BTLec - litige - Réponse ACHATS dossier '.$thisLitige[0]['dossier'];

// ---------------------------------------
// initialisation de swift
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')

			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
// ->setTo(array('valerie.montusclat@btlec.fr', 'valerie.montusclat@btlec.fr' => 'val'))
			->setTo($dest)
// ->addCc($copySender['email'])
			->addBcc('valerie.montusclat@btlec.fr');
		// ->attach($attachmentPdf)
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));
// ou
// ->setBcc([adress@btl.fr, adresse@bt.fr])

// echec => renvoie 0
			$delivered=$mailer->send($message);
			if($delivered !=0)
			{
				$success='?id='.$_GET['id'].'&success';
				unset($_POST);
				header("Location: ".$_SERVER['PHP_SELF'].$success,true,303);
			}else{
				$errors[]="Une erreur s'est produite, votre réponse n'a pas pu être envoyée";
			}


		}


	}
	else{
		$errors[]="message non enregistré";
	}

}

if(isset($_GET['success'])){
	$success[]="Votre réponse a été envoyée à BTLec";

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
	<h1 class="text-main-blue pt-5 ">Litige <?= $title=(isset($thisLitige[0]['dossier']))? $thisLitige[0]['dossier'] : '(pas de litige sélectionné)'?></h1>
	<div class="row">
		<div class="col"></div>
		<div class="col-2">
			<form action="" method="post">
				<div class="form-group">
					<label>Changer de dossier :</label>
					<select name="id_dossier" class="form-control" onchange='this.form.submit()'>
						<option value="">Selectionnez</option>
						<?php foreach ($listDossier as $dossier)
						{
							echo '<option value="'.$dossier['id'].'">'.$dossier['dossier'].'</option>';
						}
						?>
					</select>
				</div>
			</form>
		</div>
		<div class="col"></div>
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
	<?php
	ob_start();
	?>
	<div class="row mb-3 pt-3">
		<div class="col text-yellow-dark heavy">Détail du litige :</div>
	</div>
	<div class="row pb-3">
		<div class="col"><span class="heavy text-yellow-dark"> Magasin : </span><?= $thisLitige[0]['mag'] .' - '. $thisLitige[0]['btlec'] ?></div>
		<div class="col"><span class="heavy text-yellow-dark"> Centrale : </span><?=$thisLitige[0]['centrale']?></div>
		<div class="col-3"><span class="heavy text-yellow-dark">Etat :</span> <?= ($thisLitige[0]['etat_dossier']==1) ? 'Dossier clôturé' : 'Dossier en cours'?></div>
		<div class="col-2 text-right"><i class="fas fa-calendar-alt pr-3 text-yellow-dark"></i><?=$thisLitige[0]['dateFr']?></div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table table-bordered ">
				<tr class="table-warning">
					<th>CODE ARTICLE</th>
					<th>DESIGNATION</th>
					<th>QUANTITE</th>
					<th>VALORISATION</th>
					<th>RECLAMATION</th>
				</tr>
				<?php
				foreach ($thisLitige as $prod)
				{
					echo '<tr>';
					echo'<td>'.$prod['article'].'</td>';
					echo'<td>'.$prod['descr'].'</td>';
					echo'<td class="text-right">'.$prod['qte_litige'].'</td>';
					echo'<td class="text-right">'.number_format((float)$prod['valo_line'],2,'.','').'&euro;</td>';
					echo'<td>'.$prod['reclamation'].'</td>';
					echo '</tr>';
					if($prod['inversion'] !="")
					{
						$valoInv=round( $prod['qte_cde']*$prod['inv_tarif'],2);
						echo '<tr><td colspan="5" class="text-center text-prim heavy">Produit reçu à la place de la référence ci-dessus :</td></tr>';
						echo '<tr>';
						echo'<td class="text-prim heavy">'.$prod['inv_article'].'</td>';
						echo'<td class="text-prim heavy">'.$prod['inv_descr'].'</td>';
						echo'<td class="text-right text-prim heavy">'.$prod['qte_litige'].'</td>';
						echo'<td class="text-right text-prim heavy">'.number_format((float)$valoInv,2,'.','').'&euro;</td>';
						echo'<td class="text-right"></td>';
						echo '</tr>';
					}

				}
				?>
			</table>

		</div>
	</div>
	<!-- <div class="bg-separation"></div> -->
	<div class="row mb-3 pt-3">
		<div class="col heavy text-main-blue">Echanges sur le dossier :</div>
	</div>

	<?php

	foreach ($listAction as $action)
	{

		if(!empty($action['libelle']))
		{

			if($action['pj']!='')
			{
				$pj=createFileLink($action['pj']);
			}
			else
			{
				$pj='';
			}
	// conteneur
			echo '<div class="row alert '.$repColor[$action['achats']].' mb-5">';
			echo '<div class="col">';
// ligne 1
			echo '<div class="row heavy">';
			echo '<div class="col">'.UserHelpers::getFullname($pdoUser, $action['id_web_user']).'</div>';

			echo '<div class="col">';
			echo '<div class="text-right"><i class="far fa-calendar-alt pr-3"></i>'.$action['dateFr'].'</div>';
			echo '</div>';

			echo '</div>';
// ligne 2
			echo '<div class="row ">';
			echo '<div class="col">';
			echo $action['libelle'];
			echo '</div>';
			echo '<div class="col-auto">';
			echo $pj;
			echo '</div>';
			echo '</div>';

			echo '</div>';
			echo '</div>';

		}
	}
	?>
	<div class="bg-separation"></div>
	<div class="row mb-3 py-3 ">
		<div class="col">
			<div class="text-main-blue heavy">Répondre au service litige :</div>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col-2"></div>

		<div class="col border p-3">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label>Votre message :</label>
					<textarea class="form-control" name="msg" required></textarea>
				</div>
				<div id="upload-zone">
					<label for='incfile'>Ajouter des pièces jointes :
						<br><i> (pour ajouter plusieurs fichiers, maintenez la touche ctrl pendant que vous sélectionnez les fichiers)</i>  </label>
						<input type='file' class='form-control-file' id='incfile' name='incfile[]' multiple="" >
						<div id="filelist"></div>
					</div>
					<div class="text-right"><button class="btn btn-primary" name="submit"><i class="far fa-envelope pr-3"></i>Envoyer</button></div>
				</form>
			</div>
			<div class="col-2"></div>
		</div>

		<?php
		$html=ob_get_contents();
		ob_end_clean();
		if(isset($_GET['id'])){
			echo $html;
		}

		?>

		<!-- ./container -->
	</div>
	<script type="text/javascript">
		$(document).ready(function (){


			var fileName='';
			var fileList='';
			$('input[type="file"]').change(function(e){
				$('#filelist').empty();
				var nbFiles=e.target.files.length;
				for (var i = 0; i < nbFiles; i++)
				{
        		    // var fileName = e.target.files[0].name;
        		    fileName=e.target.files[i].name;
        		    fileList += fileName + ' - ';
        		}
     		   // console.log(fileList);
     		   titre='<p><span class="heavy">Fichier(s) : </span>'
     		   end='</p>';
     		   all=titre+fileList+end;
     		   $('#filelist').append(all);
     		   fileList="";
     		});


		});


	</script>
	<?php
	require '../view/_footer-bt.php';
	?>