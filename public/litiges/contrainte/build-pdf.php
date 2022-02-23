<?php 

$mpdf = new \Mpdf\Mpdf();
$mpdf->setFooter(PDF_FOOTER_PAGE);
// $stylesheet = file_get_contents('pdf.css'); 
$mpdf->WriteHTML($html);
// données pour l'envoi du mail
$pdfContent = $mpdf->Output('', 'S');
$pdfFilename='fiche suivi litige '.$litige[0]['dossier'].' -  demande d\'intervention.pdf';