<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}

// require 'pdfgenmail.php';
//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

// require('fpdf181/fpdf.php');

// <---------------------------------------------------
// STATS - add rec
//-----------------------------------------------------
// require "../../functions/stats.fn.php";
// $descr="demande mag au service ".$gt ;
// $page=basename(__file__);
// $action="envoi d'une demande";
// addRecord($pdoStat,$page,$action, $descr);
//------------------------------------->
//----------------------------------------------------
// DATAS
//----------------------------------------------------

// https://phppot.com/php/php-mysql-inline-editing-using-jquery-ajax/

// récup les inscriptions du magasin
function listing($pdoBt)
{
	if(isset($_SESSION['id_galec']))
	{
		$req=$pdoBt->prepare("SELECT * FROM salon JOIN qrcode ON salon.id=qrcode.id WHERE id_galec= :id_galec");
		$req->execute(array(
			'id_galec'=>$_SESSION['id_galec']
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}
$inscr=listing($pdoBt);


function addParticipant($pdoBt)
{
	if(isset($_POST['jour1'])){
		$date1="oui";

	}
	else{
		$date1="non";
	}

	if(isset($_POST['jour2'])){
		$date2="oui";
	}
	else{
		$date2="non";
	}
	if(isset($_POST['repas2'])){
		$repas="oui";
	}
	else{
		$repas="non";
	}



	if(isset($_POST['visite']))
	{
		$visite=$_POST['visite'];
	}
	else
	{
		$visite="non";
	}



	$insert=$pdoBt->prepare("INSERT INTO salon (id_galec, nom_mag,nom,prenom,fonction,date1,date2,visite,repas2) VALUES (:id_galec, :nom_mag, :nom, :prenom, :fonction, :date1, :date2,:visite, :repas2)");
	$insert->execute(array(
	  ':id_galec'=>$_SESSION['id_galec'],
      ':nom_mag' => $_SESSION['nom'],
      ':nom' => strip_tags($_POST['nom']),
      ':prenom'=>strip_tags($_POST['prenom']),
      ':fonction'=>strip_tags($_POST['fonction']),
      ':date1'=>$date1,
      ':date2'=>$date2,
      ':visite'=>$visite,
      ':repas2'=>$repas
	));

}




if(isset($_POST['inscrire'])){
	addParticipant($pdoBt);
	header("Location:inscription.php#inscription-lk");
}

if(isset($_POST['send']))
{
require('fpdf181/fpdf.php');
require '../../functions/mail.fn.php';

	class PDF extends FPDF
	{
// Tableau coloré
function FancyTable($header, $inscr)
	{
		$this->Image('bt300.jpg',5,5);
		$this->Ln(50);
	   // Couleurs, épaisseur du trait et police grasse
		$this->SetFont('Arial','',24);
		$this->Cell(180,0,'INSCRIPTIONS SALON BTLEC 2018');
		$this->SetFont('Arial','',12);
		$this->Ln(40);
		$this->Cell(8,0,'Bonjour,');
		$this->Ln(10);
		$this->Cell(8,0,utf8_decode('Vous trouverez ci dessous le récapitulatif des inscrits pour votre magasin pour le'));
		$this->Ln(10);
		$this->Cell(8,0,'salon BTLec EST du 12 au 13 juin 2018');
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

		function genQrCode($file,$nom,$prenom){
		// $this->Cell(8,0,utf8_decode('Vous trouverez ci dessous le récapitulatif des inscrits pour votre magasin pour le'));
			$this->SetFont('Arial','',14);
			$this->Cell(180,40,"Invitation de M. ou Mme " . utf8_decode($nom) ." " .utf8_decode($prenom) );
			$this->Ln(50);
			$this->Image($file,80,50);
			$this->Ln(50);

		}
	}



	$pdf = new PDF();
	$header = array('Nom', 'Prenom', '12/06/2018', '13/06/2018', 'Visite', 'Repas');

	$pdf->SetFont('Arial','',14);
	$pdf->AddPage();

	$pdf->FancyTable($header,$inscr);
	foreach ($inscr as $img) {
		$pdf->AddPage();
    	$fileName=$img['id_galec'];
		$file=SITE_ADDRESS."/public/img/qrcode/" .$img['qrcode'] . ".jpg";
		$pdf->genQrCode($file, $img['nom'],$img['prenom']);
	}
	// génération du pdf
	$pdf->Output("F","D:\www\_intranet\upload\\" .$fileName.".pdf");

	//GENERATION DU MAIL AVEC PDF
	$subject="Portail BTLec Est - vos invitations au salon BTlec 2018";
	$to=$_POST['email'];
	$from="ne_pas_repondre@btlec.fr";
	$message="Bonjour,<br>Vous trouverez ci joint vos invitations au salon.";
	// $fileatt="D:\www\_intranet\upload\\".$_SESSION['id_galec'] . ".pdf";
	$file=$fileName .".pdf";
	$path="D:\www\_intranet\upload\\";
	// require '../../functions/mail.fn.php';

	// sendMailSalon($destinataire,$objet,$emplate,$name,$magName, $link);
	$sentMail=mail_attachment($file,$path,$to,$from,"Portail BTLEC EST",$from,$subject, $message);
	 //header("Location:inscription2.php#inscription-lk");


}

//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
require '../view/_head.php';
require '../view/_navbar.php';
//----------------------------------------------------
// VIEW - CONTENT
//----------------------------------------------------
// require 'rec-inscription.php';
?>
<div class="container" id="up">
	<!-- main title -->
	<div class="row bgwhite">
		<div class="int-padding">
			<h1 class="blue-text text-darken-4">SALON TECHNIQUE BTLEC Est 2018<br><span class="sub-h1"><i class="fa fa-calendar" aria-hidden="true"></i> du 12/06/2018 au 13/06/2018</span></h1>
			<br>
			<!-- MESSAGE SUCCES - ERREUR ENVOI DE MAIL -->
			<p>
				<?php
				if(isset($_SESSION['notification']['message']))
				{
					echo "<p class='green-text'>" . $_SESSION['notification']['message']."</p>";
					$_SESSION['notification']=[];
				}

				?>

				</p>
			<div class="mini-nav center">
				<br>
				<ul>
					<li><a href="#salon-lk">Salon 2018</a></li>
					<li><a href="#inscription-lk">Inscriptions</a></li>
					<li><a href="#modalite-lk">Modalités</a></li>
				</ul>
				<br>
				<p><i class="fa fa-ravelry" aria-hidden="true"></i><i class="fa fa-ravelry" aria-hidden="true"></i><i class="fa fa-ravelry" aria-hidden="true"></i></p>
				<br>
			</div>
		</div>
	</div>
	<div class="row bggrey">
		<br>
		<!-- galerie -->
		<div class="int-padding">
			<div class="gallery cf">
				<div>
					<img src="../img/salon/aerien.jpg" />
				</div>
				<div>
					<img src="../img/salon/one.jpg" />
				</div>
				<div>
					<img src="../img/salon/convention.jpg" />
				</div>
				<div>
					<img src="../img/salon/coridor.jpg" />
				</div>
			</div>
		</div>

		<br><br>
	</div>
	<!-- descr salon -->
	<div class="row bgwhite">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="salon-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>LE SALON BTLEC 2018 </h4>
			<hr>
			<br><br>
			<p>Le salon BTlec 2018 se déroulera sur 2 jours, le <strong>12 juin 2018</strong> et le <strong>13 juin</strong>. Nous vous proposons cette année, de profiter de votre venue au salon pour visiter notre entrepôt et la SCAPSAV.</p>
			<p>Enfin d'organiser au mieux le déroulement du salon, nous vous prions de bien vouloir remplir le <a href="#inscription-lk" class="blue-link">formulaire d'inscription</a>.  Sous le formulaire d'inscription, vous trouverez les informations sur les <a href="#modalite-lk" class="blue-link">modalités d'accueil et d'accès</a> à BTlec Est</p>
			<p>Un badge vous sera remis à votre entrée du salon.</p>

			<br><br><br>
			<p class="right-align"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<!-- modalités -->
	<div class="row bggrey">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="modalite-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>MODALITES D'ACCUEIL ET ACCES</h4>
			<hr>
			<br><br>
			<ul class="browser-default">
				<li>restauration : un petit déjeuner vous sera servi sur le salon et un buffet traiteur vous accueillera le 13 juin</li>
				<li>Sociétés de taxi :
					<ul class="browser-default">
						<li><strong><a href="http://www.taxi-city-reims.com" target="_blank">taxi city</a></strong> - 06 64 90 93 43</li>
						<li><strong>taxis du vignoble</strong> - 06 06 60 60 20</li>
						<li><strong><a href="http://www.aid-taxi.com" target="_blank">AID Taxis</a></strong> - 06 16 17 68 70 ou 03 26 85 80 73</li>
					</ul>
				</li>
				<li>venir à BTlec : <a href="../mag/google-map.php" class="blue-link">coordonnées gps, carte</a></li>
			</ul>
			<br><br>
			<p class="right-align"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>


	<!-- form inscription -->
	<div class="row bgwhite">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true" id="inscription-lk"></i>INSCRIPTIONS</h4>
			<hr>
			<br><br>
			<!-- INSCRIPTION JOUR 1 -->
			<h5 class="blue-text text-darken-4">Liste des inscrits : </h5>
			<p></p>
			<table class="striped responsive-table" id="item_table">
					<tr>
						<th>Nom</th>
						<th>Prénom</th>
						<th>12/06/2018</th>
						<th>13/06/2018</th>
						<th>Visite <br>entrepot / scapsav</th>
						<th>Repas</th>
						<th>Supprimer</th>
					</tr>
				<?php if($inscr): ?>
				<!-- si le mag a déja renvoyé des inscriptions -->
					<?php foreach($inscr as $detInscr): ?>
						<!-- inscrit les 2 jour -->

							<tr>
								<td><?= $detInscr['nom']?></td>
								<td><?= $detInscr['prenom']?></td>
								<td><?= $detInscr['date1']?></td>
								<td><?= $detInscr['date2']?></td>
								<td><?= $detInscr['visite']?></td>
								<td><?= $detInscr['repas2']?></td>
								<td><a href="delete.php?id=<?= $detInscr['id']?>"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></a></td>
							</tr>
							<!-- inscrit uniquement jour 1 -->

					<?php endforeach ?>

					<?php else: ?>
						<!-- si pas d'inscrit du tout -->
						<tr>
							<td colspan="7">Aucun participant</td>
						</tr>
					<?php endif; ?>

			</table>
				<br><br>
					<?php
					if($_SESSION['type']<>'mag')
					{
						echo "<p class='red-text'>L'inscription est réservée aux magasins, vous ne pourrez pas utiliser le formulaire si votre compte utilisateur n'est pas rattaché à un magasin</p>";
					}
					?>
				<p class="align-right"><button class="btn modal-trigger" data-target="modal">Ajouter un participant</button></p>
				<!-- MODAL FORM  MAG-->
				<div class="modal" id="modal">
					<div class="modal-content">
						<form method="post" id="add" action="<?=$_SERVER['PHP_SELF']?>">
							<p>PARTICIPANT :</p>
							<p>
								<input type="text" name="nom" placeholder="nom" class="nom" required>
								<input type="text" name="prenom" placeholder="prenom" class="prenom" required></p>
								<select name="fonction" class="browser-default fonction" required>
									<option value="ADHERENT">ADHERENT</option>
									<option value="CHEF DE DEPARTEMENT">CHEF DE DEPARTEMENT</option>
									<option value="CHEF DE RAYON">CHEF DE RAYON</option>
									<option value="CHEF EC">CHEF EC</option>
									<option value="DIRECTEUR">DIRECTEUR</option>
									<option value="DIRECTEUR ADJOINT">DIRECTEUR ADJOINT</option>
									<option value="RESPONSABLE NON ALIMENTAIRE">RESPONSABLE NON ALIMENTAIRE</option>
									<option value="SAV">SAV</option>
									<option value="VENDEUR">VENDEUR</option>
								</select>

							<p>Quel(s) jour(s) souhaitez vous venir au salon ? </p>
							<div class="zone1">
								<p><input type="checkbox" name="jour1" value="oui" id="jour1" class="filled-in"><label for="jour1">Mardi 12 juin 2018</label></p>
								<!-- ajout choix si jour 1 sélectionné -->
								<p id="day1"></p>
							</div>
							<div id="zone2">
								<p><input type="checkbox" name="jour2" value="oui" id="jour2" class="filled-in"><label for="jour2">Mercredi 13 juin 2018</label></p>
								<p id="day2"></p>
							</div>
							<p>Si vous souhaitez visiter l'entrepot et la scapsav merci de sélectionner un jour disponible</p>
							<div class="zone1">
								<p id="date-visite1"></p>
								<p id="date-visite2"></p>
							</div>
							<p class="align-right"><button class="btn" type="submit" name="inscrire">Inscrire</button></p>
						</form>
					</div>
				</div>

				<br><br>
				<h5 class="blue-text text-darken-4">Mes invitations</h5>
				<ul class="browser-default line-height">
					<li>
						<a href="<?= SITE_ADDRESS?>/public/salon/pdfgen.php" target="_blank">Voir / enregistrer mes invitations</a>
					</li>
					<li>Recevoir mes invitations par mail
					</li>
				</ul>
				<form method="post" id="sendmail" action="<?=$_SERVER['PHP_SELF']?>">
				<p>
					<label>Votre adresse mail : </label><br><input class="browser-default" type="email" required="require" name="email">
					<button class="btn" type="submit" name="send">Envoyer</button>
				</p>
				</form>


				<p class="right-align"><a href="#up" class="blue-link">retour</a></p>


		</div>
	</div>


</div>   <!--fin container -->

	<script>
		$(document).ready(function(){
			$('.modal').modal();

			$(":checkbox#jour1").change(function(){
				var visiteDayOne="";
				visiteDayOne +="<p><input type='radio' name='visite' value='12/06/2018' id='visite1' class='fill-in'><label for='visite1'>12/06/2018</label>&nbsp; &nbsp; &nbsp;";
				if($('input:checkbox[name=jour1]').is(':checked'))
				{
					// $('#day2').append(daytwo);
					$('#date-visite1').append(visiteDayOne);
				}
				else
				{
					$('#date-visite1').empty();

				}
				});

			$(":checkbox#jour2").change(function(){
				var daytwo="";
				daytwo +="<p>Prendrez vous votre repas à BTlec ?</p>";
				daytwo +="<p><input type='radio' name='repas2' value='oui' id='jour2oui' class='fill-in'><label for='jour2oui'>oui</label>&nbsp; &nbsp; &nbsp;";
				daytwo+="<input type='radio' name='repas2' value='non'  id='jour2non' class='fill-in'><label for='jour2non'>non</label><br><span id='missingjour2' class='red-text'></span></p>";
				var visiteDayTwo="";
				visiteDayTwo +="<p><input type='radio' name='visite' value='13/06/2018' id='visite2' class='fill-in'><label for='visite2'>13/06/2018</label>&nbsp; &nbsp; &nbsp;";


				if($('input:checkbox[name=jour2]').is(':checked'))
				{
					$('#day2').append(daytwo);
					$('#date-visite2').append(visiteDayTwo);
				}
				else
				{
					$('#day2').empty();
					$('#date-visite2').empty();

				}

			});

			$(":checkbox#visite").change(function(){


				if($('input:checkbox[name=visite]').is(':checked'))
				{
					// $('#date-visite').append(visiteDayOne);
				}
				else
				{
					// $('#date-visite').empty();
				}

			});



			$("form#add").submit(function(event){
				// validation
				// jour1
				// if($('#jour1oui').prop('checked')==false && $('#jour1non').prop('checked')==false){
				// 	$('#missingjour1').text("Veuillez selectionner une option");
				// 		event.preventDefault();
				// }

				//jour2
				if($('#jour2oui').prop('checked')==false && $('#jour2non').prop('checked')==false){
					$('#missingjour2').text("Veuillez selectionner une option");
						event.preventDefault();
				}
				if($('#scapsav2oui').prop('checked')==false && $('#scapsav2non').prop('checked')==false){
					$('#missingscapsav2').text("Veuillez selectionner une option");
						event.preventDefault();
				}
				if($('#entrepot2oui').prop('checked')==false && $('#entrepot2non').prop('checked')==false){
					$('#missingentrepot2').text("Veuillez selectionner une option");
						event.preventDefault();
				}


			})

		});
	</script>


<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>