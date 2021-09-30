<div class="row focusing">
	<div class="col  pt-5">
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
			<div class="form-row ">
				<div class="col-4">
					<div class="form-group">
						<label class="font-weight-bold">Article :</label>
						<input class="form-control mr-5 pr-5" placeholder="article, palette" name="search_strg" id="search_strg" type="text"  value="<?=isset($search_strg) ? $search_strg : false?>">
					</div>
				</div>
				<div class="col-2">
					<div class="form-group">
						<label>Date de début :</label>
						<input type="date" name="date_start" id="date_start" class="form-control" value="<?=$start?>">
					</div>
				</div>
				<div class="col-2">
					<div class="form-group">
						<label>Date de fin :</label>
						<input type="date" name="date_end" id="date_end" class="form-control" value="<?=$today?>">
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label>Statut : </label>
						<select name="statut" id="statut" class="form-control">
							<option value="">Tout statut</option>
							<option value="0">En cours</option>
							<option value="1">Cloturé</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-row text-right">

				<div class="col">
					<button class="btn btn-primary " type="submit" id="search_form" name="search_casse"><i class="fas fa-search pr-2"></i>Rechercher une casse</button>
					<button class="btn secTwo" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>


				</div>

			</div>
		</form>
	</div>
</div>


<div class="row" id="result">
	<div class="col">
		<?php
			// si résultat
		if(isset($casses) && !empty($casses))
		{
			$arrStatutCasse=['<span class="text-red">en cours</span>','clos'];

			echo '<h5 class="text-main-blue py-3 text-center">Résultat pour votre recherche : <span class="heavy bg-grey patrick-hand px-3">'.$_POST['search_strg'].'</span></h5>';

			echo '<div class="text-center pb-3"><a href="xl-dashboard-casse.php?date_start='.$_POST['date_start'].'&date_end='.$_POST['date_end'].'&statut='.$_POST['statut'].'&search_strg='.$_POST['statut'].'" class="btn secTwo"><i class="fas fa-file-excel pr-3"></i>Exporter</a></div>';

			echo '<table class="table table-sm table-bordered">';
			echo '<thead class="thead-dark">';
			echo '<tr>';
			echo '<th>N°</th>';
			echo '<th>Article</th>';
			echo '<th>Date</th>';
			echo '<th>Désignation</th>';
			echo '<th>Fournisseur</th>';
			echo '<th class="text-right">PCB</th>';
			echo '<th class="text-right">Valo</th>';
			echo '<th>Statut</th>';
			echo '<th class="text-right"><i class="far fa-trash-alt"></i></th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach ($casses as $casse) {
					// $dateCasse=;
					// $dateCasse=date('d/m/y', strtotime($casse['date_casse']));
				echo '<tr>';
				echo '<td><a href="detail-casse.php?id='.$casse['id'].'">'.$casse['id'].'</td>';
				echo '<td>'.$casse['article'].'</td>';
				echo '<td class="text-right">'.date('d/m/y', strtotime($casse['date_casse'])).'</td>';

				echo '<td>'.$casse['designation'].'</td>';
				echo '<td>'.$casse['fournisseur'].'</td>';
				echo '<td class="text-right">'.$casse['pcb'].'</td>';
				echo '<td class="text-right">'.$casse['valo'].'</td>';
				echo '<td class="text-right">'.$arrStatutCasse[$casse['etat']].'</td>';
				echo '<td class="text-right"><a href="delete-casse.php?id='.$casse['id'].'" class="red-link"><i class="far fa-trash-alt"></i></a></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';


		}
		elseif(isset($casses) && empty($casses)){
			echo '<p class="alert alert-warning">Aucun résultat pour votre recherche : <span class="heavy bg-reddish text-white px-3">'.$_POST['search_strg'] .'</span></p>';
		}

		?>

	</div>
</div>

