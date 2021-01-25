<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

//----------------------------------------------------------------
// require "../../functions/stats.fn.php";
// $descr="signaler la mise à dispo de nouveaux reversements";
// $page=basename(__file__);
// $action="consultation";
// $code=101;
// addRecord($pdoStat,$page,$action, $descr,$code);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=basename(__file__);
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//header et nav bar
include ('../view/_head-mig-bis.php');
include ('../view/_navbar.php');

//----------------------------------------------------------------
//			functions
//----------------------------------------------------------------
function getDoc($pdoBt)
{
	$req=$pdoBt->query("SELECT filename,webname,id_cat, doc_lcom_cat.nom FROM doc_lcom LEFT JOIN doc_lcom_cat ON id_cat=doc_lcom_cat.id ORDER BY doc_lcom_cat.nom,date_upload");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$allDoc=getDoc($pdoBt);

$errors=[];
$success=[];

//----------------------------------------------------------------
//			traitement formmulaire
//----------------------------------------------------------------


?>

<div class="container white-container shadow">
	<h1 class="blue-text text-darken-4">Espace Lcommerce</h1>
	<br><br>
	<div class="row">
		<div class="col-1"></div>
		<div class="col-10 border p-5">
				<h3 class="text-center text-orange pb-1">Vos documents</h3>
			<p class="text-center text-orange pb-5"><i class="fa fa-files-o fa-2x" aria-hidden="true"></i></p>


			<?php
			$cat="";
				foreach ($allDoc as $doc)
				{
					if($cat!=$doc['id_cat'])
					{
						echo '<p class="mt-5 font-weight-bold">'.$doc['nom'].' : </p>';
						echo '<a  class="blue-link" href="'.URL_UPLOAD.'\lcom\\'.$doc['filename'] .'">- ' .$doc['webname'].'</a><br>';
						$cat=$doc['id_cat'];
					}
					else
					{
						echo '<a  class="blue-link" href="'.URL_UPLOAD.'\lcom\\'.$doc['filename'] .'">- ' .$doc['webname'].'</a><br>';

					}
				}
			 ?>
			</ul>
			<p class="text-center text-grey pt-5"><i class="fa fa-ravelry" aria-hidden="true"></i><i class="fa fa-ravelry" aria-hidden="true"></i><i class="fa fa-ravelry" aria-hidden="true"></i></p>

		</div>
		<div class="col-1"></div>

	</div>


</div> <!-- ./container -->


<?php





// footer avec les scripts et fin de html
include('../view/_footer-mig-bis.php');
?>