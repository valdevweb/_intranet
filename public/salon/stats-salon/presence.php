<div class="row pb-3">
  <div class="col">
    <h5 class="text-main-blue heavy"> Présence :</h5>
    <p>Nombre de magasins présents : <?= $nbMagPresent?></p>
    <p>Nombre de personnes présentes : <?= $nbPres?></p>
  </div>
</div>


<div class="row">
  <div class="col-5">
    <p class="text-main-blue heavy">Heures d'arrivées mardi :</p>
  </div>
  <div class="col-2"></div>
  <div class="col-5">
    <p class="text-main-blue heavy">Heures d'arrivées mercredi :</p>
  </div>
</div>

<div class="row">

  <div class="col-5">
    <table class="table table-sm table-bordered text-right">
      <thead class="thead-dark">
        <tr>
          <?php foreach ($statHeureMardi as $heureMa): ?>
            <th><?=$heureMa['hour']?>h</th>
          <?php endforeach ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php foreach ($statHeureMardi as $heureMa):?>
            <td><?=$heureMa['nb']?></td>
          <?php endforeach ?>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-2"></div>
  <div class="col-5">
    <table class="table table-sm table-bordered text-right">
      <thead class="thead-dark">
        <tr>
          <?php foreach ($statHeureMercredi as $heureMe):?>
            <th><?=$heureMe['hour']?>h</th>
          <?php endforeach ?>
        </tr>
      </thead>
      <tbody>
        <tr>
         <?php  foreach ($statHeureMercredi as $heureMe) :?>
          <td><?=$heureMe['nb']?></td>
        <?php endforeach ?>
      </tr>
    </tbody>
  </table>
</div>
</div>

<div class="row">
  <div class="col">
    <p class="text-main-blue heavy">Présence des participants:</p>
  </div>
</div>
<div class="row mb-3">
 <div class="col text-right">
  <a href="xl-generate-participant2020.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Export présence participants</a>
</div>
</div>
<div class="row">
  <div class="col">
    <table class="table table-sm" id="table-presence">
      <thead class="thead-dark">
        <tr>
          <th class="sortable" onclick="sortTable(0);">Magasin</th>
          <th class="sortable" onclick="sortTable(2);">Centrale</th>
          <th class="sortable" onclick="sortTable(3);">Nom</th>
          <th class="sortable" onclick="sortTable(4);">Prénom</th>
          <th class="sortable" onclick="sortTable(5);">Jour</th>
          <th class="sortable" onclick="sortTable(5);">Heure</th>

        </tr>
      </thead>
      <tbody>

        <?php foreach ($listParticipant as $part):?>
          <?php

          if($part['date_passage'] !=''){
            $jour=date('w',strtotime($part['date_passage']));
            $jourStr = ($jour==2) ? 'mardi' : 'mercredi' ;
          }
          else{
            $jourStr='';
          }
          ?>

          <tr>
            <td class="<?=strtolower($listCentrale[$part['centrale']])?>"><?=$part['deno']?></td>
            <td><?=$listCentrale[$part['centrale']]?></td>
            <td><?=$part['nom']?></td>
            <td><?=$part['prenom']?></td>
            <td><?=$jourStr?></td>
            <td><?=$part['heure']?></td>

          </tr>

        <?php endforeach ?>

      </tbody>
    </table>
  </div>
</div>




