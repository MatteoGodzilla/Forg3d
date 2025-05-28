<?php function createReview($review) { ?>
<div>
    <p>Id: <?= $review["id"] ?></p>
    <p>Email: <?= $review["email"] ?></p>
    <p>Valutazione: <?= $review["valutazione"] ?></p>
    <p>Titolo: <?= $review["titolo"] ?></p>
    <p>Testo: <?= $review["testo"] ?></p>
</div>
<?php } ?>
