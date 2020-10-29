
<div class="row mt-3">
	<div class="col-10">
		<h5 class="khand text-main-blue pb-3">Produit(s) :</h5>
		<p><span class="text-main-blue">Facture : </span><?=$infoLitige[0]['facture'] .' du '.$infoLitige[0]['datefacture']?></p>


	</div>
	<div class="col-2 text-right">
		<?php if ($infoLitige[0]['id_robbery'] !=null): ?>
			<a href="xl-robbery.php?id=<?=$infoLitige[0]['id_robbery']?>" class="img-overlay"></a>
		<?php endif ?>

	</div>
</div>

<div class="row">
	<div class="col">
		<table class="table light-shadow">
			<thead class="thead-dark">
				<tr>
					<th class="align-top">Article</th>
					<th class="align-top">Dossier</th>
					<th class="align-top">Palette</th>
					<th class="align-top">Désignation</th>
					<th class="align-top">Fournisseur</th>
					<th class="align-top">Réclamation</th>
					<th class="align-top">Qté <br>litige</th>

					<th class="align-top text-right">Valo</th>
					<th class="align-top">PJ</th>
					<th class="align-top pt-1"><img src="../img/litiges/serial-icon-title.png"></th>

				</tr>
			</thead>
			<tbody>
				<?php
				$sumValo=0;
				foreach ($infoLitige as $prod)
				{
							// $valo=round(($prod['tarif'] / $prod['qte_cde'])*$prod['qte_litige'],2);
					$pj='';

					if($prod['pj']!='')
					{
						$pj=createFileLink($prod['pj']);
					}
					if($prod['box_tete']==1){
						$classBoxHead='class=box-head';
					}
					else{
						$classBoxHead='';

					}
					if($prod['box_art']!='')
					{
						$boxIco='<i class="fas fa-box-open text-green pr-2"></i>';
					}
					else{
						$boxIco='';
					}
					if($prod['serials']){
						$serialIcon="serial-ok.png";
					}else{
						$serialIcon="serial-ko.png";
					}


							// cas général = pas d'inversion de produit
					if($prod['inversion'] =="")
					{
						echo '<tr '.$classBoxHead.'>';
						echo '<td>'.$prod['article'].'</td>';
						echo '<td>'.$prod['dossier_gessica'].'</td>';
						echo '<td>'.$prod['palette'].'</td>';
						echo '<td>'.$boxIco.$prod['descr'].'<br>Ean : '.$prod['ean'].'</td>';
						echo '<td>'.$prod['fournisseur'].'</td>';
						echo '<td>'.$prod['reclamation'].'</td>';
						echo '<td class="text-right">'.$prod['qte_litige'].'</td>';
						echo '<td class="text-right"> '.number_format((float)$prod['valo_line'],2,'.','').'&euro;</td>';

						echo '<td class="text-right">'.$pj.'</td>';

						echo '<td><a href="#largeModal" data-toggle="modal" data-id="'.$prod['id_detail'].'"><img src="../img/litiges/'.$serialIcon.'"></a></td>';
						echo '</tr>';
					}
								// si il s'agit d'une inversion de produit, on rajoute une ligne avec le produit inversé
								// si on n'a pas trouvé le produit, dans la désignation, on affiche le gencod saisi par le magasin

					else
					{

						echo '<tr class="text-reddish">';
						echo '<td>'.$prod['article'].'</td>';
						echo '<td>'.$prod['dossier_gessica'].'</td>';
						echo '<td>'.$prod['palette'].'</td>';
						echo '<td>'.$prod['descr'].'<br>Ean : '.$prod['ean'].'</td>';
						echo '<td>'.$prod['fournisseur'].'</td>';
						echo '<td>'.$prod['reclamation'].'</td>';
						echo '<td class="text-right">'.$prod['qte_litige'].'</td>';
						echo '<td class="text-right"> '.number_format((float)$prod['tarif']/$prod['qte_cde']*$prod['qte_litige'],2,'.','').'</td>';

						echo '<td class="text-right">'.$pj.'</td>';

						echo '<td><a href="#largeModal" data-toggle="modal" data-id="'.$prod['id_detail'].'">SN</a></td>';

						echo '</tr>';

						if($prod['inv_article']==''){
							echo '<tr class="text-center text-reddish"><td colspan="11">Produit reçu à la place de la référence ci-dessus :</td></tr>';

							echo '<tr class="text-reddish">';
							echo '<td colspan="4">Produit non trouvé - EAN saisi :</td>';

							echo '<td colspan="2" class="text-left">'.$prod['inversion'].'</td>';

							echo '<td class="text-right">'.$prod['inv_qte'].'</td>';
							echo '<td class="text-right"></td>';
							echo '<td class="text-right"></td>';

							echo '</tr>';
							echo '<tr class="text-center bg-reddish text-white">';
							echo '<td colspan="10" class="text-right">&nbsp;</td>';

							echo '</tr>';
							echo '<tr>';
							echo '<td colspan="10" class="text-right">&nbsp;</td>';
							echo '</tr>';


						}else{

							$valoInv=round( $prod['inv_qte']*$prod['inv_tarif'],2);
							echo '<tr class="text-center text-reddish"><td colspan="11">Produit reçu à la place de la référence ci-dessus :</td></tr>';
							echo '<tr class="text-reddish">';
							echo '<td>'.$prod['inv_article'].'</td>';
							echo '<td>&nbsp;</td>';
							echo '<td>&nbsp;</td>';
							echo '<td>'.$prod['inv_descr'].'</td>';
							echo '<td>'.$prod['inv_fournisseur'].'</td>';
							echo '<td></td>';
							echo '<td class="text-right">'.$prod['inv_qte'].'</td>';
							echo '<td class="text-right">'.number_format((float)$valoInv,2,'.','').'&euro;</td>';
							echo '<td class="text-right"></td>';
							echo '<td class="text-center"></td>';
							echo '</tr>';
							echo '<tr class="text-center bg-reddish text-white">';
							echo '<td colspan="8" class="text-right">'.number_format((float)$prod['valo_line'],2,'.','').'&euro;</td>';
							echo '<td colspan="2"></td>';

							echo '</tr>';
							echo '<tr>';
							echo '<td colspan="10" class="text-right">&nbsp;</td>';
							echo '</tr>';

						}



					}
				}
				?>
			</tbody>
		</table>

	</div>
</div>
<div class="row">
	<div class="col text-right">
		<a href="edit-litige.php?id=<?=$prod['id_main']?>" class="btn btn-primary"><i class="fas fa-tools pr-3"></i>Modifier</a>
	</div>
</div>
<div class="row">
	<div class="col">
		<p class="text-right heavy bigger mb-3 text-main-blue pr-3">Valorisation magasin : <?= $prod['valo']?> </p>
		<p><?= $articleAZero?></p>

	</div>
</div>



