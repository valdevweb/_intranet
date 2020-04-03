<div class="row">
	<div class="col">
		<h5 class="text-main-blue text-center pt-3 pb-3">Nombre de magasins affichés : <?=$nbResult?></h5>
		<div class="alert alert-primary">
			Pour obtenir plus d'information sur un magasin, veuillez cliquer sur son nom.<br>
			Survolez l'icône <i class="fas fa-not-equal"></i> pour connaître les champs qui ne sont pas identiques. <?= $diff ?> magasin(s) sont concernés dans la sélection actuelle
		</div>
		<p class="text-main-blue">Filtrer l'affichage :<a href="#" class="hide-btn px-3 mx-5"><i class="fas fa-not-equal pr-3"></i> uniquement</a><a  href="#" class="show-btn px-3"><i class="fas fa-eraser pr-3"></i>réafficher tout</a></p>

		<table class="table table-sm shadow">
			<thead class="thead-dark">
				<tr>
					<th>Btlec</th>
					<th>Deno</th>
					<th></th>
					<th>Galec</th>
					<th>Ville</th>
					<th>Type Ets</th>
					<th>Centrale</th>
					<th>Date fermeture</th>
					<th>Chargé de mission</th>
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
							<td><?= UserHelpers::getFullname($pdoUser, $mag['id_cm_web_user'])?></td>
						</tr>
					<?php endforeach ?>

				<?php endif ?>

			</tbody>
		</table>

	</div>
</div>