
<?php
//https://blog.infiniclick.fr/articles/tutoriel-creer-fichier-pdf-fpdf.html
require('fpdf181/fpdf.php');
//require('image_alpha.php');
require('../../config/autoload.php');

// $pdf = new FPDF();
// $pdf->AddPage();
// $pdf->SetFont('Arial','B',16);
// $pdf->Cell(40,10,$_SESSION['nom']);
// $pdf->Text(8,38,'N° de facture : ');
// $pdf->Text(8,43,'Date : ');
// $pdf->Text(8,48,'Mode de règlement : ');
// $tosend=$pdf->Output('F','D:\www\_intranet\upload\inscr.pdf');

function listing($pdoBt)
{
	if(isset($_SESSION['id_galec']))
	{
		$req=$pdoBt->prepare("SELECT * FROM salon WHERE id_galec= :id_galec");
		$req->execute(array(
			// 'id_galec'=>$_SESSION['id_galec']
			'id_galec'=>$_SESSION['id_galec']
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}
$inscr=listing($pdoBt);


function getQrcode($pdoBt)
{
	if(isset($_SESSION['id_galec']))
	{
		$req=$pdoBt->prepare("SELECT * FROM salon JOIN qrcode ON salon.id=qrcode.id WHERE id_galec= :id_galec");
		$req->execute(array(
			// 'id_galec'=>$_SESSION['id_galec']
			'id_galec'=>$_SESSION['id_galec']
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}
$qrcode=getQrcode($pdoBt);



class PDF extends FPDF
{

// Tableau coloré
	function FancyTable($header, $inscr)
	{
		// $this->Ln(14);
		$this->Image('bt300.jpg',10,15);
		$this->Ln(50);
	   // Couleurs, épaisseur du trait et police grasse
		$this->SetFont('Arial','',24);
		$this->Cell(180,10,'      INSCRIPTIONS - SALON BTLEC 2018');
		$this->Ln(12);
		$this->SetFont('Arial','',16);
		$this->Cell(180,10,'                  Leclerc '. $_SESSION['nom'] .' - '.$_SESSION['code_bt'].' - '.$_SESSION['id_galec'] );
		$this->SetFont('Arial','',12);
		$this->Ln(30);
		$this->Cell(8,0,'Bonjour,');
		$this->Ln(14);
		$this->Cell(8,0,utf8_decode('Vous trouverez ci dessous le récapitulatif des inscrits pour votre magasin pour le salon'));
		$this->Ln(6);
		$this->Cell(8,0,'BTLec EST du 12 au 13 juin 2018');
		$this->Ln(14);
		$this->Cell(8,0,utf8_decode('Nous vous rappelons que les visites de l\'entrepôt et de la SCAPSAV 51 se dérouleront :'));
		$this->Ln(6);
		$this->Cell(8,0,'   - le mardi : entre 14h00 et 17h30');
		$this->Ln(6);
		$this->Cell(8,0,'   - le mercredi : entre 09h00 et 16h00');
		$this->Ln(10);
    // $this->Cell(8,0,'Liste des inscrits');

		$this->Ln(20);
    // $this->Text(8,0,);

		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
    // En-tête
		$w = array(40, 40, 25, 25, 25, 25);
    // parcours les colonnes
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		$this->Ln();
    // Restauration des couleurs et de la police
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('Arial');
    // Données
		$fill = false;

		foreach($inscr as $res)
		{
			//if($res['date1']!=""){
				$this->Cell($w[0],6,ucfirst(strtolower(utf8_decode($res['nom']))),'LR',0,'L',$fill);
				$this->Cell($w[1],6,ucfirst(strtolower(utf8_decode($res['prenom']))),'LR',0,'L',$fill);
				$this->Cell($w[2],6,$res['date1'],'LR',0,'R',$fill);
				$this->Cell($w[2],6,$res['date2'],'LR',0,'R',$fill);
				$this->Cell($w[3],6,$res['visite'],'LR',0,'R',$fill);
				$this->Cell($w[4],6,$res['repas2'],'LR',0,'R',$fill);
				// $this->Cell($w[5],6,$res['scapsav1'],'LR',0,'R',$fill);
				$this->Ln();
				$fill = !$fill;
		}
    // Trait de terminaison
		$this->Cell(array_sum($w),0,'','T');
	}

	function genQrCode($file,$nom,$prenom,$mag,$datePres,$pano)
	{
		$this->Image('bt300.jpg',10,15);
		$this->Ln(50);
		$this->SetFont('Arial','',24);
		$this->Cell(180,0,'INVITATION AU SALON BTLEC 2018');
		$this->SetFont('Arial','',12);

		$this->Ln(24);
		// $this->SetFont('Arial','',14);
		$this->Cell(8,0,"      Leclerc " . $mag ." - " .$pano);
		$this->Ln(6);
		$this->Cell(8,0,"      Participant : " .utf8_decode($prenom) ." ". utf8_decode($nom));
		$this->Ln(6);
		$this->Cell(8,0,"      ". $datePres);
		$this->Ln(30);
		// $this->Ln(20);
		$this->Cell(8,0,utf8_decode('Merci de vous munir impérativement de ce document lors de votre venue. Il sera à présenter à '));
		$this->Ln(6);
		$this->Cell(8,0,utf8_decode('l\'accueil du salon afin d\'obtenir votre badge.'));
		// $this->Cell(180,40,"Invitation de M. ou Mme " . $nom ." " .$prenom );
		$this->Ln(40);
		$this->Image($file,80,160);


	}

}



$pdf = new PDF();
// Titres des colonnes
$header = array('Nom', 'Prenom', '12/06/2018', '13/06/2018', 'Visite', 'Repas');
// Chargement des données
// $data = $pdf->LoadData('pays.txt');
$pdf->SetFont('Arial','',14);
// $pdf->AddPage();
// $pdf->BasicTable($header,$data);
// $pdf->AddPage();
// $pdf->ImprovedTable($header,$data);
$pdf->AddPage();

$pdf->FancyTable($header,$inscr);
foreach ($qrcode as $img)
{
	$pdf->AddPage();
    //
    //date de présence sur le salon

	if($img['date1']=="oui" && $img['date2']=="oui")
	{
		$datePres="Dates : Mardi 12 juin et mercredi 13 juin";
	}
	else
	{
		if($img['date1']=="oui")
		{
			$datePres="Date : Mardi 12 juin";
		}
		elseif ($img['date2']=="oui") {
			$datePres="Date : Mercredi 13 juin";
		}
	}


	$file=SITE_ADDRESS."/public/img/qrcode/" .$img['qrcode'] . ".jpg";
	$pdf->genQrCode($file, $img['nom'],$img['prenom'],$img['nom_mag'],$datePres,$img['id_galec']);
}


$pdf->Output();














?>