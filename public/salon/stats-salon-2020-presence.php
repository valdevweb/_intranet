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
        <?php
        echo '<tr>';
        foreach ($statHeureMardi as $heureMa) {
          echo '<th>'.$heureMa['hour'].'h</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        foreach ($statHeureMardi as $heureMa) {
          echo '<td>'.$heureMa['nb'].'</td>';
        }
        echo '</tr>';
        ?>
      </tbody>
    </table>
  </div>
  <div class="col-2"></div>


  <div class="col-5">
    <table class="table table-sm table-bordered text-right">
      <thead class="thead-dark">
        <?php
        echo '<tr>';
        foreach ($statHeureMercredi as $heureMe) {
          echo '<th>'.$heureMe['hour'].'h</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        foreach ($statHeureMercredi as $heureMe) {
          echo '<td>'.$heureMe['nb'].'</td>';
        }
        echo '</tr>';
        ?>
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
        <?php
        foreach ($listParticipant as $part)
        {

          if($part['date_passage'] !=''){
            $jour=date('w',strtotime($part['date_passage']));
            $jourStr = ($jour==2) ? 'mardi' : 'mercredi' ;
          }
          else{
            $jourStr='';
          }


          echo '<tr>';
          echo '<td class="'.strtolower($part['centrale']).'">'.$part['deno'].'</td>';
          echo '<td>'.$part['centrale'].'</td>';
          echo '<td>'.$part['nom'].'</td>';
          echo '<td>'.$part['prenom'].'</td>';
          echo '<td>'.$jourStr.'</td>';
          echo '<td>'.$part['heure'].'</td>';

          echo '</tr>';

        }
        ?>
      </tbody>
    </table>
  </div>
</div>




