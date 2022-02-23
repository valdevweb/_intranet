<?php include 'html-head.php'; ?>
<?php include 'entete.php'; ?>

<div class="spacing-s"></div>

<h2 class="text-center">Détail du litige</h2>
<div class="spacing-s"></div>

<p>Commentaire du magasin : <?= isset($firstCmt['msg']) ? $firstCmt['msg'] : '' ?></p>

<?php include 'info-logistique.php'; ?>
<div class="spacing-s"></div>
<?php include 'details.php'; ?>
</body>
</html>