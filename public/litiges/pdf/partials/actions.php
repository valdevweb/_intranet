<div class="spacing-s"></div>
<table class="padding-table border-table-grey">
	<tr>
		<td class="cinq bg-dark-grey text-white">Date</td>
		<td class="bg-dark-grey text-white" style="width:560px;">Action</td>
	</tr>

	<?php foreach ($actionList as $action) : ?>
	<tr>
		<td><?=date('d-m-Y',strtotime($action['date_action']))?></td>
		<td><?=$action['libelle']?></td>
	</tr>
	<?php endforeach ?>

</table>