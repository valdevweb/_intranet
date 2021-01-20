<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';
require_once '../../Class/OccPaletteMgr.php';
require '../../Class/UserHelpers.php';
require '../../Class/OccHelpers.php';







//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);




// echo "<pre>";
// print_r($paletteCommandable);
// echo '</pre>';




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getListPanier($pdoOcc){
	$req=$pdoOcc->prepare("SELECT cdes_temp.*, palette FROM cdes_temp LEFT JOIN palettes ON id_palette=palettes.id WHERE id_web_user= :id_web_user");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user']

	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function addToTemp($pdoOcc){
	$req=$pdoOcc->prepare("INSERT INTO cdes_temp (id_web_user, id_palette, date_insert) VALUES (:id_web_user, :id_palette, :date_insert) ");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':id_palette'		=>$_POST['id_palette'],
		':date_insert'		=>date('Y-m-d H:i:s')

	]);

	$err=$req->errorInfo();


	if(!empty($err[2])){
		return false;
	}
	return true;
}

function addToTempArt($pdoOcc){
	$req=$pdoOcc->prepare("INSERT INTO cdes_temp (id_web_user, id_palette, article_occ, design_occ, fournisseur_occ, ean_occ, panf_occ, deee_occ, sorecop_occ ,qte_cde, date_insert, marque_occ, ppi_occ) VALUES (:id_web_user, :id_palette, :article_occ, :design_occ, :fournisseur_occ, :ean_occ, :panf_occ, :deee_occ, :sorecop_occ , :qte_cde, :date_insert, :marque_occ, :ppi_occ) ");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':id_palette'		=>0,
		':article_occ'		=>$_POST['article_qlik'],
		':design_occ'		=>$_POST['design_qlik'],
		':fournisseur_occ'	=>$_POST['fournisseur_qlik'],
		':ean_occ'		=>$_POST['ean_qlik'],
		':panf_occ'		=>$_POST['panf_qlik'],
		':deee_occ'		=>$_POST['deee_qlik'],
		':sorecop_occ'		=>$_POST['sorecop'],
		':qte_cde'		=>$_POST['qte_cde'],
		':date_insert'		=>date('Y-m-d H:i:s'),
		':ppi_occ'		=>$_POST['ppi_qlik'],
		':marque_occ'		=>$_POST['marque_qlik'],

	]);

	$err=$req->errorInfo();

	return $err;

	if(!empty($err[2])){
		return false;
	}
	return true;
}


function delTempCde($pdoOcc){
	$req=$pdoOcc->prepare("DELETE FROM cdes_temp WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['idTempDel']
	]);
}

function delPaletteCde($pdoOcc){
	$req=$pdoOcc->prepare("DELETE FROM cdes_detail WHERE id_palette= :id_palette");
	$req->execute([
		':id_palette'		=>$_GET['del-palette']
	]);
}


