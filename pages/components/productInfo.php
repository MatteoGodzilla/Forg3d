<?php function productInfo($product){ ?>
    <div class="user-info">
        <h2><?= htmlspecialchars($product["nome"]) ?></h2>
        <p>Venditore del prodotto: <?= htmlspecialchars($product["emailVenditore"]) ?></p>
        <?= $product["visibile"]==0? "<p>Il prodotto è stato rimosso dal pubblico</p>":""?>
        <a href="/api/updateSellerStatus.php?email=<?= htmlspecialchars($product["emailVenditore"]) ?>&newStatus=3" id="banSeller">Nascondi prodotto</a>
        <h3>Segnalazioni</h3>
        <a href="/api/clearReports.php?id=<?= htmlspecialchars($product["id"])?>" id="deleteAll">Elimina segnalazioni</a>
    </div>
<?php } ?>
