<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}



//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------


require_once  '../../vendor/autoload.php';
require '../../Class/MagHelpers.php';


function getAllTimeTopCA($pdoLitige){
	$req=$pdoLitige->query("SELECT sum(valo) as ca_total, galec FROM dossiers GROUP BY galec ORDER BY sum(valo) DESC  ");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getMonthTopCA($pdoLitige){
	$req=$pdoLitige->query("SELECT sum(valo) as ca_total, galec FROM dossiers WHERE DATE_FORMAT(date_crea, '%Y%m') =DATE_FORMAT(NOW(),'%Y%m') GROUP BY galec ORDER BY sum(valo) DESC ");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function getAllTimeNb($pdoLitige){
	$req=$pdoLitige->query("SELECT count(id) as nb, galec FROM dossiers GROUP BY galec ORDER BY count(id) DESC  ");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getMonthNb($pdoLitige){
	$req=$pdoLitige->query("SELECT count(id) as nb, galec FROM dossiers WHERE DATE_FORMAT(date_crea, '%Y%m') =DATE_FORMAT(NOW(),'%Y%m') GROUP BY galec ORDER BY  count(id) DESC ");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$allTimeTopCa=getAllTimeTopCA($pdoLitige);
$monthTopCa=getmonthTopCA($pdoLitige);



$allTimeNb=getAllTimeNb($pdoLitige);
$monthNb=getmonthNb($pdoLitige);

$caAnnuelByGalec=[];
$caMonthByGalec=[];
$nbMensuelByGalec=[];
$nbAllTimeByGalec=[];

foreach ($allTimeTopCa as $key => $value) {
	$caAnnuelByGalec[$value['galec']]=$value['ca_total'];
}
foreach ($monthNb as $key => $value) {
	$nbMensuelByGalec[$value['galec']]=$value['nb'];

}
foreach ($allTimeNb as $key => $value) {
	$nbAllTimeByGalec[$value['galec']]=$value['nb'];

}

foreach ($monthTopCa as $key => $value) {
	$caMonthByGalec[$value['galec']]=$value['ca_total'];
}

ob_start();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{
			font-family: helvetica, sans-serif;
			font-size: 14pt;
			color:  #212529;
		}
		.bigger{
			font-size: 20px;
		}
		.txt-small{
			font-size: 10pt;
		}
		th{
			background-color :#0D47A1;
			border: 1px solid #0D47A1;
			border-collapse: collapse;
			color: #f8f9fa;
			padding: 10px;
		}
		table,td, tr{
			font-size: 11px;
			border-collapse: collapse;

		}
		table{
			border: 1px solid #999;
		}
		td{
			padding: 10px;

		}
		.heavy{
			font-weight: bold;
		}
		.text-white{
			color: #f8f9fa;
		}
		.text-black{
			color: #000;
		}

		.mx-auto{
			margin-left: auto;
			margin-right: auto;
		}
		h2{
			font-size: 16px;
		}
		.text-center{
			text-align: center;
		}
		.text-right{
			text-align : right;
		}
		.spacing-s{
			height:  15px;
			border : 0;
		}

		.spacing-m{
			height:  20px;
			border : 0;
		}
		.spacing-l{
			height:  40px;
			border : 0;
		}
		.padding-table, .padding-table td{
			padding:  10px;
			border-collapse: collapse;
			border:  0;
		}



		.full-width{
			width: 700px;
		}
		.dix{
			width: 70px;
		}
		.neuf{
			width: 77px;
		}
		.huit{
			width: 87px;
		}
		.sept{
			width: 100px;
		}
		.six{
			width: 116px;
		}
		.cinq{
			width: 140px;
		}
		.quatre{
			width: 175px;
		}
		.trois{
			width: 233px;
		}
		.deux{
			width: 350px;
		}

	</style>
</style>
<title></title>
</head>
<body>

	<h2>Hit Parade cumulé depuis janvier 2019</h2>
	<table class="mx-auto">
		<tr>
			<th>Clt</th>
			<th>Magasin</th>
			<th>Mt annuel</th>
			<th>Nb</th>
			<th>Mt ce mois</th>
		</tr>


		<?php foreach ($allTimeTopCa as $key => $allTimeMt): ?>
			<?php if ($key<10): ?>
				<tr>
					<td class="text-right"><?=$key+1?></td>
					<td><?=MagHelpers::deno($pdoMag, $allTimeMt['galec']) . ' - '.$allTimeMt['galec']?></td>
					<td class="text-right"><?=number_format((float)$allTimeMt['ca_total'],0,'',' ') ?></td>
					<td class="text-right"><?=$nbAllTimeByGalec[$allTimeMt['galec']]?></td>
					<td class="text-right"><?=isset($caMonthByGalec[$allTimeMt['galec']])? number_format((float)$caMonthByGalec[$allTimeMt['galec']],0,'',' '):'0' ?></td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
	</table>

	<h2>Hit parade mensuel - </h2>
	<p class="txt-small">* Montant cumulé : montant des litiges déclarés de janvier 2019 à aujourd'hui</p>
	<table class="">
		<tr>
			<th>Clt</th>
			<th>Magasin</th>
			<th>Montant mensuel</th>
			<th>Montant cumulé*</th>
			<th>Nb mensuel</th>
			<th>Nb Cumul</th>
			<th>Clt montant annuel</th>
		</tr>
		<?php foreach ($monthTopCa as $key => $mtMensuel): ?>
			<?php if ($key<10): ?>
				<tr>
					<td class="text-right"><?=$key+1?></td>
					<td><?=MagHelpers::deno($pdoMag, $mtMensuel['galec']) . ' - '.$mtMensuel['galec']?></td>
					<td class="text-right"><?=number_format((float)$mtMensuel['ca_total'],0,'',' ')?></td>
					<td class="text-right"><?=number_format((float)$caAnnuelByGalec[$mtMensuel['galec']],0,'',' ')?></td>
					<td class="text-right"><?=$nbMensuelByGalec[$mtMensuel['galec']]?></td>
					<td class="text-right"><?=$nbAllTimeByGalec[$mtMensuel['galec']]?></td>
					<td class="text-right"><?= array_search( $mtMensuel['galec'], array_column($allTimeTopCa, 'galec'))+1?></td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
	</table>
</body>
</html>


<?php

$html=ob_get_contents();
ob_end_clean();
$header = (date('d-m-Y'));


$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLFooter(PDF_FOOTER);
$mpdf->SetHeader ($header);

$mpdf->WriteHTML($html);

$mpdf->Output('html.pdf',\Mpdf\Output\Destination::INLINE);

?>
