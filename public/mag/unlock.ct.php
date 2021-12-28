<div class="container">
	<div class="row mb-3">
		<div class="col">
			<h1 class="text-main-blue">Réouverture de la demande<br><span class="sub-h1">N° <?= $_GET['id_msg'] .' - '. $msg['objet'] ?></span></h1>
		</div>
	</div>
	<!-- formulaire -->
	<div class="row ">
		<div class="col border p-3">
			<form action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?id_msg='.$_GET['id_msg'] ?>"  method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="reply">Motif de la demande de réouverture :</label>
					<textarea class="form-control" id="reply" name="reply" rows="3" placeholder="votre message" required="require"></textarea>
				</div>
				<div class="pt-5 pb-2" id="file-upload">
					<p class="blue-text text-darken-4 pb-2"><i class="fa fa-download pr-3 fa-lg" aria-hidden="true"></i>Envoyer des pièces jointes</p>
					<p><input type="file" name="file[]"  class='form-control-file' ></p>
					<p class="pr-1 pt-2 blue-text text-darken-4" id="p-add-more"><a id="addmore" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Ajouter un fichier supplémentaire</a></p>
				</div>
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
		if(is_null($reply['fullname'])){
			$by="";
			$side='mag';
			$logo="../img/logos/leclerc-rond-50.jpg";

		}else{
			$color="orange-text";
			$by="<p class='nom'>" .$reply['fullname'] ."</p>";
			$side='bt';
			$logo="../img/logos/bt-rond-50.jpg";
		}
		?>
		<?= $by ?>
		<div class="row mb-5">
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
<script type="text/javascript">
	$(document).ready(function (){
		$('#addmore').click(function(){
			$('#p-add-more').prepend('<p><input type="file" name="file[]"></p>');
			$('input[type="file"]').val();
		});
	});
</script>