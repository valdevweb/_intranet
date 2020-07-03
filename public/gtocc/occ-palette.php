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
function getListPanier($pdoBt){
	$req=$pdoBt->prepare("SELECT occ_cdes_temp.*, palette FROM occ_cdes_temp LEFT JOIN occ_palettes ON id_palette=occ_palettes.id WHERE id_web_user= :id_web_user");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user']

	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function addToTemp($pdoBt){
	$req=$pdoBt->prepare("INSERT INTO occ_cdes_temp (id_web_user, id_palette, date_insert) VALUES (:id_web_user, :id_palette, :date_insert) ");
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

function addToTempArt($pdoBt){
	$req=$pdoBt->prepare("INSERT INTO occ_cdes_temp (id_web_user, id_palette, article_occ, design_occ, fournisseur_occ, ean_occ, panf_occ, deee_occ, sorecop_occ ,qte_cde, date_insert) VALUES (:id_web_user, :id_palette, :article_occ, :design_occ, :fournisseur_occ, :ean_occ, :panf_occ, :deee_occ, :sorecop_occ , :qte_cde, :date_insert) ");
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
		':date_insert'		=>date('Y-m-d H:i:s')

	]);

	$err=$req->errorInfo();

	return $err;

	if(!empty($err[2])){
		return false;
	}
	return true;
}


function delTempCde($pdoBt){
	$req=$pdoBt->prepare("DELETE FROM occ_cdes_temp WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['idTempDel']
	]);
}

function getPaletteStatut($pdoBt,$id){
	$req=$pdoBt->prepare("SELECT * FROM occ_palettes WHERE id= :id ");
	$req->execute([
		':id'	=>$id

	]);
	// return $req->errorInfo();
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updatePalette($pdoBt,$idPalette,$statut){
	$req=$pdoBt->prepare("UPDATE occ_palettes SET statut= :statut WHERE id= :id");
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

function addToCmd($pdoBt,$idPalette,$idCde, $article, $panf, $deee, $sorecop, $design, $fournisseur, $ean, $qte){
	$req=$pdoBt->prepare("INSERT INTO occ_cdes (id_web_user, id_palette, id_cde, article_occ, panf_occ, deee_occ, sorecop_occ, design_occ, fournisseur_occ, ean_occ, qte_cde, date_insert) VALUES (:id_web_user, :id_palette, :id_cde, :article_occ, :panf_occ, :deee_occ, :sorecop_occ, :design_occ, :fournisseur_occ, :ean_occ, :qte_cde, :date_insert) ");
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
		':date_insert'		=>date('Y-m-d H:i:s')

	]);
	$err=$req->errorInfo();
	return $err;
	if(!empty($err[2])){
		return false;
	}
	return true;
}
function deleteTempCmd($pdoBt,$id){
	$req=$pdoBt->prepare("DELETE FROM occ_cdes_temp WHERE id= :id");
	$req->execute([
		':id'	=>$id
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function getAssortiment($pdoBt){

	$req=$pdoBt->query(" SELECT *	FROM occ_article_qlik WHERE qte_qlik !=0 ORDER BY article_qlik");
	return $req->fetchAll();
}


function onPaletteOccas($pdoBt, $article){
	$req=$pdoBt->prepare("SELECT id_palette, palette, statut  FROM occ_articles LEFT JOIN occ_palettes ON occ_articles.id_palette=occ_palettes.id WHERE code_article= :code_article");
	$req->execute([
		':code_article'	=>$article

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function isMagArticleInTemp($pdoBt, $article){
	$req=$pdoBt->prepare("SELECT * FROM occ_cdes_temp WHERE article_occ= :article_occ AND id_web_user= :id_web_user");
	$req->execute([
		':article_occ'	=>$article,
		':id_web_user'	=>$_SESSION['id_web_user']

	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	return $data;
}


function delLine($pdoBt,$idCdeTemp){
	$req=$pdoBt->prepare("DELETE FROM occ_cdes_temp WHERE id= :id");
	$req->execute([
		':id'	=>$idCdeTemp
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function updateTempArt($pdoBt,$id){

	$req=$pdoBt->prepare("UPDATE occ_cdes_temp SET qte_cde= :qte_cde, date_insert= :date_insert WHERE id= :id" );
	$req->execute([
		':qte_cde'		=>$_POST['qte_cde'],
		':date_insert'		=>date('Y-m-d H:i:s'),
		':id'		=>$id
	]);
}

function updateQteArticle($pdoBt,$article, $qte){
	$req=$pdoBt->prepare("UPDATE occ_article_qlik SET qte_qlik= :qte_qlik WHERE article_qlik= :article_qlik");
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

function getQteArticleQlik($pdoBt, $article){
	$req=$pdoBt->prepare(" SELECT qte_qlik	FROM occ_article_qlik WHERE article_qlik= :article_qlik");
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

$paletteMgr=new OccPaletteMgr($pdoBt);
$paletteCommandable=$paletteMgr->getListPaletteDetailByStatut(1);
$paletteEnPrepa=$paletteMgr->getListPaletteDetailByStatut(0);
$paletteCommandees=$paletteMgr->getListCommandeByStatut(2);
$paletteDansPanierMag=getListPanier($pdoBt);

$listAssortiment=getAssortiment($pdoBt);
$arrayListPalette=OccHelpers::arrayPalette($pdoBt);



if(!empty($paletteDansPanierMag)){
	$nbPalettePanier=count($paletteDansPanierMag);
}else{
	$nbPalettePanier=0;
}
// if(isset($_GET['id'])){
// 	$detailPalette=getPalette($pdoBt, $_GET['id']);
// }
if(isset($_POST['addtocart'])){
	$displayCart=addToTemp($pdoBt);
		// echo "<pre>";
		// print_r($displayCart);
		// echo '</pre>';
	if($displayCart){
		$successQ='?success=cart';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

	}else{
		$errors[]="erreur";
	}
}
// lien supprimerune palette du cart

if(isset($_GET['idTempDel'])){
	delTempCde($pdoBt);
	header("Location:occ-palette.php");
}

if(isset($_GET['idTempDelArticle'])){
	delLine($pdoBt,$_GET['idTempDelArticle']);
	header("Location:occ-palette.php");
}

include 'occ-palette-checkout.php';

include 'occ-palette-addarticle.php';



if(isset($_GET['expedier'])){
	$majStatutPalette=false;
	// mettre à jour len uméro de commande
	$req=$pdoBt->prepare("UPDATE occ_cdes_numero SET statut=3 WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['expedier']
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
		$upPalette=$paletteMgr->updatePaletteCdeStatut($pdoBt,$_GET['expedier'],3);

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
include('../view/_navbar.php');


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
	if (!empty($paletteDansPanierMag)){
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



	});

</script>
<?php
require '../view/_footer-bt.php';
?>