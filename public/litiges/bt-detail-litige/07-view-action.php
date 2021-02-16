<div class="row mt-3">
	<div class="col">
		<h5 class="khand text-main-blue pb-3">Actions :</h5>
	</div>
</div>
<?php if ($infoLitige[0]['commission']==0): ?>
	<div class="row">
		<div class="col stamps pb-3">
			Cliquez sur <a href="#hidden"  class="stamps" ><i class="fas fa-user-check stamp pending"></i></a> si le dossier a été statué en commission
		</div>
	</div>


	<div class="row mb-3" id="hidden">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?> " method="post">
				<div class="form-group">
					<label class="text-main-blue">Commentaire :</label>
					<textarea class="form-control" name="cmt" rows="3" id="cmtarea"></textarea>
				</div>
				<div class="form-group">
					<input type="hidden" class="form-control" name="iddossier" id="hiddeninput" value="<?=$_GET['id']?>">
				</div>
				<button class="btn btn-black" name="validate">Valider</button>
				<button class="btn btn-red" id="annuler">Annuler</button>

			</form>
		</div>
	</div>
<?php endif ?>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">

	<div class="row">
		<div class="col">
			<table class="table light-shadow">
				<thead class="thead-dark">
					<tr>
						<th>date</th>
						<th>Par</th>
						<th>Action</th>
						<th>PJ</th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($actionList) && count($actionList)>0): ?>
					<?php foreach ($actionList as $action): ?>
						<?php
						if($action['pj']!=''){
							$pj=createFileLink($action['pj']);
						}else{
							$pj='';
						}
						$notif="";
						if ($action['read_action']==0 && $action['id_contrainte']==5){
							$notif="<i class='fas fa-bell pl-2 text-green'></i>";
						}
						?>
						<tr>
							<td class="nowrap"><?=$action['dateFr'].$notif?></td>
							<td class="nowrap"><?=UserHelpers::getFullname($pdoUser, $action['id_web_user'])?></td>
							<td>
								<?=$action['libelle']?>
								<?php if ($action['id_contrainte']==5): ?>
									<div class="text-right">Marquer comme :
									<?php if ($action['read_action']==0): ?>
										<button class="btn btn-sm btn-kaki" name="read_action">Lu</button>
										<?php else: ?>
											<button class="btn btn-sm btn-kaki" name="not_read_action">Non lu</button>
										<?php endif ?>
										</div>
										<input type="hidden" class="form-control" name="id_action"  value="<?=$action['id_action']?>">
									<?php endif ?>
							</td>
							<td><?=$pj?></td>
						</tr>
					<?php endforeach ?>
					<?php else: ?>
						<tr><td colspan="3">Aucune Action</td></tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>

</form>


<div class="bg-separation"></div>