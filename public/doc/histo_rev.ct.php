<div class="container white-container shadow">
	<h1 class="blue-text text-darken-4">Historique des dépots de reversements</h1>

	<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Choisir un type de document pour filter l'historique</h4>
	<!-- <hr> -->
	<div class="row mb-5">
		<div class="col">
			<form method='post' action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>">
				<div class="row">
					<div class="col">
						<div class="form-row pl-5">
							<select class="form-control" id="doc_type" name="doc_type">
								<option value="">Tout type de documents</option>
								<?php foreach($revList as $rev): ?>
									<?php
									if(isset($_POST['doc_type']) && $_POST['doc_type']==$rev['id'])
									{
										$select="selected";
									}
									else
									{
										$select="";
									}
									echo '<option value="'.$rev['id'].'" '. $select.'>'.$rev['name'].'</option>'
									?>
								<?php endforeach ?>
							</select>
						</div>
					</div>
						<div class="col">
							<button type="submit" id="submit" class="btn btn-primary" name="submit">OK</button>
						</div>

						<div class="col"></div>
					</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php include('../view/_errors.php') ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="info-mag">
				<p class="text-center"><i class="fa fa-lightbulb-o fa-2x pr-3" aria-hidden="true"></i>Toutes les pièces comptables sont disponibles dans Docubase.<br> <a href="http://172.30.101.66/rheaweb/auth" id="docubase" target="_blank">Cliquez ici pour y accéder</a></p>
			</div>
		</div>
	</div>

	<div class="row pt-4">
		<div class="col"></div>

		<?php
			if(!empty($fullRevList))
			{

				echo '<div class="col-6 border shadow px-5 pb-5">';
			}
		 ?>



			<?php
			//  affichage : année puis mois
			foreach ($fullRevList as $rev)
			{
				if(empty($rev['divers']))
				{
					$nomRev='reversements ' . $rev['name'];
				}
				else
				{
					$nomRev=$rev['divers'];
				}

				if($refYear != $rev['year'])
				{
					echo'<div class="pt-5"><time datetime="" class="icon"><em>&nbsp;</em><strong>&nbsp;</strong><span>'.$rev['year'].'</span></time></div>';
					echo '<p class="month-img text-center pt-4"><img  src="../img/seasons/'.$rev['month'].'.png"></p>';
					echo '<p class="text-center pb-4 month '.$monthsStr[$rev['month']].'">___ ' .ucfirst($monthsStr[$rev['month']]).' ___</span></p>';

					echo '<p class="detail">- '.$rev['fulldate']. ' : '.$nomRev.'</p>';
					$refYear = $rev['year']	;
					$month=$rev['month'];
				}
				else
				{
					if($month!= $rev['month'])
					{
						echo '<p class="month-img text-center pt-4"><img  src="../img/seasons/'.$rev['month'].'.png"></p>';
						echo '<p class=" text-center pb-3 month '.$monthsStr[$rev['month']].'">___ ' .ucfirst($monthsStr[$rev['month']]).' ___</span></p>';

						echo '<p class="detail">- '.$rev['fulldate']. ' :  ' .$nomRev .'</p>';
						$month=$rev['month'];
					}
					else
					{
						echo '<p class="detail">- '.$rev['fulldate']. ' : ' .$nomRev.'</p>';
					}

				}
			}

			?>
			<!-- <hr> -->
		</div>
		<div class="col"></div>

	</div>
</div><!-- ./container -->

<!-- </div>  -->
