
	<h1 class="text-main-blue pb-5 ">Traitement de la demande n° <?= $_GET['id'] ?></h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row py-3">
		<div class="col">
			<h5 class="khand text-main-blue"> Rappel de la demande initiale: </h5>
		</div>
	</div>
	<div class="row">
		<div class="col alert alert-primary">
			<div class="row">
				<div class="col">
					<?= $thisOuv['btlec'].'-'.$thisOuv['deno']?>
				</div>
				<div class="col text-right">
					date de la demande : <?= $thisOuv['datesaisie']?>
				</div>
			</div>
			<div class="row">
				<div class="col border-top-blue">
					<?= $thisOuv['msg']?>
				</div>
			</div>
			<div class="row pt-3">
				<div class="col">
					<?=$pj?>
				</div>
			</div>
		</div>
	</div>

	<?php
// si échange de msg
	if(!empty($theseRep))
	{
		echo '<div class="bg-separation"></div>';
		echo '<div class="row py-3">';
		echo '<div class="col">';
		echo '<h5 class="khand text-main-blue">Echanges avec le magasin : </h5>';
		echo '</div></div>';
		foreach ($theseRep as $rep)
		{
			$pj='';
			if($rep['mag']==0){
				$alertColor='alert-warning';
				$from=UserHelpers::getFullname($pdoUser, $rep['id_web_user']);
			}
			else{
				$alertColor='alert-primary';
				$from=$thisOuv['mag'];
			}
			if(!empty($rep['pj']))
			{
				$pjtemp=createFileLink($rep['pj']);
				$pj='<br>Pièce jointe : '. $pjtemp ;
			}
			echo '<div class="row">';
			echo '<div class="col alert '.$alertColor.'">';
			echo $rep['msg'];
			echo $pj;
			echo '<br><br>';
			echo '<i class="fas fa-user-circle pr-3"></i>' .$from .' - le ' .$rep['datesaisie'];
			echo '</div>';
			echo '</div>';
		}
	}

	?>
	<div class="bg-separation"></div>