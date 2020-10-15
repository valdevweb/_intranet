<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require_once '../../vendor/autoload.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------





function getAllDossier($pdoLitige)
{
	if(!empty($_SESSION['form-data']['search_strg'])){
		$strg=" AND concat(dossiers.dossier,magasin.mag.deno,dossiers.galec,magasin.mag.id) LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}else{
		$strg='';
	}
	if(!isset($_SESSION['form-data-deux']['date_start'])){
		$dateStart= (new DateTime("2019-01-01"))->format("Y-m-d H:i:s");
	}else{
		$dateStart=$_SESSION['form-data-deux']['date_start']. ' 00:00:00';
	}

	if(!isset($_SESSION['form-data']['date_end'])){
		$dateEnd= (new DateTime())->format("Y-m-d H:i:s");
	}else{
		$dateEnd=$_SESSION['form-data']['date_end'].' 23:59:59';
	}


	if(!empty($_SESSION['form-data']['etat'])){
		  $reqEtat=' and '.join(' OR ', array_map(function($value){return 'id_etat='.$value;},$_SESSION['form-data']['etat']));
	}
	else
	{
		$reqEtat='';
	}

	// echo $reqEtat;
	// attention quand pendig =0, c'est vide
	if(!empty($_SESSION['filter-data']['pending'])){
		if($_SESSION['filter-data']['pending']=='pending'){
			$reqCommission= ' AND commission !=1 ';
		}
		else{
			$reqCommission= ' AND commission =' .intval($_SESSION['filter-data']['pending']);

		}
	}
	else{
		$reqCommission='';
	}

	if(isset($_SESSION['filter-data']['vingtquatre'])){
		if($_SESSION['filter-data']['vingtquatre']==1){
			$reqLivraison= ' AND vingtquatre=1 ';
		}elseif($_SESSION['filter-data']['vingtquatre']==0){
			$reqLivraison= ' AND vingtquatre='.intval(0);
		}
	}
	else{
		$reqLivraison= '';
	}

	$query="SELECT dossiers.id as id_main, dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,DATE_FORMAT(date_cloture, '%d-%m-%Y') as dateclos, user_crea,dossiers.galec,etat_dossier, magasin.mag.deno, magasin.mag.centrale, magasin.mag.id as btlec,vingtquatre, etat, transporteur, affrete, transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, esp,
		details.palette, details.facture, DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture ,details.article, details.ean, details.dossier_gessica, details.descr, details.qte_cde, details.tarif, details.fournisseur, qte_litige, inv_palette, inv_article,inv_descr, inv_tarif, inv_fournisseur,inv_qte, reclamation, id_reclamation, valo,inversion,imputation, typo,analyse, conclusion, valo_line, details.serials,
		qte_litige, inv_article,inv_descr, inv_tarif, inv_fournisseur,inv_qte, reclamation, id_reclamation, valo,inversion,imputation, typo,analyse, conclusion

		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE
		(date_crea BETWEEN :date_start AND :date_end)
		$strg $reqEtat
		$reqCommission $reqLivraison
		ORDER BY dossiers.dossier DESC";

	$req=$pdoLitige->prepare($query);
	$req->execute(array(

		':date_start'=>$dateStart,
		':date_end'	=>$dateEnd,

	));
	// return $req->errorInfo();

	return $req->fetchAll(PDO::FETCH_ASSOC);
}
	// echo "<pre>";
	// print_r($dossiers);
	// echo '</pre>';

// exit;
// function getPaletteCde($pdoLitige,$id_dossier)
// {
// 	$req=$pdoLitige->prepare("SELECT sum(tarif) as tarif_palette_cde, sum(qte_cde) as qte_art_cde FROM details WHERE id_dossier= :id_dossier");
// 	$req->execute(array(
// 		':id_dossier'	=>$id_dossier
// 	));
// 	return $req->fetch(PDO::FETCH_ASSOC);
// }

