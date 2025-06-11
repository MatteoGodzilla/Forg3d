<?php
function generateSellerList($seller) { ?>
	<div class="seller-card">
        <a href="../sellerProduct.php?email=<?php echo $seller['emailUtente']; ?>">
            <?= htmlspecialchars($seller['nome']) ?> <?= htmlspecialchars($seller['cognome']) ?>
        </a>
    </div>
<?php }
