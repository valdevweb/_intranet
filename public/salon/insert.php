<?php
//insert.php;
 require('../../config/autoload.php');
if(isset($_POST["nom"]))
{

  $del=$pdoBt->prepare("DELETE FROM salon WHERE id_galec=:id_galec");
  $del->execute(array(
    ':id_galec'=>$_SESSION['id_galec']
  ));
 // $order_id = uniqid();
 for($count = 0; $count < count($_POST["nom"]); $count++)
 {
  // global $pdoBt;
  $query = "INSERT INTO salon
  (id_galec, nom_mag,nom,prenom,fonction,date,entrepot,scapsav, repas)
  VALUES (:id_galec, :nom_mag, :nom, :prenom, :fonction, :date,:entrepot,:scapsav,:repas)
  ";
  $statement = $pdoBt->prepare($query);
  $statement->execute(
   array(
      ':id_galec'=>$_SESSION['id_galec'],
      ':nom_mag' => $_SESSION['nom'],
      ':nom' => strip_tags($_POST['nom'][$count]),
      ':prenom'=>strip_tags($_POST['prenom'][$count]),
      ':fonction'=>strip_tags($_POST['fonction'][$count]),
      ':date'=>strip_tags($_POST['date-salon'][$count]),
      ':entrepot'=>strip_tags($_POST['entrepot'][$count]),
      ':scapsav'=>strip_tags($_POST['sav'][$count]),
      ':repas'=>strip_tags($_POST['repas'][$count])
   )
  );
 }
 $result = $statement->fetchAll();
 if(isset($result))
 {
  echo 'ok';
 }

 /*
require('fpdf181/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,$_SESSION['nom']);
$pdf->Text(8,38,'N° de facture : ');
$pdf->Text(8,43,'Date : ');
$pdf->Text(8,48,'Mode de règlement : ');
// Insère un logo en haut à gauche à 300 dpi
$pdf->Image('logo.png',10,10,-300);
$tosend=$pdf->Output('F','D:\www\_intranet\upload\inscr.pdf');
*/

}
?>