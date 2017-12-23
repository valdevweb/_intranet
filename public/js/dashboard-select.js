
$(document).ready(function() {
		var list = $("#list-msg .msg");
		$(list).fadeOut("fast");

		value= $("select#services").attr("selected","selected" ).val();
		 $("#list-msg").find("article[data-service=" + value + "]").each(function (i) {
		 $(this).delay(200).slideDown("fast");
		 });

// jQuery("select#cboDays option[selected]").removeAttr("selected");
// jQuery("select#cboDays option[value='Wednesday']").attr("selected", "selected");

	//on récupère la valeur de la list deroulante => son id
	$('select#services').change(function()
	{
		var value = $(this).val()
		filterList(value);
	});

	//News filter function
	function filterList(value) {
		// on cache tout
		var list = $("#list-msg .msg");
		$(list).fadeOut("fast");


		//on affiche que ce qui est selectionné
		// if (value == "default")
		// {
		// 	$("#list-msg").find("article").each(function (i) {
		// 		$(this).delay(200).slideDown("fast");
		// 	});
		// }
		// else
		// {
		$("#list-msg").find("article[data-service=" + value + "]").each(function (i) {
		$(this).delay(200).slideDown("fast");
		});
		// }
	}
});

