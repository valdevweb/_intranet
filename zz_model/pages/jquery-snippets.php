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