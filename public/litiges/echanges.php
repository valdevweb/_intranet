
<?php foreach ($dials as $dial): ?>
	<?php if (!empty($dial['msg'])): ?>
		<?php
		if($dial['mag']==1){
			if(isset($infoLitige[0]['mag'])){
				$name=$infoLitige[0]['mag'];
			}else{
				$name=MagHelpers::deno($pdoMag,$infoLitige['galec']);
				// $infoLitige['mag'];
			}
			$type='bg-kaki-light';
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

		<div class="row alert <?=$type?> mb-5">
			<div class="col">
				<div class="row heavy">
					<div class="col">
						<?=$name ?>
					</div>
					<div class="col">
						<div class="text-right">
							<i class="far fa-calendar-alt pr-3"></i><?=$dial['dateFr']?><i class="far fa-clock px-3"></i><?=$dial['heure']?>
						</div>
					</div>
				</div>
				<div class="row ">
					<div class="col">
						<?=$dial['msg']?>
					</div>
					<div class="col-auto">
						<?=$pj?>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>

<?php endforeach ?>

