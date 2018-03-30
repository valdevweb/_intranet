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



	//---------------------------------------------------------------------------------------------
	//
	//						UPLOAD DE FICHIERS
	//
	//--------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//		MULTI UPLOAD : ajout de btn input file pour uploader d'autres fichiers
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

	//---------------------------------------------------------------------------------------------
	//		upload-gazette
	//		récupère et affiche les noms des fichiers choisis via les 2 input file
	//		champ input file limités à un fichier
	//--------------------------------------------------------------------------------------------


		$("input[type='file']#gazette-upload").change(function(){
			//get the input and UL list
			//on traite plusieurs formulaires d'upload de fichier avec des id différents
			// id de l'input file
			if(document.getElementById('gazette-upload') != null)
			{
				var input = document.getElementById('gazette-upload');
				// paragrphe ou on affiche la liste des fichiers uploadés
				var list = document.getElementById('file-name-gazette');
			}
			if(input !=null)
			{
				//on vide la liste au départ
				while (list.hasChildNodes())
				{
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
			}

			});



		$("input[type='file']#appros-upload").change(function(){
			//get the input and UL list
			//on traite plusieurs formulaires d'upload de fichier avec des id différents
			// id de l'input file

			if(document.getElementById('appros-upload') != null)
			{
				var input = document.getElementById('appros-upload');
				// paragrphe ou on affiche la liste des fichiers uploadés
				var list = document.getElementById('file-name-appros');
			}
			if(input !=null)
			{
				//on vide la liste au départ
				while (list.hasChildNodes())
				{
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
			}

			});






// $('input[type="file"]').change(function(e){
//             var fileName = e.target.files[0].name;
//             alert('The file "' + fileName +  '" has been selected.');
//         });




	//---------------------------------------------------------------------------------------------
	//
	//						UPLOAD DE FICHIERS
	//
	//--------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//		stats clic liens externes
	//--------------------------------------------------------------------------------------------
		$('.stat-link').click(function(event)
		{
			var user=$(this).attr('data-user-session');
			var link=this.href;
			var from=document.location.href;
			$.ajax({
				type: 'POST',
				url:'http://172.30.92.53/btlecest/functions/ajax.stats.php',
				data:{
					urlSend: link,
					page:from,
					action : 'lien externe',
					user: user
				},
				success: function(response) {
         		   // document.getElementById("test").innerHTML = response;
        		}
			});
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


