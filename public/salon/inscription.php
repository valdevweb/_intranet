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
<div class="container">
	<h1 class="blue-text text-darken-2">SALON TECHNIQUE BTLEC Est 2018<br><span class="sub-h1"><i class="fa fa-calendar" aria-hidden="true"></i> du dd/mm/yyyy au dd/mm/yyyy</span>
	</h1>
	<br>
	<h3 class="blue-text text-darken-2">LE SALON BTLEC 2018 : </h3>
	<br>

	<div class="sub">


		<p>Comme tous les ans, un petit déjeuner sera offert sur le salon. Le midi un service de restauration sous forme de buffet traiteur sera à votre disposition. Il vous sera aussi proposé cette année de visiter l'entrepôt de BTlec</p>
		<p>Enfin d'organiser au mieux le déroulement du salon, nous vous prions de bien vouloir remplir le <a href="#insert_form" class="salon">formulaire d'inscription</a>. Un badge vous sera remis à votre entrée du salon. Sous le formulaire d'inscription, vous trouverez les informations sur les <a href="#modalite" class="salon">modalités d'accueil et d'accès</a> à BTlec Est</p>
	</div>
<br><br><br>
	<h3 class="blue-text text-darken-2">FORMULAIRE D'INSCRIPTION</h3>
	<br>
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
			<div align="center">
				<p class="align-right"><button class="btn" type="submit" name="inscription">S'inscrire</button></p>

			</div>

		</form>
		<span id="error"></span>
</div>
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
						console.log(error);
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