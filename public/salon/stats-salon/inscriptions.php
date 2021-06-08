 <div class="row">
  <div class="col">
    <table class="table table-sm" id="table-inscr">
      <thead class="thead-dark">
        <tr>
          <th class="sortable" onclick="sortTable(0);">Magasin</th>
          <th class="sortable" onclick="sortTable(1);">Galec</th>
          <th class="sortable" onclick="sortTable(2);">Centrale</th>
          <th class="sortable" onclick="sortTable(3);">Nom</th>
          <th class="sortable" onclick="sortTable(4);">Prénom</th>
          <th class="sortable" onclick="sortTable(5);">Fonction</th>
          <th class="sortable" onclick="sortTable(6);">Date Inscription</th>
          <th class="sortable" onclick="sortTable(7);">Mardi</th>
          <th class="sortable" onclick="sortTable(8);">Mercredi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($listParticipant as $part):?>
          <?php
          $bgMardi=$part['mardi'] +$part['repas_mardi'];
          $bgMardi=$class[$bgMardi];
          $bgMercredi=$part['mercredi'] +$part['repas_mercredi'];
          $bgMercredi=$class[$bgMercredi];
          ?>
          <tr>
            <td class="<?=strtolower($listCentrale[$part['centrale']])?>"><?=$part['deno']?></td>
            <td><?=$part['galec']?></td>
            <td><?=$part['centrale']?></td>
            <td><?=$part['nom']?></td>
            <td><?=$part['prenom']?></td>
            <td><?=$part['fonction']?></td>
            <td><?=$part['datesaisie']?></td>
            <td class="<?=$bgMardi?>"><?=$presence[$part['mardi']].$repas[$part['repas_mardi']]?></td>
            <td class="<?=$bgMercredi?>"><?=$presence[$part['mercredi']].$repas[$part['repas_mercredi']]?></td>
          </tr>
        <?php endforeach?>
      </tbody>
    </table>
  </div>
</div>