function getPaletteRecu($pdoLitige, $id_dossier)
{
	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main, dossiers.dossier,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,DATE_FORMAT(date_cloture, '%d-%m-%Y') as dateclos, magasin.mag.deno, magasin.mag.id as btlec, dossiers.galec,magasin.mag.centrale, etat, vingtquatre, esp, etat_dossier, palette_inv.palette as palette,DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture, palette_inv.article, palette_inv.ean,palette_inv.descr, palette_inv.qte_cde, palette_inv.tarif as valo_line,palette_inv.fournisseur, palette_inv.cnuf,
		CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg,
		mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, imputation, typo,analyse, conclusion, transporteur, transit, affrete

	 FROM palette_inv
		LEFT JOIN dossiers ON palette_inv.id_dossier=dossiers.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE palette_inv.id_dossier= :id_dossier GROUP BY palette_inv.id");
	$req->execute(array(
		':id_dossier'	=>$id_dossier
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


function nullToZero($value){
	if($value==null){
		$value=0;
	}
	return $value;
}

$dossiers=getAllDossier($pdoLitige);


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$listDossierInvPalette=[];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ------------------------------------
// JULIE
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Dossier');
$sheet->setCellValue('B1', 'Date déclaration');
$sheet->setCellValue('C1', 'Magasin');
$sheet->setCellValue('D1', 'Code BT');
$sheet->setCellValue('E1', 'Galec');
$sheet->setCellValue('F1', 'Centrale');
$sheet->setCellValue('G1', 'Etat');
$sheet->setCellValue('H1', 'Date clôture');
$sheet->setCellValue('I1', '24/48h');
$sheet->setCellValue('J1', '24/48h ESP');
$sheet->setCellValue('K1', 'Soldé');
$sheet->setCellValue('L1', 'palette');
$sheet->setCellValue('M1', 'date facture');
$sheet->setCellValue('N1', 'article');
$sheet->setCellValue('O1', 'ean');
$sheet->setCellValue('P1', 'sn');
$sheet->setCellValue('Q1', 'Désignation');
$sheet->setCellValue('R1', 'Fournisseur');
$sheet->setCellValue('S1', 'Quantité commandée');
$sheet->setCellValue('T1', 'Tarif');
$sheet->setCellValue('U1', 'Quantité litige');
$sheet->setCellValue('V1', 'Réclamation');
$sheet->setCellValue('W1', 'Article reçu');
$sheet->setCellValue('X1', 'Quantité reçue');
$sheet->setCellValue('Y1', 'EAN / palette reçu');
$sheet->setCellValue('Z1', 'Tarif article reçu');
$sheet->setCellValue('AA1', 'Valo');
$sheet->setCellValue('AB1', 'Transporteur');
$sheet->setCellValue('AC1', 'Affreteur');
$sheet->setCellValue('AD1', 'Transit');
$sheet->setCellValue('AE1', 'Preparateur');
$sheet->setCellValue('AF1', 'Contrôleur');
$sheet->setCellValue('AG1', 'Chargeur');
$sheet->setCellValue('AH1', 'Réglement Transporteur');
$sheet->setCellValue('AI1', 'Réglement assurance');
$sheet->setCellValue('AJ1', 'Réglement fournisseur');
$sheet->setCellValue('AK1', 'Réglement magasin');
$sheet->setCellValue('AL1', 'Facture magasin');
$sheet->setCellValue('AM1', 'Typologie');
$sheet->setCellValue('AN1', 'Imputation');
$sheet->setCellValue('AO1', 'Analyse');
$sheet->setCellValue('AP1', 'Réponse');

$styleArray = [
	'font' => [
		'bold' => true,
		'color'=>['rgb'=>'FFFFFF']
	],
	'borders' => [
		'top' => [
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		],
	],
];
$styleArrayDossier = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => '0075BC',
		],
	],
];
$styleArrayDetail = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => '004570',
		],
	],
];
$styleArrayMontant = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => 'FF5666',
		],
	],
];
$styleArrayAutre = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => '38686A',
		],
	],
];
$styleArrayEquipe = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => 'A3B4A2',
		],
	],
];
$styleArrayAnalyse = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => 'FF1B31',
		],
	],
];

$spreadsheet->getActiveSheet()->getStyle('A1:AP1')->applyFromArray($styleArray);

$spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArrayDossier);
$spreadsheet->getActiveSheet()->getStyle('L1:Z1')->applyFromArray($styleArrayDetail);
$spreadsheet->getActiveSheet()->getStyle('AA1:AC1')->applyFromArray($styleArrayAutre);
$spreadsheet->getActiveSheet()->getStyle('AD1:AF1')->applyFromArray($styleArrayEquipe);
$spreadsheet->getActiveSheet()->getStyle('AG1:AL1')->applyFromArray($styleArrayMontant);
$spreadsheet->getActiveSheet()->getStyle('AM1:AP1')->applyFromArray($styleArrayAnalyse);

$dossierW=0;

