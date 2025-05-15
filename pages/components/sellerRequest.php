<?php function sellerRequest($request){ ?>
<article class="product">
    <div>
        <p><?php echo ($request['cognome']." ".$request['nome']); ?></p>
        <small><?php echo ($request['emailUtente']); ?></small><br>
        <small><?php echo ($request['telefono']); ?></small>
        <div>
            <a href="<?= "../api/updateSellerStatus.php?email=".$request["emailUtente"]."&newStatus=1" ?>">Accetta</a>
            <a href="<?= "../api/updateSellerStatus.php?email=".$request["emailUtente"]."&newStatus=2" ?>">Rifiuta</a>
        </div>
    </div>
</article>
<?php } ?>
