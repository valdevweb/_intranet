<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="blue-text text-darken-4">Réouverture de la demande<br><span class="sub-h1">N° <?= $infoMsg['id_msg'] .' - '. $infoMsg['objet'] ?></span></h1>
		</div>
	</div>
	<!-- formulaire -->
	<div class="row ">
		<div class="col bg-white p-5">
			<form method="post" enctype="multipart/form-data">
				<p>Motif de la demande de réouverture</p>
				<div class="form-group">
					<label for="reply"></label>
					<textarea class="form-control" id="reply" name="reply" rows="3" placeholder="votre message" required="require"></textarea>
				</div>
				<label for='incfile'>Ajouter une pièce jointe : </label><input type='file' class='form-control-file' id='incfile' name='incfile' >
				<p id="p-add-more"><a id="add_more" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Envoyer d'autres fichiers</a></p>

				<p class="text-right"><button type="submit" id="submit" class="btn btn-primary" name="submit">Envoyer</button></p>
				<?php include ('../view/_errors.php');?>

			</form>
			<p class="text-right pt-3"><a href="histo-mag.php" class="blue-text"> Retour</a></p>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<h1 class="blue-text text-darken-4 no-margin">Historique de votre demande : <br><span class='sub-h1'>n° <?= $_GET['id_msg']?> - <?= $msg['objet']?> </span></h1>
		</div>
	</div>
	<!-- message 1  -->
	<div class="row">
		<div class="col-12">
			<div class="card-panel mag mb-5">
				<p class="text-right date"><?= date('d-m-Y', strtotime($msg['date_msg']))?></p>
				<p><?=$msg['msg']?></p>
				<?php
				if(!empty($msg['inc_file']))
				{
					echo "<p><span class='labelFor'>Pièce jointe : </span></p>";
					echo "<p>".formatPJ($msg['inc_file'])."</p>";
				}
				?>
			</div>
			<div class="center-text">
				<hr class="line">
			</div>
		</div>
	</div>

<?php foreach($replies as $reply): ?>
		<?php
	//nom de la personne qui a répondu si bt
		$by=repliedByIntoName($pdoBt,$reply['replied_by']);
		if(is_null($by))
		{
			$by="";
			$side='mag';;
			$logo="../img/logos/leclerc-rond-50.jpg";

		}
		else
		{
			$color="orange-text";
			$by="<p class='nom'>" .$by ."</p>";
			$side='bt';
			$logo="../img/logos/bt-rond-50.jpg";


		}
		?>
		<?= $by ?>
		<div class="row">
			<div class="col-12">
				<div class="card-panel <?= $side ?>">
					<img class="w3-circle" src="<?=$logo ?>">

					<p class="text-right date"><?= date('d-m-Y', strtotime($reply['date_reply']))?></p>
					<p><?= $reply['reply'] ?></p>
					<?php
					if(!empty($reply['inc_file']))
					{
						echo "<p><span class='labelFor'>Pièce(s) jointe(s) :</p>";
						echo  "<p>".formatPJ($reply['inc_file'])."</p>";
					}
					?>
				</div>
			</div>
		</div>
	<?php endforeach ?>



	<!--fin container  -->
</div>