$row=2;
foreach ($dossiers as $key => $dossier){

	if($dossier['vingtquatre']==1){
		$vingtquatre='oui';
	}else{
		$vingtquatre='non';
	}

	if($dossier['esp']==1){
		$esp='oui';
	}else{
		$esp='non';
	}


	// si inversion de palette, il faut aller chercher dans la table palette_inv
	if($dossier['id_reclamation']==7){
		$valoLig= $dossier['valo_line'];
		if(!in_array($dossier['id_main'],$listDossierInvPalette)){
			$listDossierInvPalette[]=$dossier['id_main'];
		}
	}elseif($dossier['id_reclamation']==5){
		// ce qui a été commandé - ce qui a été reçu
		if($dossier['inv_tarif']==null){
			$valoLig=(($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige']);

		}else{
			$valoLig=(($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige'])-($dossier['inv_qte']*$dossier['inv_tarif']);
		}
		//
	}

	else{
		$valoLig= $dossier['valo_line'];

	}

	if($dossier['id_main']==$dossierW){
		$mtTransp=0;
		$mtAssur=0;
		$mtFourn=0;
		$mtMag=0;
	}else{

		$mtTransp=nullToZero($dossier['mt_transp']);
		$mtAssur=nullToZero($dossier['mt_assur']);
		$mtFourn=nullToZero($dossier['mt_fourn']);
		$mtMag=nullToZero($dossier['mt_mag']);
	}


	$solde=($dossier['etat_dossier']==0)?'non':'oui';
	$sheet->setCellValue('A'.$row, $dossier['dossier']);
	$sheet->setCellValue('B'.$row, $dossier['datecrea']);
	$sheet->setCellValue('C'.$row, $dossier['deno']);
	$sheet->setCellValue('D'.$row, $dossier['btlec']);
	$sheet->setCellValue('E'.$row, $dossier['galec']);
	$sheet->setCellValue('F'.$row, $dossier['centrale']);
	$sheet->setCellValue('G'.$row, $dossier['etat']);
	$sheet->setCellValue('H'.$row, $dossier['dateclos']);
	$sheet->setCellValue('I'.$row, $vingtquatre);
	$sheet->setCellValue('J'.$row, $esp);
	$sheet->setCellValue('K'.$row, $solde);
	$sheet->setCellValue('L'.$row, $dossier['palette']);
	$sheet->setCellValue('M'.$row, $dossier['datefacture']);
	$sheet->setCellValue('N'.$row, $dossier['article']);
	$sheet->setCellValue('O'.$row, $dossier['ean']);
	$sheet->setCellValue('P'.$row, $dossier['serials']);
	$sheet->setCellValue('Q'.$row, $dossier['descr']);
	$sheet->setCellValue('R'.$row, $dossier['fournisseur']);
	$sheet->setCellValue('S'.$row, $dossier['qte_cde']);
	$sheet->setCellValue('T'.$row, $dossier['tarif']);
	$sheet->setCellValue('U'.$row, $dossier['qte_litige']);
	$sheet->setCellValue('V'.$row, $dossier['reclamation']);
	$sheet->setCellValue('W'.$row, $dossier['inv_article']);
	$sheet->setCellValue('X'.$row, $dossier['inv_qte']);
	$sheet->setCellValue('Y'.$row, $dossier['inversion']);
	$sheet->setCellValue('Z'.$row, $dossier['inv_tarif']);
	$sheet->setCellValue('AA'.$row, $valoLig);
	$sheet->setCellValue('AB'.$row, $dossier['transporteur']);
	$sheet->setCellValue('AC'.$row, $dossier['affrete']);
	$sheet->setCellValue('AD'.$row, $dossier['transit']);
	$sheet->setCellValue('AE'.$row, $dossier['fullprepa']);
	$sheet->setCellValue('AF'.$row, $dossier['fullctrl']);
	$sheet->setCellValue('AG'.$row, $dossier['fullchg']);
	$sheet->setCellValue('AH'.$row, $mtTransp);
	$sheet->setCellValue('AI'.$row, $mtAssur);
	$sheet->setCellValue('AJ'.$row, $mtFourn);
	$sheet->setCellValue('AK'.$row, $mtMag);
	$sheet->setCellValue('AL'.$row, $dossier['fac_mag']);
	$sheet->setCellValue('AM'.$row, $dossier['typo']);
	$sheet->setCellValue('AN'.$row, $dossier['imputation']);
	$sheet->setCellValue('AO'.$row, $dossier['analyse']);
	$sheet->setCellValue('AP'.$row, $dossier['conclusion']);

	$dossierW=$dossier['id_main'];
	$row++;
}


if(!empty($listDossierInvPalette)){
	for ($i=0; $i <count($listDossierInvPalette) ; $i++) {
		$dossiers=getPaletteRecu($pdoLitige, $listDossierInvPalette[$i]);


		if($dossier['id_main']==$dossierW){
			$mtTransp=0;
			$mtAssur=0;
			$mtFourn=0;
			$mtMag=0;
		}else{
		$mtTransp=nullToZero($dossier['mt_transp']);
		$mtAssur=nullToZero($dossier['mt_assur']);
		$mtFourn=nullToZero($dossier['mt_fourn']);
		$mtMag=nullToZero($dossier['mt_mag']);
		}

		foreach ($dossiers as $key => $dossier){

			if($dossier['vingtquatre']==1){
				$vingtquatre='oui';
			}else{
				$vingtquatre='non';
			}
			if($dossier['esp']==1){
				$esp='oui';
			}else{
				$esp='non';
			}
			$vide="";
			$solde=($dossier['etat_dossier']==0)?'non':'oui';
			$sheet->setCellValue('A'.$row, $dossier['dossier']);
			$sheet->setCellValue('B'.$row, $dossier['datecrea']);
			$sheet->setCellValue('C'.$row, $dossier['deno']);
			$sheet->setCellValue('D'.$row, $dossier['btlec']);
			$sheet->setCellValue('E'.$row, $dossier['galec']);
			$sheet->setCellValue('F'.$row, $dossier['centrale']);
			$sheet->setCellValue('G'.$row, $dossier['etat']);
			$sheet->setCellValue('H'.$row, $dossier['dateclos']);
			$sheet->setCellValue('I'.$row, $vingtquatre);
			$sheet->setCellValue('J'.$row, $esp);
			$sheet->setCellValue('K'.$row, $solde);
			$sheet->setCellValue('L'.$row, $dossier['palette']);
			$sheet->setCellValue('M'.$row, $dossier['datefacture']);
			$sheet->setCellValue('N'.$row, $dossier['article']);
			$sheet->setCellValue('O'.$row, $dossier['ean']);
			$sheet->setCellValue('P'.$row, $dossier['serials']);
			$sheet->setCellValue('Q'.$row, $dossier['descr']);
			$sheet->setCellValue('R'.$row, $dossier['fournisseur']);
			$sheet->setCellValue('S'.$row, $dossier['qte_cde']);
			$sheet->setCellValue('T'.$row, $dossier['valo_line']);
			$sheet->setCellValue('U'.$row, $dossier['qte_cde']);
			$sheet->setCellValue('V'.$row, "Inversion palette");
			$sheet->setCellValue('W'.$row, $vide);
			$sheet->setCellValue('X'.$row, $vide);
			$sheet->setCellValue('Y'.$row, $vide);
			$sheet->setCellValue('Z'.$row, $vide);
			$sheet->setCellValue('AA'.$row, -$dossier['valo_line']);
			$sheet->setCellValue('AB'.$row, $dossier['transporteur']);
			$sheet->setCellValue('AC'.$row, $dossier['affrete']);
			$sheet->setCellValue('AD'.$row, $dossier['transit']);
			$sheet->setCellValue('AE'.$row, $dossier['fullprepa']);
			$sheet->setCellValue('AF'.$row, $dossier['fullctrl']);
			$sheet->setCellValue('AG'.$row, $dossier['fullchg']);
			$sheet->setCellValue('AH'.$row, $mtTransp);
			$sheet->setCellValue('AI'.$row, $mtAssur);
			$sheet->setCellValue('AJ'.$row, $mtFourn);
			$sheet->setCellValue('AK'.$row, $mtMag);
			$sheet->setCellValue('AL'.$row, $dossier['fac_mag']);
			$sheet->setCellValue('AM'.$row, $dossier['typo']);
			$sheet->setCellValue('AN'.$row, $dossier['imputation']);
			$sheet->setCellValue('AO'.$row, $dossier['analyse']);
			$sheet->setCellValue('AP'.$row, $dossier['conclusion']);
	$dossierW=$dossier['id_main'];

			$row++;

		}

	}
}


 // dimensionnement des colnes
$cols=['A','B','C','D','E','F','G', 'H', 'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC', 'AD','AE','AF','AG', 'AH', 'AI','AJ', 'AK', 'AL','AM', 'AN', 'AO', 'AP'];
for ($i=0; $i < sizeof($cols) ; $i++)
{
	$sheet->getColumnDimension($cols[$i])->setAutoSize(true);
}
$sheet->setTitle('litiges');




// pour lancer le téléchargement sur le poste client
$writer = new Xlsx($spreadsheet);
$writer->save('export-litiges.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export-litiges.xlsx"');
$writer->save("php://output");
exit;





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