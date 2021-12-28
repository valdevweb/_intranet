<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

require '../../Class/UserDao.php';

$userDao=new UserDao($pdoUser);
$droitExploitLcom=$userDao->isUserAllowed([44]);

if(!$droitExploitLcom){
	header('Location:../home/home.php?access-denied');
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




//----------------------------------------------------------------
//			functions
//----------------------------------------------------------------
function getDoc($pdoBt)
{
	$req=$pdoBt->query("SELECT doc_lcom.id as id_doc,filename,webname,id_cat,DATE_FORMAT(date_upload,'%d/%m/%Y') as date_upload, doc_lcom_cat.nom FROM doc_lcom LEFT JOIN doc_lcom_cat ON id_cat=doc_lcom_cat.id ORDER BY date_upload DESC");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$allDoc=getDoc($pdoBt);

function delete($pdoBt){
	$req=$pdoBt->prepare("DELETE FROM doc_lcom WHERE id= :id");
	$req->execute(array(
		':id'	=>$_GET['delete']
	));
	$row=$req->rowCount();
	return	$row;
}


$errors=[];
$success=[];

//----------------------------------------------------------------
//			traitement formmulaire
//----------------------------------------------------------------
if(isset($_GET['delete']))
{
	$row=delete($pdoBt);
	if($row>0)
	{
		$success[]="le document a été supprimé";
		// header('Location:'. ROOT_PATH.'/public/lcom/move-lcom.php');


	}
	else
	{
		$errors[]="suppression impossible";
		// header('Location:'. ROOT_PATH.'/public/lcom/move-lcom.php');


	}
}
include ('../view/_head-bt.php');
include ('../view/_navbar.php');
?>

<div class="container white-container shadow">
	<h1 class="blue-text text-darken-4">Espace Lcommerce</h1>
	<br><br>
	<div class="row">
		<div class="col-1"></div>
		<div class="col-10 border p-5">
			<h3 class="text-center text-orange pb-1">Gestion des documents</h3>
			<p class="text-center text-orange pb-5"><i class="fa fa-files-o fa-2x" aria-hidden="true"></i></p>
			<!-- start table -->
			<table class="table">
				<thead class="thead-blue">
					<tr>
						<th>Nom du document</th>
						<th>Déposé le </th>
						<th>Catégorie</th>
						<th class="text-center">Supprimer</th>
						<th class="text-center">Changer de catégorie</th>
					</tr>
				</thead>
				<tbody>



					<?php
					$cat="";
					foreach ($allDoc as $doc)
					{
						echo '<tr><td><a  class="blue-link" href="'.URL_UPLOAD.'\lcom\\'.$doc['filename'] .'">' .$doc['webname'].'</a></td>';
						echo '<td>'.$doc['date_upload'].' </td>';
						echo '<td>'.$doc['nom'].' </td>';
						echo '<td class="text-center"><a href="move-lcom.php?delete='.$doc['id_doc'].'"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></td>';
						echo '<td class="text-center"><a href="chg-lcom.php?id='.$doc['id_doc'].'"><i class="fa fa-list" aria-hidden="true"></i></a></td>';


					}
					?>

				</tbody>
			</table>
			<p class="text-center text-grey pt-5"><i class="fa fa-ravelry" aria-hidden="true"></i><i class="fa fa-ravelry" aria-hidden="true"></i><i class="fa fa-ravelry" aria-hidden="true"></i></p>
			<?php include('../view/_errors.php')  ?>

		</div>
		<div class="col-1"></div>

	</div>


</div> <!-- ./container -->


<?php





// footer avec les scripts et fin de html
include('../view/_footer-bt.php');
?>