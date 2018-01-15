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

	// à garder uniquement pour la gazette -upload fichier
		$("input[type='file']").change(function(){
		//get the input and UL list
		//on traite plusieurs formulaires d'upload de fichier avec des id différents
		//upload contact
		if (document.getElementById('file') != null)
		{
			var input = document.getElementById('file');
		}
		//upload gazette
		else if(document.getElementById('gazette') != null)
		{
			var input = document.getElementById('gazette');

		}


		var list = document.getElementById('file-name');
		//empty list for now...
		while (list.hasChildNodes()) {
			list.removeChild(ul.firstChild);
		}

			//for every file...
			for (var x = 0; x < input.files.length; x++)
			{
				//add to list
				var li = document.createElement('li');
				li.innerHTML = 'Fichier '  + ':  ' + input.files[x].name;
				list.append(li);
			}
		});

	//---------------------------------------------------------------------------------------------
	//
	//						UPLOAD DE FICHIERS
	//
	//--------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//		gestion ajout de fichier à uploader : nouveau bouton input file quand clique sur lien
	//--------------------------------------------------------------------------------------------

	//selection du bouton pour ajouter des input type file
	$('#add_more').click(function(){
		//compte le nombre d'input file
		var current_count=$('input[type="file"]').length;
		var next_count=current_count+1;
		$('#p-add-more').prepend('<p><input type="file" name="file_' +next_count +' "></p>');
		$('input[type="file"]').val('hello');
	});

	//---------------------------------------------------------------------------------------------
	//		message confirmation à la validation du formulaire de réponse BT cloture ou non
	//--------------------------------------------------------------------------------------------

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

//calendrier
	// $('.datepicker').pickadate({
	// 	selectMonths: true, // Creates a dropdown to control month
	// 	selectYears: 15, // Creates a dropdown of 15 years to control year,
	// 	today: 'Today',
	// 	clear: 'Clear',
	// 	close: 'Ok',
	// 	closeOnSelect: false // Close upon selecting a date,
	// });


//PAS TOUCHER !!!!!!!

});


