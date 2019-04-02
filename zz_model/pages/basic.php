<?php

include('../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. AUTHENTIFICATION);

}
//----------------------------------------------------------------
//		INCLUDES
//----------------------------------------------------------------

require "../functions/stats.fn.php";
// $descr="page pour réouvrir une demande";
// $page=basename(__file__);
// $action="consultation";
// $code=101;
// addRecord($pdoStat,$page,$action, $descr,$code);


//----------------------------------------------
// css dynamique
//----------------------------------------------

$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile="../css/".$pageCss.".css";


//----------------------------------------------
//  		FUNCTIONS
//----------------------------------------------
function insert($pdoSav)
{
	$req=$pdoSav->prepare("INSERT INTO table() VALUES()");
	$req->execute(array(

	));
	return $pdoSav->lastInsertId();
	// return $req->errorInfo();
	// return	$req->rowCount();
}

function delete($pdoSav){
	$req=$pdoSav->prepare("DELETE FROM table WHERE");
	$req->execute();
	$row=$req->rowCount();
	return	$row;
}

function select($pdoSav){
	$req=$pdoSav->prepare("SELECT fields FROM table WHERE ");
	$req->execute(array(
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return	$req->errorInfo();
}

function update($pdoSav)
{
	$req=$pdoSav->prepare("UPDATE table_dest SET champ=valeur");
	$req->execute();
	$row=$req->rowCount();
	return	$row;
}


function updateFromTable($pdoSav)
{
	$req=$pdoSav->prepare("UPDATE table_dest INNER JOIN table_info ON table_dest.champ=table_info.champ SET table_dest.champ2=table_info.champ2");
	$req->execute();
	$row=$req->rowCount();
	return	$row;
}


include('../view/_head.php');
include('../view/_navbar.php');
?>

<div class="container py-5">
	<!-- main title -->
	<div class="row">
		<div class="col">
			<h1 class="text-center underline-anim mt-5"></h1>
		</div>
	</div>
	<!-- ./main title -->
	<!-- start row -->
	<div class="row">
		<div class="col-lg-1 col-xl-2"></div>
		<div class="col">

		</div>
		<div class="col-lg-1 col-xl-2"></div>
	</div>
	<!-- ./row -->



<!-- /container -->
</div>


<?php
include('../view/_footer.php');
?>