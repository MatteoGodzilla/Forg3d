<?php
function generateSellerList($seller) { ?>
    <a href="../sellerProduct.php?email=<?php echo $seller['emailUtente']; ?>">
	<article class="seller-card">
		<!-- In futuro potresti aggiungere link a pagina venditore -->
		<p><?= htmlspecialchars($seller['nome']) ?> <?= htmlspecialchars($seller['cognome']) ?></p>
	</article>
<?php }