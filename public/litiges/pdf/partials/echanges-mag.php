<h2 class="text-center">Echanges avec le magasin</h2>
<?php
if(isset($dials[0]['msg']))	{

    echo '<table class="padding-table border-table-sec">';
    echo '<tr class="border-table-sec">';
    echo '<td class="bg-sec text-white">date</td>';
    echo '<td class="bg-sec text-white">Interlocuteur</td>';
    echo '<td class="bg-sec text-white">Message</td>';
    echo '</tr>';
    foreach ($dials as $dial) {
        if($dial['mag']==1){
            $personn=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $dial['id_web_user'],'deno');

        }else{
            $personn=UserHelpers::getInternUser($pdoUser, $dial['id_web_user']);
            $personn=$personn['fullname'];
        }

        echo '<tr class="border-table-sec">';
        echo '<td>'.$dial['dateFr'].'</td>';
        echo '<td>'.$personn.'</td>';
        echo '<td>'.$dial['msg'].'</td>';
        echo '</tr>';
    }
    echo '</table>';
}
else{
    echo '<p>Aucun message n\'a été échangé avec le magasin</p>';

}

?>
