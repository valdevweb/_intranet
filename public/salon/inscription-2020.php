<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}

require '../../functions/mail.fn.php';

//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require_once '../../vendor/autoload.php';
require_once '../../Class/MagHelpers.php';
require_once '../../Class/FormationDAO.php';
require_once '../../Class/FormHelpers.php';


// <---------------------------------------------------
// STATS - add rec
//-----------------------------------------------------
require "../../functions/stats.fn.php";
$descr="page inscription salon 2020";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);
// ------------------------------------->

//------------------------------------------------------
//			FONCTIONS
//------------------------------------------------------




function getFunction($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM salon_fonction ORDER BY fonction");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$fonctionList=getFunction($pdoBt);
function addParticipant($pdoBt,$mobile)
{
	$req=$pdoBt->prepare("INSERT INTO salon_2020 (date_saisie, id_web_user, galec, genre, nom, prenom, id_fonction, email, mobile, mardi, mercredi, repas_mardi, repas_mercredi)
		VALUES (:date_saisie, :id_web_user, :galec, :genre, :nom, :prenom, :id_fonction, :email, :mobile, :mardi, :mercredi, :repas_mardi, :repas_mercredi)");
	$req->execute(array(
		':date_saisie'			=>date('Y-m-d H:i:s'),
		':id_web_user'			=>$_SESSION['id_web_user'],
		':galec'			=>$_SESSION['id_galec'],
		':genre'			=>$_POST['genre'],
		':nom'			=>$_POST['nom'],
		':prenom'			=>$_POST['prenom'],
		':id_fonction'			=>$_POST['fonction'],
		':email'			=>$_POST['email'],
		':mobile'			=>$mobile,
		':mardi'			=>$_POST['mardi'],
		':mercredi'			=>$_POST['mercredi'],
		':repas_mardi'			=>$_POST['repas-mardi'],
		':repas_mercredi'			=>$_POST['repas-mercredi'],
	));
	return $req->rowCount();
}

function getParticipant($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM salon_2020 LEFT JOIN qrcode ON salon_2020.id=qrcode.id WHERE galec= :galec");
	$req->execute(array(
		':galec'	=>$_SESSION['id_galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$YesNo=array('_','oui');
if(isset($_SESSION['id_galec'])){
	$participantList=getParticipant($pdoBt);
}

$formationDOA=new FormationDAO($pdoBt);
$listCreneau=$formationDOA->getCreneaux($pdoBt);


//------------------------------------------------------
//			TRAITEMeNT
//------------------------------------------------------
if(isset($_POST['submit']))
{
	extract($_POST);

	if(isset($_POST['email']))
	{
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$errors[]="Merci de saisir une adresse mail valide";
		}
	}
	else
	{
		$errors[]="Merci de saisir une adresse mail";
	}
	// au moins une des case doit être cochée
	if(empty($_POST['mardi']) &&  empty($_POST['mercredi'])){
		$errors[]="Merci de sélectionner au moins un jour de présence";

	}
	if(isset($_POST['mardi']) && !isset($_POST['repas-mardi']))
	{
		$errors[]="Veuillez préciser si vous souhaitez prendre votre repas à BTlec ou non le mardi";
	}
	if(!isset($_POST['mardi']))
	{
		$_POST['mardi']=0;
		$_POST['repas-mardi']=0;
	}
	if(isset($_POST['mercredi']) && !isset($_POST['repas-mercredi']))
	{
		$errors[]="Veuillez préciser si vous souhaitez prendre votre repas à BTlec ou non le mercredi";
	}
	if(!isset($_POST['mercredi']))
	{
		$_POST['mercredi']=0;
		$_POST['repas-mercredi']=0;
	}
	if(!isset($_POST['mobile']))
	{
		$mobile="";
	}


	if(count($errors)==0)
	{
		$row=addParticipant($pdoBt,$mobile);
		if($row==1)
		{
			$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success-inscr=ok#inscription-lk';
			header($loc);

		}
		else
		{
			$errors[]="impossible";
		}
	}
}
else
{
	$nom=$prenom=$email="";
}

if(isset($_GET['success-inscr']))
{
	$success[]="Votre inscription a bien été prise en compte";

}


// si demande de rensiegnement


if(isset($_POST['more-info']))
{
	if(isset($_POST['msg'])){

		$formMsg=htmlspecialchars(stripslashes($_POST['msg']));
	}
	else
	{
		$errors[]="Merci de saisir votre demande";

	}
	if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
	{
		$formMail=$_POST['email'];
	}
	else
	{
		$errors[]="Merci d'indiquer votre adresse mail";
	}
	//si pas d'erreur
	if(count($errors)==0){
    	//envoi mail
		$to="salonbtlecest@btlec.fr";

		if(VERSION=="_"){
			$to="valerie.montusclat@btlec.fr";
		}


		$subject="Portail BTLec - Salon 2020 - demande de renseignement - " .$_SESSION['nom'];
		$infosMag="demande du magasin : " . $_SESSION['nom'] ." - " .$_SESSION['id_galec'] ."<br><br>";
		$formMsg= $infosMag .$formMsg;
		if (sendMailContact($to,$subject,$formMail, $formMsg))
		{
			$success[]="Votre demande de renseignements a bien été envoyée";
		}
		else
		{
			$errors[]="Erreur à l'envoi du mail";

		}

	}
}


if( isset($_POST['send']))
{
	$mpdf = new \Mpdf\Mpdf();
	foreach ($participantList as $invit)
	{
		ob_start();
		include('badge-multiple.php');
		$html=ob_get_contents();
		ob_end_clean();

		$mpdf->AddPage();
		$mpdf->WriteHTML($html);

	}

	$pdfContent = $mpdf->Output('', 'S');
	// $pdfContent = $mpdf->Output();
	$pdfname='salon BTLec Est 2020 - badges.pdf';

	$htmlMail = file_get_contents('mail_invitation.php');
	$subject='Portail BTLec EST - Salon 2020 - Vos badges';




		// ---------------------------------------
		// initialisation de swift
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$attachmentPdf = new Swift_Attachment($pdfContent, $pdfname, 'application/pdf');

	$message = (new Swift_Message($subject))
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec EST'))
// // // ->setTo(array('valerie.montusclat@btlec.fr', 'valerie.montusclat@btlec.fr' => 'val'))
	->setTo(array($_POST['email']))
	->setBody($htmlMail, 'text/html')
	->attach($attachmentPdf);

// 		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));

	$delivered=$mailer->send($message);
	if($delivered !=0)
	{
		$success[]='Vos badges ont été envoyées';
	}
	else{
		$errors[]='impossible d\'envoyer les invitations';
	}
}


if(isset($_POST['add-formation-google'])){
	if(empty($_POST['email'])){
		$errors[]="Merci de renseigner une adresse email";
	}
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors[]="L'adresse mail ". $_POST['email']. " n'est pas valide";
	}

	if(empty($errors)){
		$updated=$formationDOA->addFormation($pdoBt, 1);
		if($updated==1){
			$successQ='?success=2';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}

	}
}

if(isset($_GET['success'])){
	$arrSuccess=[
		2=>'Choix de formation ajouté',
	];
	$success[]=$arrSuccess[$_GET['success']];
}

//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
require '../view/_head-bt.php';
require '../view/_navbar.php';

?>
<div class="container" id="up">
	<!-- main title -->
	<div class="row pt-5">
		<div class="col">
			<h1 class="text-main-blue">SALON TECHNIQUE BTLEC Est 2020<br>
				<span class="sub-h1"><i class="far fa-calendar-alt pr-3" aria-hidden="true"></i> du 22/09/2020 au 23/09/2020</span>
			</h1>

		</div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col">
			<div class="mini-nav text-center">
				<ul>

					<li><a href="#badge-lk">Badges</a></li>
					<li><a href="#inscription-lk">Inscription</a></li>
					<li><a href="#formation-lk">Formations</a></li>
					<li><a href="#modalite-lk">Modalités</a></li>
				</ul>
				<div class="text-secondary"><i class="fab fa-ravelry"></i><i class="fab fa-ravelry"></i><i class="fab fa-ravelry"></i></div>

			</div>

		</div>
	</div>


	<div class="row mb-4">
		<div class="col text-center">
			<!-- galerie -->
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
	</div>
	<div class="bg-separation"></div>
	<!-- descr salon -->
	<div class="row">
		<div class="int-padding">
			<h4 class="text-main-blue" id="salon-lk"><i class="fas fa-hand-point-right pr-3"></i>LE SALON BTLEC 2020 </h4>


			<p>Le salon BTlec 2020 se déroulera sur 2 jours, le <strong>22 septembre de 9h30 à 17h30</strong> et le <strong>23 septembre de 9h00 à 16h00</strong>.<br>
			Nous vous proposons, d'assister à des présentations faites directement par GFK. Elles se dérouleront sur 2 sessions de 45 minutes.</p>
			<p>
				<ul class="browser-default">
					<li><strong>Mardi 11h30 : </strong><span class="text-blue">conférence GFK Maison </span>(Petit et Gros Electroménager) de 45 mn</li>
					<li><strong>Mardi 14h30 : </strong><span class="text-blue">conférence GFK Multimédia </span>(Informatique, TV-Vidéo...) de 45 mn</li>
				</ul>
			</p>
			<p>Afin d'organiser au mieux le déroulement du salon, nous vous prions de bien vouloir remplir le <a href="#inscription-lk" class="blue-link">formulaire d'inscription</a> et d'<a href="#badge-lk" class="blue-link">imprimer votre badge</a>.</p>
			<p> Sous le formulaire d'inscription, vous trouverez les informations sur les <a href="#modalite-lk" class="blue-link">modalités d'accueil et d'accès</a> à BTlec Est</p>


			<p class="text-right"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<div class="bg-separation"></div>



	<!-- form inscription -->
	<div class="row mt-5">
		<div class="col">
			<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3" id="badge-lk"></i>VOS BADGES</h4>
			<p>Cette année, afin d'assurer la sécurité de tous et de limiter les regroupements de personnes notamment au niveau de l'accueil, nous mettons à votre disposition un document pdf vous permettant d'imprimer vos badges. Ces badges sont munis d'un qrcode qu'il vous suffira de scanner à l'accueil du salon.</p>
			<p><b>Aucun badge ne sera délivré sur le salon, en revanche, nous vous fournirons le support de badge</b></p>

			<p class="pb-3">Lors de votre venue au salon, vous devrez <span class="text-blue">Scanner votre badge</span> à l'accueil.</p>

		</div>
	</div>

	<div class="row">
		<div class="col"></div>
		<div class="col-auto">
			<table class="table table-striped table-nonfluid" id="item_table">
				<thead class="thead-dark">
					<tr>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Email</th>
						<th class="text-right">Mardi</th>
						<th class="text-right">Mercredi</th>
						<th class="text-center">Imprimer</th>
						<th>Supprimer</th>

					</tr>
				</thead>

				<?php
				if(empty($participantList))
				{
					echo '<tr><td colspan="7">Vous n\'avez inscrit aucun participant pour l\'instant</td></tr>';
				}
				else
				{

					foreach ($participantList as $part)
					{
						$repasMardi=$repasMercredi='';

						if($part['repas_mardi']==1)
						{
							$repasMardi='+ <i class="fas fa-utensils"></i>';
						}
						if($part['repas_mercredi']==1)
						{
							$repasMercredi='+ <i class="fas fa-utensils"></i>';
						}

						echo '<tr>';
						echo '<td>'.$part['nom'].'</td>';
						echo '<td>'.$part['prenom'].'</td>';
						echo '<td>'.$part['email'].'</td>';
						echo '<td class="text-right">'.$YesNo[$part['mardi']].$repasMardi.'</td>';
						echo '<td class="text-right">'.$YesNo[$part['mercredi']].$repasMercredi.'</td>';
						echo '<td class="text-center"><a href="pdf-gen-one.php?id='.$part['id'].'" target="_blank"><i class="fas fa-print"></i></a></td>';
						echo '<td class="text-center"><a href="inscription-modif.php?id='.$part['id'].'" class="red-link"><i class="fas fa-user-minus"></i></a></td>';


						echo '</tr>';

					}

				}
				?>
			</table>
		</div>
		<div class="col"></div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<div class="alert alert-primary">
				Avant d'imprimer, pensez à vérifier vos paramètres d'impression. Dans les paramètres avancés, modifiez la mise à l'échelle pour qu'elle soit sur la valeur "défaut"
			</div>

		</div>
	</div>
	<div class="row mt-3">
		<div class="col">Si vous préférez recevoir toutes vos invitations par mail, merci de renseigner votre adresse et de cliquer sur envoyer</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<form method="post"  action="<?=$_SERVER['PHP_SELF']?>">
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<input class="form-control" type="email" required="require" name="email" placeholder="votre adresse mail">
						</div>
					</div>
					<div class="col">
						<button class="btn btn-primary" type="submit" name="send">Envoyer</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="bg-separation"></div>

	<div class="row">
		<div class="col">
			<div class="row mt-5">
				<div class="col">
					<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3" id="inscription-lk"></i>FORMULAIRE D'INSCRIPTION</h4>
				</div>
			</div>
			<?php
			if($_SESSION['type']=='mag'  || $_SESSION['user']=="user" ||  $_SESSION['type']=='centrale' ){
				include('inscription-form-2020.php');
			}
			else{
				echo "<div class='alert alert-danger'>L'inscription est réservée aux magasins</div>";
			}

			?>
		</div>
	</div>
	<!-- MODAL FORM  MAG-->


	<div class="row mt-3">
		<div class="col">
			<p class="text-right"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row">
		<div class="col">
			<div class="row mt-5">
				<div class="col">
					<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3"  id="formation-lk"></i>INSCRIPTION AUX FORMATIONS</h4>
				</div>
			</div>
			<?php
			if($_SESSION['type']=='mag'  || $_SESSION['user']=="user" ||  $_SESSION['type']=='centrale' ){
				include('formation-google-2020.php');
			}
			else{
				echo "<div class='alert alert-danger'>L'inscription est réservée aux magasins</div>";
			}

			?>
		</div>
	</div>


	<!-- fin zone inscription -->
	<div class="bg-separation"></div>

	<!-- modalités -->
	<div class="row pt-3">
		<div class="col">
			<h4 class="text-main-blue" id="modalite-lk"><i class="fas fa-hand-point-right pr-3"></i>MODALITES D'ACCUEIL ET ACCES</h4>


			<ul class="browser-default">
				<li>restauration : un petit déjeuner vous sera servi sur le salon et un buffet traiteur vous accueillera le 22 et 23 septembre</li>
				<li>Sociétés de taxi :
					<ul class="browser-default">
						<li><strong><a href="http://www.taxi-city-reims.com" target="_blank">taxi city</a></strong> - 06 64 90 93 43</li>
						<li><strong><a href="#" target="_blank">taxis du vignoble</a></strong> - 06 06 60 60 20</li>
						<li><strong><a href="http://www.aid-taxi.com" target="_blank">AID Taxis</a></strong> - 06 16 17 68 70 ou 03 26 85 80 73</li>
						<li><strong><a href="#" target="_blank">Taxi LCDLM</a></strong> - 06 08 47 00 27</li>
					</ul>
				</li>
				<li>venir à BTlec : <a href="../mag/google-map.php" class="blue-link">coordonnées gps, carte</a></li>
			</ul>
			<!-- <br> -->
			<!-- <p><i class="fa fa-envelope-o" aria-hidden="true"></i>Nous contacter  pour tout renseignement complémentaire</a></p> -->

			<p class="text-right"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row pt-3">
		<div class="col">
			<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3" id="inscription-lk"></i>DEMANDE DE RENSEIGNEMENT COMPLEMENTAIRES</h4>
		</div>
	</div>
	<div class="row">
		<div class="col p-4 alert alert-primary">
			<form method="post" id="add" action="<?=$_SERVER['PHP_SELF']?>">
				<div class="row">
					<div class="col ">
						<div class="form-group">
							<label>Votre demande :</label>
							<textarea name="msg" class="form-control" required="require"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-auto">
						<div class="form-group">
							<label>Votre email :</label>
							<input type="email" name="email" required="require" class="form-control" >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col text-right">
						<button class="btn btn-primary" type="submit" name="more-info">Envoyer</button>
					</div>
				</div>

			</form>
		</div>
	</div>









</div>   <!--fin container -->

<script type="text/javascript">
	$(document).ready(function(){

		$(":checkbox#mardi").change(function(){
			if($(this).prop("checked")) {
				// $('#toogle_transit').show();
				$('#repas-mardi').attr('class','show');
			} else {
				$('#repas-mardi').attr('class', 'hidden');
			}
		});
		$(":checkbox#mercredi").change(function(){
			if($(this).prop("checked")) {
				// $('#toogle_transit').show();
				$('#repas-mercredi').attr('class','show');
			} else {
				$('#repas-mercredi').attr('class', 'hidden');
			}
		});

		$('#show-google').on("click",function(){
			$('#google').attr('class','show');
		});
		$("#email").keyup(function(){

			var email = $("#email").val();
			var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(email)) {
             //alert('Please provide a valid email address');
             $("#error_email").text(email+" n'est pas une adresse mail valide");
             $("#error_email").addClass('alert alert-danger');
             email.focus;
         } else {
         	$("#error_email").text("");
         	$("#error_email").removeClass('alert alert-danger');
         	$("#error_email").text(email+' : adresse valide');
         	$("#error_email").addClass('alert alert-success');
         }
     });

	});


</script>


<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer-bt.php';

?>