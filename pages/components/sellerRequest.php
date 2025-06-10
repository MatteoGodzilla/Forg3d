<?php function sellerRequest($request){ ?>
    <div class="request">
            <p><?php echo htmlspecialchars($request['cognome']." ".$request['nome']); ?></p>
            <small><?php echo htmlspecialchars($request['emailUtente']); ?></small><br>
            <small><?php echo htmlspecialchars($request['telefono']); ?></small>
        <div>
            <a class="button-accept" href="<?= "../api/updateSellerStatus.php?email=".$request["emailUtente"]."&newStatus=1" ?>">Accetta</a>
            <a class="button-deny" href="<?= "../api/updateSellerStatus.php?email=".$request["emailUtente"]."&newStatus=2" ?>">Rifiuta</a>
        </div>
    </div>
</article>
<?php } ?>
