


	<table class="table table-sm">
		<thead class="thead-dark">
			<tr>
				<th>id_dossier</th>
				<th>dossier</th>
				<th>id_detail</th>
				<th>valo detail</th>
				<th>valo somme</th>
				<th>dossier</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $key => $value): ?>

				<?php if ($dossierEncours!=$value['id_dossier']): ?>
					<?php
					if (trim($prevValo)!=trim($dossierEncoursValo)){
						$class="bg-reddish";
					}else{
						$class="bg-grey";
					}
					?>


					<tr class="<?=$class?>">
						<td colspan="4"></td>
						<td><?=$prevValo?></td>
						<td><?=$dossierEncoursValo?></td>
					</tr>
				<?php endif ?>

				<?php
				if($dossierEncours!=$value['id_dossier']){
					$prevValo=$value['valo_line'];
				}else{
					$prevValo=$value['valo_line'] + $prevValo;
				}
				?>
				<tr>
					<td><?=$value['id_dossier']?></td>
					<td><?=$value['dossier']?></td>
					<td><?=$value['id']?></td>
					<td><?=$value['valo_line']?></td>
					<td><?=$prevValo?></td>
					<td></td>
				</tr>
				<?php
				$dossierEncours=$value['id_dossier'];
				$dossierEncoursValo=$value['valo'];

				?>
			<?php endforeach ?>

		</tbody>
	</table>
