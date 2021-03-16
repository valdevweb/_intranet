
<?php foreach ($dials as $dial): ?>
	<?php if (!empty($dial['msg'])): ?>
		<?php
		$notif="";
		if($dial['mag']==1){
			if(isset($infoLitige[0]['mag'])){
				$name=$infoLitige[0]['mag'];
			}else{
				$name=MagHelpers::deno($pdoMag,$infoLitige['galec']);
				// $infoLitige['mag'];
			}
			$type='bg-kaki-light';
			if ($dial['read_dial']==0){
				$notif="<i class='fas fa-bell pr-3 text-yellow'></i>";
			}
		}
		else{
			$name=UserHelpers::getFullname($pdoUser, $dial['id_web_user']);
			$type='bg-alert-primary';
		}
		if($dial['filename']!=''){
			$pj=createFileLink($dial['filename']);
		}else{
			$pj='';
		}
		?>
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
			<div class="row alert <?=$type?> mb-5" id="<?=$dial['id']?>">
				<div class="col">
					<div class="row heavy">
						<div class="col">
							<?=$notif.$name ?>
						</div>
						<div class="col">
							<div class="text-right">
								<i class="far fa-calendar-alt pr-3"></i><?=$dial['dateFr']?><i class="far fa-clock px-3"></i><?=$dial['heure']?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<?=$dial['msg']?>
						</div>
						<div class="col-auto">
							<?=$pj?>
						</div>
					</div>
					<?php if ($dial['mag']==1): ?>
						<div class="row">
							<div class="col text-right">Marquer le message comme :</div>
							<div class="col-auto">
								<?php if ($dial['read_dial']==0): ?>
									<button class="btn btn-sm btn-kaki" name="read[<?=$dial['id']?>]">Lu</button>
									<?php else: ?>
										<button class="btn btn-sm btn-kaki" name="not_read[<?=$dial['id']?>]">Non lu</button>
									<?php endif ?>
									<input type="hidden" class="form-control" name="id_dial[<?=$dial['id']?>]"  value="<?=$dial['id']?>">
								</div>
							</div>
						<?php endif ?>
					</form>
				</div>
			</div>



		<?php endif ?>

	<?php endforeach ?>

