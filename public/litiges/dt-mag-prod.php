<div class="row mt-3 mb-3">
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="khand text-main-blue pb-3">Produit(s) :</h5>

					<table class="table light-shadow">
						<thead class="thead-dark">
							<tr>
								<th class="align-top">Article</th>
								<th class="align-top">EAN</th>
								<th class="align-top">Désignation</th>
								<th class="align-top">Fournisseur</th>
								<th class="align-top">Réclamation</th>
								<th class="align-top">Quantité <br>litige</th>
								<!-- <th class="align-top text-right">Date déclaration</th> -->
								<th class="align-top">Pièces jointes</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sumValo=0;
							foreach ($thisLitige as $prod)
							{
								$pj='';
								if($prod['pj']!='')
								{
									$pj=createFileLink($prod['pj']);
								}
								echo '<tr>';
								echo '<td>'.$prod['article'].'</td>';
								echo '<td>'.$prod['ean'].'</td>';
								echo '<td>'.$prod['descr'].'</td>';
								echo '<td>'.$prod['fournisseur'].'</td>';
								echo '<td>'.$prod['reclamation'].'</td>';
								echo '<td class="text-right">'.$prod['qte_litige'].'</td>';
								echo '<td class="text-right">'.$pj.'</td>';
								echo '</tr>';
								if($prod['inversion'] !="")
								{
									echo '<tr class="text-center bg-reddish text-white"><td colspan="8">Produit reçu à la place de la référence ci-dessus :</td></tr>';
									echo '<tr>';
									echo '<td>'.$prod['inv_article'].'</td>';
									echo '<td>'.$prod['inversion'].'</td>';
									echo '<td>'.$prod['inv_descr'].'</td>';
									echo '<td>'.$prod['inv_fournisseur'].'</td>';
									echo '<td></td>';
									echo '<td class="text-right">'.$prod['inv_qte'].'</td>';
									echo '<td class="text-right"></td>';
									echo '</tr>';
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>