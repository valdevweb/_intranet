<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}

//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

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
			<h1 class="blue-text text-darken-4">SALON TECHNIQUE BTLEC Est 2018<br><span class="sub-h1"><i class="fa fa-calendar" aria-hidden="true"></i> du dd/mm/yyyy au dd/mm/yyyy</span></h1>
			<br>
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

			<p>Le salon BTlec 2018 se déroulera sur 2 jours, le <strong>xxxxxxx</strong> et le <strong>xxxxxxxx</strong>. Nous vous proposons cette année, de profiter de votre venue au salon pour visiter notre entrepôt.</p>
			<p>Enfin d'organiser au mieux le déroulement du salon, nous vous prions de bien vouloir remplir le <a href="#inscription-lk" class="blue-link">formulaire d'inscription</a>.  Sous le formulaire d'inscription, vous trouverez les informations sur les <a href="#modalite-lk" class="blue-link">modalités d'accueil et d'accès</a> à BTlec Est</p>
			<p>Un badge vous sera remis à votre entrée du salon.</p>

			<br><br><br>
			<p class="right-align"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<!-- modalités -->
	<div class="row bggrey">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="modalite-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>MODALITES D'ACCEUIL ET ACCES</h4>
			<hr>
			<br><br>
			<ul class="browser-default">
				<li>restauration : un petit déjeuner vous sera servi sur le salon et un buffet traiteur vous accueillera le xxxxxx</li>
				<li>Sociétés de taxi :
					<ul class="browser-default">
						<li><strong>taxi city</strong> - 06 64 90 93 43</li>
						<li><strong>taxis du vignoble</strong> - 06 06 60 60 20</li>
						<li><strong>AID Taxis</strong> - 06 16 17 68 70 ou 03 26 85 80 73</li>
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
			<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true" id="inscription-lk"></i>FORMULAIRE D'INSCRIPTION </h4>
			<hr>
			<br><br>
			<form method="post" id="insert_form" >
				<table class="bordered" id="item_table">
					<tr>
						<th>Nom</th>
						<th>Prenom</th>
						<th>Fonction</th>
						<th>Date</th>
						<th>Repas</th>
						<th>Visite de l'entrepot</th>
						<th>ajouter/supprimer<br> des lignes</th>
					</tr>
					<tr>
						<td><input type="text" name="nom[]" placeholder="nom" class="nom"></td>
						<td><input type="text" name="prenom[]" placeholder="prenom" class="prenom"></td>
						<td><input type="text" name="fonction[]" placeholder="fonction" class="fonction"></td>
						<td><select class="browser-default date-salon" name="date-salon[]" ><option value="" disabled selected>date</option><option value="01/03/2018">01/03/2018</option><option value="02/03/2018">02/03/2018</option></select></td>
						<td><select class="browser-default repas" name="repas[]" ><option value="" disabled selected>repas</option><option value="oui">Oui</option><option value="non">Non</option></select></td>
						<td><select class="browser-default visite" name="visite[]"><option value="oui" disabled selected>visite</option><option value="oui">Oui</option><option value="non">Non</option></select></td>
						<td><span class="add"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span><span class="remove"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span></td>
					</tr>
				</table>
					<!-- <p><label>Merci d'indiquer votre adresse mail</label><br><input class="browser-default" type="email" required="require" name="email" placeholder="votre email"></p> -->
					<?php
					if($_SESSION['type']<>'mag')
					{
						echo "<p class='red-text'>L'inscription est réservée aux magasins, vous ne pourrez pas utiliser le formulaire si votre compte utilisateur n'est pas rattaché à un magasin</p>";
					}
					?>

					<p class="align-right"><button class="btn" type="submit" name="inscription">S'inscrire</button></p>

			</form>
			<span id="error"></span>
			<br><br><br>
			<p class="right-align"><a href="#up" class="blue-link">retour</a></p>

		</div>
	</div>


</div>   <!--fin container -->












<!-- </div> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
<script>
	$(document).ready(function(){
		$(document).on('click', '.add', function(){
			var html = '';
			html += '<tr>';
			html += '<td><input type="text" name="nom[]" placeholder="nom" class="nom"></td>';
			html += '<td><input type="text" name="prenom[]" placeholder="prenom" class="prenom"></td>';
			html += '<td><input type="text" name="fonction[]" placeholder="fonction" class="fonction"></td>';
			html += '<td><select class="browser-default date-salon" name="date-salon[]" ><option value="" disabled selected>date</option><option value="01/03/2018">01/03/2018</option><option value="02/03/2018">02/03/2018</option></select></td>';
			html += '<td><select class="browser-default repas" name="repas[]" ><option value="" disabled selected>repas</option><option value="oui">Oui</option><option value="non">Non</option></select></td>';
			html += '<td><select class="browser-default visite" name="visite[]"><option value="oui" disabled selected>visite</option><option value="oui">Oui</option><option value="non">Non</option></select></td>';
			html += '<td><span class="add"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></span><span class="remove"><i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i></span></td>';
			html += '</tr>';



			$('#item_table').append(html);
		});

		$(document).on('click', '.remove', function(){
			$(this).closest('tr').remove();
		});


		$('#insert_form').on('submit', function(event){

			event.preventDefault();
			var error = '';

			$('.nom').each(function(){
				var count = 1;
				if($(this).val() == '')
				{
					error += "<p>Merci de saisir un nom, ligne "+count+" </p>";
					return false;
				}
				count = count + 1;
			});

			$('.prenom').each(function(){
				var count = 1;
				if($(this).val() == '')
				{
					error += "<p>Merci de saisir un prenom, ligne "+count+" </p>";
					return false;
				}
				count = count + 1;
			});

			$('.fonction').each(function(){
				var count = 1;
				if($(this).val() == '')
				{
					error += "<p>Merci de saisir une fonction, ligne "+count+" </p>";
					return false;
				}
				count = count + 1;
			});

			$('.date-salon').each(function(){

				var datesalon = $(this).val();
				if(!datesalon)
				{
					error += "<p>Merci de préciser une date pour chaque participant</p>";
					return false;
				}

			});

			$('.repas').each(function(){
				var repas = $(this).val();
				if(!repas)
				{
					error += "<p>Merci de préciser si les personnes comptent participer au repas</p>";
					return false;
				}

			});

			$('.visite').each(function(){
				var visite = $(this).val();
				if(!visite)
				{
					error += "<p>Merci de préciser si les personnes souhaitent visiter l'entrepôt</p>";
					return false;
				}
			});

			var form_data = $(this).serialize();
			if(error == '')
			{
				// console.log(error);
				$.ajax({
					url:"insert.php",
					method:"POST",
					data:form_data,
					success:function(data)
					{
						if(data == 'ok')
						{
							console.log("data ok");
							$('#item_table').find("tr:gt(0)").remove();
							$('#error').html('<div class="alert alert-success">Inscription réussie - vous allez recevoir un mail de confirmation</div>');
						}
					}
				});
			}
			else
			{
				$('#error').html('<div class="alert alert-danger">'+error+'</div>');
			}
		});

	});
</script>
<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>