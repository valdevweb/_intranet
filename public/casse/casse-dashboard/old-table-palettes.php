<div class="row mb-3">
				<div class="col">
					<table class="table table-sm table-bordered table-striped" id="palettes">
						<thead class="thead-dark">
							<tr>
								<th class="sortable" onclick="sortTable(0);">Exp</th>
								<th class="sortable" onclick="sortTable(1);">Palette</th>
								<th class="sortable" onclick="sortTable(2);">Palette<br> contremarque</th>
								<th class="sortable text-center" onclick="sortTable(3);">Statut</th>

								<th class="sortable" onclick="sortTable(4);">Date expé</th>
								<th class="sortable" onclick="sortTable(5);">Magasin</th>
								<th class="sortable" onclick="sortTable(6);">Valo<br>palette</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($palettes as $palette)
							{
								echo '<tr>';
								echo '<td>'.$palette['id_exp'].'</td>';
								echo '<td><a href="detail-palette.php?id='.$palette['id_palette'].'">'.$palette['palette'].'</a></td>';
								echo '<td>'.$palette['contremarque'].'</td>';


								if($palette['statut']==0 && $palette['NumeroPalette']==null){
									$statut='<img src="../img/casse/encours.jpg">';
								}
								elseif($palette['statut']==1 || $palette['NumeroPalette']!=null){
									$statut='<img src="../img/casse/livrer.png">';
								}elseif ($palette['exp']==1 && $palette['mt_fac']!='') {
									$statut='<img src="../img/casse/livre.png"><img src="../img/casse/creditcard.png">';
								}elseif ($palette['exp']==1 && $palette['mt_fac']==null){
									$statut='<img src="../img/casse/livre.png"><img src="../img/casse/logo_deee.jpg">';
								}
								echo '<td class="text-center">'.$statut.'</td>';



								echo '<td class="text-right">'.$palette['dateDelivery'].'</td>';
								echo '<td class="text-right">'.$palette['btlec'].'</td>';
								echo '<td class="text-right">'.$palette['valopalette'].'</td>';

								echo '</tr>';
							}

							?>
						</tbody>
					</table>
				</div>
			</div>
