<?php function productInfo($product){ ?>
    <h2>Elenco segnalazioni</h2>
    <h3>Prodotto</h3>
    <p>Nome Prodotto:<?= htmlspecialchars($product["nome"]) ?></p>
    <p>Venditore del prodotto: <?= htmlspecialchars($product["emailVenditore"]) ?></p>
    <?= $product["visibile"]==0? "<p>Il prodotto è stato rimosso dal pubblico</p>":""?>
    <a href="/api/updateSellerStatus.php?email=<?= htmlspecialchars($product["email"]) ?>&newStatus=3" id="banSeller">Nascondi prodotto</a>
    <h3>Segnalazioni</h3>
    <a href="/api/clearReports.php?id=<?= htmlspecialchars($product["id"])?>" id="deleteAll">Elimina segnalazioni</a>
<?php } ?>