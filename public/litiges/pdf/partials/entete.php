<p class="text-right entete">date d'édition : <?= date('d-m-Y') ?></p>
<table class="padding-table border-table-prim">
    <tr>
        <td class="deux bg-prim text-white bigger text-center" colspan="4">LITIGE n° <?= $litige[0]['dossier'] ?></td>
    </tr>
    <tr>
        <td class="quatre heavy"><?= $listCentrales[$litige[0]['centrale']] ?></td>
        <td class="quatre"><?= $litige[0]['mag'] . ' - ' . $litige[0]['btlec'] ?></td>
        <td class="quatre  heavy">DATE DECLARATION</td>
        <td class="quatre"><?= $litige[0]['datecrea'] ?></td>
    </tr>
</table>