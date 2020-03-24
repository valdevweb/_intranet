<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';


function getNewMagTable($pdoMag){
	$req=$pdoMag->query("SELECT * FROM mag");
	return $req->fetchAll(PDO::FETCH_GROUP);
}

function getOldMagTable($pdoBt){
	$req=$pdoBt->query("SELECT btlec, sca3.* FROM sca3");
	return $req->fetchAll(PDO::FETCH_GROUP);
}

function compareMagToSca3($pdoMag){
	$req=$pdoMag->query("SELECT
		mag.id as magId,
		sca3.btlec as scaBtlec,
		sca3.id as scaId,
		mag.deno as magDeno,
		sca3.mag as scaDeno,
		mag.galec as magGalec,
		sca3.galec as scaGalec,
		mag.id_centrale as magIdCentrale,
		sca3.centrale as scaCentrale,
		mag.ad1 as magAd1,
		sca3.ad1 as scaAd1,
		mag.ad2 as magAd2,
		sca3.ad2 as scaAd2,
		sca3.ad3 as scaAd3,
		mag.cp as magCp,
		sca3.cp as scaCp ,
		mag.ville as magVille,
		sca3.city as scaCity,
		mag.tel as magTel,
		sca3.tel as scaTel,
		mag.fax as magFax,
		sca3.fax as scaFax,
		mag.surface as magSurf,
		sca3.surface as scaSurf,
		mag.adherent as magAdh,
		sca3.adherent as scaAdh,
		mag.directeur,
		mag.pole_sav,
		mag.closed
		FROM mag LEFT JOIN btlec.sca3 ON mag.id=btlec.sca3.btlec ");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function arrayCentrale($pdoMag){
	$req=$pdoMag->query("SELECT id_ctbt, centrale FROM centrales WHERE id_ctbt!=''");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}

$centraleArr=arrayCentrale($pdoMag);
	echo "<pre>";
	print_r($centraleArr);
	echo '</pre>';

	exit;



// $compMag=compareMagToSca3($pdoMag);
$newTable=getNewMagTable($pdoMag);
$oldTable=getOldMagTable($pdoBt);




?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.css">

	<title>Document</title>
</head>
<body>



	<table class="table table-sm">
		<thead class="thead-dark">
			<tr>

				<th>BTLec</th>
				<th>Galec</th>
				<th>Deno</th>
				<th>Centrale</th>
				<th>AD1</th>
				<th>AD2</th>
				<th>AD3</th>
				<th>CP</th>
				<th>Ville</th>
				<th>Tel</th>
				<th>Fax</th>
				<th>adhérent</th>
				<th>directeur</th>
				<th>pole_sav</th>
				<th>closed</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($newTable as $key => $newMag):?>
				<tr class="text-primary">
					<td><?=trim($key)?></td>
					<td><?=trim($newMag[0]['galec'])?></td>
					<td><?=trim($newMag[0]['deno'])?></td>
					<td><?=trim($newMag[0]['id_centrale'])?></td>
					<td><?=trim($newMag[0]['ad1'])?></td>
					<td><?=trim($newMag[0]['ad2'])?></td>
					<td><?=trim($newMag[0]['ad3'])?></td>
					<td><?=trim($newMag[0]['cp'])?></td>
					<td><?=trim($newMag[0]['ville'])?></td>
					<td><?=trim($newMag[0]['tel'])?></td>
					<td><?=trim($newMag[0]['fax'])?></td>
					<td><?=trim($newMag[0]['adherent'])?></td>
					<td><?=trim($newMag[0]['directeur'])?></td>
					<td><?=trim($newMag[0]['pole_sav'])?></td>
					<td><?=trim($newMag[0]['closed'])?></td>
				</tr>

				<?php if(isset($oldTable[$key])):?>
					<tr>
						<td><?=trim($key)?></td>
						<td><?=trim($oldTable[$key][0]['galec'])?></td>
						<td><?=trim($oldTable[$key][0]['mag'])?></td>
						<td><?=trim($oldTable[$key][0]['centrale'])?></td>
						<td><?=trim($oldTable[$key][0]['ad1'])?></td>
						<td><?=trim($oldTable[$key][0]['ad2'])?></td>
						<td><?=trim($oldTable[$key][0]['ad3'])?></td>
						<td><?=trim($oldTable[$key][0]['cp'])?></td>
						<td><?=trim($oldTable[$key][0]['city'])?></td>
						<td><?=trim($oldTable[$key][0]['tel'])?></td>
						<td><?=trim($oldTable[$key][0]['fax'])?></td>
						<td><?=trim($oldTable[$key][0]['adherent'])?></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<?php else: ?>

						<tr>
							<td colspan="14" class="text-danger">
								Ce code BTLec n'est pas présent dans le sca3
							</td>
						</tr>


					<?php endif ?>
				<?php endforeach ?>
				<tr>
							<td colspan="14" class="bg-danger">
								Magasins absent de gessica
							</td>
						</tr>
				<?php foreach ($oldTable as $key => $oldMag):?>
					<?php if(!isset($newTable[$key])):?>
						<tr>
							<td><?=trim($key)?></td>
							<td><?=trim($oldMag[0]['galec'])?></td>
							<td><?=trim($oldMag[0]['mag'])?></td>
							<td colspan="11" class="text-danger">
								Ce code BTLec n'est pas présent dans gessica
							</td>
						</tr>
					<?php endif ?>

				<?php endforeach ?>

			</tbody>
		</table>



	</body>
	</html>