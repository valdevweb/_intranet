<div class="row mt-3 mb-3">
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="khand text-main-blue pb-3">Produit(s) :</h5>

					<table class="table light-shadow">
						<thead class="thead-dark">
							<tr>
								<th class="align-top">Article</th>
								<th class="align-top">Dossier</th>
								<th class="align-top">Désignation</th>
								<th class="align-top">Fournisseur</th>
								<th class="align-top">Réclamation</th>
								<th class="align-top">Quantité <br>litige</th>
								<!-- <th class="align-top text-right">Date déclaration</th> -->
								<th class="align-top text-right">Valo</th>
								<th class="align-top">Pièces jointes</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sumValo=0;
							foreach ($fLitige as $prod)
							{
								$valo=round(($prod['tarif'] / $prod['qte_cde'])*$prod['qte_litige'],2);
								$pj='';

								if($prod['pj']!='')
								{
									$pj=createFileLink($prod['pj']);
										// $pj="jkoezfaji"	;

								}
								echo '<tr>';
								echo '<td>'.$prod['article'].'</td>';
								echo '<td>'.$prod['dossier_gessica'].'</td>';
								echo '<td>'.$prod['descr'].'</td>';
								echo '<td>'.$prod['fournisseur'].'</td>';
								echo '<td>'.$prod['reclamation'].'</td>';
								echo '<td class="text-right">'.$prod['qte_litige'].'</td>';
								echo '<td class="text-right">'.number_format((float)$valo,2,'.','').'&euro;</td>';
								echo '<td class="text-right">'.$pj.'</td>';
								echo '</tr>';
								if($prod['inversion'] !="")
								{
									$valoInv=round( $prod['inv_qte']*$prod['inv_tarif'],2);
									echo '<tr class="text-center bg-reddish text-white"><td colspan="8">Produit reçu à la place de la référence ci-dessus :</td></tr>';
									echo '<tr>';
									echo '<td>'.$prod['inv_article'].'</td>';
									echo '<td></td>';
									echo '<td>'.$prod['inv_descr'].'</td>';
									echo '<td>'.$prod['inv_fournisseur'].'</td>';
									echo '<td></td>';
									echo '<td class="text-right">'.$prod['inv_qte'].'</td>';
									echo '<td class="text-right">'.number_format((float)$valoInv,2,'.','').'&euro;</td>';
									echo '<td class="text-right"></td>';
									echo '</tr>';
								}
							}
							?>
						</tbody>
					</table>
					<p class="text-right heavy bigger mb-3 text-main-blue pr-3">Valorisation magasin : <?= $valoMag?> </p>
				</div>
			</div>
		</div>
	</div>