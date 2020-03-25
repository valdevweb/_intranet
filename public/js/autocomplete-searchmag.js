		$(document).ready(function(){

			$('#search_term').keyup(function(){
				var path = window.location.pathname;
				var page = path.split("/").pop();
				var query = $(this).val()+"#"+page;
				if(query != '')
				{
					$.ajax({
						url:"ajax-search-mag.php",
						method:"POST",
						data:{query:query},
						success:function(data)
						{
							$('#magList').fadeIn();
							$('#magList').html(data);
						}
					});
				}
			});
			$(document).on('click', 'li', function(){
				$('#search_term').val($(this).text());
				$('#magList').fadeOut();
			});

			$(document).on('keypress', '#search_term', function(e){
				if(e.which == 13){
					e.preventDefault();
					var url=$('.result-item').first().attr('href');
					var goto="./"+url;
					$(location).attr('href',goto);
				}

			});


		});