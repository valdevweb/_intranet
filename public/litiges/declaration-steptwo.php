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

include '../../Class/LitigeDao.php';
include '../../Class/LitigeHelpers.php';
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
	$req=$pdoLitige->prepare("SELECT dossiers_temp.id as id, details_temp.id as detail_id,details_temp.dossier,palette,facture, date_facture,DATE_FORMAT(date_facture,'%d-%m-%Y')as datefac,article, ean, dossier_gessica, descr,qte_cde,tarif, fournisseur,details_temp.box_tete, details_temp.box_art FROM dossiers_temp LEFT JOIN details_temp ON dossiers_temp.id=details_temp.id_dossier WHERE dossiers_temp.id= :id");
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


function getProdInversion($pdoQlik,$ean)
{
	$req=$pdoQlik->prepare("SELECT id,`GESSICA.CodeArticle` as article,`GESSICA.CodeDossier` as dossier,`GESSICA.LibelleArticle` as libelle,`GESSICA.PFNP` AS pfnp,`GESSICA.PCB` as pcb,`GESSICA.NomFournisseur` as fournisseur, `GESSICA.CodeFournisseur` as cnuf FROM basearticles WHERE `GESSICA.Gencod` LIKE :inversion ORDER BY `GESSICA.CodeDossier`");
	$req->execute(array(
		':inversion'	=>'%'.$ean.'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$litigeDao=new LitigeDao($pdoLitige);

$fMotif=$litigeDao->getReclamation();
$idReclamPhoto=LitigeHelpers::listReclamationPhoto($pdoLitige);


$foreachErrors=[];
$foreachSuccess=[];

$errors=[];
$success=[];
$newData=0;
$uploadDir= '..\..\..\upload\litiges\\';
$valoTotal=0;

if(isset($_GET['id'])){
	$fLitige=getLitigeTemp($pdoLitige);


}

// si on vient de la page bt-ouv-saisie, la var de $_SESSION['dd_ouv'] conteint le numéro de la demande d'ouverture temporaire
// on récupère alors le 1er message envoyé par la mag pour la demande d'ouverture, cad le message de la table ouv (l'id "dossier" est celui de la table ouv,
// les autres échanges sont dans la table ouv_rep)
// on l'insere alors dans le commentaire
if(isset($_SESSION['dd_ouv'])){
	$req=$pdoLitige->prepare("SELECT * FROM ouv WHERE id= :id");
	$req->execute([
		':id'		=>$_SESSION['dd_ouv']
	]);
	$cmt=$req->fetch(PDO::FETCH_ASSOC);
	$cmt=$cmt['msg'];

}




if(isset($_POST['submit'])){

	include 'declaration-steptwo-exec-inc.php';

// on récupère le nombre d'article pour pouvoir boucler sur tableau de champ de formulaire

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
				<?php include'declaration-steptwo-form-inc.php' ?>
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
			var selectId=e.target.id;
			var value=$(this).val();
			sizeStrg=selectId.length;
			selectId=selectId.substring(5,sizeStrg);

			var toShowMissing="#toggleMissing"+selectId;
				// var centrale = ;
				if($(this).val()==8){
					$(toShowMissing).attr('class','show');
				}
				else{
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