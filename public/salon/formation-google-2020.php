<div class="row">
	<div class="col">
		<ul>
			<li>Formation Google ChromeBook: <a href="#google" id="show-google"> s'incrire</a><br>
			Au programme : fonctionnement de l'OS, explication de la gamme Leclerc, <br>points forts : simplicité, Office, sécurité, batterie, rapidité
			</li>
		</ul>

	</div>
</div>

<div class="row hidden" id="google">
	<div class="col" >
		<form method="post" action="<?=$_SERVER['PHP_SELF']?>">

			<div class="row">
				<div class="col border p-4">
					<div class="row mb-3">
						<div class="col text-main-blue">
						<b>Formation Google ChromeBook - Inscription</b>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							Quel jour souhaitez-vous suivre la formation ?<br>
							<div class="form-check">
								<input class="form-check-input" type="radio" value="mardi" id="jour" name="jour"  <?= FormHelpers::checkChecked("mardi","jour") ?> required>
								<label class="form-check-label" for="jour">mardi</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" value="mercredi" id="jour" <?= FormHelpers::checkChecked("mercredi","jour") ?> name="jour">
								<label class="form-check-label" for="jour">mercredi</label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<div class="form-group">
								Combien de personnes de votre magasin souhaitez vous inscrire à la formation ?
								<input type="text" class="form-control nb-input" name="nb"  pattern="[0-9]+" title="Seuls les chiffres sont autorisés"  id="nb" required>
							</div>
						</div>
					</div>

					<div class="row ">
						<div class="col">
							Un créneau de 30 min vous sera communiqué. Quelle plage horaire vous arrange le plus ?
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							Choix numéro 1 :
							<?php foreach ($listCreneau as $key => $creneau): ?>
								<div class="form-check">
									<input class="form-check-input" type="radio" value="<?=$creneau['id']?>"  name="first-choice" <?= FormHelpers::checkChecked($creneau['id'],"first-choice") ?> required>
									<label class="form-check-label" for="one"><?=$creneau['start'] .' - ' .$creneau['end']?></label>
								</div>
							<?php endforeach ?>



						</div>
						<div class="col">
							Choix numéro 2 :
							<?php foreach ($listCreneau as $key => $creneau): ?>
								<div class="form-check">
									<input class="form-check-input" type="radio" value="<?=$creneau['id']?>"  name="second-choice"  <?= FormHelpers::checkChecked($creneau['id'],"second-choice") ?> required>
									<label class="form-check-label" for="one"><?=$creneau['start'] .' - ' .$creneau['end']?></label>
								</div>
							<?php endforeach ?>


						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								Pour la confirmation du créneau, sur quelle adresse email souhaitez vous être contacté ?
								<input type="text" class="form-control email-input" name="email" id="email" required>
							</div>
							<div id="error_email"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-6 text-right">
							<button class="btn btn-primary" type="submit" name="add-formation-google">Envoyer</button>
						</div>
					</div>
				</div>
			</div>
		</form>

	</div>
</div>