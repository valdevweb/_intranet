<!--historique-->
<div class="container">
	<div class="row">
		<div class="col l12">
			<h1 class="light-blue-text text-darken-2">Vos demandes</h1>
		</div>
	</div>
	<div class="row">
		<div class="col l9"></div>
		<div class="white col l3">
			<p class="smallTxt center"><i>Signification des icônes </i></p>
			<p class="smallTxt"><i class='fa fa-lock' aria-hidden='true'></i><span class="pd-left">dossier clos</span></p>
			<p class="smallTxt"><i class='fa fa-fire' aria-hidden='true'></i><span class="pd-left">nouvelle réponse</p></span></p>
		</div>
	</div>





<br>

	<div class="row">
	<?php
	$allMsg=listAllMsg($pdoBt);
	//tri le tableau en fonction des id réponse et date msg
	if($allMsg)
	{
		$allMsg = array_msort($allMsg, array('reply_id'=>'SORT_DESC','date_msg'=>'SORT_DESC'));
	}



	?>

		<div class="col s12 l12">
		<table class="striped s12 l12 grey-text text-darken-2 z-depth-2">
			<thead>
				<tr>
					<!-- ajouter historique des demandes sur tous les services (date / service / titre/ repondu le / btn détail) -->
					<th class='contact'>Date</th>
					<th class='contact'>Service</th>
					<th class='contact'>Objet</th>
					<th class='contact'>Date réponse</th>
					<th class="center">Consulter</th>
					<th class='contact center'>Statut</th>
				</tr>
			</thead>
			<?php if($allMsg): ?>
			<?php foreach($allMsg as $key => $value): ?>
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
				<td class="center">
					<?php
					// if(!empty($value['max(table_replies.date_reply)']))
					// {
						echo "<a class='btn-floating  orange' href='../mag/edit-msg.php?msg=". $value['msg_id']."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
					// }
					?>
		    	</td>
				<td class="center">
					<?php
					if($value['etat']==="clos")
					{
						echo "<i class='fa fa-lock' aria-hidden='true'></i>";
					}
					// au moins une rép btlec
					elseif ($value['etat']==="en cours")
					{
					 echo "<i class='fa fa-fire' aria-hidden='true'></i>";

					}
					else
					{
						echo "en attente";
					}
					?>
				</td>

			</tr>
			<?php endforeach ?>
			<?php endif; ?>
		</table>
		</div>
	</div> <!-- fin row histo-->
	</div>
