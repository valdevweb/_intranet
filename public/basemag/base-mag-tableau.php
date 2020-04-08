<div class="row">
	<div class="col">
		<h5 class="text-main-blue text-center pt-3 pb-3">Nombre de magasins affichés : <?=$nbResult?></h5>
		<div class="alert alert-primary">
			Pour obtenir plus d'information sur un magasin, veuillez cliquer sur son nom.<br>
			Survolez l'icône <i class="fas fa-not-equal"></i> pour connaître les champs qui ne sont pas identiques. <?= $diff ?> magasin(s) sont concernés dans la sélection actuelle
		</div>

	</div>
</div>

<div class="row">
	<div class="col">
		<p class="text-main-blue text-center">Filtrer l'affichage :</p>
	</div>
</div>
<div class="row">
	<div class="col-8">

		<a href="#" class="hide-btn px-3"><i class="fas fa-not-equal pr-3"></i> uniquement</a>
		<a  href="#" class="show-btn px-3"><i class="fas fa-eraser pr-3"></i>réafficher tout</a></p>
	</div>

	<div class="col-auto">
		Chiffre d'affaire :
	</div>
	<div class="col-auto mb-1">
		<label class="switch">
			<input class="switch-input" type="checkbox" />
			<span class="switch-label" data-on="On" data-off="Off"></span>
			<span class="switch-handle"></span>
		</label>
	</div>
</div>
<div class="row">
	<div class="col">
		<table class="table table-sm shadow">
			<thead class="thead-dark">
				<tr>
					<th class="sortable">Btlec</th>
					<th class="sortable">Deno</th>
					<th class="sortable"></th>
					<th class="sortable">Galec</th>
					<th class="sortable">Ville</th>
					<th class="sortable">Type Ets</th>
					<th class="sortable">Centrale</th>
					<th class="sortable">Date fermeture</th>
					<th class="sortable">Chargé de mission</th>
					<th class="sortable">CA</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($magList)): ?>
					<?php foreach ($magList as $key => $mag): ?>
						<tr class="<?=isset($mag['diff'])?'ko':"ok"?>">
							<td><?=$mag['id']?></td>
							<td><a class="text-sca" href="fiche-mag.php?id=<?=$mag['id']?>"><?=$mag['deno_sca']?></a></td>
							<td><?=isset($mag['diff'])?'<a href="#" class="diff" title='.$mag['diff-field'].'><i class="fas fa-not-equal"></i></a>':""?></td>
							<td class="text-sca"><?=$mag['galec_sca']?></td>
							<td class="text-sca"><?=$mag['cp_sca'] .' '.$mag['ville']?></td>
							<td class="text-gessica"><?= isset($ets[ $mag['acdlec_code']])?$ets[ $mag['acdlec_code']]:"" ?></td>
							<td class="text-sca"><?=isset($centraleName[$mag['centrale_doris']])?$centraleName[$mag['centrale_doris']]:"" ?></td>
							<td class="text-sca"><?= !empty($mag['date_fermeture'])? (new DateTime($mag['date_fermeture']))->format('d-m-Y'):"" ?></td>
							<td><?= UserHelpers::getPrenom($pdoUser, $mag['id_cm_web_user'])?></td>
							<td class="text-right"><nobr><?= number_format((float)$mag['CA_Annuel'],0,'',' ') ?></nobr></td>
						</tr>
					<?php endforeach ?>

				<?php endif ?>

			</tbody>
		</table>
	</div>
</div>

</div>
</div>