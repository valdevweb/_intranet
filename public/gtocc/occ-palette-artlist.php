
<div class="row">
	<div class="col">
		<h3 class="text-main-blue text-center pt-4" id="article">Articles d'occasion</h3>

	</div>
</div>

<div class="row ">
	<div class="col">

		<table class="table table-sm">
			<thead class="thead-dark">
				<tr>
					<th>Code article</th>
					<th>Code Dossier</th>
					<th>Désignation</th>
					<th>Fournisseur</th>
					<th>EAN</th>
					<th>PANF</th>
					<th>DEEE</th>
					<th>SORECOP</th>
					<th class="text-right">Qté à dispo</th>
					<th colspan="2" class="text-center">Ajouter</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($listAssortiment as $assor): ?>
					<?php
					$artInTemp=isMagArticleInTemp($pdoBt, $assor['article_qlik']);
					?>


						<tr>
							<td id="<?=$assor['article_qlik']?>"><?=$assor['article_qlik']?></td>
							<td><?=$assor['dossier_qlik']?></td>
							<td><?=$assor['design_qlik']?></td>
							<td><?=$assor['fournisseur_qlik']?></td>
							<td><?=$assor['ean_qlik']?></td>
							<td><?=$assor['panf_qlik']?></td>
							<td><?=$assor['deee_qlik']?></td>
							<td><?=$assor['sorecop']?></td>
							<td class="text-right"><?=$assor['qte_qlik']?></td>
							<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

								<td class="text-right">
									<input type="text" data-input="<?=$assor['article_qlik']?>" name="qte_cde" class="form-control mini-input" value="<?=!empty($artInTemp)? $artInTemp['qte_cde']:""?>">

								</td>
								<td>
									<div  data-btn="<?=$assor['article_qlik']?>">
										<button class="btn btn-primary btn-strange" name="add-article"><i class="fa fa-shopping-cart"></i></button>
									</div>

								</td>
								<input type="hidden" name="article_qlik" value="<?=$assor['article_qlik']?>">
								<input type="hidden" name="design_qlik" value="<?=$assor['design_qlik']?>">
								<input type="hidden" name="fournisseur_qlik" value="<?=$assor['fournisseur_qlik']?>">
								<input type="hidden" name="ean_qlik" value="<?=$assor['ean_qlik']?>">
								<input type="hidden" name="panf_qlik" value="<?=$assor['panf_qlik']?>">
								<input type="hidden" name="deee_qlik" value="<?=$assor['deee_qlik']?>">
								<input type="hidden" name="sorecop" value="<?=$assor['sorecop']?>">
								<input type="hidden" name="qte_qlik" value="<?=$assor['qte_qlik']?>">
							</form>


						</tr>


				<?php endforeach ?>

			</tbody>
		</table>

	</div>
</div>

<div class="row pb-3">
	<div class="col text-right">
	</div>
</div>

<div class="bg-separation"></div>