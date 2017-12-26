<!--historique-->
<div class="container">
	<div class="row">
		<div class="col s12">
			<h1 class="light-blue-text text-darken-2">Vos demandes</h1>
		</div>
	</div>
	<div class="row">
	<?php
	$allMsg=listAllMsg($pdoBt);


	function etat($etat,$repliedBy,$dateReply){
		switch ($data['etat']) {
			case 'nouveau':
			$value="en attente de réponse";
			break;
			case 'clos':
			$value="clôturé le " . $data['date_reply'] ;
			break;
			case 'en cours':
			$value= $data['replied_by'] . "vous a répondu le  " . $data['date_reply'] ;
			break;
			default:
			$value="";
			break;
		}
	}
			echo "<pre>";
			var_dump($allMsg);
			echo '</pre>';


	?>

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
					<?= $value['objet']?>
				</td>
				<td>
					<?php
					if($value['etat']=="en cours")
					{
						echo $value['replied_by'] ." a répondu le ". date('d-m-Y',strtotime($value['max(table_replies.date_reply)']));

					}
					else
					{
						echo $value['etat'];
					}

					?>

				</td>
				<td class="center"> <a class="btn-floating  orange" href="../mag/edit-msg.php?msg=<?= $value['msg_id']?>"><i class="fa fa-eye" aria-hidden="true"></i></a>

		    	</td>
			</tr>
			<?php endforeach ?>
		</table>
		</div>
	</div> <!-- fin row histo-->
	</div>
