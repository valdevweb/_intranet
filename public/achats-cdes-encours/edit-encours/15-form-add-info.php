<div class="row  border-left border-right border-top rounded py-3">
    <div class="col-auto">
        <div class="form-group">
            <label>Date de livraison prévisionnelle :</label>
            <input type="date" class="form-control date" name="date_previ[<?= $prod['id'] ?>]">
        </div>
    </div>
    <div class="col-1"></div>
    <div class="col-3">
        <div class="form-group">
            <label>Quantité prévisionnelle :</label>
            <input type="text" class="form-control qte-saisie" id="qte-saisie-<?= $prod['id'] ?>" name="qte_previ[<?= $prod['id'] ?>]" placeholder="qte prévi" data-id="<?= $prod['id'] ?>">
            <div class="restant" data-id-prod-restant="<?= $prod['id'] ?>"></div>
        </div>
    </div>

    <input type="hidden" id="input-restant-<?= $prod['id'] ?>" name="restant_previ[<?= $prod['id'] ?>]" value="<?= $prod['qte_uv_cde'] - $sommePrevi[$prod['id']] ?>">
</div>
<div class="row  border-left border-right border-bottom  mb-5">
    <div class="col">
        <div class="form-group">
            <label>Commentaires BTLEC:</label>
            <textarea class="form-control cmt" name="cmt_btlec[<?= $prod['id'] ?>]" row="3"><?= $prod['cmt_btlec'] ?></textarea>
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            <label>Commentaires Galec:</label>
            <textarea class="form-control" name="cmt_galec[<?= $prod['id'] ?>]" row="3"><?= $prod['cmt_galec'] ?></textarea>
        </div>
    </div>
</div>