<div class="row mt-5">
	<div class="col">
		<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3" id="badge-lk"></i>VOS BADGES</h4>
		<p>Cette année, afin d'assurer la sécurité de tous et de limiter les regroupements de personnes notamment au niveau de l'accueil, nous mettons à votre disposition un document pdf vous permettant d'imprimer vos badges. Ces badges sont munis d'un qrcode qu'il vous suffira de scanner à l'accueil du salon.</p>
		<p><b>Aucun badge ne sera délivré sur le salon, en revanche nous vous fournirons le support de badge</b></p>
	</div>
</div>

<div class="row">
	<div class="col"></div>
	<div class="col-auto">
		<table class="table table-striped table-nonfluid" id="item_table">
			<thead class="thead-dark">
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Email</th>
					<th class="text-right">Mardi</th>
					<th class="text-right">Mercredi</th>
					<th class="text-center">Imprimer</th>
					<th>Supprimer</th>

				</tr>
			</thead>

			<?php if (!empty($participantList)): ?>

				<?php foreach ($participantList as $key => $part): ?>

					<tr>

						<td><?=$part['nom']?></td>
						<td><?=$part['prenom']?></td>
						<td><?=$part['email']?></td>
						<td class="text-right"><?=$YesNo[$part['mardi']]?><?=($part['repas_mardi']==1)?'<i class="fas fa-utensils pl-2"></i>':""?></td>
						<td class="text-right"><?=$YesNo[$part['mercredi']]?><?=($part['repas_mercredi']==1)?'<i class="fas fa-utensils pl-2"></i>':""?></td>
						<td class="text-center"><a href="?print-one=<?=$part['id']?>" target="_blank"><i class="fas fa-print"></i></a></td>
						<td class="text-center"><a href="inscription-modif.php?id=<?=$part['id']?>" class="red-link"><i class="fas fa-user-minus"></i></a></td>

					</tr>

				<?php endforeach ?>

			<?php else: ?>
				<tr><td colspan="7">Vous n'avez inscrit aucun participant pour l'instant</td></tr>
			<?php endif ?>

		</table>
	</div>
	<div class="col"></div>
</div>
<div class="row mt-3">
	<div class="col">
		<div class="alert alert-primary">
			Avant d'imprimer, pensez à vérifier vos paramètres d'impression. Dans les paramètres avancés, modifiez la mise à l'échelle pour qu'elle soit sur la valeur "défaut"
		</div>

	</div>
</div>
<div class="row mt-3">
	<div class="col">Si vous préférez recevoir toutes vos invitations par mail, merci de renseigner votre adresse et de cliquer sur envoyer</div>
</div>
<div class="row mt-3">
	<div class="col">
		<form method="post"  action="<?=$_SERVER['PHP_SELF']?>">
			<div class="row">
				<div class="col-4">
					<div class="form-group">
						<input class="form-control" type="email" required="require" name="email" placeholder="votre adresse mail">
					</div>
				</div>
				<div class="col">
					<button class="btn btn-primary" type="submit" name="send">Envoyer</button>
				</div>
			</div>
		</form>
	</div>
</div>
	<div class="row mt-3">
		<div class="col">
			<p class="text-right"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>