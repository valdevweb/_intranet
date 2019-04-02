<?php if(!empty($flashNews)): ?>
<?php foreach ($flashNews as $flash): ?>
<div class="bg-white row">
	<div class="col s12">
		<div class="row no-margin-bottom">
			<div class="col">
				<img src="../public/img/documents/flash-400.png">
			</div>
			<div class="col s6">
				<h1 class="center blue-text text-darken-4"><?=$flash['title']?></h1>
			</div>
		</div>
		<div class="row p-5">
			<div class="col s8">
				<p><?=$flash['content']?></p>
				<p class="align-right"><i>Cliquez sur la photo pour afficher le document</i></p>
			</div>
			<div class="col s4 center">
				<a href="<?=UPLOAD_DIR.'/flash/'.$flash['pj']?>" target="_blank"><img src="<?=UPLOAD_DIR.'/flash/'.$flash['vignette']?>" class="p-5"></a>
			</div>
		</div>
	</div>
</div>
<?php endforeach ?>
<?php endif;?>




