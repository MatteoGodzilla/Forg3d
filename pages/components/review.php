<?php function createReview($review) { ?>
<div class="review">
    <div>
    <h3><?= $review["titolo"] ?></h3>
    <?php 
        for($i = 1; $i <= 5; $i++){ 
            if($i <= $review["valutazione"]){ 
    ?>
        <!-- Filled in star-->
        <span class="material-symbols-outlined filled">star</span>

    <?php } else { ?>

        <!-- Unfilled star -->
        <span class="material-symbols-outlined">star</span>

    <?php 
            }
        }
    ?>
    </div>
    <p><?= $review["testo"] ?></p>
    <p><?= $review["email"] ?></p>
</div>
<?php } ?>