function getPaletteStatut($pdoOcc,$id){
	$req=$pdoOcc->prepare("SELECT * FROM palettes WHERE id= :id ");
	$req->execute([
		':id'	=>$id

	]);
	// return $req->errorInfo();
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updatePalette($pdoOcc,$idPalette,$statut){
	$req=$pdoOcc->prepare("UPDATE palettes SET statut= :statut WHERE id= :id");
	$req->execute([
		':id'		=>$idPalette,
		':statut'	=>$statut
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function addToCmd($pdoOcc,$idPalette,$idCde, $article, $panf, $deee, $sorecop, $design, $fournisseur, $ean, $qte, $marque, $ppi){
	$req=$pdoOcc->prepare("INSERT INTO cdes_detail (id_web_user, id_palette, id_cde, article_occ, panf_occ, deee_occ, sorecop_occ, design_occ, fournisseur_occ, ean_occ, qte_cde, date_insert, marque_occ, ppi_occ) VALUES (:id_web_user, :id_palette, :id_cde, :article_occ, :panf_occ, :deee_occ, :sorecop_occ, :design_occ, :fournisseur_occ, :ean_occ, :qte_cde, :date_insert, :marque_occ, :ppi_occ) ");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':id_cde'		=>$idCde,
		':article_occ'		=>$article,
		':panf_occ'		=>$panf,
		':deee_occ'		=>$deee,
		':sorecop_occ'		=>$sorecop,
		':design_occ'		=>$design,
		':fournisseur_occ'		=>$fournisseur,
		':ean_occ'		=>$ean,
		':qte_cde'		=>$qte,
		':id_palette'		=>$idPalette,
		':date_insert'		=>date('Y-m-d H:i:s'),
		':marque_occ'		=>$marque,
		':ppi_occ'		=>$ppi,

	]);
	$err=$req->errorInfo();
	return $err;
	if(!empty($err[2])){
		return false;
	}
	return true;
}
function deleteTempCmd($pdoOcc,$id){
	$req=$pdoOcc->prepare("DELETE FROM cdes_temp WHERE id= :id");
	$req->execute([
		':id'	=>$id
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function getAssortiment($pdoOcc){
	$req=$pdoOcc->query(" SELECT articles_qlik.*, cmt, file	FROM articles_qlik LEFT JOIN articles_qlik_cmt ON articles_qlik.article_qlik= articles_qlik_cmt.article LEFT JOIN fiche_prod ON articles_qlik.article_qlik= fiche_prod.article WHERE qte_qlik !=0 ORDER BY article_qlik");
	return $req->fetchAll();
}


function onPaletteOccas($pdoOcc, $article){
	$req=$pdoOcc->prepare("SELECT id_palette, palette, statut  FROM palettes_articles LEFT JOIN palettes ON palettes_articles.id_palette=palettes.id WHERE code_article= :code_article");
	$req->execute([
		':code_article'	=>$article

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getListArticleMagInTemp($pdoOcc){
	$req=$pdoOcc->prepare("SELECT article_occ, qte_cde FROM cdes_temp WHERE id_web_user= :id_web_user AND article_occ IS NOT NULL");
	$req->execute([
		':id_web_user'	=>$_SESSION['id_web_user']

	]);
	$data=$req->fetchAll(PDO::FETCH_KEY_PAIR);
	return $data;
}
function isMagArticleInTemp($pdoOcc, $article){
	$req=$pdoOcc->prepare("SELECT * FROM cdes_temp WHERE article_occ= :article_occ AND id_web_user= :id_web_user");
	$req->execute([
		':article_occ'	=>$article,
		':id_web_user'	=>$_SESSION['id_web_user']

	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	return $data;
}


function delLine($pdoOcc,$idCdeTemp){
	$req=$pdoOcc->prepare("DELETE FROM cdes_temp WHERE id= :id");
	$req->execute([
		':id'	=>$idCdeTemp
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function updateTempArt($pdoOcc,$id){

	$req=$pdoOcc->prepare("UPDATE cdes_temp SET qte_cde= :qte_cde, date_insert= :date_insert WHERE id= :id" );
	$req->execute([
		':qte_cde'		=>$_POST['qte_cde'],
		':date_insert'		=>date('Y-m-d H:i:s'),
		':id'		=>$id
	]);
}

function updateQteArticle($pdoOcc,$article, $qte){
	$req=$pdoOcc->prepare("UPDATE articles_qlik SET qte_qlik= :qte_qlik WHERE article_qlik= :article_qlik");
	$req->execute([
		':qte_qlik'	=>$qte,
		':article_qlik'		=>$article

	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function getQteArticleQlik($pdoOcc, $article){
	$req=$pdoOcc->prepare(" SELECT qte_qlik	FROM articles_qlik WHERE article_qlik= :article_qlik");
	$req->execute([
		':article_qlik' =>$article
	]);
	$data=$req->fetch();
	return $data['qte_qlik'];
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$displayCart=false;

$paletteMgr=new OccPaletteMgr($pdoOcc);
$paletteCommandable=$paletteMgr->getListPaletteDetailByStatut(1);
$importW="";


$paletteEnPrepa=$paletteMgr->getListPaletteDetailByStatut(0);
$paletteCommandees=$paletteMgr->getListCommandeByStatut(2);

$paletteEtArticleDansPanier=getListPanier($pdoOcc);

$listAssortiment=getAssortiment($pdoOcc);
$arrayListPalette=OccHelpers::arrayPalette($pdoOcc);

$cmtPalette=$paletteMgr->getActiveListPaletteCmt();




// si le magasin a des article ou des palettes dans son panier
if(!empty($paletteEtArticleDansPanier)){
	$nbPalettePanier=count($paletteEtArticleDansPanier);
	// on récupère la liste des articles d'occasion dans son panier pour pouvoir afficher la quantité commandée dans les inputs et modifier le stock affiché
	$artInTemp=getListArticleMagInTemp($pdoOcc);

}else{
	$nbPalettePanier=0;
}


//  permet d'ajouter une palette au panier
if(isset($_POST['addtocart'])){
	$displayCart=addToTemp($pdoOcc);
	if($displayCart){
		$successQ='?success=cart';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

	}else{
		$errors[]="erreur";
	}
}

// lien supprimer une palette du cart
if(isset($_GET['idTempDel'])){
	delTempCde($pdoOcc);
	header("Location:occ-palette.php");
}

if(isset($_GET['idTempDelArticle'])){
	delLine($pdoOcc,$_GET['idTempDelArticle']);
	header("Location:occ-palette.php");
}

include 'occ-palette-checkout.php';

include 'occ-palette-addarticle.php';

if(isset($_GET['del-palette'])){
// supprimer la palette de la commande
// remettre le statut de la palette à 1
	delPaletteCde($pdoOcc);
	updatePalette($pdoOcc, $_GET['del-palette'], 1);
	header("Location:occ-palette.php#cde");

}

if(isset($_GET['expedier'])){
	$majStatutPalette=false;
	// mettre à jour len uméro de commande
	$req=$pdoOcc->prepare("UPDATE cdes_numero SET statut=3, date_exp= :date_exp WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['expedier'],
		'date_exp'	=>date('Y-m-d H:i:s')
	]);
	// // mettre à jour les palette si palette !!!
	$detailCde=$paletteMgr->getCdeByIdCde($_GET['expedier']);
	foreach ($detailCde as $detail) {
		// on met les id palette à mettre à jour dans un tableau
		// pour les produits unitaird par de mise à jour à faire
		if(!empty($detail['id_palette'])){
			$majStatutPalette=true;
		}
	}
	// si on a des palettes à mettre à jour
	if($majStatutPalette){

		// on n'utilise pas le nuémro de palette pour mettre à jour mais le numéro de commande mais
		$upPalette=$paletteMgr->updatePaletteCdeStatut($pdoOcc,$_GET['expedier'],3);

		if($upPalette>=1){
			header("Location:occ-palette.php?success=expedie");
		}else{
			$errors[]="une erreur est survenue, impossible de mettre la palette à jour";

		}

	}else{
		header("Location:occ-palette.php?success=expedie");
	}
}


if(isset($_GET['success'])){
	$arrSuccess=[
		'cart'=>'Palette ajoutée à votre panier.<br> Attention, si un autre magasin valide sa commande sur ces palettes,  les stocks ne seront peut être plus suffisant',
		'article-add'=>'Article ajouté à votre panier.<br> Attention, si un autre magasin valide sa commande sur ces produits, les stocks ne seront peut être plus suffisant',

		'cde'	=>"Votre commande a bien été envoyée",
		'cdeok'	=>"Un mail de confirmation de commande vient de vous être envoyé",
		'expedie'	=>"la commande a bien été passée en statut expédiée",
		'mod'	=>"Quantité modifiée",
	];
	$success[]=$arrSuccess[$_GET['success']];
}
//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');

if($_SESSION['id']==1531){
	include('../view/_navbar_restricted.php');

}else{
	include('../view/_navbar.php');

}


$infoMag=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $_SESSION['id_web_user']);


?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<div id="top"></div>

	<h1 class="text-main-blue pt-5 pb-2">Produits d'occasion</h1>




	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row">
		<div class="col-lg-2"></div>

		<div class="col">
			<div class="alert alert-danger text-center">
				<h5><i class="fas fa-exclamation-circle pr-2"></i>INFORMATION</h5>
				Dorénavant les nouvelles palettes occasion seront ajoutées <strong>seulement le mardi</strong><br>
				Merci de votre compréhension.
			</div>
		</div>
		<div class="col-lg-2"></div>

	</div>
	<!-- partie réservée BT -->

	<?php if ($_SESSION['type']=='btlec'): ?>

		<div class="row justify-content-center mb-2">
			<div class="col-md-6 border rounded py-3">
				<div class="row">
					<div class="col text-center text-main-blue">
						Voir les palettes :
					</div>
				</div>
				<div class="row ">
					<div class="col ">
						<nav class="text-center nav-planning">
							<a href="#prepa" class="nav-elt">En prépa</a>
							<a href="#over" class="nav-elt">Terminées</a>
							<a href="#cde" class="nav-elt">Commandées</a>
							<a href="occ-expedie.php" class="nav-elt">Expédiées</a>
						</nav>

					</div>

				</div>
			</div>
		</div>
	<?php endif ?>

	<?php
	// affichage du panier si article en commande
	if (!empty($paletteEtArticleDansPanier)){
		include 'occ-palette-cart.php';
	}
	// affichage de la liste des articles gt13 commandables
	include 'occ-palette-artlist.php';
	// affich&age palettes en prepa pour bt
	if ($_SESSION['type']=='btlec'){
		include 'occ-palette-prepa.php';
	}
	// affichage palette terminées pour tout le monde



	include 'occ-palette-dispo.php';
	// affichage palettes commandéew et expédiées
	if ($_SESSION['type']=='btlec'){
		include 'occ-palette-cdes.php';

	}

	?>











	<!-- ./container -->
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		$("#cart").on("click", function() {
			$(".shopping-cart").toggleClass("hidden shown");
		});
		$("input.mini-input").focus(function(){
			console.log("panier");
			var inputFocused=$(this).attr("data-input");
			var btn=$("div").find(`[data-btn='${inputFocused}']`);

			btn.removeClass("btn-strange")
			btn.addClass("btn-primary");
		});
		$("input.mini-input").focusout(function(){
			console.log("panier");
			var inputFocused=$(this).attr("data-input");
			var btn=$("div").find(`[data-btn='${inputFocused}']`);

			btn.removeClass("btn-primary")
			btn.addClass("btn-strange");
		});
		$('table.more').hide();
		$('.detail-btn').on("click", function(){
			var id= $(this).data("btn-id");
			console.log(id);
			if($('table[data-table-id="'+id+'"]').is(":visible")){
				$('table[data-table-id="'+id+'"]').hide();

			}else{
				$('table[data-table-id="'+id+'"]').show();
			}
		});
		$('.detail-palette').hide();

		$('.lot').on("click", function(){
			var id= $(this).data("lot-id");
			if($('.detail-palette[data-lot-list="'+id+'"]').is(":visible")){
				$('.detail-palette[data-lot-list="'+id+'"]').hide();


			}else{
				$('.detail-palette[data-lot-list="'+id+'"]').show();
				console.log("id" +id);

			}
		});

	});

</script>
<?php
require '../view/_footer-bt.php';
?>