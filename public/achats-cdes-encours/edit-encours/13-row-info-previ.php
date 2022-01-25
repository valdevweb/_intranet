<div class="row pt-3">
    <div class="col border-left border-right ">

        <?php foreach ($listInfos[$prod['id']] as $keyInfo => $value) : ?>
            <?php
            if ($listInfos[$prod['id']][$keyInfo]['date_previ'] >= date('Y-m-d')) {
                $sommePrevi[$prod['id']] += $listInfos[$prod['id']][$keyInfo]['qte_previ'];
            }
            ?>
            <div class="row">
                <div class="col font-weight-bold text-orange">
                    <i class="fas fa-arrow-alt-circle-right pr-2"></i>Infos du <?= date('d/m/y', strtotime($listInfos[$prod['id']][$keyInfo]['date_insert'])) ?>
                </div>
            </div>
            <div class="row ">

                <div class="col-lg-2 pl-5">
                    <span class="font-weight-bold">Date prévi : </span>
                    <span class="text-right"><?= ($listInfos[$prod['id']][$keyInfo]['date_previ'] != null) ? date('d/m/y', strtotime($listInfos[$prod['id']][$keyInfo]['date_previ'])) : "" ?></span>
                </div>
                <div class="col-lg-2">
                    <span class="font-weight-bold">Qte prévi : </span>
                    <span class="text-right"><?= $listInfos[$prod['id']][$keyInfo]['qte_previ'] ?></span>
                </div>

                <!-- zone de modification -->
                <div class="col-lg-4">
                    <div class="row update-form-<?= $listInfos[$prod['id']][$keyInfo]['id'] ?> d-none">
                        <div class="col-5">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date_previ_update[<?= $listInfos[$prod['id']][$keyInfo]['id'] ?>]" value="<?= $listInfos[$prod['id']][$keyInfo]['date_previ'] ?>">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <input type="text" class="form-control" name="qte_previ_update[<?= $listInfos[$prod['id']][$keyInfo]['id'] ?>]" placeholder="qte prévi" value="<?= $listInfos[$prod['id']][$keyInfo]['qte_previ'] ?>">
                            </div>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary" name="update" value="<?= $listInfos[$prod['id']][$keyInfo]['id'] ?>">Enregistrer</button>
                        </div>
                    </div>
                </div>
                <!-- bouton pour afficher le form de modif ou supprimer une info -->
                <div class="col-auto">
                    <div class="row show-update-<?= $listInfos[$prod['id']][$keyInfo]['id'] ?>">
                        <div class="col-auto text-right">
                            <div class="btn btn-secondary update" data-id-prod-update="<?= $listInfos[$prod['id']][$keyInfo]['id'] ?>">Modifier</div>
                        </div>
                        <div class="col-auto text-right">
                            <a href="?del=<?= $listInfos[$prod['id']][$keyInfo]['id'] ?>" class="btn btn-danger">Supprimer</a>
                        </div>

                    </div>
                </div>

            </div>
        <?php endforeach ?>
    </div>
</div>