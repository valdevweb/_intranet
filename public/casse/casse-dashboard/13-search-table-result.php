<div class="row">
	<div class="col">
		<table class="table table-sm table-bordered table-striped" id="palettes">
			<thead class="thead-dark">
				<tr>
					<th class="sortable" onclick="sortTable(0);">Exp</th>
					<th class="sortable" onclick="sortTable(1);">Palette</th>
					<th class="sortable" onclick="sortTable(2);">Palette<br> contremarque</th>
					<th class="sortable text-center" onclick="sortTable(3);">Statut</th>
					<th class="sortable" onclick="sortTable(4);">Date expé</th>
					<th class="sortable" onclick="sortTable(5);">Magasin</th>
					<th class="sortable" onclick="sortTable(6);">Valo<br>palette</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($palettesToDisplay as $key => $palette): ?>
					<?php
					$statutImg="";
					if($palette['statut']==0 && $palette['NumeroPalette']==null){
						$statutImg='<img src="../img/casse/encours.jpg">';
					}
					elseif($palette['statut']==1 || $palette['NumeroPalette']!=null){
						$statutImg='<img src="../img/casse/livrer.png">';
					}elseif ($palette['exp']==1 && $palette['mt_fac']!='') {
						$statutImg='<img src="../img/casse/livre.png"><img src="../img/casse/creditcard.png">';
					}elseif ($palette['exp']==1 && $palette['mt_fac']==null){
						$statutImg='<img src="../img/casse/livre.png"><img src="../img/casse/logo_deee.jpg">';
					}

					?>

					<tr>
						<td><?=$palette['id_exp']?></td>
						<td><a href="detail-palette.php?id=<?=$palette['id_palette']?>"><?=$palette['palette']?></a></td>
						<td><?=$palette['contremarque']?></td>
						<td class="text-right"><?=$statutImg?></td>
						<td class="text-center"><?=(!empty($palette['date_delivery']))?date('d-m-Y', strtotime($palette['date_delivery'])):""?></td>
						<td class="text-center"><?=$palette['btlec']?></td>
						<td class="text-center"><?=$palette['valopalette']?></td>
					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>