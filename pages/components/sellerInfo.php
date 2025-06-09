<?php function sellerInfo($user){ ?>
    <div class="user-info">
        <h2><?= htmlspecialchars($user["nome"]) ?> <?= htmlspecialchars($user["cognome"]) ?></h2>
        <p>email: <?= htmlspecialchars($user["email"]) ?></p>
        <p>Telefono: <?= htmlspecialchars($user["telefono"]) ?></p>
        <?= $user["stato"]==3? "<p>L'utente è attualmente bandito</p>":""?>
        <a href="/api/updateSellerStatus.php?email=<?= htmlspecialchars($user["email"]) ?>&newStatus=3" id="banSeller">Bandisci</a>
        <h3>Segnalazioni:</h3>
        <a href="/api/clearReports.php?email=<?= htmlspecialchars($user["email"])?>" id="deleteAll">Elimina segnalazioni</a>
    </div>
<?php } ?>
