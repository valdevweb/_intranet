<div class="row">
	<div class="col">
		<table class="table table-sm table-bordered table-striped" id="palettes">
			<thead class="thead-dark">
				<tr>
					<th class="sortable" onclick="sortTable(0);">Exp</th>
					<th class="sortable" onclick="sortTable(1);">Palette</th>
					<th class="sortable" onclick="sortTable(2);">Palette<br> contremarque</th>
					<th >Affectation</th>
					<th class="sortable text-center" onclick="sortTable(4);">Statut</th>
					<th class="sortable" onclick="sortTable(5);">Date expé</th>
					<th class="sortable" onclick="sortTable(6);">Magasin</th>
					<th class="sortable" onclick="sortTable(7);">Valo<br>palette</th>
					<th><i class="fas fa-tools"></i></th>
					<th><i class="fas fa-trash"></i></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($palettesToDisplay as $key => $palette): ?>
					<?php
					$statutImg=$classAffectation="";
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
					if($palette['id_affectation']==3){
						$classAffectation="text-success";
					}elseif($palette['id_affectation']==2){
						$classAffectation="text-orange";
					}elseif($palette['id_affectation']==1){
						$classAffectation="text-primary";
					}
					?>

					<tr  id="palette-<?=$palette['id']?>">
						<td class="<?=$classAffectation?>"><a href="#" class="<?=$classAffectation?>"><?=$palette['id_exp']?></a></td>
						<td><a class="<?=$classAffectation?>" href="detail-palette.php?id=<?=$palette['id']?>"><?=$palette['palette']?></a></td>
						<td><a href="#" class="<?=$classAffectation?>"><?=$palette['contremarque']?></a></td>
						<td ><?=isset($listAffectationIco[$palette['id_affectation']]) ? "<img src='../img/logos/".$listAffectationIco[$palette['id_affectation']]."'>":""?></td>
						<td class="text-right"><?=$statutImg?></td>
						<td class="text-center"><a  href="#" class="<?=$classAffectation?>"><?=(!empty($palette['date_delivery']))?date('d-m-Y', strtotime($palette['date_delivery'])):""?></a></td>
						<td class="text-center"><a  href="#" class="<?=$classAffectation?>"><?=$palette['btlec']?></a></td>
						<td class="text-center"><a  href="#" class="<?=$classAffectation?>"><?=$palette['valopalette']?></a></td>
						<td><a class="<?=$classAffectation?>" href="#" data-toggle="modal" data-target="#edit-palette" data-id-palette="<?=$palette['id']?>" data-palette="<?=$palette['palette']?>" ><i class="fas fa-tools"></i></a></td>
						<td><a class="<?=$classAffectation?>" href="?del-palette=<?=$palette['id']?>" onclick="return confirm('Etes vous sûr de vouloir supprimer la palette <?=$palette['palette']?> ?')"><i class="fas fa-trash"></i></a></td>
					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>

