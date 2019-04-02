<script type="text/javascript">
	$(document).ready(function(){
		$('#hide-transp').click(function(){
			$('#transp > tbody > tr').each(function(){
				if ($(this).find('i.fa-eye-slash').length)
				{
					$(this).toggleClass('hide');
				}
			});
		});
	</script>




	<table class="table border table-striped" id="transp">
		<thead class="thead-blue">
			<tr><th>Etat</th><th>Affreteur</th></tr>
		</thead>
		<tbody>
			<tr>
				<td>cache moi</td>
				<td><i class="fas fa-eye-slash"></i></td>
			</tr>
			<tr>
				<td>ne me cahce pas</td>
				<td><i class="fas autre"></i></td>
			</tr>
		</tbody>
	</table>