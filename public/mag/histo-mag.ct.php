<!--historique-->
<div class="container">
	<div class="row">
		<div class="col l12">
			<h1 class="light-blue-text text-darken-2">Vos demandes</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-9"></div>
		<div class="col-3">
			<div class="shadow-sm bg-white rounded border p-2">
				<p class="smallTxt center"><i>Signification des icônes </i></p>
				<p class="smallTxt"><i class="fas fa-lock"></i><span class="pd-left">dossier clos</span></p>
				<p class="smallTxt"><i class="fas fa-fire-alt"></i><span class="pd-left">nouvelle réponse</p></span></p>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col">
			<p class="alert alert-primary mb-5"><i class="fa fa-exclamation-triangle fa-lg  pr-4" aria-hidden="true"></i>Vous pouvez désormais rouvrir une demande en cliquant sur le cadenas <i class="fas fa-lock"></i> de la colonne statut</p>

			<table class="table table-sm table-striped">
				<thead class="thead-light">
					<tr>
						<th>Date</th>
						<th>Service</th>
						<th>Objet</th>
						<th>Date réponse</th>
						<th>Consulter</th>
						<th>Statut</th>
					</tr>
				</thead>
				<tbody>
					<?php if($allMsg): ?>
						<?php foreach($allMsg as $key => $value): ?>
							<tr>
								<td><?= date('d-m-Y', strtotime($value['date_msg']))?></td>
								<td>
									<?php
									$service=$userManager->getService($pdoUser,$value['id_service']);
									?>
									<?= $service['service'] ?>
								</td>
								<td>
									<?= nl2br($value['objet'])?>
								</td>
								<td>
									<?php
									if(!empty($value['max(table_replies.date_reply)']))
									{
										echo date('d-m-Y',strtotime($value['max(table_replies.date_reply)']));
									}
									?>
								</td>
								<td>
									<a href="../mag/edit-msg.php?msg=<?=$value['msg_id']?>"><i class="far fa-eye"></i></a>
								</td>
								<td>
									<?php if ($value['etat']=="clos"): ?>
										<a href="unlock.php?id_msg=<?=$value['msg_id']?>"><i class="fas fa-lock"></i></a>
									<?php elseif ($value['etat']=="en cours"): ?>
										<i class="fas fa-fire-alt"></i>
									<?php else: ?>
										en attente
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif; ?>
				</tbody>
			</table>


		</div>
	</div>
	<!-- fin row histo-->
</div>
<script src="../js/sorttable.js"></script>




