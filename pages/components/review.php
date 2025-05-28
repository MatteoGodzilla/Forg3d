<?php function createReview($review) { ?>
<div>
    <p>Utente: <?= $review["email"] ?></p>
    <p>Valutazione: <?= $review["valutazione"] ?></p>
    <h3><?= $review["titolo"] ?></h3>
    <p><?= $review["testo"] ?></p>
</div>
<?php } ?>
