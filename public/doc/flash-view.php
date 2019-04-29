<?php if(!empty($flashNews)): ?>
	<?php foreach ($flashNews as $flash): ?>
		<?php
		if(!empty($flash['pj']) && !empty($flash['vignette']))
		{
			$link='<a href="'.UPLOAD_DIR.'/flash/'.$flash['pj'].'" target="_blank"><img src="'.UPLOAD_DIR.'/flash/'.$flash['vignette'].'" class="p-5"></a>';
			$vignetteplusdoc='<p class="align-right"><i>Cliquez sur la photo pour afficher le document</i></p>';


		}
		elseif (empty($flash['pj']) && !empty($flash['vignette']))
		{
			$link='<img src="'.UPLOAD_DIR.'/flash/'.$flash['vignette'].'" class="p-5">';
			$vignetteplusdoc='';


		}
		elseif (!empty($flash['pj']) && empty($flash['vignette']))
		{
			$link='';
			$vignetteplusdoc='<a href="'.UPLOAD_DIR.'/flash/'.$flash['pj'].'"  class="blue-link" target="_blank">Plus d\'information en cliquant ici</a>';


		}
		else{
			$link='';
			$vignetteplusdoc='';

		}
		?>
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
						<?= $vignetteplusdoc?>

					</div>
					<div class="col s4 center">
						<?php
						echo $link;
						?>

					</div>
				</div>
			</div>
		</div>
	<?php endforeach ?>
<?php endif;?>




