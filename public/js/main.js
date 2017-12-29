$(document).ready(function(){



	// ouverture fenetre modal
	$('.modal').modal();
		$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
	$('#cssmenu #menu-button').on('click', function(){
		var menu = $(this).next('ul');
		if (menu.hasClass('open')) {
			menu.removeClass('open');
		}
		else {
			menu.addClass('open');
		}
	});

	//nom fichier upload page upload-gazette
	//$('#gazette').change(function(){
	//
	//
	$("input[type='file']").change(function(){

		//on récupère le nom du fichier (input type file)
		var filename=$(this).val();
		// on découpe : chrome ajout c:\fake au nom du fichier
		var cleanfilename = filename.split( '\\' );
		if(cleanfilename.length > 1)
		{
			// alert(cleanfilename.length);
			var last = cleanfilename.length
			// alert(cleanfilename[last -1]);
			$('#file-name').text(cleanfilename[last -1]);

		}
		else
		{
			$('#file-name').text(filename);
		}


		// on n'affiche que la dernière partie du tableau

		//$('#gaz-name').text(cleanfilename[cleanfilename.length]);
		//alert($( this ).val());

	});



	//calendrier
	$('.datepicker').pickadate({
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 15, // Creates a dropdown of 15 years to control year,
		today: 'Today',
		clear: 'Clear',
		close: 'Ok',
		closeOnSelect: false // Close upon selecting a date,
	});

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



//PAS TOUCHER !!!!!!!

});


