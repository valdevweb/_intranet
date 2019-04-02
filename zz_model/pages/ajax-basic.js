<script type="text/javascript">
		$(document).ready(function(){
			$('#centrale').on('change',function(){
				$('#mag').html('<option value="">Sélectionnez votre magasin</option>');
				var centrale = $(this).val();
				if(centrale){
					$.ajax({
						type:'POST',
						url:'ajaxMag.php',
						data:'centrale='+centrale,
						success:function(html){
							$('#mag').append(html);
						}
					});
				}
				else
				{
					$('#mag').html('<option value="">Sélectionnez votre magasin</option>');
				}
			});
		});
</script>