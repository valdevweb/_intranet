<?php if(!empty($flashNews)): ?>
	<?php foreach ($flashNews as $flash): ?>
		<?php
		if(!empty($flash['pj']) && !empty($flash['vignette']))
		{
			$link='<a href="'.URL_UPLOAD.'flash/'.$flash['pj'].'" target="_blank"><img src="'.URL_UPLOAD.'/flash/'.$flash['vignette'].'" ></a>';
			$vignetteplusdoc='<p class="align-right"><i>Cliquez sur la photo pour afficher le document</i></p>';


		}
		elseif (empty($flash['pj']) && !empty($flash['vignette']))
		{
			$link='<img src="'.URL_UPLOAD.'flash/'.$flash['vignette'].'" >';
			$vignetteplusdoc='';


		}
		elseif (!empty($flash['pj']) && empty($flash['vignette']))
		{
			$link='';
			$vignetteplusdoc='<a href="'.URL_UPLOAD.'flash/'.$flash['pj'].'"  class="blue-link" target="_blank">Plus d\'information en cliquant ici</a>';


		}
		else{
			$link='';
			$vignetteplusdoc='';

		}
		?>
		<div class="row my-3">
			<div class="col"></div>
			<div class="col-8 shadow">
				<div class="row no-margin-bottom">
					<div class="col">
						<img src="../img/documents/flash-400.png">
					</div>
					<div class="col">
						<h1 class=" text-main-blue mt-5"><?=$flash['title']?></h1>
					</div>
				</div>
				<div class="row p-5 align-top">
					<?php if (empty($link)): ?>
						<div class="col">
							<p><?=$flash['content']?></p>
							<?= $vignetteplusdoc?>

						</div>
						<?php else: ?>

							<div class="col-8">
								<p><?=$flash['content']?></p>
								<?= $vignetteplusdoc?>

							</div>
							<div class="col text-center ">
								<?php
								echo $link;
								?>

							</div>
						<?php endif ?>



					</div>
				</div>
				<div class="col"></div>

			</div>
		<?php endforeach ?>
	<?php endif;?>




