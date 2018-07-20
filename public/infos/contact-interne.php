<?php

require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";


function getPersonel($pdoBt)
{
	$req=$pdoBt->query("SELECT * FROM btlec LEFT JOIN services ON btlec.id_service= services.id  WHERE nom !='BART' and nom !='utilisateur' ORDER BY id_service");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-mig.php');
include('../view/_navbar.php');

$data=getPersonel($pdoBt);
?>
<div class="container">
	<!-- main title -->
	<div class="row">

			<h1 class="blue-text text-darken-4">Répertoire téléphonique interne</h1>
</div>
	<div class="row">

		<div class="col-2"></div>

		<div class="col-8">
			<br>
			<table class="table table-striped">
				 <thead class="thead-dark">
    				<tr>
    					<th class="focus asc selected">Service</th>
    					<th>Nom</th>
    					<th>Prénom</th>
    					<th>Poste</th>
    					<th>Ligne directe</th>
    				</tr>
    			</thead>

    				<?php foreach($data as $contact){
    					echo '<tr><td>'.$contact['full_name'] .'</td>';
    					echo '<td>'.$contact['nom'].'</td>';
    					echo '<td>'.$contact['prenom'] .'</td>';
    					echo '<td>'.$contact['short_tel'] .'</td>';
    					echo '<td>'. $tel=isset($contact['tel']) ? $contact['tel'] : $contact['mobile'] .'</td></tr>';

    				}
    				?>
			</table>
		<div class="col-2"></div>

		</div>
	</div>
</div>
<script src="../js/sorttable.js"></script>

<?php
include('../view/_footer.php');

?>