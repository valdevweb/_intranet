<div class="row">
    <div class="col border-left border-right border-top">
        <div class="row bg-dark-grey py-2">
            <div class="col">
                <?= $prod['libelle_art'] ?>
            </div>
        </div>
        <div class="row bg-orange">
            <div class="col-lg-2">
                <span class="font-weight-bold">Référence :</span>
                <?= $prod['ref'] ?>
            </div>
            <div class="col-lg-2">
                <span class="font-weight-bold">Article :</span>
                <?= $prod['article'] ?>
            </div>
            <div class="col-lg-2">
                <span class="font-weight-bold">Dossier :</span>
                <?= $prod['dossier'] ?>
            </div>

            <div class="col">
                <span class="font-weight-bold">EAN :</span>
                <?= $prod['ean'] ?>
            </div>
            <div class="col">
                <span class="font-weight-bold">OP :</span>
                <?= $prod['libelle_op'] ?>

            </div>
        </div>
        <div class="row ">
            <div class="col-auto">
                <span class="font-weight-bold">Date commande : </span>
                <?= ($prod['date_cde'] != null) ? date('d/m/y', strtotime($prod['date_cde'])) : "" ?>

            </div>
            <div class="col-auto">
                <span class="font-weight-bold text-orange">Numéro : </span>
                <span class="text-orange"><?= $prod['id_cde'] ?></span>

            </div>
            <div class="col-auto">
                <span class="font-weight-bold">Qte init. :</span>
                <?= $prod['qte_init'] ?>

            </div>
            <div class="col-auto">
                <span class="font-weight-bold"> Colis restants: </span>
                <?= $prod['qte_cde'] ?>
            </div>
            <div class="col-auto">
                <span class="font-weight-bold">UV restants : </span>
                <span data-id-prod-uv="<?= $prod['id'] ?>" data-nb-uv="<?= $prod['qte_uv_cde'] ?>"><?= $prod['qte_uv_cde'] ?></span>
            </div>
            <div class="col-auto">
                <span class="font-weight-bold">PCB : </span>
                <?= $prod['cond_carton'] ?>
            </div>
            <div class="col-auto">
                <span class="font-weight-bold">Date livraison : </span>
                <?= ($prod['date_liv'] != null) ? date('d/m/y', strtotime($prod['date_liv'])) : "" ?>
            </div>
            <div class="col-auto">
                <span class="font-weight-bold">Date début op : </span>
                <?= ($prod['date_start'] != null) ? date('d/m/y', strtotime($prod['date_start'])) : "" ?>
            </div>
        </div>
    </div>
</div>