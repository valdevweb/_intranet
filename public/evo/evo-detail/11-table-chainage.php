<?php if (!empty($parents)): ?>
	<div class="row">
		<div class="col">
			<?php if (count($parents)==1): ?>
				La demande d'évo <?=$_GET['id']?> a été transférée sur la demande :

				<?php foreach ($parents as $key => $parent): ?>
					<a href="evo-detail.php?id=<?=$parent['enfant']?>">#<?=$parent['enfant']?></a>
				<?php endforeach ?>
			<?php else: ?>
			La demande d'évo <?=$_GET['id']?> a été transférée sur les demandes :<br>

				<?php foreach ($parents as $key => $parent): ?>
					<a href="evo-detail.php?id=<?=$parent['enfant']?>">demande #<?=$parent['enfant']?></a><br>
				<?php endforeach ?>
			<?php endif ?>

		</div>
	</div>

	<?php endif ?>

	<?php if (!empty($enfants)): ?>
	<div class="row">
		<div class="col">
			<?php if (count($enfants)==1): ?>
				La demande d'évo <?=$_GET['id']?> est issue de la demande :

				<?php foreach ($enfants as $key => $enfant): ?>
					<a href="evo-detail.php?id=<?=$enfant['parent']?>">#<?=$enfant['parent']?></a>
				<?php endforeach ?>
			<?php else: ?>
			La demande d'évo <?=$_GET['id']?> est issue des demandes :<br>

				<?php foreach ($enfants as $key => $enfant): ?>
					<a href="evo-detail.php?id=<?=$enfant['parent']?>">demande #<?=$enfant['parent']?></a><br>
				<?php endforeach ?>
			<?php endif ?>

		</div>
	</div>

	<?php endif ?>