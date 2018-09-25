<div class="container box-bd px-5 pb-5 shadow">
	<h1 class="blue-text text-darken-4">Upload des documents</h1>
	<br><br>
	<!-- formulaire -->
	<div class="row">
		<div class="col">
			<div class="">
				<form method="post" action="<?= $_SERVER['PHP_SELF']?>"  enctype="multipart/form-data" >
					<div class="form-row">
						<div class="col">
							<label for="type">Selectionnez le type de document à uploader</label>
							<!-- listes des types de documents existants -->
							<select class="form-control" name="type" id="type">
								<option value="">type de fichier</option>
								<?php foreach($types as $type):  ?>
								<option value="<?= $type['id']?>"><?= $type['name']?></option>
							<?php endforeach ?>

							</select>
						</div>
					</div>
					<!-- champs de formulaire spécifique au type de document -->
					<div id="specific-fields">

					</div>
					<br>
					<div class="form-row">
						<div class="col">
							<label for="file">Joindre les fichiers :</label>
							<p><input type="file" name="file" class='input-file'id="file"></p>
						</div>
					</div>
					<br>
					<!-- affichage des erreurs -->
					<?= isset($errorsDisplayOdr) ? $errorsDisplayOdr : ""; ?>
					<div class="form-row">
						<div class="col-sm12 col-md-10">
							<button type="submit" class="btn btn-primary" name="send" id="send">Envoyer</button>
						</div>
					</div>
				</form>
			</div> <!-- ./box -->
			<!-- <p class="right-align"><a href="#cssmenu" class="blue-link">retour</a></p> -->
		</div> <!-- ./col -->
		<div class="col"></div>
	</div><!-- ./row -->
	<!-- ./form -->
	<div class="row">
		<div class="col">
			<!-- listing pour les fichiers gazette uniquement -->
			<div id="listing">
			</div>
		</div>
	</div>
</div> <!-- ./container -->
	<script type="text/javascript">

	//fonction de suppression de gazette - tableau des gazettes construit parr la page gazette.ajax
	function deleteEl(id){
		//id de la gazette à supprimer
		deleteid=id;
		// # du td cliqué => comme on ne peut pas récupérer l'évenement clic, on passe le numéro d'id à la fonction
		var el = $('#'+id);
		  $.ajax({
		  	url: 'delete.ajax.php',
		  	type: 'POST',
		  	data: { id:deleteid },
		  	success: function(response){

			    // Removing row from HTML Table
			    $(el).closest('tr').css('background','tomato');
			    $(el).closest('tr').fadeOut(800, function(){
			    	$(this).remove();
			    });

			}
		});
	}
	$(document).ready(function(){
// if( document.getElementById("videoUploadFile").files.length == 0 ){
//     console.log("no files selected");
// }
		$("#send").click(function(e) { // bCheck is a input type button
			var fileName = $("#file").val();

		    if(!fileName) { // no file was selected
		    	alert("Veuillez sélectionner un fichier");
		    	e.preventDefault();

		    }
		});

		$('#type').on('change',function(){

			//code html des divers balises utilisée
			var startFormRow="<div class='form-row'>";
			var col="<div class='col'>";
			var dateUniqueLabel="<label for='date'>date : </label>";
			var inputDateUnique="<input type='date' class='browser-default form-control' id='date'  name='date' required>";
			var dateDebutLabel="<label for='date'>date de début : </label>";
			var dateFinLabel="<label for='date-fin'>date de fin : </label>";
			var inputDateDebut="<input type='date' class='browser-default form-control' id='date'  name='date' required>";
			var inputDateFin="<input type='date' class='browser-default form-control' id='date-fin'  name='dateFin' required>";
			var finDiv="</div>";
			var inputText="<input type='text' class='browser-default form-control' id='libelle'  name='libelle' required>";
			var inputTextLabel="<label for='libelle'>libellé : </label>";
			var textareaZone ="<textarea class='form-control' id='descriptif' name='descriptif' rows='10'></textarea>";
			var textareaLabel ="<label for='descriptif'>Descriptif</label>";

			//assemblage des balises
			var dateUnique = startFormRow + col + dateUniqueLabel + inputDateUnique +  finDiv + col + finDiv + finDiv;
			var libelle= startFormRow + col + inputTextLabel + inputText + finDiv +finDiv;
			var dateDebut = startFormRow + col + dateDebutLabel + inputDateDebut +  finDiv + col + finDiv +finDiv;
			var dateFin = startFormRow + col + dateFinLabel + inputDateFin +  finDiv + col + finDiv +finDiv;
			var descriptif= startFormRow + col + textareaLabel + textareaZone + finDiv+finDiv;

			//affichage du formulaire suivant le type de document sélectionné
			//cas général = dateunique
			//8 = mdd - 9 = kit affiche
			var id_doc_type =$(this).val();
			console.log(id_doc_type);
			if (id_doc_type==9 || id_doc_type ==8)
			{
				$('#specific-fields').html(libelle);
			}
			//2 = gazette appro
			else if(id_doc_type==2)
			{
				$('#specific-fields').html(dateDebut + dateFin + descriptif);
			}
			else if(id_doc_type==6 || id_doc_type==7 || id_doc_type==11)
			{
				$('#specific-fields').html();

			}
			else
			{
				$('#specific-fields').html(dateUnique);
			}
			//si gazette, on affiche la liste des dernières gazette
			//on réinitialise la liste sinon elles s'ajoutent
			$('#listing').html("");
			if(id_doc_type==1 || id_doc_type== 2 || id_doc_type == 8)

			// if(code==1 || code== 2 || code == 8)
			{
				$.ajax({
					type: 'POST',
					url:'gazette.ajax.php',
					data:'id_doc_type='+id_doc_type,
					success:function(html){
						$('#listing').append(html);
					}
				});
			}
		});
	});

</script>