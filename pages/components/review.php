<?php 
//NOTE: $review is an object of type ReviewNode made for the tree
//the database row is inside $review->review
function createReview($review, $productId, $depth) {
    if($depth >= 200){
        //This is just to avoid accidental circular references, so the recursion always stops
        return;
    } else {
?>
<div class="review">
    <?php if($depth == 0) { ?>
    <div class="reviewInfo">
        <h3><?= $review->review["titolo"] ?></h3>
        <?php 
            for($i = 1; $i <= 5; $i++){ 
                if($i <= $review->review["valutazione"]){ 
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
    <?php } ?>
    <p><?= htmlspecialchars($review->review["testo"]) ?></p>
    <p><?= htmlspecialchars($review->review["email"]) ?></p>

    <div class="replies">
        <?php 
            foreach($review->children as $child){
                createReview($child, $productId, $depth+1);
            }
        ?>

        <?php if(getUserType()===UserType::ADMIN->value){?>
            <a href="./api/hideReview.php?id=<?=$review->review["id"]?>">Elimina questa recensione</a>
        <?php } else if(utenteLoggato()){ ?>
            <!-- It is correct that it's a class and not an id because there are multiple of them -->
            <button class="showReplyForm">Rispondi</button>
            <form class="reply hidden" action="./api/addReply.php" method="POST">
                <input type="hidden" name="idProduct" value="<?= $productId ?>"/>
                <input type="hidden" name="idParent" value="<?= $review->review["id"] ?>"/>
                <label for="reply_<?= $review->review["id"]; ?>" >Risposta</label>
                <textarea name="reply" id="reply_<?= $review->review["id"]; ?>"></textarea>
                <input type="submit" value="Invia" />
            </form> 
        <?php 
                } 
            }
        ?>
    </div>
</div>
<?php } ?>
