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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
function getCasses($pdoCasse){

	$req=$pdoCasse->query("SELECT *  FROM `casses` WHERE `id_palette` = 53  OR id_palette = 48 or id_palette = 54 or id_palette = 55   ORDER BY `article` ASC")	;
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$casseList=getCasses($pdoCasse);

function getArticle($pdoQlik, $art, $dossier){
$req=$pdoQlik->prepare("SELECT * FROM basearticles WHERE `GESSICA.CodeArticle`= :article AND `GESSICA.CodeDossier`= :dossier ORDER BY `GESSICA.CodeDossier`");
	$req->execute(array(
		':article'	=>$art,
		':dossier'=>$dossier
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updateCasse($pdoCasse, $idCasse, $pfnp,$deee, $sacem, $decote, $valo){
	$req=$pdoCasse->prepare("UPDATE casses SET pfnp= :pfnp, deee= :deee, sacem= :sacem, mt_decote= :decote, valo= :valo WHERE id= :id");
	$req->execute([
		':pfnp'	=> $pfnp,
		':deee'	=>	$deee,
		':sacem'=>	$sacem,
		':decote'	=>$decote,
		':valo'		=>$valo,
		':id'		=>$idCasse
	]);
	return $req->rowCount();
	// return $req->errorInfo();
}
$i=0;
	echo count($casseList);
$errors=[];
$success=[];
foreach ($casseList as $casse) {
	// echo $casse['article'];
	$data=getArticle($pdoQlik, $casse['article'], $casse['dossier']);
	echo "<br>";

		$valo=$casse['uvc']*$data['GESSICA.PFNP'];
		$decote=round($valo/2);

		echo $i .' : '.$valo .' '.$casse['id'] .' ' .$data['GESSICA.D3E'];
		echo "<br>";

		$maj=updateCasse($pdoCasse, $casse['id'], $data['GESSICA.PFNP'], $data['GESSICA.D3E'], $data['GESSICA.SORECOP'], $decote, $valo);


		if($maj!=1){
			$errors[]="impossible de maj l'id" .$casse['id'];
		}
		else{
			$success[]=" maj " .$casse['id'];
		}
		$i++;

}






//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------





//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>