<!--historique-->
<div class="container">
	<div class="row">
		<div class="col s12">
			<h1 class="light-blue-text text-darken-2">Vos demandes</h1>
		</div>
	</div>
	<div class="row">

		<div class="col s12">
		<table class="striped s12 grey-text text-darken-2 z-depth-2">
			<thead>
				<tr>
					<!-- ajouter historique des demandes sur tous les services (date / service / titre/ repondu le / btn détail) -->
					<th class='contact'>Date</th>
					<th class='contact'>Service</th>
					<th class='contact'>Objet</th>
					<th class='contact'>Etat de la demande</th>
					<th class="center">Consulter</th>
				</tr>
			</thead>
			<?php foreach($allMagMsg as $key => $value): ?>
			<tr>
				<td>
					<!--  H:i:s -->
					<?= date('d-m-Y', strtotime($value['date_msg']))?>
				</td>
				<td>

					<?php

					$service=service($pdoBt,$value['id_service']);
					$service=$service['full_name'];


					?>
				<?= $service ?>

				</td>
				<td>
					<?= $value['objet']?>
				</td>
				<td>
					<?= ($value['date_reply'])? date('d-m-Y', strtotime($value['date_reply'])) : '' ?>

				</td>
				<td class="center"> <a class="btn-floating z orange" href="../mag/edit-msg.php?msg=<?= $value['id']?>"><i class="fa fa-eye" aria-hidden="true"></i></a>

		    	</td>
			</tr>
			<?php endforeach ?>
		</table>
		</div>
	</div> <!-- fin row histo-->
	</div>
