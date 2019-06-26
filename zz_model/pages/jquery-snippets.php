<script type="text/javascript">

$(document).ready(function(){

// confirmation on submit => suivant état de la case à cocher
// ATTENTION l'id fait référence à l'id du formulaire et non de submit
	$('#answer').submit(function()
	{
		var box=$("input[type='checkbox']#clos");
		var boxState=box.prop("checked");
		if(boxState)
		{
			boxState="Confirmez l'envoi de la réponse et la cloture du dossier ?";
				//	return confirm(boxState);

			}
			else
			{
				boxState="Confirmez l'envoi de la réponse sans cloture du dossier ?";
				//	return confirm(boxState);

			}
			// console.log(boxState);
			return confirm(boxState);
		});
// loader : on submit affichage du loader
	$("#search").submit(function( event )
		{
			$("#waitun" ).append('<i class="fas fa-spinner fa-spin"></i><span class="pl-3">Merci de patienter pendant la recherche</span>')
		});

});

// afficher message si option (radio) non cliquée (choose = le submit)
	$("#choose").click(function() {
			if ($('input[name="rapid"]:checked').length == 0) {
				alert('Vous devez préciser si il s\'agit d\'une livraison 24/48h');
				return false; }

			});





// masquer ligne du tableeau ayant la class ""

	$(document).ready(function(){
		$('#hide-clos').click(function(){
			$('#dossier > tbody > tr').each(function(){
				if ($(this).find('td.text-dark-grey').length)
				{
					$(this).toggleClass('hide');
					// console.log($(this).find('img.test').length);
				}
				else{
					console.log('na');
				}
			});
		});
	});

</script>

		<aside id="modal1" class="vm-modal" aria-hidden="true" role="modal"  style="display: none;">
			<div class="vm-modal-wrapper">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="form-group">
						<label class="text-main-blue">Commentaire :</label>
						<textarea class="form-control" name="cmt" rows="3" id="cmtarea"></textarea>
					</div>
					<div class="form-group">

						<input type="hidden" class="form-control" name="iddossier" id="hiddeninput">
					</div>
					<button class="btn btn-primary" name="validate">Valider</button>
					<button class="btn btn-red" id="annuler">Annuler</button>
				</form>
			</div>
		</aside>





	<script type="text/javascript">

		$(document).ready(function(){
			// recup url et decoupage
			var url = window.location + '';
			var splited=url.split("#");
			if(splited[1]==undefined)
			{
				var line='';
			}
			else if(splited.length==2)
			{
				var line=splited[1];
				$("tr#"+line).addClass("anim");
			}

			// affichage modal
			$('.stamps').on('click',function(){
				var line=$(this).attr("data")
			;
				$('#hiddeninput').val(line);
				$('#modal1').css("display","null");
				$('#modal1').removeAttr('aria-hidden');
				// $('#modal1').attr('aria-modal', true);
				$('#cmtarea').focus();
			// $("tr#"+line).addClass("anim");
		});
			$('#annuler').on('click', function(){

				$('#modal1').css("display","hidden");

			});

			// boite de dialogue confirmation clic sur lien
			$('.unvalidate').on('click', function(){
			return confirm('Etes vous sûrs de vouloir passer le statut du dossier en non statué ?')
		});

		});



	</script>