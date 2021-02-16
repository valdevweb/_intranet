<div class="row pt-2 pb-1 rounded bg-grey">
	<div class="col">
		<p class="text-secondary">Mes articles/palettes en attente de commande</p>

	</div>
	<div class="col text-right mr-2">
		<div class="cart-toggle position-relative">
			<a href="#cart" id="cart">
				<i class="fa fa-shopping-cart fa-lg"></i>
				<div class='cart-wrapper'><span id='cart-count'><?=$nbPalettePanier?></span></div>
			</a>
		</div>
	</div>
</div>
<div class="row ontop">
	<div class="col text-right">
		<div class="shopping-cart shadow hidden">
			<div class="shopping-cart-header">
				<i class="fa fa-shopping-cart cart-icon"></i>
				<div class="shopping-cart-total">
					<span class="lighter-text">Palette(s)/ article:</span>
					<span class="text-danger font-weight-bold"><?=$nbPalettePanier?></span>
				</div>
			</div> <!--end shopping-cart-header -->

			<div class="shopping-cart-items">
				<?php foreach ($paletteEtArticleDansPanier as $key => $tempPalette): ?>
					<?php if (empty($tempPalette['id_palette'])): ?>
						<div class="row no-gutters">
							<div class="col text-left">
								Article :
							</div>
							<div class="col text-right pr-2">
								<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']).'#'.$tempPalette['article_occ']?>"><?=$tempPalette['article_occ']?></a>
								x <?= $tempPalette['qte_cde']?>
							</div>
							<div class="col-auto">
								<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?idTempDelArticle='.$tempPalette['id']?>">
									<i class="fas fa-ban"></i>
								</a>
							</div>
						</div>
						<?php else: ?>
							<div class="row no-gutters">
								<div class="col text-left">
									Palette :
								</div>
								<div class="col text-right pr-2">
									<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$tempPalette['id_palette']?>#detailPalette"><?=$tempPalette['palette']?></a>
								</div>
								<div class="col-auto">
									<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?idTempDel='.$tempPalette['id']?>">
										<i class="fas fa-ban"></i>
									</a>
								</div>
							</div>
						<?php endif ?>

					<?php endforeach ?>


				</div>
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<button href="#" name="checkout" class="btn button">Commander</button>
				</form>
			</div>
		</div>
	</div>