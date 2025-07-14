
<?php 
function formatRelativeDate($seconds) {
    if($seconds == 1){
        return "1 secondo fa.";
    } elseif($seconds < 60){
        return $seconds . " secondi fa.";
    } elseif(floor($seconds/60) == 1){
        return "1 minuto fa.";
    } elseif(floor($seconds/60) < 60){
        return floor($seconds/60) . " minuti fa.";
    } elseif(floor($seconds/3600) == 1){
        return "1 ora fa.";
    } elseif(floor($seconds/3600) < 24){
        return floor($seconds/3600) . " ore fa.";
    } elseif(floor($seconds/86400) == 1){
        return "1 giorno fa." ;
    } elseif(floor($seconds/86400) < 30){
        //Yes, i know this is kinda wrong
        return floor($seconds/86400) . " giorni fa.";
    } elseif(floor($seconds/2592000) == 1){
        return "1 mese fa.";
    } elseif(floor($seconds/2592000) < 365){
        return floor($seconds/2592000) . " mesi fa.";
    } elseif(floor($seconds/94608000) == 1){
        return "1 anno fa.";
    } else {
        return floor($seconds/946080000) . " anni fa.";
    }
}

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
    <p><?= formatRelativeDate($review->review["dataCreazione"]) ?></p>

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

