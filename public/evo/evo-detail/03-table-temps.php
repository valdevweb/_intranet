
<table class="table table-sm w-auto">
	<thead class="thead-light">
		<tr>
			<th class="text-right">Date</th>
			<th class="text-right">Temps passé</th>
			<th class="text-center"><i class="fas fa-trash"></i></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tempsPasses as $key => $temps): ?>
			<tr>
				<td class="text-right"><?=date('d/m/Y', strtotime($temps['date_exec']))?></td>
				<td class="text-right"><?=$temps['minutes']?></td>
				<td class="text-center"><a href="?id=<?=$_GET['id'].'&del_temps='.$temps['id']?>" class="link-orange"><i class="fas fa-trash"></i></a></td>

			</tr>

			<?php  $tempsTotal+=$temps['minutes'];?>

		<?php endforeach ?>
		<?php
		$jour=0;
		$heure=0;
		$minutes=0;
		if($tempsTotal>1440){
			$jour=floor($tempsTotal/1440);
			$reste=$tempsTotal-($jour*1440);
			if($reste>60){
				$heure=floor($reste/60);
				$minutes=$reste-$heure*60;
			}
		}
		if($tempsTotal > 60){
			$heure=floor($tempsTotal /60);
			$minutes=$tempsTotal-($heure*60);

		}
		?>
		<tr class="bg-light-grey">
			<td>Total :</td>
			<td colspan='2' class="text-right" ><?=$tempsTotal?>min</td>

		</tr>
		<tr class="bg-light-grey">
			<td>Soit :</td>

			<td class="text-right" colspan="2"><?=$jour?> j, <?=$heure?>h<?=$minutes?>min</td>
		</tr>
	</tbody>
</table>