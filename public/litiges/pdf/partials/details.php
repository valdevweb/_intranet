<h2 class="text-center">Détail du litige</h2>

<p>Commentaire du magasin : <?= isset($initialCmt['msg']) ? $initialCmt['msg'] : '' ?></p>

<div class="spacing-s"></div>
<table class="padding-table border-table-sec">
    <tr class="border-table-sec">
        <td class="cinq bg-sec text-white">PALETTE</td>
        <td class="cinq bg-sec text-white">CODE ARTICLE</td>
        <td class="cinq bg-sec text-white">DESIGNATION</td>
        <td class="cinq bg-sec text-white text-right">QUANTITE</td>
        <td class="cinq bg-sec text-white text-right">VALORISATION</td>
        <td class="cinq bg-sec text-white">RECLAMATION</td>
    </tr>
    <?php
   
    foreach ($litige as $prod) {
        echo '<tr class="border-table-sec">';
        echo '<td>' . $prod['palette'] . '</td>';
        echo '<td>' . $prod['article'] . '</td>';
        echo '<td>' . $prod['descr'] . '</td>';
        echo '<td class="text-right">' . $prod['qte_litige'] . '</td>';
        echo '<td class="text-right">' . number_format((float)$prod['valo_line'], 2, '.', '') . '&euro;</td>';
        echo '<td>' . $prod['reclamation'] . '</td>';
        echo '</tr>';
        if ($prod['inversion'] != "") {
            $valoInv = round($prod['qte_cde'] * $prod['inv_tarif'], 2);
            echo '<tr class="border-table-sec"><td colspan="5" class="text-center text-prim heavy">Produit reçu à la place de la référence ci-dessus :</td></tr>';
            echo '<tr class="border-table-sec">';
            echo '<td>' . $prod['palette'] . '</td>';
            echo '<td class="text-prim heavy">' . $prod['inv_article'] . '</td>';
            echo '<td class="text-prim heavy">' . $prod['inv_descr'] . '</td>';
            echo '<td class="text-right text-prim heavy">' . $prod['qte_litige'] . '</td>';
            echo '<td class="text-right text-prim heavy">' . number_format((float)$valoInv, 2, '.', '') . '&euro;</td>';
            echo '<td class="text-right"></td>';
            echo '</tr>';
        }
    }
    ?>
</table>