<div class="spacing-s"></div>
<table class="padding-table border-table-grey">

    <tr>
        <td class="trois text-center bg-dark-grey text-white">IMPUTATION</td>
        <td class="trois text-center bg-dark-grey text-white">TYPOLOGIE</td>
        <td class="trois text-center bg-dark-grey text-white">TRAITEMENT</td>
    </tr>
    <tr>
        <td class="trois text-center"><?= $analyse['imputation'] ?></td>
        <td class="trois text-center"><?= $analyse['typo'] ?></td>
        <td class="trois text-center"><?= $analyse['conclusion'] ?></td>
    </tr>
</table>
<div class="spacing-s"></div>
<table class="padding-table border-table-grey">
    <tr>
        <td class="trois text-center bg-dark-grey text-white">PREPARATEUR</td>
        <td class="trois text-center bg-dark-grey text-white">CONTROLEUR</td>
        <td class="trois text-center bg-dark-grey text-white">CHARGEUR</td>
    </tr>
    <tr>
        <td class="trois text-center"><?= $infos['fullprepa'] ?></td>
        <td class="trois text-center"><?= $infos['fullctrl'] ?></td>
        <td class="trois text-center"><?= $infos['fullchg'] ?></td>
    </tr>
</table>

<div class="spacing-s"></div>

<table class="padding-table border-table-grey">
    <tr>
        <td class="cinq bg-dark-grey text-white">DATE PREPA :</td>
        <td class="cinq"><?= $infos['dateprepa'] ?></td>

    </tr>
</table>
<div class="spacing-s"></div>

<table class="padding-table border-table-grey">
    <tr>
        <td class="trois text-center bg-dark-grey text-white">TRANSPORTEUR</td>
        <td class="trois text-center bg-dark-grey text-white">AFFRETE</td>
        <td class="trois text-center bg-dark-grey text-white">TRANSIT</td>
    </tr>
    <tr>
        <td class="trois text-center"><?= $infos['transporteur'] ?></td>
        <td class="trois text-center"><?= $infos['affrete'] ?></td>
        <td class="trois text-center"><?= $infos['transit'] ?></td>
    </tr>
</table>